// Publications dedicated module: node tests/e2e/publications.mjs
import fs from 'node:fs';
import os from 'node:os';
import path from 'node:path';
import { launch, login, check, finish, BASE } from './lib.mjs';

const tmp = fs.mkdtempSync(path.join(os.tmpdir(), 'pubqa-'));
const png = path.join(tmp, 'cover.png'); // 1x1 PNG
fs.writeFileSync(png, Buffer.from('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==', 'base64'));
const mkpdf = t => '%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj\n2 0 obj<</Type/Pages/Kids[3 0 R]/Count 1>>endobj\n3 0 obj<</Type/Page/Parent 2 0 R/MediaBox[0 0 200 200]>>endobj\ntrailer<</Root 1 0 R>>\n%' + t + '\n%%EOF\n';
const pdf1 = path.join(tmp, 'one.pdf'), pdf2 = path.join(tmp, 'two.pdf'), bad = path.join(tmp, 'notpdf.pdf');
fs.writeFileSync(pdf1, mkpdf('one')); fs.writeFileSync(pdf2, mkpdf('two')); fs.writeFileSync(bad, 'just text');

const { browser, page, errors } = await launch();
const R = [];
await login(page);
const IDX = BASE + '/en/data-resources/publications';
const SLUG = 'qa-16th-edition-test-bulletin';
const DET = BASE + '/en/publication/' + SLUG;
const text = async u => { const r = await page.request.get(u); return [r.status(), await r.text()]; };
const row = () => page.locator(`tr[data-pub="${SLUG}"]`);
const saveAndWait = async () => { await page.click('[data-testid=save]'); await page.waitForURL(/\/admin\/publications$/); };

try {
  await page.goto(BASE + '/admin/publications');
  while (await row().count()) { page.once('dialog', d => d.accept()); await row().locator('[data-testid=delete]').click(); await page.waitForLoadState(); }

  // --- validation errors
  await page.goto(BASE + '/admin/publications/create');
  await page.evaluate(() => document.querySelectorAll('[required]').forEach(e => e.removeAttribute('required')));
  await page.click('[data-testid=save]');
  await page.waitForSelector('[data-testid=form-errors]');
  const errTxt = await page.locator('[data-testid=form-errors]').innerText();
  check(R, 'validation: empty form shows title/edition errors', /title/i.test(errTxt) && /edition/i.test(errTxt), errTxt);
  await page.fill('input[name=title]', 'QA 16th Edition Test Bulletin');
  await page.fill('input[name=edition]', '16th');
  await page.fill('input[name=slug]', '8th-edition-aseanapol-bulletin'); // duplicate
  await page.click('[data-testid=save]');
  await page.waitForSelector('[data-testid=form-errors]');
  check(R, 'validation: duplicate slug rejected', /slug/i.test(await page.locator('[data-testid=form-errors]').innerText()));
  await page.fill('input[name=slug]', SLUG);
  await page.setInputFiles('input[name=pdf]', bad);
  await page.click('[data-testid=save]');
  await page.waitForSelector('[data-testid=form-errors]');
  check(R, 'validation: non-PDF upload rejected', /pdf/i.test(await page.locator('[data-testid=form-errors]').innerText()));

  // --- create
  await page.goto(BASE + '/admin/publications/create');
  await page.fill('input[name=title]', 'QA 16th Edition Test Bulletin');
  await page.fill('input[name=edition]', '16th');
  await page.selectOption('select[name=kind]', 'bulletin');
  await page.fill('input[name=year]', '2027');
  await page.fill('input[name=theme]', 'QA Theme Alpha');
  await page.fill('input[name=slug]', SLUG);
  await page.fill('input[name=sort_order]', '1');
  await saveAndWait();
  check(R, 'create: row appears in admin list', (await row().count()) === 1);

  let [st, html] = await text(IDX);
  check(R, 'create: shows on public index', html.includes('QA 16th Edition Test Bulletin'));
  check(R, 'create: no PDF => "not available"', html.includes('PDF not available'));
  check(R, 'sort: new (order 1) listed before 15th (order 10)', html.indexOf('QA 16th Edition') < html.indexOf('15th Edition ASEANAPOL Magazine'));
  [st, html] = await text(DET);
  check(R, 'create: detail page 200 with theme+year', st === 200 && html.includes('QA Theme Alpha') && html.includes('2027'), st);
  check(R, 'detail: no-PDF request button', html.includes('Request This Publication') && html.includes('mailto:aseanapolsec@aseanapol.org'));

  // --- edit title / size / theme + upload pdf & cover
  await page.goto(BASE + '/admin/publications');
  await row().locator('[data-testid=edit]').click();
  await page.fill('input[name=title]', 'QA 16th Edition RENAMED');
  await page.fill('input[name=size]', '7 MB');
  await page.fill('input[name=theme]', 'QA Theme Beta');
  await page.setInputFiles('input[name=pdf]', pdf1);
  await page.setInputFiles('input[name=cover]', png);
  await saveAndWait();
  [st, html] = await text(IDX);
  check(R, 'edit: title+size on index', html.includes('QA 16th Edition RENAMED') && html.includes('PDF, 7 MB'));
  [st, html] = await text(DET);
  check(R, 'edit: theme/size/title on detail', html.includes('QA Theme Beta') && html.includes('(PDF, 7 MB)') && !html.includes('QA Theme Alpha'));
  const pdfUrl1 = (html.match(/href="([^"]*storage\/publications\/[^"]+\.pdf)"/) || [])[1];
  const coverUrl = (html.match(/src="([^"]*storage\/publications\/[^"]+\.png)"/) || [])[1];
  check(R, 'upload: pdf link + cover img on detail', !!pdfUrl1 && !!coverUrl);
  check(R, 'upload: pdf is served', pdfUrl1 && (await page.request.get(pdfUrl1)).status() === 200);
  check(R, 'upload: cover is served', coverUrl && (await page.request.get(coverUrl)).status() === 200);
  check(R, 'edit: Download PDF replaces request button', html.includes('Download PDF') && !html.includes('Request This Publication'));

  // --- re-upload => new filename, old file removed
  await page.goto(BASE + '/admin/publications');
  await row().locator('[data-testid=edit]').click();
  await page.setInputFiles('input[name=pdf]', pdf2);
  await saveAndWait();
  [st, html] = await text(DET);
  const pdfUrl2 = (html.match(/href="([^"]*storage\/publications\/[^"]+\.pdf)"/) || [])[1];
  check(R, 're-upload: new unique filename', pdfUrl2 && pdfUrl2 !== pdfUrl1, pdfUrl1 + ' vs ' + pdfUrl2);
  const s2 = (await page.request.get(pdfUrl2)).status(), s1 = (await page.request.get(pdfUrl1)).status();
  check(R, 're-upload: new file served, old removed', s2 === 200 && s1 !== 200, `new=${s2} old=${s1}`);

  // --- hide / show
  await page.goto(BASE + '/admin/publications');
  await row().locator('[data-testid=toggle]').click();
  await page.waitForLoadState();
  [st, html] = await text(IDX);
  check(R, 'hide: gone from index', !html.includes('QA 16th Edition RENAMED'));
  check(R, 'hide: detail 404', (await text(DET))[0] === 404);
  check(R, 'hide: existing 15th still 200', (await text(BASE + '/en/publication/15th-edition-aseanapol-magazine'))[0] === 200);
  await row().locator('[data-testid=toggle]').click();
  await page.waitForLoadState();
  check(R, 'show again: detail 200', (await text(DET))[0] === 200);

  // --- reorder via sort_order
  await page.goto(BASE + '/admin/publications');
  await row().locator('[data-testid=edit]').click();
  await page.fill('input[name=sort_order]', '999');
  await saveAndWait();
  [st, html] = await text(IDX);
  check(R, 'reorder: sort_order 999 => listed last', html.indexOf('QA 16th Edition RENAMED') > html.indexOf('8th Edition ASEANAPOL Bulletin'));

  // --- admin screenshots (usability review)
  await page.goto(BASE + '/admin/publications');
  await page.screenshot({ path: path.join(tmp, 'admin-publications-index.png'), fullPage: true });
  await row().locator('[data-testid=edit]').click();
  await page.screenshot({ path: path.join(tmp, 'admin-publications-edit.png'), fullPage: true });
  console.log('screenshots in ' + tmp);
} finally {
  await page.goto(BASE + '/admin/publications');
  if (await row().count()) {
    page.once('dialog', d => d.accept());
    await row().locator('[data-testid=delete]').click();
    await page.waitForLoadState();
  }
}
check(R, 'delete: gone from admin', (await row().count()) === 0);
check(R, 'delete: gone from index', !(await text(IDX))[1].includes('QA 16th Edition'));
check(R, 'delete: detail 404', (await text(DET))[0] === 404);
finish(R, errors);
await browser.close();
