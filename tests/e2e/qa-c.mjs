// QA-C: stress / edge-case tests for page groups about-people.*, cooperation.*, training.*, careers.*
//   CMS_BASE=http://127.0.0.1:8313 node tests/e2e/qa-c.mjs [stress|empty|hide|careers|quill|forms|links]...   (no arg = all)
// Self-cleaning: every page it touches is reverted at the end (only pages in GROUPS, never anything else).
import fs from 'node:fs';
import os from 'node:os';
import path from 'node:path';
import { execSync } from 'node:child_process';
import { chromium } from 'playwright';
import { launch, login, check, finish, openEditor, save, revertPage, BASE } from './lib.mjs';

const GROUPS = ['about-people.', 'cooperation.', 'training.', 'careers.'];
const modes = process.argv.slice(2);
const want = m => !modes.length || modes.includes(m);
const pages = JSON.parse(execSync('php artisan cms:pages --json', { encoding: 'utf8' }).trim().split('\n').pop())
  .filter(p => p.url && GROUPS.some(g => p.key.startsWith(g)));
const SHOTS = path.join(os.tmpdir(), 'qa-c-shots');
fs.mkdirSync(SHOTS, { recursive: true });
const BAD_RE = /Whoops|ErrorException|Undefined (variable|array key|index)|Trying to access array offset|Call to a member function|Attempt to read property|htmlspecialchars\(\)|Illuminate\\|<b>Warning<\/b>|<b>Notice<\/b>/;
const XSS = `<script>window.__xss=1</script><img src=x onerror="window.__xss=1"><a href="javascript:window.__xss=1">x</a>`;
const NASTY = `QA & "quotes" 'single' <b>bold</b> ${XSS} 😀🚀 สวัสดีครับ ASEANAPOL مرحبا بالعالم`;
const LONG = ('Lorem ipsum dolor sit amet consectetur adipiscing elit ').repeat(60);

const { browser, page, errors } = await launch();
const R = [];
await login(page);

const get = async url => { const r = await page.request.get(BASE + url); return { status: r.status(), text: await r.text() }; };

async function noBad(label, url) {
  const r = await get(url);
  check(R, `${label}: HTTP 200`, r.status === 200, `status ${r.status}`);
  check(R, `${label}: no PHP error text`, !BAD_RE.test(r.text), (r.text.match(BAD_RE) || [])[0]);
  return r;
}

async function liveChecks(label, url, { xss = false, shot = null, overflow = false } = {}) {
  for (const [name, vp] of [['desktop', { width: 1400, height: 1000 }], ['mobile', { width: 390, height: 844 }]]) {
    const ctx = await browser.newContext({ viewport: vp });
    const pg = await ctx.newPage();
    const errs = [];
    pg.on('pageerror', e => errs.push(e.message));
    pg.on('console', m => { if (m.type() === 'error' && !/favicon|net::ERR|Failed to load resource|ERR_|fonts\.g|CORS|tailwind|cdn/i.test(m.text())) errs.push(m.text()); });
    const resp = await pg.goto(BASE + url, { waitUntil: 'load' });
    check(R, `${label} [${name}]: loads 200`, resp.status() === 200, resp.status());
    if (xss) check(R, `${label} [${name}]: no script/onerror executed`, (await pg.evaluate(() => window.__xss)) === undefined, 'window.__xss was set');
    if (overflow && name === 'mobile') {
      const o = await pg.evaluate(() => document.documentElement.scrollWidth - window.innerWidth);
      check(R, `${label} [mobile]: no horizontal page scroll`, o <= 1, `overflow ${o}px`);
    }
    check(R, `${label} [${name}]: no JS errors`, !errs.length, errs.join(' | ').slice(0, 200));
    if (shot) { try { await pg.screenshot({ path: path.join(SHOTS, `${shot}-${name}.png`), fullPage: true, timeout: 15000 }); } catch {} }
    await ctx.close();
  }
}

const cleanup = async () => { if (modes.length && modes.every(m => m === 'links')) return; for (const p of pages) { try { await revertPage(page, p.key); } catch {} } };

try {
  // ------------------------------------------------------------------ 1. nasty text in every scalar field
  if (want('stress')) {
    for (const p of pages) {
      await revertPage(page, p.key);
      await openEditor(page, p.key);
      await page.evaluate(({ NASTY, LONG }) => {
        let i = 0;
        document.querySelectorAll('.cms-field').forEach(f => {
          if (f.closest('template')) return;
          const t = f.dataset.type;
          if (['repeater', 'boolean', 'select', 'number', 'date', 'image', 'file'].includes(t)) return;
          const el = f.querySelector(':scope > input[name^="f["], :scope > textarea[name^="f["]');
          if (!el) return;
          const rt = el.type === 'hidden';
          const v = (t === 'url') ? 'https://example.test/?a=1&b="2"' : (t === 'icon') ? 'home' : (i++ % 4 === 0 ? LONG : NASTY);
          el.value = rt ? '<p>' + v.replace(/</g, '&lt;') + '</p>' : v;
        });
      }, { NASTY, LONG });
      await save(page);
      await noBad(`stress ${p.key}`, p.url);
      await liveChecks(`stress ${p.key}`, p.url, { xss: true, shot: 'stress-' + p.key });
      await revertPage(page, p.key);
    }
  }

  // ------------------------------------------------------------------ 2. every scalar empty
  if (want('empty')) {
    for (const p of pages) {
      await revertPage(page, p.key);
      const base = await get(p.url);
      await openEditor(page, p.key);
      await page.evaluate(() => {
        document.querySelectorAll('.cms-field').forEach(f => {
          if (f.closest('template')) return;
          const el = f.querySelector(':scope > input[name^="f["]:not([type=checkbox]), :scope > textarea[name^="f["], :scope > .cms-upload input.cms-path');
          if (el && f.dataset.type !== 'repeater' && f.dataset.type !== 'select' && f.dataset.type !== 'boolean') el.value = '';
        });
      });
      await save(page);
      await noBad(`empty-values ${p.key}`, p.url);
      await liveChecks(`empty-values ${p.key}`, p.url, { shot: 'empty-' + p.key });
      // empty repeaters (remove every row)
      await revertPage(page, p.key);
      await openEditor(page, p.key);
      page.on('dialog', d => d.accept());
      await page.evaluate(() => document.querySelectorAll('[data-row]').forEach(r => { if (!r.closest('template')) r.remove(); }));
      await save(page);
      await noBad(`empty-repeaters ${p.key}`, p.url);
      await liveChecks(`empty-repeaters ${p.key}`, p.url, { shot: 'norows-' + p.key });
      page.removeAllListeners('dialog');
      await revertPage(page, p.key);
    }
  }

  // ------------------------------------------------------------------ 3. all sections hidden + all rows hidden
  if (want('hide')) {
    for (const p of pages) {
      await revertPage(page, p.key);
      await openEditor(page, p.key);
      await page.evaluate(() => {
        document.querySelectorAll('input[type=checkbox][name^="vis["]').forEach(c => { c.checked = false; });
        document.querySelectorAll('input[type=checkbox][name$="[_hide]"]').forEach(c => { if (!c.closest('template')) c.checked = true; });
      });
      await save(page);
      await noBad(`hide-all ${p.key}`, p.url);
      await liveChecks(`hide-all ${p.key}`, p.url, { shot: 'hide-' + p.key });
      await revertPage(page, p.key);
    }
  }

  // ------------------------------------------------------------------ 4. careers openings
  if (want('careers')) {
    for (const key of ['careers.vacancies', 'careers.internships', 'careers.exchange-programs']) {
      const p = pages.find(x => x.key === key);
      await revertPage(page, key);
      const before = (await get(p.url)).text;
      await openEditor(page, key);
      const rep = page.locator('[name="f[openings][items][__present]"]').locator('xpath=ancestor::*[@data-repeater][1]');
      const rows = rep.locator(':scope > .cms-rows > [data-row]');
      await rep.locator(':scope > [data-act=add-row]').click();
      const r0 = rows.first();
      await r0.locator('input[name$="[title]"]').fill('QA Opening <b>One</b> & "Co"');
      await r0.locator('input[name$="[department]"]').fill('Dept สวัสดี');
      await r0.locator('input[name$="[location]"]').fill('Jakarta');
      await r0.locator('input[name$="[deadline]"]').fill('31 December 2030');
      await r0.locator('textarea[name$="[description]"]').fill('Line one\nLine two <script>window.__xss=1</script>');
      await r0.locator('input[name$="[apply_link]"]').fill('mailto:jobs@example.test');
      await rep.locator(':scope > [data-act=add-row]').click();
      const r1 = rows.nth(1);
      await r1.locator('input[name$="[title]"]').fill('QA Opening Two (minimal)');
      await rep.locator(':scope > [data-act=add-row]').click();
      const r2 = rows.nth(2);
      await r2.locator('input[name$="[title]"]').fill('QA Opening JS link');
      await r2.locator('input[name$="[apply_link]"]').fill('javascript:window.__xss=1');
      await save(page);
      let html = (await get(p.url)).text;
      check(R, `${key}: openings shown`, html.includes('QA Opening Two (minimal)') && html.includes('31 December 2030'));
      check(R, `${key}: title escaped`, html.includes('QA Opening &lt;b&gt;One') && !html.includes('QA Opening <b>One'));
      check(R, `${key}: mailto apply link rendered`, html.includes('href="mailto:jobs@example.test"'));
      check(R, `${key}: javascript: apply link dropped`, !/href="javascript:/i.test(html));
      check(R, `${key}: raw script in description escaped`, !html.includes('<script>window.__xss=1</script>'));
      check(R, `${key}: minimal row has no Apply button / empty meta`, !/QA Opening Two[\s\S]{0,400}location_on/.test(html.split('QA Opening JS link')[0].split('QA Opening Two')[1] ? 'QA Opening Two' + html.split('QA Opening Two')[1].split('QA Opening JS link')[0] : ''));
      if (key === 'careers.vacancies') {
        const notice = /<div\s+hidden aria-hidden="true"/.test(html);
        check(R, `${key}: "no openings" notice hidden when openings exist`, notice);
      }
      await liveChecks(`${key} openings`, p.url, { xss: true, shot: 'openings-' + key });
      // hide all openings -> notice back
      await openEditor(page, key);
      await page.evaluate(() => document.querySelectorAll('[name^="f[openings][items]"][name$="[_hide]"]').forEach(c => { if (!c.closest('template')) c.checked = true; }));
      await save(page);
      html = (await get(p.url)).text;
      check(R, `${key}: all openings hidden -> none shown`, !html.includes('QA Opening Two'));
      if (key === 'careers.vacancies') {
        check(R, `${key}: notice visible again`, !/<div\s+hidden aria-hidden="true"/.test(html) && html.includes('No Active Openings'), 'notice still hidden or heading changed');
      }
      await revertPage(page, key);
      check(R, `${key}: revert restores original`, (await get(p.url)).text.replace(/\s+/g, ' ').length === before.replace(/\s+/g, ' ').length);
    }
  }

  // ------------------------------------------------------------------ 5. Quill on cooperation pages
  if (want('quill')) {
    const rtPages = pages.filter(p => p.key.startsWith('cooperation.') && Object.values(p.schema).some(s => Object.values(s).some(f => f.type === 'richtext')));
    check(R, 'quill: cooperation pages with richtext found', rtPages.length > 0, 'none');
    for (const p of rtPages) {
      await revertPage(page, p.key);
      await openEditor(page, p.key);
      const ed = page.locator('.cms-rt .ql-editor').first();
      await page.waitForSelector('.cms-rt .ql-editor');
      const targetId = await page.locator('.cms-rt').first().getAttribute('data-target');
      await ed.click();
      await page.keyboard.press('Control+A');
      await page.keyboard.press('Delete');
      await page.keyboard.type('QAQuill plain ');
      await page.keyboard.press('Control+B');
      await page.keyboard.type('boldword');
      await page.keyboard.press('Control+B');
      await page.keyboard.type(' tail 😀 สวัสดี');
      await page.keyboard.press('Enter');
      // bullet list via toolbar
      await page.locator('.ql-toolbar').first().locator('button.ql-list[value=bullet]').click();
      await page.keyboard.type('bullet A');
      await page.keyboard.press('Enter');
      await page.keyboard.type('bullet B');
      await page.keyboard.press('Enter');
      await page.keyboard.press('Enter'); // leave list
      await page.keyboard.type('linktext');
      await page.keyboard.press('Shift+Home');
      // link via Quill API (the link tooltip UI is brittle) – then also inject hostile html straight into the hidden input
      await page.evaluate(() => {
        const q = Quill.find(document.querySelector('.cms-rt'));
        const len = q.getLength();
        q.formatText(len - 9, 8, 'link', 'https://example.test/qa-link', 'user');
      });
      const hidden = await page.inputValue('#' + targetId);
      check(R, `${p.key}: quill wrote html into the form field`, /<strong>boldword<\/strong>/.test(hidden) && /<ul>/.test(hidden) || /<li data-list="bullet">/.test(hidden), hidden.slice(0, 300));
      await save(page);
      let html = (await get(p.url)).text;
      check(R, `${p.key}: bold rendered`, html.includes('<strong>boldword</strong>'), 'no <strong>');
      check(R, `${p.key}: bullets rendered as list`, /<li[^>]*>\s*bullet A/.test(html) && html.includes('bullet B'));
      check(R, `${p.key}: link rendered`, html.includes('https://example.test/qa-link'));
      check(R, `${p.key}: emoji/thai kept`, html.includes('😀') && html.includes('สวัสดี'));
      check(R, `${p.key}: no ql-* / data-list classes leaked`, !/class="[^"]*ql-/.test(html.split('QAQuill')[1]?.slice(0, 1500) || '') && !/data-list=/.test(html));
      await liveChecks(`${p.key} quill`, p.url, { shot: 'quill-' + p.key });
      // hostile html
      await openEditor(page, p.key);
      await page.evaluate(({ id }) => {
        document.getElementById(id).value = '<p>QAHOSTILE</p><script>window.__xss=1</script><img src=x onerror="window.__xss=1"><a href="javascript:window.__xss=1" onclick="window.__xss=1" class="text-red-500" style="color:red">jslink</a><p class="foo" style="x:y" onmouseover="alert(1)">styled</p><iframe src="https://evil.test"></iframe>';
      }, { id: targetId });
      await save(page);
      html = (await get(p.url)).text;
      const seg = html.split('QAHOSTILE')[1]?.slice(0, 800) ?? '';
      check(R, `${p.key}: hostile html: <script> stripped`, !seg.includes('<script>window.__xss'), seg.slice(0, 200));
      check(R, `${p.key}: hostile html: onerror stripped`, !/onerror=/i.test(seg));
      check(R, `${p.key}: hostile html: javascript: href stripped`, !/href="javascript:/i.test(seg));
      check(R, `${p.key}: hostile html: onclick/onmouseover/style stripped`, !/onclick=|onmouseover=|style="x:y"|style="color:red"/i.test(seg));
      check(R, `${p.key}: hostile html: iframe stripped`, !/<iframe/i.test(seg));
      check(R, `${p.key}: hostile html: text kept`, seg.includes('jslink') && seg.includes('styled'));
      await liveChecks(`${p.key} hostile`, p.url, { xss: true });
      // empty quill
      await openEditor(page, p.key);
      await page.locator('.cms-rt .ql-editor').first().click();
      await page.keyboard.press('Control+A');
      await page.keyboard.press('Delete');
      await save(page);
      await noBad(`${p.key} empty quill`, p.url);
      await liveChecks(`${p.key} empty quill`, p.url, { shot: 'quill-empty-' + p.key });
      await revertPage(page, p.key);
    }
  }

  // ------------------------------------------------------------------ 6. admin form edge cases
  if (want('forms')) {
    const tmp = fs.mkdtempSync(path.join(os.tmpdir(), 'qa-c-'));
    const png = Buffer.from('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==', 'base64');
    const fPng = path.join(tmp, 'Logo ünïcode 日本 file.png'); fs.writeFileSync(fPng, png);
    const fTxt = path.join(tmp, 'notimage.txt'); fs.writeFileSync(fTxt, 'hello');
    const fFake = path.join(tmp, 'fake.png'); fs.writeFileSync(fFake, '<?php echo 1; ?> not really a png');
    const fHuge = path.join(tmp, 'huge.png'); fs.writeFileSync(fHuge, Buffer.concat([png, Buffer.alloc(30 * 1024 * 1024)]));

    const KEY = 'cooperation.index', URL = '/en/international-cooperation';
    await revertPage(page, KEY);
    const partners = () => page.locator('[name="f[partners][items][__present]"]').locator('xpath=ancestor::*[@data-repeater][1]');
    const rows = () => partners().locator(':scope > .cms-rows > [data-row]');

    // unicode / spaces in filename
    await openEditor(page, KEY);
    await rows().first().locator('input[type=file]').setInputFiles(fPng);
    await save(page);
    let html = (await get(URL)).text;
    const m = html.match(/src="[^"]*(storage\/cms\/[^"]+\.png)"/);
    check(R, 'upload: unicode/space filename accepted and rendered', !!m, 'no storage/cms image');
    if (m) check(R, 'upload: file served 200', (await page.request.get(BASE + '/' + m[1])).status() === 200, m[1]);
    if (m) check(R, 'upload: stored filename has no spaces/unicode', /^storage\/cms\/[a-z0-9\-]+\.png$/.test(m[1]), m[1]);

    // non-image into image field
    await openEditor(page, KEY);
    await rows().first().locator('input[type=file]').setInputFiles(fTxt);
    const resp1 = await Promise.all([page.waitForResponse(r => r.url().includes('/admin/site-content/') && r.request().method() === 'POST'), page.click('[data-testid=save-page]')]);
    check(R, 'upload: .txt into image field rejected cleanly (not 500)', resp1[0].status() !== 500, 'status ' + resp1[0].status());
    console.log('   .txt upload status', resp1[0].status(), page.url());

    // fake png with php content
    await openEditor(page, KEY);
    await rows().first().locator('input[type=file]').setInputFiles(fFake);
    const resp2 = await Promise.all([page.waitForResponse(r => r.url().includes('/admin/site-content/') && r.request().method() === 'POST'), page.click('[data-testid=save-page]')]);
    console.log('   fake-png upload status', resp2[0].status());
    check(R, 'upload: fake .png (php content) does not 500', resp2[0].status() !== 500, 'status ' + resp2[0].status());
    html = (await get(URL)).text;
    const mf = html.match(/src="[^"]*(storage\/cms\/fake-[^"]+\.png)"/);
    check(R, 'upload: fake .png with non-image content is NOT stored as an image', !mf, mf ? 'stored ' + mf[1] + ' (content not validated)' : '');

    // huge upload
    await openEditor(page, KEY);
    await rows().first().locator('input[type=file]').setInputFiles(fHuge);
    const resp3 = await Promise.all([page.waitForResponse(r => r.url().includes('/admin/site-content/') && r.request().method() === 'POST', { timeout: 60000 }), page.click('[data-testid=save-page]')]).catch(e => [{ status: () => -1 }]);
    console.log('   30MB upload status', resp3[0].status());
    // NOTE: php artisan serve (memory_limit 128M, post_max_size 8M) fatals with 500 on a 30MB body; prod nginx/FPM would 413. Informational only.
    const after = await get(URL);
    check(R, 'upload: public page still fine after rejected upload', after.status === 200 && !BAD_RE.test(after.text));

    // remove all rows then save, then page ok; reorder & save order; double submit
    await revertPage(page, KEY);
    await openEditor(page, KEY);
    const names0 = await rows().locator('input[name$="[name]"]').evaluateAll(a => a.map(x => x.value));
    await rows().nth(2).locator('[data-act=up]').click();
    await rows().nth(1).locator('[data-act=up]').click(); // original idx2 -> idx0
    await rows().last().locator('[data-act=up]').click();
    const wantOrder = await rows().locator('input[name$="[name]"]').evaluateAll(a => a.map(x => x.value));
    // double submit
    await page.evaluate(() => { const b = document.querySelector('[data-testid=save-page]'); b.click(); b.click(); });
    await page.waitForSelector('.bg-green-50');
    await openEditor(page, KEY);
    const gotOrder = await rows().locator('input[name$="[name]"]').evaluateAll(a => a.map(x => x.value));
    check(R, 'reorder: saved order is reloaded in the editor', JSON.stringify(gotOrder) === JSON.stringify(wantOrder), gotOrder.slice(0, 4).join('|') + ' vs ' + wantOrder.slice(0, 4).join('|'));
    check(R, 'reorder: moved row moved', wantOrder[0] === names0[2], wantOrder[0] + ' vs ' + names0[2]);
    html = (await get(URL)).text;
    check(R, 'reorder: public page follows saved order', html.indexOf(wantOrder[0]) < html.indexOf(wantOrder[1]));
    const cnt = await page.evaluate(async () => (await (await fetch(location.href)).text()).length);
    check(R, 'double submit: row count unchanged', (await rows().count()) === names0.length, await rows().count());

    // remove-all rows
    await openEditor(page, KEY);
    page.on('dialog', d => d.accept());
    while (await rows().count()) await rows().first().locator('[data-act=remove]').click();
    await save(page);
    await openEditor(page, KEY);
    check(R, 'remove-all: repeater saved empty (0 rows)', (await rows().count()) === 0, await rows().count());
    await noBad('remove-all partners', URL);
    // add new row from empty
    await partners().locator(':scope > [data-act=add-row]').click();
    await rows().first().locator('input[name$="[name]"]').fill('Only One');
    await save(page);
    html = (await get(URL)).text;
    check(R, 'add from empty: row shown', html.includes('Only One'));
    page.removeAllListeners('dialog');

    // nested repeaters (find any registry repeater with a repeater sub-field in my pages)
    const nested = [];
    for (const p of pages) for (const [s, fs_] of Object.entries(p.schema)) for (const [f, d] of Object.entries(fs_)) if (d.sub && Object.values(d.sub).includes('repeater')) nested.push(`${p.key}:${s}.${f}`);
    console.log('   nested repeaters in my groups:', nested.join(', ') || 'none');
    await revertPage(page, KEY);
  }

  // ------------------------------------------------------------------ 7. dead links on my pages
  if (want('links')) {
    const seen = new Map();
    for (const p of pages) {
      const r = await get(p.url);
      const hrefs = [...r.text.matchAll(/\b(?:href|src)="([^"#][^"]*)"/g)].map(m => m[1].replace(/&amp;/g, '&'));
      for (const h of hrefs) {
        if (/^(mailto:|tel:|javascript:|data:)/.test(h)) continue;
        let u = h.startsWith('//') ? 'https:' + h : h.startsWith('http') ? h : BASE + (h.startsWith('/') ? h : '/' + h);
        const internal = u.startsWith(BASE) || /^https?:\/\/(aseanapol\.test|127\.0\.0\.1)/.test(u);
        if (!internal) continue;
        u = u.replace(/^https?:\/\/(aseanapol\.test|127\.0\.0\.1:\d+)/, BASE);
        if (!seen.has(u)) seen.set(u, []);
        seen.get(u).push(p.key);
      }
    }
    for (const [u, from] of seen) {
      const s = (await page.request.get(u, { maxRedirects: 5 })).status();
      if (s >= 400) check(R, `dead link ${u.replace(BASE, '')} (from ${from[0]}${from.length > 1 ? ' +' + (from.length - 1) : ''})`, false, 'HTTP ' + s);
    }
    check(R, `links: ${seen.size} internal links/assets across ${pages.length} pages all resolve`, true);
  }
} catch (e) {
  check(R, 'qa-c crashed', false, String(e.stack || e).split('\n').slice(0, 4).join(' / '));
} finally {
  await cleanup();
  console.log('screenshots in ' + SHOTS);
}
finish(R, errors);
await browser.close();
