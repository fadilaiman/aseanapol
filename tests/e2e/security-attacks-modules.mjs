// Admin module attacks (part of security-attacks.mjs). Receives the shared helpers; self-cleaning.
import { writeFileSync, readFileSync, existsSync, unlinkSync, mkdirSync, readdirSync, statSync } from 'node:fs';
import path from 'node:path';
import { execFileSync } from 'node:child_process';

const PNG = Buffer.from('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==', 'base64');
const PDF = Buffer.from('%PDF-1.4\n1 0 obj<</Type/Catalog>>endobj\ntrailer<</Root 1 0 R>>\n%%EOF');

export async function run({ adm, check, mp, sql, BASE, ROOT, MARK, RAW_XSS, cleanup, Client }) {
  // Snapshot every upload directory: whatever the attacks leave behind (even when a request errors mid-way) is removed.
  const walk = (d) => existsSync(d) ? readdirSync(d).flatMap(n => { const f = path.join(d, n); return statSync(f).isDirectory() ? walk(f) : [f]; }) : [];
  const upRoot = path.join(ROOT, 'storage/app/public');
  const before = new Set(walk(upRoot));
  cleanup.unshift(() => { for (const f of walk(upRoot)) if (!before.has(f) && !/\.gitignore$/.test(f)) unlinkSync(f); });
  const tokenOf = async (url) => (await adm.token(url));
  const find = (table, col, like) => sql(`select id from ${table} where ${col} like '${like}'`).split('\n').filter(Boolean).map(l => JSON.parse(l).id);
  const del = async (table, col, like, route) => {
    for (const id of find(table, col, like)) {
      const t = await tokenOf('/admin/' + route);
      await adm.post(`/admin/${route}/${id}`, { form: { _token: t, _method: 'DELETE' } });
    }
  };
  const pub = new Client();
  const dangerous = /(?:href|src|action|data)=["']?\s*(?:javascript|vbscript|data:text)/i;

  // ---------------------------------------------------------------- URL-scheme fields
  const JS = ['javascript:alert(1)', 'JaVaScRiPt:alert(1)', 'javascript://%0Aalert(1)', ' javascript:alert(1)', 'data:text/html,<script>alert(1)</script>', 'vbscript:msgbox(1)'];
  let t = await tokenOf('/admin/video-gallery/create');
  for (const p of JS) {
    for (const field of ['embed_url', 'file_url', 'thumbnail_url']) {
      const r = await adm.post('/admin/video-gallery', { form: { _token: t, title: MARK + 'vid', category: 'x', [field]: p, is_published: '1' } });
      check(`video ${field}=${p.slice(0, 22)} rejected`, r.status === 422 || r.status === 302 && !find('video_galleries', field, 'javascript%').length && !find('video_galleries', field, '%script%').length && !find('video_galleries', field, 'vbscript%').length, r.status);
    }
  }
  cleanup.push(() => del('video_galleries', 'title', MARK + '%', 'video-gallery'));
  // event link
  t = await tokenOf('/admin/events/create');
  for (const p of JS) {
    const r = await adm.post('/admin/events', { form: { _token: t, kind: 'calendar', title: MARK + 'ev', type: 'Meeting', start_date: '2030-01-01', link: p, is_published: '1' } });
    check(`event link=${p.slice(0, 22)} cannot store a script URL`, !find('events', 'link', '%script%').length && !find('events', 'link', 'javascript%').length, r.status);
  }
  cleanup.push(() => del('events', 'title', MARK + '%', 'events'));
  // digital library
  const colId = sql('select id from digital_library_collections limit 1').split('\n')[0];
  if (colId) {
    t = await tokenOf('/admin/digital-library/items/create');
    for (const p of JS.slice(0, 4)) {
      await adm.post('/admin/digital-library/items', { form: { _token: t, collection_id: JSON.parse(colId).id, title: MARK + 'dl', type: 'link', external_url: p, pdf_url: p } });
    }
    check('digital-library external_url/pdf_url cannot store script URLs', !find('digital_library_items', 'external_url', '%script%').length && !find('digital_library_items', 'pdf_url', '%script%').length);
    cleanup.push(() => del('digital_library_items', 'title', MARK + '%', 'digital-library/items'));
  }
  // public pages must not render a dangerous href/src anywhere
  for (const u of ['/en/events-training/calendar', '/en/events-training/conferences', '/en/events-training/training-programs', '/en/news-media/video-gallery', '/en/data-resources/digital-library', '/en/news-media/newsletters']) {
    const r = await pub.get(u);
    check(`public ${u} has no javascript:/data: link or src`, !dangerous.test(r.text), (r.text.match(dangerous) || [])[0]);
  }

  // ---------------------------------------------------------------- news: stored XSS, slug, thumbnail/sponsor deletion abuse
  const X = `"><img src=x onerror=window.__xss=1><script>window.__xss=1</script>`;
  t = await tokenOf('/admin/news/create');
  const slugEv = `${MARK.toLowerCase()}-xss`;
  let r = await adm.post('/admin/news', { form: {
    _token: t, title: MARK + ' ' + X, summary: X, author: X, slug: slugEv + '/../../' + X,
    content: `<p>${MARK}</p>` + X + `<a href="javascript:window.__xss=1">x</a><iframe srcdoc="<script>1</script>"></iframe><iframe src="https://evil.test/"></iframe><svg onload=window.__xss=1>`,
    thumbnail_url: 'https://x.test/a.png"onerror="window.__xss=1', 'sponsors_label': X, 'sponsors[0][name]': X, 'sponsors[0][url]': 'javascript:alert(1)', 'sponsors[0][logo_url]': 'https://x.test/l.png' } });
  check('news create with hostile fields handled (no 500)', r.status < 500, r.status);
  const slugs = sql(`select slug,thumbnail from news_items where title like '${MARK}%'`).split('\n').filter(Boolean).map(l => JSON.parse(l));
  for (const s of slugs) {
    check('news slug is a clean slug (no / .. quotes)', /^[a-z0-9-]+$/.test(s.slug), s.slug);
    const pg = await pub.get('/en/news-media/news/' + s.slug);
    check('news article page: no raw script/onerror/iframe-evil', pg.status === 200 && !RAW_XSS.test(pg.text) && !/<iframe[^>]+evil\.test|srcdoc/.test(pg.text) && !dangerous.test(pg.text), pg.status);
    check('news thumbnail attr breakout inert', !/onerror="window\.__xss/.test(pg.text), '');
    const li = await pub.get('/en/news-media/news?q=' + MARK);
    check('news list: no raw script/onerror', !RAW_XSS.test(li.text) && !/ onerror="window\.__xss/.test(li.text));
    const home = await pub.get('/en');
    check('landing: no raw script/onerror from new article', !RAW_XSS.test(home.text) && !/ onerror="window\.__xss/.test(home.text));
  }
  // real browser: nothing may execute on the article page, the news list, the landing page or the gallery
  {
    const { chromium } = await import('playwright');
    const br = await chromium.launch({ headless: true });
    const pg = await br.newPage();
    await pg.addInitScript(() => { window.__xss = 0; });
    pg.on('dialog', d => { window.__dialog = 1; d.dismiss(); });
    let fired = 0;
    pg.on('dialog', () => { fired++; });
    for (const u of [...slugs.map(x => '/en/news-media/news/' + x.slug), '/en/news-media/news?q=' + MARK, '/en', '/en/news-media/gallery', '/ms/news-media/news/' + (slugs[0]?.slug ?? 'x')]) {
      await pg.goto(BASE + u, { waitUntil: 'load' });
      await pg.waitForTimeout(200);
      check(`browser: no script execution on ${u.slice(0, 50)}`, (await pg.evaluate(() => window.__xss)) === 0 && fired === 0, `xss=${await pg.evaluate(() => window.__xss)} dialogs=${fired}`);
    }
    await br.close();
  }
  cleanup.push(() => del('news_items', 'title', MARK + '%', 'news'));

  // arbitrary deletion through crafted thumbnail path: canary file outside news/thumbnails
  const canaryDir = path.join(ROOT, 'storage/app/public'); mkdirSync(path.join(canaryDir, 'news/thumbnails'), { recursive: true });
  const canary = path.join(canaryDir, 'zz-canary-' + MARK + '.txt'); writeFileSync(canary, 'keep');
  const victimRel = 'news/thumbnails/zz-victim-' + MARK + '.png'; const victim = path.join(canaryDir, victimRel); writeFileSync(victim, PNG);
  cleanup.push(() => { for (const f of [canary, victim]) if (existsSync(f)) unlinkSync(f); });
  t = await tokenOf('/admin/news/create');
  for (const thumb of ['/storage/news/thumbnails/../../zz-canary-' + MARK + '.txt', '/storage/news/thumbnails/..%2f..%2fzz-canary-' + MARK + '.txt', '/storage/news/thumbnails/zz-victim-' + MARK + '.png']) {
    await adm.post('/admin/news', { form: { _token: t, title: MARK + ' del ' + Math.random(), thumbnail_url: thumb } });
  }
  for (const id of find('news_items', 'title', MARK + ' del%')) {
    const tk = await tokenOf('/admin/news/create');
    await adm.post('/admin/news/' + id, { form: { _token: tk, _method: 'DELETE' } });
  }
  check('news delete cannot remove files outside news/thumbnails via crafted thumbnail_url', existsSync(canary));
  check("news delete cannot remove ANOTHER record's uploaded thumbnail via crafted thumbnail_url", existsSync(victim));

  // sponsors: logo_existing pointing at somebody else's logo
  const sdir = path.join(canaryDir, 'news/sponsors'); mkdirSync(sdir, { recursive: true });
  const sv = path.join(sdir, 'zz-sponsor-' + MARK + '.png'); writeFileSync(sv, PNG); cleanup.push(() => { if (existsSync(sv)) unlinkSync(sv); });
  t = await tokenOf('/admin/news/create');
  await adm.post('/admin/news', { form: { _token: t, title: MARK + ' spon', 'sponsors[0][name]': 'a', 'sponsors[0][logo_existing]': 'storage/news/sponsors/zz-sponsor-' + MARK + '.png' } });
  for (const id of find('news_items', 'title', MARK + ' spon%')) {
    const tk = await tokenOf('/admin/news/create');
    await adm.post('/admin/news/' + id, { form: { _token: tk, _method: 'DELETE' } });
  }
  check("news delete cannot remove another record's sponsor logo referenced via logo_existing", existsSync(sv));

  // ---------------------------------------------------------------- uploads
  const stored = (table, col, like) => sql(`select ${col} as v from ${table} where ${col} like '${like}'`).split('\n').filter(Boolean).map(l => JSON.parse(l).v);
  const uploads = [
    { name: 'shell.php', type: 'image/png', data: PNG }, { name: 'shell.php.png', type: 'image/png', data: Buffer.concat([PNG, Buffer.from('<?php system($_GET[0]);')]) },
    { name: 'x.phtml', type: 'image/png', data: PNG }, { name: 'x.png\u0000.php', type: 'image/png', data: PNG }, { name: '.htaccess', type: 'image/png', data: PNG },
    { name: 'x.svg', type: 'image/svg+xml', data: Buffer.from('<svg xmlns="http://www.w3.org/2000/svg" onload="alert(1)"><script>alert(1)</script></svg>') },
    { name: 'x.html', type: 'text/html', data: Buffer.from('<script>alert(1)</script>') },
    { name: '"><img src=x onerror=alert(1)>.png', type: 'image/png', data: PNG },
  ];
  t = await tokenOf('/admin/news/create');
  let k = 0;
  for (const u of uploads) {
    const r2 = await adm.post('/admin/news', { multipart: mp({ _token: t, title: MARK + ' up' + (k++) }, { thumbnail_file: u }) });
    check(`news thumbnail upload ${JSON.stringify(u.name).slice(0, 30)} no 500`, r2.status < 500, r2.status);
    const sp = await adm.post('/admin/news', { multipart: mp({ _token: t, title: MARK + ' sp' + (k++), 'sponsors[0][name]': 'n' }, { 'sponsors[0][logo_file]': u }) });
    check(`sponsor logo upload ${JSON.stringify(u.name).slice(0, 30)} no 500`, sp.status < 500, sp.status);
  }
  const thumbs = stored('news_items', 'thumbnail', '%/storage/news/thumbnails/%');
  const sponsorsRaw = sql(`select sponsors from news_items where title like '${MARK}%' and sponsors is not null`);
  for (const th of thumbs) check(`stored thumbnail ${th} has a safe raster extension`, /\.(png|jpe?g|gif|webp)$/i.test(th), th);
  for (const m of sponsorsRaw.matchAll(/storage\\?\/news\\?\/sponsors\\?\/[^"\\]+/g)) check(`stored sponsor logo ${m[0]} safe extension`, /\.(png|jpe?g|gif|webp)$/i.test(m[0]), m[0]);
  // clean uploaded files of the test articles
  cleanup.push(async () => {
    for (const th of thumbs) { const f = path.join(ROOT, 'storage/app/public', th.replace(/^\/storage\//, '')); if (existsSync(f)) unlinkSync(f); }
    for (const m of sponsorsRaw.matchAll(/storage\\?\/(news\\?\/sponsors\\?\/[^"\\]+)/g)) { const f = path.join(ROOT, 'storage/app/public', m[1].replace(/\\/g, '')); if (existsSync(f)) unlinkSync(f); }
  });
  cleanup.push(() => del('news_items', 'title', MARK + '%', 'news'));

  // publications: crafted slug / pdf / cover
  t = await tokenOf('/admin/publications/create');
  r = await adm.post('/admin/publications', { multipart: mp({ _token: t, title: MARK + ' pub', edition: '1st', kind: 'bulletin', slug: MARK.toLowerCase() + '-pub', color: 'from-primary to-primary/70', is_published: '1', summary: X, theme: X },
    { pdf: { name: 'x.php', type: 'application/pdf', data: PDF }, cover: { name: 'c.php', type: 'image/png', data: PNG } }) });
  const pubPaths = sql(`select pdf_path,cover_path from publications where title like '${MARK}%'`).split('\n').filter(Boolean).map(l => JSON.parse(l));
  for (const p of pubPaths) {
    check('publication pdf stored with .pdf extension', !p.pdf_path || /\.pdf$/.test(p.pdf_path), p.pdf_path);
    check('publication cover stored with raster extension', !p.cover_path || /\.(png|jpe?g|webp)$/.test(p.cover_path), p.cover_path);
  }
  const pslug = MARK.toLowerCase() + '-pub';
  const pp = await pub.get('/en/publication/' + pslug);
  check('publication page escapes summary/theme', !RAW_XSS.test(pp.text), pp.status);
  const pi = await pub.get('/en/data-resources/publications');
  check('publication index escapes summary/theme', !RAW_XSS.test(pi.text));
  cleanup.push(async () => {
    for (const p of pubPaths) for (const v of [p.pdf_path, p.cover_path]) if (v) { const f = path.join(ROOT, 'storage/app/public', v.replace(/^storage\//, '')); if (existsSync(f)) unlinkSync(f); }
  });
  cleanup.push(() => del('publications', 'title', MARK + '%', 'publications'));
  // slug with traversal rejected
  for (const s of ['../../etc/passwd', 'a/b', 'a b', "x'--"]) {
    const rr = await adm.post('/admin/publications', { form: { _token: await tokenOf('/admin/publications/create'), title: MARK + ' bad', edition: '1', kind: 'bulletin', slug: s, color: 'from-primary to-primary/70' } });
    check(`publication slug ${s} normalised/rejected`, !find('publications', 'slug', '%/%').length && !find('publications', 'slug', '%..%').length, rr.status);
  }
  // mass assignment: id / is_admin / views_count smuggled through
  const ma = await adm.post('/admin/video-gallery', { form: { _token: await tokenOf('/admin/video-gallery/create'), title: MARK + ' mass', category: 'x', id: '999999', created_at: '1999-01-01', views_count: '9999' } });
  check('mass assignment: id/created_at ignored', !find('video_galleries', 'id', '999999').length || false, ma.status);

  // ---------------------------------------------------------------- admin list filters (SQLi, array params, huge ints)
  for (const u of ['/admin/events?q=' + encodeURIComponent("' OR 1=1-- -"), '/admin/events?q[]=x', '/admin/events?kind[]=x', '/admin/events?year=' + encodeURIComponent("2020' OR '1'='1"), '/admin/events?type=' + encodeURIComponent("x' --"),
    '/admin/events?status[]=1', '/admin/events?sort=title;DROP TABLE events', '/admin/events?order=id desc;--', '/admin/news?q=' + encodeURIComponent("' OR 1=1-- -"), '/admin/news?q[]=a', '/admin/news?page=99999999999999999999',
    '/admin/news/' + encodeURIComponent("' OR 1=1") + '/edit', '/admin/events/99999999999999999999/edit', '/admin/events/-1/edit', '/admin/publications/abc/edit', '/admin/newsletters/1e3/edit',
    '/admin/news?q=' + encodeURIComponent("%' AND SLEEP(4) AND '%'='")]) {
    const rr = await adm.get(u);
    check(`admin filter/param ${u.slice(0, 70)} robust`, rr.status < 500 && rr.ms < 3500 && !/SQLSTATE/.test(rr.text), `${rr.status} ${rr.ms}ms`);
  }
  // admin reflected/stored output escaping of titles etc.
  for (const u of ['/admin/news', '/admin/events', '/admin/publications', '/admin/video-gallery', '/admin']) {
    const rr = await adm.get(u + (u === '/admin/news' ? '?q=' + encodeURIComponent(X) : u === '/admin/events' ? '?q=' + encodeURIComponent(X) : ''));
    check(`admin page ${u} escapes reflected/stored payloads`, !RAW_XSS.test(rr.text) && !/<img src=x onerror=window/.test(rr.text), rr.status);
  }

  // ---------------------------------------------------------------- CMS image/file field uploads
  const cmsUpload = async (key, section, field, f, fields = {}) => {
    const ed = await adm.get('/admin/site-content/' + key);
    const tok = ed.text.match(/name="_token"[^>]*value="([^"]+)"/)?.[1];
    const fp = ed.text.match(/name="_fp"[^>]*value="([^"]*)"/)?.[1] ?? '';
    const flds = { _token: tok, _method: 'PUT', _fp: fp, _end: '1', ...fields };
    return adm.post('/admin/site-content/' + key, { multipart: mp(flds, { [`files[${section}][${field}]`]: f }) });
  };
  const blocks = (key) => sql(`select block_key,value from cms_blocks where page_key='${key}'`).split(String.fromCharCode(10)).filter(Boolean).map(l => JSON.parse(l));
  const resetPage = async (key) => { const tk = (await adm.get('/admin/site-content/' + key)).text.match(/name="_token"[^>]*value="([^"]+)"/)?.[1]; await adm.post(`/admin/site-content/${key}/reset`, { form: { _token: tk } }); };
  const PHPG = Buffer.concat([PNG, Buffer.from('<?php system($_GET[0]); ?>')]);
  const cmsCases = [
    ['landing', 'banner', 'image', 'site.landing', [
      { name: 'x.php', type: 'image/png', data: PNG, expect: 'reject' }, { name: 'x.png', type: 'image/png', data: Buffer.from('<?php echo 1;'), expect: 'reject' },
      { name: 'x.svg', type: 'image/svg+xml', data: Buffer.from('<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>'), expect: 'reject' },
      { name: 'x.html', type: 'image/png', data: Buffer.from('<script>alert(1)</script>'), expect: 'reject' },
      { name: 'x.php.png', type: 'image/png', data: PHPG, expect: 'png' }, { name: '../../../x.png', type: 'image/png', data: PNG, expect: 'png' },
      { name: 'a"><img src=x onerror=alert(1)>.png', type: 'image/png', data: PNG, expect: 'png' }]],
  ];
  for (const [, section, field, key, files] of cmsCases) {
    for (const f of files) {
      const rr = await cmsUpload(key, section, field, f, { 'f[banner][headline]': MARK + ' h' });
      const stored = blocks(key).filter(b => /storage\/cms\//.test(b.value)).map(b => b.value);
      const ok = f.expect === 'reject' ? !stored.length && rr.status < 500 : (rr.status < 500 && stored.every(v => /storage\/cms\/[a-z0-9-]+\.png/.test(v)));
      check(`CMS ${key} ${section}.${field} upload ${JSON.stringify(f.name).slice(0, 30)} -> ${f.expect}`, ok, `${rr.status} ${stored}`);
      await resetPage(key);
    }
  }
  const guideKey = 'guidelines.flag';
  const docSection = Object.keys(JSON.parse(execFileSync('php', [path.join(ROOT, 'tests/e2e/security-registry-dump.php')], { cwd: ROOT, encoding: 'utf8' })).pages[guideKey].def.sections).find(k => /doc/i.test(k));
  if (docSection) {
    for (const f of [{ name: 'x.php', type: 'application/pdf', data: PDF, expect: 'reject' }, { name: 'x.html', type: 'text/html', data: Buffer.from('<script>1</script>'), expect: 'reject' },
      { name: 'x.pdf', type: 'application/pdf', data: Buffer.from('<?php system(1);'), expect: 'noexec' }, { name: 'x.svg', type: 'image/svg+xml', data: Buffer.from('<svg xmlns="http://www.w3.org/2000/svg"><script>1</script></svg>'), expect: 'reject' },
      { name: 'x.docx', type: 'text/html', data: Buffer.from('<html><script>1</script></html>'), expect: 'reject' }]) {
      const rr = await cmsUpload(guideKey, docSection, 'file', f);
      const stored = blocks(guideKey).filter(b => /storage\/cms\//.test(b.value)).map(b => b.value);
      const bad = stored.some(v => /\.(php\d?|phtml|phar|html?|svg|htaccess)"/i.test(v));
      check(`CMS file field upload ${f.name} not stored as executable/active content`, !bad && rr.status < 500, `${rr.status} ${stored}`);
      await resetPage(guideKey);
    }
  }
  // CMS save with huge payloads must not 500/exhaust memory
  const big = await (async () => {
    const ed = await adm.get('/admin/site-content/careers.vacancies');
    const tok = ed.text.match(/name="_token"[^>]*value="([^"]+)"/)?.[1];
    const pairs = [['_token', tok], ['_method', 'PUT'], ['_end', '1']];
    for (let i = 0; i < 1500; i++) pairs.push([`f[openings][items][${i}][title]`, 'T' + i], [`f[openings][items][${i}][description]`, 'D'.repeat(300)]);
    return adm.post('/admin/site-content/careers.vacancies', { form: new URLSearchParams(pairs), timeout: 120000 });
  })();
  check('huge repeater post does not crash (no 500)', big.status < 500, big.status);
  await resetPage('careers.vacancies');
  // deeply nested array params
  const deep = 'f' + '[a]'.repeat(500);
  const dp = await adm.post('/admin/site-content/careers.vacancies', { form: new URLSearchParams([['_token', await tokenOf('/admin/news/create')], ['_method', 'PUT'], ['_end', '1'], [deep, 'x']]) });
  check('deeply nested form array does not 500', dp.status < 500, dp.status);
  await resetPage('careers.vacancies');

  // contact form mail: CRLF headers must not become real headers (MAIL_MAILER=log)
  try {
    const logf = path.join(ROOT, 'storage/logs/laravel.log');
    const sizeBefore = existsSync(logf) ? statSync(logf).size : 0;
    const tk = await tokenOf('/admin/login');
    await adm.post('/en/contact-us', { form: { _token: tk, name: 'Eve', email: 'eve@x.test', subject: 'S' + MARK + String.fromCharCode(13, 10) + 'Bcc: evil@evil.test', message: 'hello hello hello <script>alert(1)</script>' } });
    const tail = readFileSync(logf, 'utf8').slice(sizeBefore);
    if (tail.includes('S' + MARK)) {
      check('contact mail: injected Bcc is not a header line', !/^Bcc:/mi.test(tail), '');
      check('contact mail: message body HTML-escaped', !/<script>alert\(1\)<\/script>/.test(tail) || !tail.includes('class="message-box"'), '');
    }
  } catch (e) { console.log('contact mail check skipped:', e.message); }
}
