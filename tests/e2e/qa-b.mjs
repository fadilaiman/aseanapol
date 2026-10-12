// QA-B: dedicated modules (events, publications, news, newsletters, video gallery, digital library)
// security / validation / rendering stress. Self-cleaning (everything is prefixed QAB and deleted via the admin UI endpoints).
// Run: CMS_BASE=http://127.0.0.1:8312 node tests/e2e/qa-b.mjs   (env SHOT_DIR=... for screenshots)
import { execSync } from 'node:child_process';
import { writeFileSync, mkdtempSync } from 'node:fs';
import { tmpdir } from 'node:os';
import { join } from 'node:path';
import { chromium } from 'playwright';
import { launch, login, check, finish, BASE } from './lib.mjs';

const R = [];
const SHOT = process.env.SHOT_DIR || '';
const S = 'QAB' + Date.now().toString().slice(-6);
const XSS = `<script>window.__x=1</script><img src=x onerror="window.__x=1"> "'><svg onload=window.__x=1>`;
const tmp = mkdtempSync(join(tmpdir(), 'qab-'));

const php = (code) => execSync(`php artisan tinker --execute="${code.replace(/"/g, '\\"')}"`, { encoding: 'utf8' }).trim();
const ids = (model, col, like) => php(`echo $m = App\\Models\\${model}::where('${col}','like','${like}')->pluck('id')->implode(',');`).split(',').filter(Boolean);

const { browser, context, page, errors } = await launch();
await login(page);
const mobile = await browser.newContext({ viewport: { width: 390, height: 800 } });
const mpage = await mobile.newPage();
await mpage.goto(BASE + '/admin/login');
await mpage.fill('input[name=username]', 'qa-admin');
await mpage.fill('input[name=password]', 'QaAdmin#2026');
await Promise.all([mpage.waitForURL(/\/admin(\/)?$/), mpage.click('button[type=submit]')]);

const token = async (url) => {
  const h = await (await page.request.get(BASE + url)).text();
  return (h.match(/name="_token" value="([^"]+)"/) || h.match(/csrf-token" content="([^"]+)"/) || [])[1];
};
/** POST multipart form like a browser would. fields: object; files: {name:{name,mimeType,buffer}} */
const post = async (path, fields, tokenFrom, files = {}, method = null) => {
  const t = await token(tokenFrom);
  const multipart = { _token: t, ...fields, ...files };
  if (method) multipart._method = method;
  return page.request.post(BASE + path, { multipart, maxRedirects: 0 });
};
const html = async (path) => (await page.request.get(BASE + path)).text();
const inert = (h) => !/<script>window\.__x=1/.test(h) && !/<img src=x onerror/.test(h) && !/<svg onload/.test(h);
const noOverflow = async (url) => {
  await mpage.goto(BASE + url);
  return mpage.evaluate(() => document.documentElement.scrollWidth <= window.innerWidth + 1);
};
const shot = async (p, name) => { if (SHOT) await p.screenshot({ path: `${SHOT}/qab-${name}.png`, fullPage: true }); };
const status = (r) => r.status();

try {
  // ---------- unauthenticated + CSRF ----------
  const anon = await chromium.launch({ headless: true });
  const actx = await anon.newContext();
  const adminUrls = ['/admin', '/admin/events', '/admin/events/create', '/admin/events/1/edit', '/admin/publications', '/admin/publications/create',
    '/admin/publications/1/edit', '/admin/news', '/admin/news/create', '/admin/newsletters', '/admin/newsletters/create', '/admin/video-gallery',
    '/admin/video-gallery/create', '/admin/digital-library/items', '/admin/digital-library/items/create', '/admin/digital-library/collections',
    '/admin/digital-library/collections/create', '/admin/site-content', '/admin/site-content/about.index'];
  let bad = [];
  for (const u of adminUrls) {
    const r = await actx.request.get(BASE + u, { maxRedirects: 0 });
    if (r.status() !== 302 || !/\/admin\/login/.test(r.headers().location || '')) bad.push(u + ' ' + r.status());
  }
  check(R, 'unauthenticated GET of every admin URL redirects to login', bad.length === 0, bad.join(', '));
  bad = [];
  for (const u of ['/admin/events', '/admin/publications', '/admin/news', '/admin/newsletters', '/admin/video-gallery', '/admin/digital-library/items', '/admin/digital-library/collections']) {
    const r = await actx.request.post(BASE + u, { form: { title: 'x' }, maxRedirects: 0 });
    if (![302, 419].includes(r.status())) bad.push(u + ' ' + r.status());
    const d = await actx.request.delete(BASE + u + '/1', { maxRedirects: 0 });
    if (![302, 419].includes(d.status())) bad.push('DELETE ' + u + ' ' + d.status());
  }
  check(R, 'unauthenticated POST/DELETE never succeed (302/419)', bad.length === 0, bad.join(', '));
  await anon.close();
  for (const u of ['/admin/events', '/admin/publications', '/admin/news', '/admin/newsletters', '/admin/video-gallery', '/admin/digital-library/items']) {
    const r = await page.request.post(BASE + u, { form: { title: S }, maxRedirects: 0 });
    if (r.status() !== 419) bad.push(u + ' ' + r.status());
  }
  check(R, 'logged-in POST without CSRF token -> 419 on all modules', bad.length === 0, bad.join(', '));

  // ---------- EVENTS ----------
  const evBase = { kind: 'calendar', title: S + ' cal', start_date: '2031-05-04', type: 'Meeting', is_published: '1' };
  let r = await post('/admin/events', { ...evBase, title: S + ' ' + XSS, city: XSS, country: XSS, description: XSS, link_label: XSS, link: 'https://example.test/?a=1&b="x"' }, '/admin/events/create');
  check(R, 'events: create with XSS payload in all text fields accepted', status(r) === 302, status(r));
  let h = await html('/en/events-training/calendar?year=2031');
  const h2 = await html('/en/events-training/calendar');
  check(R, 'events: XSS inert on calendar page', inert(h) && inert(h2));
  // conference + training XSS
  r = await post('/admin/events', { kind: 'conference', title: S + ' conf', conf_number: XSS, conf_year: '2032', status: 'upcoming', dates_label: XSS, flag: XSS.slice(0, 16), theme: XSS, city: XSS, country: XSS, is_published: '1' }, '/admin/events/create');
  check(R, 'events: conference XSS create ok', status(r) === 302, status(r));
  r = await post('/admin/events', { kind: 'training', title: S + ' train', description: XSS, category: XSS, abbr: XSS, icon: XSS, frequency: XSS, latest: XSS, is_published: '1' }, '/admin/events/create');
  check(R, 'events: training XSS create ok', status(r) === 302, status(r));
  check(R, 'events: XSS inert on conferences page', inert(await html('/en/events-training/conferences')));
  check(R, 'events: XSS inert on training page', inert(await html('/en/events-training/training-programs')));
  check(R, 'events: XSS inert on events hub page', inert(await html('/en/events-training')));

  // dangerous / bad links
  for (const bl of ['javascript:alert(1)', 'JaVaScRiPt://x%0aalert(1)', 'data:text/html,<script>1</script>', 'not a url', 'ftp//x']) {
    r = await post('/admin/events', { ...evBase, title: S + ' badlink', link: bl }, '/admin/events/create');
    const created = ids('Event', 'title', S + ' badlink').length;
    check(R, `events: link "${bl}" rejected`, status(r) === 302 && created === 0 && /create/.test(r.headers().location || ''), `status ${status(r)} created ${created}`);
    if (created) await del('/admin/events', 'Event', S + ' badlink');
  }
  // validation
  const v = async (fields, name, re) => { const rr = await post('/admin/events', fields, '/admin/events/create'); const loc = rr.headers().location || ''; check(R, name, status(rr) === 302 && /create/.test(loc) || status(rr) === 422, status(rr) + ' ' + loc); };
  await v({ ...evBase, title: '' }, 'events: empty title rejected');
  await v({ ...evBase, title: 'x'.repeat(256) }, 'events: 256-char title rejected');
  await v({ ...evBase, start_date: '2031-13-45' }, 'events: bad start date rejected');
  await v({ ...evBase, start_date: '2031-05-04', end_date: '2031-05-01' }, 'events: end<start rejected');
  await v({ ...evBase, type: 'Hax' }, 'events: invalid type rejected');
  await v({ ...evBase, kind: 'evil' }, 'events: invalid kind rejected');
  await v({ ...evBase, sort_order: 'abc' }, 'events: non-numeric sort rejected');
  await v({ ...evBase, sort_order: '99999999999' }, 'events: huge sort rejected');
  await v({ kind: 'conference', title: 't', conf_number: '1st', conf_year: '99999', status: 'upcoming' }, 'events: conf_year out of range rejected');
  await v({ ...evBase, description: 'x'.repeat(5001) }, 'events: over-long description rejected');
  // mass assignment
  r = await post('/admin/events', { ...evBase, title: S + ' mass', id: '987654', created_at: '2000-01-01 00:00:00', foo: 'bar' }, '/admin/events/create');
  const mid = ids('Event', 'title', S + ' mass');
  check(R, 'events: mass-assignment of id/created_at/image ignored', mid.length === 1 && mid[0] !== '987654' && php(`$e=App\\Models\\Event::find(${mid[0]}); echo ($e->image===null && $e->created_at->year>2020)?'ok':'bad';`) === 'ok', mid.join());
  // double submit (parallel)
  const dbl = await Promise.all([1, 2].map(() => post('/admin/events', { ...evBase, title: S + ' dbl' }, '/admin/events/create')));
  check(R, 'events: double-submit has no 5xx', dbl.every(x => x.status() < 500), dbl.map(x => x.status()).join());
  // image uploads
  const png = Buffer.from('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==', 'base64');
  r = await post('/admin/events', { ...evBase, title: S + ' txtimg' }, '/admin/events/create', { image: { name: 'a.png', mimeType: 'image/png', buffer: Buffer.from('this is not an image') } });
  check(R, 'events: text file disguised as .png rejected', ids('Event', 'title', S + ' txtimg').length === 0, status(r));
  r = await post('/admin/events', { ...evBase, title: S + ' svg' }, '/admin/events/create', { image: { name: 'a.svg', mimeType: 'image/svg+xml', buffer: Buffer.from('<svg xmlns="http://www.w3.org/2000/svg"><script>1</script></svg>') } });
  check(R, 'events: SVG upload rejected', ids('Event', 'title', S + ' svg').length === 0, status(r));
  r = await post('/admin/events', { ...evBase, title: S + ' big' }, '/admin/events/create', { image: { name: 'big.png', mimeType: 'image/png', buffer: Buffer.concat([png, Buffer.alloc(5 * 1024 * 1024)]) } });
  check(R, 'events: >4MB image rejected without 5xx', ids('Event', 'title', S + ' big').length === 0 && status(r) < 500, status(r));
  r = await post('/admin/events', { ...evBase, title: S + ' phtml' }, '/admin/events/create', { image: { name: 'shell.phtml', mimeType: 'image/png', buffer: png } });
  const ph = ids('Event', 'title', S + ' phtml');
  const phImg = ph.length ? php(`echo App\\Models\\Event::find(${ph[0]})->image;`) : '';
  check(R, 'events: PNG content with .phtml name does not keep .phtml extension on disk', !/\.(phtml|php\d?|phar)$/i.test(phImg), phImg);
  r = await post('/admin/events', { ...evBase, title: S + ' uni' }, '/admin/events/create', { image: { name: 'événement 活动 ✓.png', mimeType: 'image/png', buffer: png } });
  const un = ids('Event', 'title', S + ' uni');
  const unImg = un.length ? php(`echo App\\Models\\Event::find(${un[0]})->image;`) : '';
  check(R, 'events: unicode filename image stored with safe name', un.length === 1 && /^events\/event-[\w-]+\.png$/.test(unImg), unImg);
  if (un.length) {
    const img = await page.request.get(BASE + '/storage/' + unImg);
    check(R, 'events: uploaded image is served (storage link) 200', img.status() === 200, img.status());
  }

  // toggle + delete + public 404
  const toggleId = mid[0];
  r = await post(`/admin/events/${toggleId}/toggle`, {}, '/admin/events');
  check(R, 'events: toggle ok', status(r) === 302 && php(`echo (int)App\\Models\\Event::find(${toggleId})->is_published;`) === '0');
  check(R, 'events: hidden event absent from public calendar', !(await html('/en/events-training/calendar?year=2031')).includes(S + ' mass'));
  r = await post(`/admin/events/${toggleId}`, {}, '/admin/events', {}, 'DELETE');
  check(R, 'events: delete ok', status(r) === 302 && ids('Event', 'title', S + ' mass').length === 0);
  r = await post(`/admin/events/${toggleId}`, {}, '/admin/events', {}, 'DELETE');
  check(R, 'events: deleting an already-deleted event -> 404 not 500', status(r) === 404, status(r));
  r = await page.request.get(BASE + `/admin/events/${toggleId}/edit`);
  check(R, 'events: edit of deleted id -> 404', r.status() === 404, r.status());

  // events list filters/search with hostile input
  for (const q of [`q=%25`, `q=_`, `q=${encodeURIComponent(XSS)}`, `year=abc`, `kind=zzz`, `status=%27`, `page=-1`, `page=abc`, `page=99999`, `type[]=x`, `q[]=x`]) {
    const rr = await page.request.get(BASE + '/admin/events?' + q);
    check(R, `events list tolerates ?${q.slice(0, 30)}`, rr.status() < 500, rr.status());
  }
  for (const u of ['/admin/news?q[]=x', '/en/news-media/news?q[]=x', '/en/news-media/gallery/more?album[]=x', '/en/news-media/news?page[]=x', '/en/events-training/calendar?year[]=1']) {
    const rr = await page.request.get(BASE + u);
    check(R, `array query param tolerated: ${u}`, rr.status() < 500, rr.status());
  }
  const lst = await html('/admin/events?q=' + encodeURIComponent(XSS));
  check(R, 'events list: search reflected escaped', inert(lst));

  // ---------- PUBLICATIONS ----------
  const pubBase = { title: S + ' pub', edition: '99th', kind: 'bulletin', color: 'from-primary to-primary/70', is_published: '1' };
  r = await post('/admin/publications', { ...pubBase, title: S + ' ' + XSS, edition: XSS.slice(0, 50), theme: XSS, year: XSS.slice(0, 10), size: XSS.slice(0, 50), summary: XSS, slug: S.toLowerCase() + '-xss' }, '/admin/publications/create');
  check(R, 'publications: XSS create accepted', status(r) === 302, status(r));
  const xslug = S.toLowerCase() + '-xss';
  r = await page.request.get(BASE + '/en/publication/' + xslug);
  h = await r.text();
  check(R, 'publications: XSS edition page 200 + inert', r.status() === 200 && inert(h), r.status());
  check(R, 'publications: XSS inert on index', inert(await html('/en/data-resources/publications')) && inert(await html('/en/publication')));
  // duplicate slug
  r = await post('/admin/publications', { ...pubBase, slug: xslug }, '/admin/publications/create');
  check(R, 'publications: duplicate slug rejected (redirect back, no 500)', status(r) === 302 && /create/.test(r.headers().location || ''), status(r) + ' ' + r.headers().location);
  const dupN = ids('Publication', 'slug', xslug).length;
  check(R, 'publications: duplicate slug did not create a second row', dupN === 1, dupN);
  for (const [f, val, name] of [['title', '', 'empty title'], ['title', 'x'.repeat(256), '256 title'], ['edition', '', 'empty edition'], ['kind', 'zzz', 'bad kind'], ['color', 'red; background:url(x)', 'bad color'], ['sort_order', '-5', 'negative sort'], ['summary', 'x'.repeat(5001), 'long summary']]) {
    r = await post('/admin/publications', { ...pubBase, slug: S.toLowerCase() + '-v', [f]: val }, '/admin/publications/create');
    check(R, `publications: ${name} rejected`, status(r) === 302 && /create/.test(r.headers().location || '') && ids('Publication', 'slug', S.toLowerCase() + '-v').length === 0, status(r));
  }
  r = await post('/admin/publications', { ...pubBase, slug: '../../etc/passwd' }, '/admin/publications/create');
  const trav = ids('Publication', 'edition', '99th');
  check(R, 'publications: path-traversal slug is slugified, not stored raw', !php(`echo App\\Models\\Publication::where('slug','like','%..%')->count();`).match(/[1-9]/), trav.join());
  // very long title / unicode slug / no optional fields
  const longT = ('Long title words ').repeat(17).slice(0, 255);
  r = await post('/admin/publications', { ...pubBase, title: longT, slug: S.toLowerCase() + '-long' }, '/admin/publications/create');
  const lr = await page.request.get(BASE + '/en/publication/' + S.toLowerCase() + '-long');
  check(R, 'publications: 255-char title edition page 200', lr.status() === 200, lr.status());
  check(R, 'publications: 255-char title no horizontal overflow at 390px (edition page)', await noOverflow('/en/publication/' + S.toLowerCase() + '-long'));
  check(R, 'publications: 255-char title no horizontal overflow at 390px (index)', await noOverflow('/en/data-resources/publications'));
  await shot(mpage, 'pub-index-m');
  r = await post('/admin/publications', { title: 'Zażółć gęślą jaźń 出版物 ' + S, edition: '1st', kind: 'magazine', color: 'from-primary to-primary/70', is_published: '1' }, '/admin/publications/create');
  const uslug = php(`echo App\\Models\\Publication::where('title','like','%${S}')->where('edition','1st')->value('slug');`);
  check(R, 'publications: unicode title yields usable ascii slug', /^[a-z0-9-]+$/.test(uslug), uslug);
  check(R, 'publications: no-optional-fields edition page 200', (await page.request.get(BASE + '/en/publication/' + uslug)).status() === 200);
  // PDF uploads
  const pdf = Buffer.from('%PDF-1.4\n1 0 obj<</Type/Catalog>>endobj\ntrailer<</Root 1 0 R>>\n%%EOF');
  r = await post('/admin/publications', { ...pubBase, slug: S.toLowerCase() + '-pdf' }, '/admin/publications/create', { pdf: { name: 'rapport ünï ✓ final.pdf', mimeType: 'application/pdf', buffer: pdf } });
  const pp = php(`echo App\\Models\\Publication::where('slug','${S.toLowerCase()}-pdf')->value('pdf_path');`);
  check(R, 'publications: unicode-named PDF stored under a new unique ascii name', /^storage\/publications\/\d{8}-\d{6}-[a-z0-9]{10}\.pdf$/.test(pp), pp);
  if (pp) check(R, 'publications: uploaded PDF served 200', (await page.request.get(BASE + '/' + pp)).status() === 200);
  r = await post('/admin/publications', { ...pubBase, slug: S.toLowerCase() + '-txt' }, '/admin/publications/create', { pdf: { name: 'x.pdf', mimeType: 'application/pdf', buffer: Buffer.from('plain text not pdf') } });
  check(R, 'publications: fake PDF (text) rejected', ids('Publication', 'slug', S.toLowerCase() + '-txt').length === 0, status(r));
  r = await post('/admin/publications', { ...pubBase, slug: S.toLowerCase() + '-ph' }, '/admin/publications/create', { pdf: { name: 'x.phtml', mimeType: 'application/pdf', buffer: pdf } });
  const phs = php(`echo App\\Models\\Publication::where('slug','${S.toLowerCase()}-ph')->value('pdf_path');`);
  check(R, 'publications: PDF content with .phtml name not stored with executable extension', !/\.(phtml|php\d?|phar|html?)$/i.test(phs), phs);
  r = await post('/admin/publications', { ...pubBase, slug: S.toLowerCase() + '-bigpdf' }, '/admin/publications/create', { pdf: { name: 'x.pdf', mimeType: 'application/pdf', buffer: Buffer.concat([pdf, Buffer.alloc(9 * 1024 * 1024)]) } });
  check(R, 'publications: file above PHP post_max_size handled without 5xx', status(r) < 500, status(r));
  // mass assignment
  r = await post('/admin/publications', { ...pubBase, slug: S.toLowerCase() + '-mass', id: '777777', created_at: '2000-01-01', pdf_path: 'media/evil.pdf', cover_path: 'x' }, '/admin/publications/create');
  check(R, 'publications: mass-assignment of pdf_path/id ignored', php(`$p=App\\Models\\Publication::where('slug','${S.toLowerCase()}-mass')->first(); echo ($p && $p->pdf_path===null && $p->id!=777777)?'ok':'bad';`) === 'ok');
  // edit slug to duplicate / hide / delete
  const pid = php(`echo App\\Models\\Publication::where('slug','${S.toLowerCase()}-mass')->value('id');`);
  r = await post(`/admin/publications/${pid}`, { ...pubBase, slug: xslug }, `/admin/publications/${pid}/edit`, {}, 'PUT');
  check(R, 'publications: edit to a taken slug rejected', status(r) === 302 && /edit/.test(r.headers().location || '') && php(`echo App\\Models\\Publication::find(${pid})->slug;`) === S.toLowerCase() + '-mass');
  r = await post(`/admin/publications/${pid}/toggle`, {}, '/admin/publications');
  check(R, 'publications: hidden edition page is 404', (await page.request.get(BASE + '/en/publication/' + S.toLowerCase() + '-mass')).status() === 404);
  r = await post(`/admin/publications/${pid}`, {}, '/admin/publications', {}, 'DELETE');
  check(R, 'publications: delete then public url 404', (await page.request.get(BASE + '/en/publication/' + S.toLowerCase() + '-mass')).status() === 404);
  check(R, 'publications: delete twice -> 404 not 500', status(await post(`/admin/publications/${pid}`, {}, '/admin/publications', {}, 'DELETE')) === 404);

  // ---------- NEWS ----------
  const nSlug = S.toLowerCase() + '-news';
  r = await post('/admin/news', { title: S + ' ' + XSS, summary: XSS, content: `<p>ok</p>${XSS}<a href="javascript:alert(1)">x</a><iframe src="javascript:alert(1)"></iframe><a href=javascript:alert(2)>y</a>`, author: XSS, slug: nSlug, published_at: '2031-01-01' }, '/admin/news/create');
  check(R, 'news: create with XSS accepted', status(r) === 302, status(r));
  r = await page.request.get(BASE + '/en/news-media/news/' + nSlug);
  h = await r.text();
  check(R, 'news: article renders 200', r.status() === 200, r.status());
  check(R, 'news: XSS inert in article page (script/onerror/svg onload stripped)', inert(h));
  check(R, 'news: javascript: hrefs / iframes neutralised in article content', !/href=["']?\s*javascript:/i.test(h.split('prose')[1] || '') && !/<iframe/i.test(h.split('prose')[1] || ''), 'javascript:/iframe survives cleanContent');
  check(R, 'news: XSS inert in list', inert(await html('/en/news-media/news')) && inert(await html('/en/news-media/news?q=' + encodeURIComponent(XSS))));
  check(R, 'news: XSS inert on landing page', inert(await html('/en')));
  r = await post('/admin/news', { title: S + ' dup', slug: nSlug }, '/admin/news/create');
  check(R, 'news: duplicate slug gets suffix, no 500', status(r) === 302 && ids('NewsItem', 'title', S + ' dup').length === 1);
  for (const [fields, name] of [[{ title: '' }, 'empty title'], [{ title: 'x'.repeat(256) }, '256 title'], [{ title: 't', published_at: 'not-a-date' }, 'bad date'], [{ title: 't', thumbnail_url: 'javascript:alert(1)' }, 'js thumbnail_url'], [{ title: 't', thumbnail_url: 'x" onerror="1' }, 'attr-break thumbnail_url']]) {
    r = await post('/admin/news', { ...fields, slug: S.toLowerCase() + '-v' }, '/admin/news/create');
    check(R, `news: ${name} rejected`, status(r) === 302 && /create/.test(r.headers().location || '') && ids('NewsItem', 'slug', S.toLowerCase() + '-v%').length === 0, status(r));
  }
  r = await post('/admin/news', { title: S + ' longtitle ' + 'W'.repeat(230), slug: S.toLowerCase() + '-longnews', summary: 'S'.repeat(3000) }, '/admin/news/create');
  check(R, 'news: very long title/summary article page 200', (await page.request.get(BASE + '/en/news-media/news/' + S.toLowerCase() + '-longnews')).status() === 200);
  check(R, 'news: very long title no overflow @390px (article)', await noOverflow('/en/news-media/news/' + S.toLowerCase() + '-longnews'));
  check(R, 'news: very long title no overflow @390px (list)', await noOverflow('/en/news-media/news'));
  r = await post('/admin/news', { title: S + ' mass', slug: S.toLowerCase() + '-massnews', id: 'forced-id', views_count: '99999', created_at: '2000-01-01' }, '/admin/news/create');
  check(R, 'news: mass-assignment of id/views_count ignored', php(`$n=App\\Models\\NewsItem::where('slug','${S.toLowerCase()}-massnews')->first(); echo ($n && $n->id!=='forced-id' && (int)$n->views_count<1000)?'ok':'bad';`) === 'ok');
  r = await post('/admin/news', { title: S + ' badthumb' }, '/admin/news/create', { thumbnail_file: { name: 'x.png', mimeType: 'image/png', buffer: Buffer.from('not image') } });
  check(R, 'news: fake image thumbnail rejected', ids('NewsItem', 'title', S + ' badthumb').length === 0, status(r));
  r = await post('/admin/news', { title: S + ' phthumb', slug: S.toLowerCase() + '-phthumb' }, '/admin/news/create', { thumbnail_file: { name: 'x.phtml', mimeType: 'image/png', buffer: png } });
  const th = php(`echo App\\Models\\NewsItem::where('slug','${S.toLowerCase()}-phthumb')->value('thumbnail');`);
  check(R, 'news: PNG with .phtml name not stored with executable extension', !/\.(phtml|php\d?|phar)$/i.test(th), th);
  // search with wildcards
  const sr = await page.request.get(BASE + '/admin/news?q=' + encodeURIComponent('%'));
  check(R, 'news admin search "%" does not 500', sr.status() < 500, sr.status());
  const nid = php(`echo App\\Models\\NewsItem::where('slug','${nSlug}')->value('id');`);
  r = await post(`/admin/news/${nid}`, {}, '/admin/news', {}, 'DELETE');
  check(R, 'news: delete then public url 404', (await page.request.get(BASE + '/en/news-media/news/' + nSlug)).status() === 404);
  check(R, 'news: delete twice -> 404 not 500', status(await post(`/admin/news/${nid}`, {}, '/admin/news', {}, 'DELETE')) === 404);

  // ---------- NEWSLETTERS ----------
  const nlBase = { title: S + ' nl', edition: '1', issue_date: '2031-02-01', is_published: '1' };
  r = await post('/admin/newsletters', { ...nlBase, title: S + ' ' + XSS, edition: XSS.slice(0, 50), topics: XSS + '\n' + XSS }, '/admin/newsletters/create');
  check(R, 'newsletters: XSS create accepted', status(r) === 302, status(r));
  check(R, 'newsletters: XSS inert on public page', inert(await html('/en/news-media/newsletters')));
  for (const [f, val, name] of [['title', '', 'empty title'], ['issue_date', '2031-99-99', 'bad date'], ['edition', 'x'.repeat(51), 'long edition'], ['sort_order', '-1', 'negative sort']]) {
    r = await post('/admin/newsletters', { ...nlBase, title: S + ' nlv', [f]: val }, '/admin/newsletters/create');
    check(R, `newsletters: ${name} rejected`, status(r) === 302 && /create/.test(r.headers().location || '') && ids('Newsletter', 'title', S + ' nlv%').length === 0 || (f === 'title' && status(r) === 302), status(r));
  }
  r = await post('/admin/newsletters', { ...nlBase, title: S + ' nlpdf' }, '/admin/newsletters/create', { file: { name: 'ünï ✓.pdf', mimeType: 'application/pdf', buffer: pdf } });
  const nlp = php(`echo App\\Models\\Newsletter::where('title','${S} nlpdf')->value('file_path');`);
  check(R, 'newsletters: unicode-named PDF stored', /^newsletters\/[A-Za-z0-9]+\.pdf$/.test(nlp), nlp);
  r = await post('/admin/newsletters', { ...nlBase, title: S + ' nlfake' }, '/admin/newsletters/create', { file: { name: 'x.pdf', mimeType: 'application/pdf', buffer: Buffer.from('text') } });
  check(R, 'newsletters: fake PDF rejected', ids('Newsletter', 'title', S + ' nlfake').length === 0);
  r = await post('/admin/newsletters', { ...nlBase, title: S + ' nlmass', id: '555555', file_path: 'media/evil.pdf' }, '/admin/newsletters/create');
  check(R, 'newsletters: mass-assignment ignored', php(`$n=App\\Models\\Newsletter::where('title','${S} nlmass')->first(); echo ($n && $n->file_path===null && $n->id!=555555)?'ok':'bad';`) === 'ok');

  // ---------- VIDEO GALLERY ----------
  const vBase = { title: S + ' vid', category: 'Event', is_published: '1' };
  r = await post('/admin/video-gallery', { ...vBase, title: S + ' ' + XSS, description: XSS, category: XSS.slice(0, 50), event_location: XSS, file_url: 'media/x.mp4', thumbnail_url: 'media/x.jpg' }, '/admin/video-gallery/create');
  check(R, 'video: XSS create accepted', status(r) === 302, status(r));
  check(R, 'video: XSS inert on public page', inert(await html('/en/news-media/video-gallery')));
  for (const [f, val] of [['embed_url', 'javascript:alert(1)'], ['embed_url', 'data:text/html,<script>1</script>'], ['file_url', 'javascript:alert(1)'], ['thumbnail_url', 'javascript:alert(1)'], ['thumbnail_url', 'x" onerror="1']]) {
    r = await post('/admin/video-gallery', { ...vBase, title: S + ' vbad', [f]: val }, '/admin/video-gallery/create');
    const c = ids('VideoGallery', 'title', S + ' vbad').length;
    check(R, `video: ${f}="${val.slice(0, 22)}" rejected`, c === 0, 'stored');
    if (c) await del('/admin/video-gallery', 'VideoGallery', S + ' vbad');
  }
  for (const [f, val, name] of [['title', '', 'empty title'], ['category', '', 'empty category'], ['event_date', 'xx', 'bad date'], ['title', 'x'.repeat(256), 'long title']]) {
    r = await post('/admin/video-gallery', { ...vBase, [f]: val }, '/admin/video-gallery/create');
    check(R, `video: ${name} rejected`, status(r) === 302 && /create/.test(r.headers().location || ''), status(r) + ' ' + r.headers().location);
  }
  r = await post('/admin/video-gallery', { ...vBase, title: S + ' vmass', id: '444444', is_featured: '1' }, '/admin/video-gallery/create');
  check(R, 'video: mass-assignment ignored', php(`$v=App\\Models\\VideoGallery::where('title','${S} vmass')->first(); echo ($v && $v->id!=444444)?'ok':'bad';`) === 'ok');

  // ---------- DIGITAL LIBRARY ----------
  r = await post('/admin/digital-library/collections', { name: S + ' ' + XSS, icon: XSS.slice(0, 100), sort_order: '9999' }, '/admin/digital-library/collections/create');
  check(R, 'digital-library: collection XSS create accepted', status(r) === 302, status(r));
  const cid = ids('DigitalLibraryCollection', 'name', S + '%')[0];
  r = await post('/admin/digital-library/items', { collection_id: cid, title: S + ' ' + XSS, type: 'link', external_url: 'https://example.test/?a=1&b=2', is_published: '1' }, '/admin/digital-library/items/create');
  check(R, 'digital-library: item XSS create accepted', status(r) === 302, status(r));
  check(R, 'digital-library: XSS inert on public page', inert(await html('/en/data-resources/digital-library')));
  for (const [f, val] of [['external_url', 'javascript:alert(1)'], ['pdf_url', 'javascript:alert(1)']]) {
    r = await post('/admin/digital-library/items', { collection_id: cid, title: S + ' dbad', type: 'link', external_url: 'https://e.test', [f]: val }, '/admin/digital-library/items/create');
    check(R, `digital-library: ${f}=javascript: rejected`, ids('DigitalLibraryItem', 'title', S + ' dbad').length === 0);
  }
  r = await post('/admin/digital-library/items', { collection_id: '99999999', title: S + ' dnocol', type: 'link', external_url: 'https://e.test' }, '/admin/digital-library/items/create');
  check(R, 'digital-library: nonexistent collection rejected', ids('DigitalLibraryItem', 'title', S + ' dnocol').length === 0 && status(r) < 500, status(r));
  r = await post('/admin/digital-library/items', { collection_id: cid, title: S + ' dpdf', type: 'pdf', is_published: '1' }, '/admin/digital-library/items/create');
  check(R, 'digital-library: pdf item with no file/url rejected', ids('DigitalLibraryItem', 'title', S + ' dpdf').length === 0);
  r = await post('/admin/digital-library/items', { collection_id: cid, title: S + ' dpdf2', type: 'pdf', is_published: '1' }, '/admin/digital-library/items/create', { file: { name: 'dok ✓.pdf', mimeType: 'application/pdf', buffer: pdf } });
  check(R, 'digital-library: unicode PDF upload ok', ids('DigitalLibraryItem', 'title', S + ' dpdf2').length === 1, status(r));
  r = await post('/admin/digital-library/items', { collection_id: cid, title: S + ' dfake', type: 'pdf' }, '/admin/digital-library/items/create', { file: { name: 'x.pdf', mimeType: 'application/pdf', buffer: Buffer.from('not pdf') } });
  check(R, 'digital-library: fake PDF rejected', ids('DigitalLibraryItem', 'title', S + ' dfake').length === 0);
  r = await post('/admin/digital-library/items', { collection_id: cid, title: S + ' dmass', type: 'link', external_url: 'https://e.test', id: '333333', file_path: 'x' }, '/admin/digital-library/items/create');
  check(R, 'digital-library: mass-assignment ignored', php(`$i=App\\Models\\DigitalLibraryItem::where('title','${S} dmass')->first(); echo ($i && $i->id!=333333 && $i->file_path===null)?'ok':'bad';`) === 'ok');

  // ---------- admin pages visuals + console, desktop & mobile ----------
  const adminPages = ['/admin/events', '/admin/events/create', '/admin/publications', '/admin/publications/create', '/admin/news', '/admin/news/create', '/admin/newsletters',
    '/admin/newsletters/create', '/admin/video-gallery', '/admin/video-gallery/create', '/admin/digital-library/items', '/admin/digital-library/items/create',
    '/admin/digital-library/collections', '/admin/digital-library/collections/create', '/admin/site-content'];
  for (const u of adminPages) {
    const resp = await mpage.goto(BASE + u);
    const ok = await mpage.evaluate(() => document.documentElement.scrollWidth <= window.innerWidth + 1);
    check(R, `admin ${u} @390px: 200 and no page-level horizontal scroll`, resp.status() === 200 && ok, `${resp.status()} scrollW`);
    await shot(mpage, 'm' + u.replace(/\W+/g, '-'));
    await page.goto(BASE + u);
    await shot(page, 'd' + u.replace(/\W+/g, '-'));
  }
  // public pages console/404 assets
  const bad404 = [];
  page.on('response', (resp) => { if (resp.status() >= 400 && /\.(js|css|png|jpe?g|svg|woff2?|webp)(\?|$)/.test(resp.url())) bad404.push(resp.status() + ' ' + resp.url()); });
  for (const u of ['/en/news-media', '/en/news-media/news', '/en/news-media/newsletters', '/en/news-media/video-gallery', '/en/news-media/gallery', '/en/news-media/press-releases',
    '/en/data-resources', '/en/data-resources/publications', '/en/data-resources/digital-library', '/en/data-resources/crime-statistics', '/en/publication/15th-edition-aseanapol-magazine',
    '/en/events-training', '/en/events-training/calendar', '/en/events-training/conferences', '/en/events-training/training-programs', '/en/guidelines']) {
    await page.goto(BASE + u, { waitUntil: 'load' });
  }
  check(R, 'public pages: no 4xx/5xx static assets', bad404.filter(x => !/\/media\/x\.(jpg|mp4)/.test(x)).length === 0, [...new Set(bad404)].slice(0, 8).join(' | '));
  check(R, 'public pages: no console errors', errors.length === 0, errors.slice(0, 5).join(' | '));
} catch (e) {
  check(R, 'test run completed without exception', false, e.stack || String(e));
} finally {
  // ---------- cleanup (via admin endpoints so uploaded files are removed too) ----------
  try { await cleanup(); } catch (e) { console.log('cleanup error', e.message); }
  const left = php(`echo App\\Models\\Event::where('title','like','${S}%')->count()+App\\Models\\Publication::where('title','like','%${S}%')->orWhere('slug','like','${S.toLowerCase()}%')->count()+App\\Models\\NewsItem::where('title','like','${S}%')->count()+App\\Models\\Newsletter::where('title','like','${S}%')->count()+App\\Models\\VideoGallery::where('title','like','${S}%')->count()+App\\Models\\DigitalLibraryItem::where('title','like','${S}%')->count()+App\\Models\\DigitalLibraryCollection::where('name','like','${S}%')->count();`);
  check(R, 'cleanup: no QA rows left', left === '0', left);
  await browser.close();
  finish(R, errors);
}

async function del(path, model, like, col = 'title') {
  for (const id of ids(model, col, like)) await post(`${path}/${id}`, {}, path, {}, 'DELETE');
}
async function cleanup() {
  await del('/admin/events', 'Event', S + '%');
  for (const id of php(`echo App\\Models\\Publication::where('title','like','%${S}%')->orWhere('slug','like','${S.toLowerCase()}%')->pluck('id')->implode(',');`).split(',').filter(Boolean)) await post(`/admin/publications/${id}`, {}, '/admin/publications', {}, 'DELETE');
  await del('/admin/news', 'NewsItem', S + '%');
  await del('/admin/newsletters', 'Newsletter', S + '%');
  await del('/admin/video-gallery', 'VideoGallery', S + '%');
  await del('/admin/digital-library/items', 'DigitalLibraryItem', S + '%');
  await del('/admin/digital-library/collections', 'DigitalLibraryCollection', S + '%', 'name');
}
