// QA of the CMS core (Cms / HtmlSanitizer / CmsPageController / admin layout) using a private sandbox page.
// Self-cleaning: installs config/cms_pages/zz-qa.php + routes/admin/zz-qa.php from tests/e2e/fixtures, removes them
// (and the uploads / throw-away users it made) in `finally`. Run: CMS_BASE=http://127.0.0.1:8315 node tests/e2e/qa-core.mjs
import fs from 'node:fs';
import path from 'node:path';
import { execFileSync } from 'node:child_process';
import { request as pwRequest } from 'playwright';
import { launch, login, check, finish, BASE, USER, PASS } from './lib.mjs';

const ROOT = path.resolve(import.meta.dirname, '../..');
const CFG = path.join(ROOT, 'config/cms_pages/zz-qa.php');
const RTE = path.join(ROOT, 'routes/admin/zz-qa.php');
const KEY = 'zz-qa.sandbox';
const EDIT = `${BASE}/admin/site-content/${KEY}`;
const R = [];
const sql = (q) => { try { return execFileSync('mysql', ['-uroot', 'aseanapol', '-N', '-e', q], { encoding: 'utf8' }).trim(); } catch (e) { return 'ERR ' + e.message; } };
const blocks = () => sql(`select block_key from cms_blocks where page_key='${KEY}' order by block_key`).split('\n').filter(Boolean);

fs.copyFileSync(path.join(import.meta.dirname, 'fixtures/zz-qa.config.php'), CFG);
fs.copyFileSync(path.join(import.meta.dirname, 'fixtures/zz-qa.routes.php'), RTE);
let browser, guest, other;
try {
  const L = await launch();
  browser = L.browser;
  const { page, context, errors } = L;
  page.on('dialog', d => d.accept());
  await login(page);
  const req = context.request;
  const token = async () => { await page.goto(EDIT); return page.locator('#cms-form input[name=_token]').inputValue(); };
  const post = async (fields, extra = {}) => {
    const t = await token();
    const fp = await page.locator('input[name=_fp]').inputValue();
    return req.post(EDIT, { form: { _token: t, _method: 'PUT', _fp: fp, _end: '1', ...fields }, maxRedirects: 0, ...extra });
  };
  const json = async () => (await req.get(`${BASE}/admin/zz-qa-preview?mode=json`)).json();
  const html = async () => (await req.get(`${BASE}/admin/zz-qa-preview`)).text();
  const reset = async () => { const t = await token(); await req.post(EDIT + '/reset', { form: { _token: t }, maxRedirects: 0 }); };
  await reset();

  // ---------------------------------------------------------------- authn / authz / csrf
  guest = await pwRequest.newContext({ baseURL: BASE, maxRedirects: 0 });
  for (const r of ['/admin', '/admin/site-content', `/admin/site-content/${KEY}`, '/admin/news', '/admin/events', '/admin/publications', '/admin/newsletters', '/admin/video-gallery', '/admin/digital-library/items', '/admin/zz-qa-preview']) {
    const x = await guest.get(r);
    check(R, `guest GET ${r} -> login`, x.status() === 302 && /\/admin\/login$/.test(x.headers().location || ''), x.status());
  }
  for (const [m, r] of [['put', `/admin/site-content/${KEY}`], ['post', `/admin/site-content/${KEY}/reset`], ['delete', '/admin/news/1'], ['post', '/admin/events/1/toggle']]) {
    const x = await guest[m](r);
    check(R, `guest ${m} ${r} refused`, [405, 419, 302].includes(x.status()), x.status());
  }
  sql("delete from users where username='zz-qa-user'");
  const hash = execFileSync('php', ['-r', 'echo password_hash("NonAdmin#2026", PASSWORD_BCRYPT);'], { encoding: 'utf8' });
  sql(`insert into users (name,username,email,is_admin,password,created_at,updated_at) values ('ZZ QA','zz-qa-user','zz-qa-user@example.test',0,'${hash}',now(),now())`);
  const nctx = await (await browser.newContext()).newPage();
  await nctx.goto(BASE + '/admin/login');
  await nctx.fill('input[name=username]', 'zz-qa-user'); await nctx.fill('input[name=password]', 'NonAdmin#2026');
  await nctx.click('button[type=submit]'); await nctx.waitForLoadState('networkidle');
  check(R, 'non-admin user cannot log in to /admin', /\/admin\/login/.test(nctx.url()), nctx.url());
  const nr = await nctx.request.get(BASE + '/admin/site-content', { maxRedirects: 0 });
  check(R, 'non-admin session still redirected', nr.status() === 302, nr.status());
  await nctx.context().close();
  sql("delete from users where username='zz-qa-user'");
  for (const [m, r] of [['put', EDIT], ['post', EDIT + '/reset'], ['post', `${BASE}/admin/events/1/toggle`], ['delete', `${BASE}/admin/news/zzz`], ['post', `${BASE}/admin/logout`]]) {
    const x = await req[m](r, { maxRedirects: 0 });
    check(R, `admin ${m} without CSRF token -> 419`, x.status() === 419, x.status());
  }
  check(R, 'unknown page 404', (await req.get(`${BASE}/admin/site-content/nope.nothing`)).status() === 404);
  check(R, 'odd page keys 404/no leak', (await Promise.all(["a'b.c", 'a.b.c', '..%2f..%2f.env', 'x.%00'].map(async k => { const x = await req.get(`${BASE}/admin/site-content/${k}`); return x.status() === 404 && !/Stack trace|vendor\/laravel/.test(await x.text()); }))).every(Boolean));
  const hdr = (await req.get(BASE + '/admin')).headers();
  check(R, 'admin responses: no-store + nosniff + frame protection', /no-store/.test(hdr['cache-control'] || '') && hdr['x-content-type-options'] === 'nosniff' && !!hdr['x-frame-options'], JSON.stringify(hdr));

  // ---------------------------------------------------------------- core round-trip
  await page.goto(EDIT);
  const hasEnd = await page.evaluate(() => { const f = document.getElementById('cms-form'); const els = [...f.elements].filter(e => e.name); return els[els.length - 1].name; });
  check(R, '_end sentinel is the LAST form field', hasEnd === '_end', hasEnd);
  await page.click('[data-testid=save-page]'); await page.waitForSelector('.bg-green-50');
  check(R, 'no-op save stores nothing', blocks().length === 0, blocks().join(','));
  let j = await json();
  check(R, 'defaults served', j['main.text'] === 'Hello' && j['main.num'] === 5 && j['main.bool'] === true && j['rep.rows'].length === 2);

  // dirty flag + beforeunload hook state
  await page.goto(EDIT);
  check(R, 'form starts clean', (await page.locator('#cms-form').getAttribute('data-dirty')) === null);
  await page.fill('input[name="f[main][text]"]', 'Changed');
  check(R, 'typing marks form dirty (unsaved warning armed)', (await page.locator('#cms-form').getAttribute('data-dirty')) === '1' && await page.locator('[data-testid=dirty-flag]').isVisible());

  // every type + nested repeater + Quill in dynamically added rows (id-collision regression)
  await page.fill('textarea[name="f[main][area]"]', 'x\ny');
  await page.fill('textarea[name="f[main][list]"]', ' one \n\n two ');
  await page.fill('input[name="f[main][url]"]', 'https://example.com/a?b=1');
  await page.fill('input[name="f[main][num]"]', '7.5');
  await page.uncheck('input[type=checkbox][name="f[main][bool]"]');
  await page.selectOption('select[name="f[main][sel]"]', 'y');
  await page.fill('input[name="f[main][icon]"]', 'star');
  await page.fill('textarea[name="f[main][html]"]', '<p>H <b>b</b><script>alert(1)</script></p>');
  const rt = page.locator('.cms-field[data-type=richtext] .ql-editor').first();
  await rt.click(); await page.keyboard.press('Control+A'); await page.keyboard.type('Rich typed');
  const rows = page.locator('[data-repeater]').first().locator(':scope > .cms-rows > [data-row]');
  const addOuter = () => page.locator('[data-repeater]').first().locator(':scope > [data-act=add-row]').click();
  await addOuter(); await addOuter();
  check(R, 'two rows added (4 total)', (await rows.count()) === 4, await rows.count());
  for (const [i, txt] of [[2, 'AAA'], [3, 'BBB']]) {
    await rows.nth(i).locator('> div input[name$="[title]"]').first().fill('T' + txt);
    const ed = rows.nth(i).locator('.ql-editor').first();
    await ed.click(); await page.keyboard.type('body ' + txt);
  }
  const ids = await page.$$eval('#cms-form [id]', els => els.map(e => e.id));
  check(R, 'element ids unique after adding rows', new Set(ids).size === ids.length, ids.length - new Set(ids).size + ' dupes');
  // nested repeater row on row 0
  await rows.nth(0).locator('[data-repeater] [data-act=add-row]').click();
  await rows.nth(0).locator('[data-repeater] [data-row]').last().locator('input[name$="[name]"]').fill('k3');
  // hide row 1, move row 3 up
  await rows.nth(1).locator('input[name$="[_hide]"]').check();
  await rows.nth(3).locator('> div [data-act=up]').click();
  await page.click('[data-testid=save-page]'); await page.waitForSelector('.bg-green-50');
  j = await json();
  check(R, 'scalar types saved', j['main.text'] === 'Changed' && j['main.area'] === 'x\ny' && j['main.num'] === 7.5 && j['main.bool'] === false && j['main.sel'] === 'y' && j['main.icon'] === 'star', JSON.stringify(j));
  check(R, 'list trimmed / blanks dropped', JSON.stringify(j['main.list']) === '["one","two"]', JSON.stringify(j['main.list']));
  check(R, 'richtext typed + html sanitised', /Rich typed/.test(j['main.rich']) && !/script/.test(j['main.html']) && /<b>b<\/b>/.test(j['main.html']), j['main.rich'] + ' | ' + j['main.html']);
  const titles = j['rep.rows'].map(r => r.title);
  check(R, 'rows: hidden dropped, order moved, both Quill rows kept their own body', JSON.stringify(titles) === JSON.stringify(['R1', 'TBBB', 'TAAA']) && /body BBB/.test(j['rep.rows'][1].body) && /body AAA/.test(j['rep.rows'][2].body), JSON.stringify(j['rep.rows']));
  check(R, 'nested repeater row saved', j['rep.rows'][0].kids.map(k => k.name).join() === 'k1,k2,k3', JSON.stringify(j['rep.rows'][0]));
  check(R, 'success flash is a status region', (await page.locator('[role=status]').count()) === 1);

  // saving again unchanged is stable and does not drop overrides
  const before = blocks().join();
  await page.goto(EDIT); await page.click('[data-testid=save-page]'); await page.waitForSelector('.bg-green-50');
  check(R, 'second unchanged save keeps identical override set', blocks().join() === before, before + ' vs ' + blocks().join());
  // set a field back to default -> override removed
  await page.goto(EDIT); await page.fill('input[name="f[main][text]"]', 'Hello'); await page.click('[data-testid=save-page]'); await page.waitForSelector('.bg-green-50');
  check(R, 'value equal to default removes its override', !blocks().includes('main.text'));
  // toggle section
  await page.goto(EDIT); await page.uncheck('input[type=checkbox][name="vis[rep]"]'); await page.click('[data-testid=save-page]'); await page.waitForSelector('.bg-green-50');
  check(R, 'toggle hides section on public output', !(await html()).includes('id="rows"'));
  await page.goto(EDIT); check(R, 'toggle state restored in editor', !(await page.locator('input[type=checkbox][name="vis[rep]"]').isChecked()));
  await reset();
  check(R, 'revert deletes every override', blocks().length === 0);

  // ---------------------------------------------------------------- truncation guard (max_input_vars)
  await post({ 'f[main][text]': 'KEEP ME' });
  const keep = blocks().join();
  const t = await token();
  const big = { _token: t, _method: 'PUT', 'f[main][text]': '' };
  for (let i = 0; i < 1300; i++) big['pad' + i] = 'x';
  big._end = '1'; // last, so PHP (max_input_vars=1000 by default) drops it together with everything after the limit
  const rb = await req.post(EDIT, { form: big, maxRedirects: 0 });
  check(R, 'oversized/truncated form is refused', rb.status() === 302, rb.status());
  check(R, 'truncated form deleted nothing', blocks().join() === keep && (await json())['main.text'] === 'KEEP ME', blocks().join());
  const noend = await req.post(EDIT, { form: { _token: t, _method: 'PUT', 'f[main][text]': 'HACK' }, maxRedirects: 0 });
  await page.goto(EDIT);
  check(R, 'refusal explains itself', /Nothing was saved/.test(await page.locator('main').innerText()));
  check(R, 'form without _end sentinel is refused', noend.status() === 302 && (await json())['main.text'] === 'KEEP ME');
  await reset();

  // ---------------------------------------------------------------- stored XSS / sanitizer through every field type
  const payloads = [
    '<img src=x onerror=window.__x=1>', '<svg onload=window.__x=1>', '<script>window.__x=1</script>', '<a href="javascript:window.__x=1">l</a>',
    '<a href="jav&#x09;ascript:window.__x=1">l</a>', '<a href=" \tJaVaScRiPt:window.__x=1">l</a>', '<iframe srcdoc="<script>parent.__x=1</script>"></iframe>',
    '<p style="background:url(javascript:window.__x=1)" onclick="window.__x=1">p</p>', '<math><mtext><table><mglyph><style><img src=x onerror=window.__x=1>',
    '<a href="//evil.example" target="_blank">t</a>', '<base href="//evil.example/">', '<meta http-equiv=refresh content="0;url=//evil.example">',
    '<img src="data:image/svg+xml,<svg onload=window.__x=1>">', '<form><button formaction="javascript:window.__x=1">b</button></form>', '"><img src=x onerror=window.__x=1>',
  ];
  const xssPayload = payloads.join('');
  const fields = {
    'f[main][text]': xssPayload, 'f[main][area]': xssPayload, 'f[main][list]': payloads.join('\n'), 'f[main][rich]': xssPayload, 'f[main][html]': xssPayload,
    'f[main][url]': 'javascript:window.__x=1', 'f[main][img]': ' JaVa\tScRiPt:window.__x=1', 'f[main][file]': 'vbscript:msgbox(1)', 'f[main][icon]': '"><img src=x onerror=window.__x=1>',
    'f[main][sel]': '"><script>window.__x=1</script>', 'f[main][date]': '<script>window.__x=1</script>', 'f[main][num]': '1e999',
    'f[rep][rows][0][title]': xssPayload, 'f[rep][rows][0][body]': xssPayload, 'f[rep][rows][0][img]': 'data:text/html,<script>window.__x=1</script>', 'f[rep][rows][0][kids][0][name]': xssPayload,
  };
  const rx = await post(fields);
  check(R, 'hostile payload save succeeds without 500', rx.status() === 302, rx.status());
  j = await json();
  check(R, 'javascript:/vbscript:/data: url/image/file values dropped', j['main.url'] === '' && j['main.img'] === '' && j['main.file'] === '' && j['rep.rows'][0].img === '', JSON.stringify([j['main.url'], j['main.img'], j['main.file'], j['rep.rows'][0].img]));
  check(R, 'select falls back to default for unknown option', j['main.sel'] === 'x', j['main.sel']);
  check(R, 'absurd number (1e999) becomes empty, not INF/500', j['main.num'] === null, String(j['main.num']));
  const dump = JSON.stringify([j['main.rich'], j['main.html'], j['rep.rows'][0].body]);
  check(R, 'sanitised html has no executable markup', !/<script|<svg|<math|<iframe|<base|<meta|<form|onerror|onload|onclick|formaction|srcdoc|style=|javascript:/i.test(dump.replace(/\\u003c/gi, '<')), dump.slice(0, 400));
  check(R, 'target=_blank links get rel=noopener', !/target=\\"_blank\\"(?![^>]*noopener)/.test(dump));
  const ph = await context.newPage();
  const dialogs = []; ph.on('dialog', d => { dialogs.push(d.message()); d.dismiss(); });
  await ph.goto(`${BASE}/admin/zz-qa-preview`); await ph.waitForTimeout(400);
  check(R, 'rendered public output executes nothing', (await ph.evaluate(() => window.__x)) === undefined && dialogs.length === 0, String(dialogs));
  const bodyHtml = await ph.content();
  check(R, 'text/area/list/select/icon values are HTML-escaped on output', !/<img src=x onerror/.test(bodyHtml) && !/<svg onload/.test(bodyHtml.replace(/<svg[^>]*>/g, m => (/onload/.test(m) ? m : ''))));
  await ph.close();
  // editor re-render of hostile stored values must not execute either
  const eh = await context.newPage(); const edialogs = []; eh.on('dialog', d => { edialogs.push(d.message()); d.dismiss(); });
  await eh.goto(EDIT); await eh.waitForTimeout(600);
  check(R, 'admin editor renders hostile values inertly', (await eh.evaluate(() => window.__x)) === undefined && edialogs.length === 0);
  await eh.close();
  // malformed payload shapes must not 500
  for (const [name, f] of [['array for scalar', { 'f[main][text][]': 'a', 'f[main][num][x]': '1' }], ['string for section', { 'f[main]': 'oops' }], ['string for repeater', { 'f[rep][rows]': 'x' }], ['deep junk', { 'f[rep][rows][0][kids][0][name][z]': 'x', 'vis[rep][]': '1' }]]) {
    const x = await post(f); check(R, `malformed input (${name}) handled`, [302, 422].includes(x.status()), x.status());
  }
  await reset();

  // ---------------------------------------------------------------- uploads
  const png = Buffer.from('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==', 'base64');
  const tk = await token();
  const up = (name, buf, mime, field = 'files[main][img]') => req.post(EDIT, { multipart: { _token: tk, _method: 'PUT', _end: '1', 'f[main][text]': 'Hello', [field]: { name, mimeType: mime, buffer: buf } }, maxRedirects: 0 });
  let x = await up('zzqa-good.png', png, 'image/png');
  check(R, 'valid png accepted', x.status() === 302 && /^storage\/cms\/zzqa-good-\d+-[a-z0-9]{5}\.png$/.test((await json())['main.img']), (await json())['main.img']);
  await reset();
  for (const [n, buf, mt, label] of [
    ['zzqa-shell.php', Buffer.from('<?php system($_GET[1]); ?>'), 'image/png', '.php extension'],
    ['zzqa-shell.php.png', Buffer.from('<?php system($_GET[1]); ?>'), 'image/png', 'php code with .png name'],
    ['zzqa-shell.phtml', png, 'image/png', '.phtml extension'],
    ['zzqa-x.svg', Buffer.from('<svg xmlns="http://www.w3.org/2000/svg" onload="alert(1)"/>'), 'image/svg+xml', '.svg'],
    ['zzqa-x.png', Buffer.from('<html><script>alert(1)</script></html>'), 'image/png', 'html content with .png name'],
    ['zzqa-x.html', png, 'text/html', '.html'],
    ['..%2f..%2fzzqa-evil.png', Buffer.from('<?php 1; ?>'), 'image/png', 'traversal name with php body'],
  ]) {
    const r = await up(n, buf, mt); const stored = (await json())['main.img'];
    check(R, `upload abuse rejected: ${label}`, r.status() !== 500 && stored === '', `status ${r.status()} stored=${stored}`);
  }
  x = await up('zzqa-page.html.pdf', Buffer.from('<html><script>alert(1)</script></html>'), 'application/pdf', 'files[main][file]');
  check(R, 'html content disguised as .pdf rejected (file field)', (await json())['main.file'] === '');
  x = await up('zzqa-big.png', Buffer.concat([png, Buffer.alloc(3 * 1024 * 1024)]), 'image/png');
  const bigMsg = await (async () => { await page.goto(EDIT); return (await page.locator('main').innerText()); })();
  check(R, 'upload above PHP upload limit is reported, never silently ignored', (await json())['main.img'] === '' && /Upload failed|too large|exceed/i.test(bigMsg + (await x.text())), `${x.status()} ${bigMsg.slice(0, 200)}`);
  await reset();
  const strays = fs.readdirSync(path.join(ROOT, 'storage/app/public/cms')).filter(f => f.startsWith('zzqa-'));
  check(R, 'rejected uploads leave no files behind (only the one valid png)', strays.length === 1 && /zzqa-good/.test(strays[0]), strays.join());
  for (const f of strays) fs.unlinkSync(path.join(ROOT, 'storage/app/public/cms', f));

  // ---------------------------------------------------------------- concurrent edit warning
  other = await (await browser.newContext()).newPage(); await login(other);
  await other.goto(EDIT); await other.fill('input[name="f[main][text]"]', 'FROM ADMIN B');
  await page.goto(EDIT); await page.fill('input[name="f[main][text]"]', 'FROM ADMIN A'); await page.click('[data-testid=save-page]'); await page.waitForSelector('.bg-green-50');
  await other.click('[data-testid=save-page]'); await other.waitForLoadState('networkidle');
  check(R, 'stale form shows conflict banner and does NOT save', await other.locator('[data-testid=cms-conflict]').isVisible() && (await json())['main.text'] === 'FROM ADMIN A');
  check(R, 'conflict view keeps the loser\'s edits on screen', (await other.inputValue('input[name="f[main][text]"]')) === 'FROM ADMIN B');
  await other.click('[data-testid=save-page]'); await other.waitForSelector('.bg-green-50');
  check(R, 'second Save after warning overwrites knowingly', (await json())['main.text'] === 'FROM ADMIN B');
  await reset();

  // ---------------------------------------------------------------- admin UX: keyboard, aria, mobile
  await page.goto(EDIT);
  check(R, 'row control buttons have accessible names', (await page.$$eval('[data-row] button[data-act=up], [data-row] button[data-act=down], [data-row] button[data-act=remove]', bs => bs.every(b => b.getAttribute('aria-label')))));
  check(R, 'Quill editors are labelled', (await page.$$eval('.ql-editor', es => es.length > 0 && es.every(e => e.getAttribute('aria-label')))));
  const labels = await page.$$eval('#cms-form [name^="f["]:not([type=hidden]):not([type=file])', els => els.filter(e => !e.closest('template')).filter(e => !document.querySelector(`label[for="${e.id}"]`) && !e.closest('label') && !e.getAttribute('aria-label')).length);
  check(R, 'every visible input has a label', labels === 0, labels);
  await page.focus('input[name="f[main][text]"]'); await page.keyboard.press('Tab');
  check(R, 'Tab moves focus along the form', (await page.evaluate(() => document.activeElement && document.activeElement.name)) !== 'f[main][text]');
  const m = await context.newPage(); await m.setViewportSize({ width: 390, height: 800 });
  await m.goto(EDIT); await m.waitForSelector('#cms-form');
  const sbBox = await m.locator('#admin-sidebar').boundingBox();
  check(R, 'mobile: sidebar is off-canvas by default', sbBox.x + sbBox.width <= 1, JSON.stringify(sbBox));
  check(R, 'mobile: no horizontal page overflow', await m.evaluate(() => document.documentElement.scrollWidth <= window.innerWidth + 1 && document.getElementById('admin-main').scrollWidth <= window.innerWidth + 1), await m.evaluate(() => [document.documentElement.scrollWidth, document.getElementById('admin-main').scrollWidth].join('/')));
  const sv = await m.locator('[data-testid=save-page]').boundingBox();
  check(R, 'mobile: Save button visible inside viewport', sv && sv.x >= 0 && sv.x + sv.width <= 391 && sv.y + sv.height <= 801, JSON.stringify(sv));
  await m.click('#admin-menu-btn'); await m.waitForTimeout(400);
  check(R, 'mobile: hamburger opens the nav drawer', (await m.locator('#admin-sidebar').boundingBox()).x >= 0 && (await m.locator('#admin-menu-btn').getAttribute('aria-expanded')) === 'true');
  await m.keyboard.press('Escape'); await m.waitForTimeout(400);
  check(R, 'mobile: Escape closes the drawer', (await m.locator('#admin-sidebar').boundingBox()).x < 0);
  await m.close();
  const dsk = await context.newPage(); await dsk.setViewportSize({ width: 1400, height: 900 }); await dsk.goto(EDIT);
  check(R, 'desktop: sidebar visible, hamburger hidden', (await dsk.locator('#admin-sidebar').boundingBox()).x >= 0 && !(await dsk.locator('#admin-menu-btn').isVisible()));
  await dsk.close();

  // ---------------------------------------------------------------- brute force throttle + session fixation
  // opt-in: exhausting the per-IP login throttle locks out every other test run on this machine for a minute
  if (process.env.QA_BRUTE === '1') {
  const bf = await pwRequest.newContext({ baseURL: BASE, maxRedirects: 0 });
  const lp = await (await bf.get('/admin/login')).text(); const ltok = /name="_token" value="([^"]+)"/.exec(lp)[1];
  const codes = [];
  for (let i = 0; i < 9; i++) codes.push((await bf.post('/admin/login', { form: { _token: ltok, username: 'zz-nobody', password: 'x' + i } })).status());
  check(R, 'login throttled after repeated failures (429)', codes.includes(429), codes.join());
  await bf.dispose();

  }

  check(R, 'no JS errors in editor sessions', errors.length === 0, errors.join(' | '));
  await reset();
  finish_(R);
} finally {
  try { fs.unlinkSync(CFG); } catch {}
  try { fs.unlinkSync(RTE); } catch {}
  sql(`delete from cms_blocks where page_key='${KEY}'`);
  sql("delete from users where username='zz-qa-user'");
  try { for (const f of fs.readdirSync(path.join(ROOT, 'storage/app/public/cms'))) if (f.startsWith('zzqa-')) fs.unlinkSync(path.join(ROOT, 'storage/app/public/cms', f)); } catch {}
  try { await guest?.dispose(); await browser?.close(); } catch {}
}
function finish_(r) {
  const failed = r.filter(x => !x.ok);
  console.log(`\n${r.length - failed.length}/${r.length} passed`);
  process.exitCode = failed.length ? 1 : 0;
}
