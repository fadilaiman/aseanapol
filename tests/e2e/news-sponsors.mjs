// News "Supported by / sponsors": admin repeater + public list card + article strip.
// CMS_BASE=http://127.0.0.1:<port> node tests/e2e/news-sponsors.mjs   (test data prefixed QAS, self-cleaning)
import fs from 'node:fs';
import os from 'node:os';
import path from 'node:path';
import { launch, login, check, finish, BASE } from './lib.mjs';

const TAG = 'QAS' + Date.now().toString(36);
const tmp = fs.mkdtempSync(path.join(os.tmpdir(), 'qas-'));
const PNG = Buffer.from('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==', 'base64');
const mk = (n) => { const f = path.join(tmp, n + '.png'); fs.writeFileSync(f, Buffer.concat([PNG, Buffer.from(n)])); return f; };
const logoA = mk('a'), logoB = mk('b'), logoC = mk('c');
const bad = path.join(tmp, 'evil.png'); fs.writeFileSync(bad, '<?php echo 1;');
const diskDir = path.resolve('storage/app/public/news/sponsors');
const onDisk = (p) => fs.existsSync(path.join(diskDir, path.basename(p)));

const { browser, page, errors } = await launch();
const R = [];
const SUBMIT = 'form:has(input[name=title]) button[type=submit]';
page.on('dialog', d => d.accept());
const body = async (p) => (await page.request.get(BASE + p)).text();

async function cleanup() {
  for (let i = 0; i < 10; i++) {
    await page.goto(`${BASE}/admin/news?q=${TAG}`);
    const del = page.locator('form[action*="/admin/news/"] button:has-text("Delete")').first();
    if (!(await del.count())) break;
    await Promise.all([page.waitForNavigation(), del.click()]);
  }
}
const rows = () => page.locator('[data-testid=sponsor-row]');
async function fillRow(i, { name, url, file, logoUrl }) {
  const r = rows().nth(i);
  if (name !== undefined) await r.locator('[data-f=name]').fill(name);
  if (url !== undefined) await r.locator('[data-f=url]').fill(url);
  if (file) await r.locator('[data-f=logo_file]').setInputFiles(file);
  if (logoUrl) await r.locator('[data-f=logo_url]').fill(logoUrl);
}
async function openEdit(title) {
  await page.goto(`${BASE}/admin/news?q=${TAG}`);
  await page.locator(`tr:has-text("${title}") a[href*="/edit"]`).first().click();
  await page.waitForSelector('[data-testid=sponsors-block]');
}
const stored = async () => (await page.locator('[data-testid=sponsor-row] input[type=hidden]').evaluateAll(els => els.map(e => e.value)));

const T1 = `${TAG} With Sponsors`, T2 = `${TAG} Plain`, TX = `${TAG} <script>window.__xss=1</script> Evil`;
try {
  await login(page);
  await cleanup();

  // ---- create with two sponsors
  await page.goto(`${BASE}/admin/news/create`);
  await page.fill('input[name=title]', T1);
  await page.fill('textarea[name=content]', '<p>Body QAS</p>');
  check(R, 'create form has sponsors block, no rows', (await rows().count()) === 0);
  await page.click('[data-testid=sponsor-add]'); await page.click('[data-testid=sponsor-add]');
  await fillRow(0, { name: 'Alpha Fund', url: 'https://alpha.example.org/', file: logoA });
  await fillRow(1, { name: 'Beta Corp', file: logoB });
  await page.fill('[data-testid=sponsors-label]', 'Proudly backed by');
  await Promise.all([page.waitForNavigation(), page.click(SUBMIT)]);
  check(R, 'article with 2 sponsors saved', /Article created/i.test(await page.content()));

  const list = await body(`/en/news-media/news?q=${TAG}`);
  const imgs = [...list.matchAll(/<img src="([^"]*storage\/news\/sponsors\/[^"]+)" alt="([^"]*)" class="h-8 w-auto"/g)];
  check(R, 'list card shows both logos (h-8 w-auto)', imgs.length === 2, imgs.length);
  check(R, 'list alt text = name', imgs[0]?.[2] === 'Alpha Fund' && imgs[1]?.[2] === 'Beta Corp', JSON.stringify(imgs.map(m => m[2])));
  const slug = (await page.request.get(BASE + '/en/news-media/news?q=' + TAG).then(r => r.text())).match(/news-media\/news\/([^"?]*with-sponsors[^"]*)"/)?.[1];
  check(R, 'article slug found', !!slug, slug);
  const art = await body(`/en/news-media/news/${slug}`);
  check(R, 'article page has Supported-by strip with custom label', art.includes('data-testid="news-sponsors"') && art.includes('Proudly backed by'));
  check(R, 'linked sponsor has https link, other unlinked', art.includes('href="https://alpha.example.org/"') && (art.match(/<a href="https:\/\/alpha/g) || []).length === 1 && art.includes('rel="noopener noreferrer"'));
  const logoPaths = imgs.map(m => new URL(m[1]).pathname);
  check(R, 'uploaded files exist on disk with unique names', logoPaths.every(onDisk) && new Set(logoPaths).size === 2 && logoPaths.every(p => /sponsor-\d{8}-\d{6}-[0-9a-f]{8}\.png$/.test(p)), logoPaths.join());

  // ---- reorder
  await openEdit(T1);
  check(R, 'edit form shows 2 rows with previews', (await rows().count()) === 2 && (await page.locator('[data-testid=sponsor-preview]').count()) === 2);
  await rows().nth(1).locator('[data-act=up]').click();
  await Promise.all([page.waitForNavigation(), page.click(SUBMIT)]);
  let l2 = await body(`/en/news-media/news?q=${TAG}`);
  const alts = [...l2.matchAll(/storage\/news\/sponsors\/[^"]+" alt="([^"]*)"/g)].map(m => m[1]);
  check(R, 'reorder persists (Beta, Alpha)', alts.join() === 'Beta Corp,Alpha Fund', alts.join());

  // ---- remove one (Beta, now first) -> its file deleted
  const betaPath = logoPaths[1];
  await openEdit(T1);
  await rows().nth(0).locator('[data-act=remove]').click();
  await Promise.all([page.waitForNavigation(), page.click(SUBMIT)]);
  l2 = await body(`/en/news-media/news?q=${TAG}`);
  check(R, 'remove: one logo left', (l2.match(/storage\/news\/sponsors\//g) || []).length === 1);
  check(R, 'remove: removed logo file deleted, kept one remains', !onDisk(betaPath) && onDisk(logoPaths[0]));

  // ---- edit label
  await openEdit(T1);
  await page.fill('[data-testid=sponsors-label]', 'Funded by Alpha');
  await Promise.all([page.waitForNavigation(), page.click(SUBMIT)]);
  check(R, 'label edited', (await body(`/en/news-media/news/${slug}`)).includes('Funded by Alpha'));

  // ---- replace logo -> new filename, old deleted
  await openEdit(T1);
  await fillRow(0, { file: logoC });
  await Promise.all([page.waitForNavigation(), page.click(SUBMIT)]);
  const l3 = await body(`/en/news-media/news?q=${TAG}`);
  const newPath = l3.match(/(\/storage\/news\/sponsors\/[^"]+)" alt="Alpha Fund"/)?.[1];
  check(R, 'replace: new filename', !!newPath && newPath !== logoPaths[0], newPath);
  check(R, 'replace: old file deleted, new exists', !onDisk(logoPaths[0]) && onDisk(newPath));

  // ---- invalid url / bad file / missing name / missing logo
  await openEdit(T1);
  await fillRow(0, { url: 'javascript:alert(1)' });
  await Promise.all([page.waitForNavigation(), page.click(SUBMIT)]);
  check(R, 'javascript: url rejected', /sponsors\.0\.url|format is invalid/i.test(await page.content()) && page.url().includes('/edit'));
  check(R, 'entered values kept after error', (await page.locator('[data-testid=sponsor-row] [data-f=name]').first().inputValue()) === 'Alpha Fund');
  await fillRow(0, { url: 'ftp://x.example.org' });
  await Promise.all([page.waitForNavigation(), page.click(SUBMIT)]);
  check(R, 'ftp url rejected', page.url().includes('/edit') && /invalid/i.test(await page.content()));
  await fillRow(0, { url: '', file: bad });
  await Promise.all([page.waitForNavigation(), page.click(SUBMIT)]);
  check(R, 'non-image upload rejected', /must be an image|must be a file of type/i.test(await page.content()));
  await page.goto(`${BASE}/admin/news/create`);
  await page.fill('input[name=title]', T2 + ' x');
  await page.click('[data-testid=sponsor-add]');
  await fillRow(0, { name: '' , url: 'https://ok.example.org' });
  await Promise.all([page.waitForNavigation(), page.click(SUBMIT)]);
  check(R, 'row without name/logo rejected', /needs a name/i.test(await page.content()) && /needs a logo/i.test(await page.content()));
  // too many rows
  await page.goto(`${BASE}/admin/news/create`);
  for (let i = 0; i < 12; i++) await page.locator('[data-testid=sponsor-add]').dispatchEvent('click');
  check(R, 'add button stops at 8 rows', (await rows().count()) === 8 && await page.locator('[data-testid=sponsor-add]').isDisabled());

  // ---- XSS in name
  await page.goto(`${BASE}/admin/news/create`);
  await page.fill('input[name=title]', TX);
  await page.click('[data-testid=sponsor-add]');
  await fillRow(0, { name: '"><script>window.__xss=1</script>', logoUrl: 'media/logos/japan-flag.png' });
  await page.fill('[data-testid=sponsors-label]', '<img src=x onerror=window.__xss=1>');
  await Promise.all([page.waitForNavigation(), page.click(SUBMIT)]);
  const lx = await body(`/en/news-media/news?q=${TAG}`);
  check(R, 'script in sponsor name escaped on list', !/<script>window\.__xss/.test(lx) && lx.includes('&quot;&gt;&lt;script&gt;'));
  const sx = lx.match(/news-media\/news\/([^"?]*evil[^"]*)"/)?.[1];
  const ax = await body(`/en/news-media/news/${sx}`);
  check(R, 'script in name/label escaped on article', !/<script>window\.__xss/.test(ax) && !/<img src=x onerror/.test(ax) && ax.includes('&lt;img src=x'));
  await page.goto(`${BASE}/en/news-media/news/${sx}`);
  check(R, 'no xss executed', (await page.evaluate(() => window.__xss)) === undefined);
  await openEdit(TX.slice(0, 20));
  check(R, 'edit form re-escapes values', (await page.locator('[data-f=name]').first().inputValue()) === '"><script>window.__xss=1</script>');

  // ---- article without sponsors
  await page.goto(`${BASE}/admin/news/create`);
  await page.fill('input[name=title]', T2);
  await page.fill('textarea[name=content]', '<p>Plain QAS</p>');
  await Promise.all([page.waitForNavigation(), page.click(SUBMIT)]);
  const lp = await body(`/en/news-media/news?q=${encodeURIComponent(T2)}`);
  const sp = lp.match(/news-media\/news\/([^"?]*plain[^"]*)"/)?.[1];
  const ap = await body(`/en/news-media/news/${sp}`);
  check(R, 'no sponsors: no strip on article, no logos on card', !ap.includes('news-sponsors') && !/storage\/news\/sponsors|media\/logos/.test(lp.split(TAG + ' Plain')[0].split('<article').pop() + ''), '');

  // ---- delete article -> its logo file removed
  await openEdit(T1);
  const delPath = newPath;
  await page.goto(`${BASE}/admin/news?q=${TAG}`);
  await Promise.all([page.waitForNavigation(), page.locator(`tr:has-text("${T1}") form[action*="/admin/news/"] button:has-text("Delete")`).click()]);
  check(R, 'delete article removes its uploaded logo', !onDisk(delPath));

  // ---- legacy hack gone, migrated Anti-Scam cards unchanged
  const all = await body('/en/news-media/news?q=Anti-Scam+Study+Visit');
  check(R, 'migrated Anti-Scam cards still show JAIF + Japan', /media\/logos\/jaif-cooperation\.png" alt="Japan-ASEAN Cooperation \(JAIF\)" class="h-8 w-auto"/.test(all));
} catch (e) {
  check(R, 'test crashed', false, String(e.stack || e).split('\n').slice(0, 4).join(' | '));
} finally {
  try { await cleanup(); } catch {}
  fs.rmSync(tmp, { recursive: true, force: true });
  await browser.close();
}
finish(R, errors);
