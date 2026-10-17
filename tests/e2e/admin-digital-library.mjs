// Admin QA of the Digital Library module: node tests/e2e/admin-digital-library.mjs
import fs from 'node:fs';
import os from 'node:os';
import path from 'node:path';
import { launch, login, check, finish, BASE } from './lib.mjs';

const tmp = fs.mkdtempSync(path.join(os.tmpdir(), 'dlqa-'));
const mkpdf = t => '%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj\n2 0 obj<</Type/Pages/Kids[3 0 R]/Count 1>>endobj\n3 0 obj<</Type/Page/Parent 2 0 R/MediaBox[0 0 200 200]>>endobj\ntrailer<</Root 1 0 R>>\n%' + t + '\n%%EOF\n';
const pdf1 = path.join(tmp, 'one.pdf'), pdf2 = path.join(tmp, 'two.pdf'), bad = path.join(tmp, 'notpdf.pdf');
fs.writeFileSync(pdf1, mkpdf('one')); fs.writeFileSync(pdf2, mkpdf('two')); fs.writeFileSync(bad, 'just text');

const { browser, page, errors } = await launch();
const R = [];
await login(page);
const PUB = BASE + '/en/data-resources/digital-library';
const COL = 'QA Collection Zeta', COL2 = 'QA Collection Omega';
const SUBMIT = 'form[action*="/admin/digital-library"][method=POST] button[type=submit]';
const submit = async () => { await page.evaluate(() => document.querySelectorAll('form').forEach(f => { f.noValidate = true; }));
  await Promise.all([page.waitForNavigation({ waitUntil: 'load' }), page.click(SUBMIT)]); };
const T1 = 'QA Item Alpha PDF', T2 = 'QA Item Beta Link';
const pubHtml = async () => (await page.request.get(PUB)).text();
const errs = async () => (await page.locator('.bg-red-50').first().innerText().catch(() => ''));
const stripRequired = () => page.evaluate(() => document.querySelectorAll('[required]').forEach(e => e.removeAttribute('required')));
const colRow = n => page.locator('tbody tr', { hasText: n });
const itemRow = n => page.locator('tbody tr', { hasText: n });
// links may be absolute with the app's own APP_URL host: always fetch through the test server
const storageFiles = html => [...html.matchAll(/href="([^"]*\/storage\/digital-library\/[^"]+\.pdf)"/g)].map(m => BASE + new URL(m[1], 'http://x').pathname);

async function cleanup() {
  await page.goto(BASE + '/admin/digital-library/items');
  for (const t of [T1, T2]) while (await itemRow(t).count()) { page.once('dialog', d => d.accept()); await itemRow(t).first().locator('button:has-text("Delete")').click(); await page.waitForLoadState(); }
  await page.goto(BASE + '/admin/digital-library/collections');
  for (const n of [COL, COL2]) while (await colRow(n).count()) { page.once('dialog', d => d.accept()); await colRow(n).first().locator('button:has-text("Delete")').click(); await page.waitForLoadState(); }
}

try {
  await cleanup();

  // ---- collections: validation + create
  await page.goto(BASE + '/admin/digital-library/collections/create');
  await stripRequired();
  await submit();
  check(R, 'collection: empty form shows validation errors', /name/i.test(await errs()), await errs());
  await page.fill('input[name=name]', COL);
  await page.fill('input[name=icon]', 'folder');
  await page.fill('input[name=sort_order]', '-3');
  await submit();
  check(R, 'collection: negative sort order rejected', /sort/i.test(await errs()), await errs());
  await page.fill('input[name=sort_order]', '990');
  await submit();
  await page.waitForURL(/collections$/);
  check(R, 'collection: created', (await colRow(COL).count()) === 1);

  // ---- edit collection
  await colRow(COL).locator('a:has-text("Edit")').click();
  await page.fill('input[name=name]', COL2);
  await submit();
  await page.waitForURL(/collections$/);
  check(R, 'collection: renamed', (await colRow(COL2).count()) === 1 && (await colRow(COL).count()) === 0);

  // ---- items: validation
  await page.goto(BASE + '/admin/digital-library/items/create');
  await stripRequired();
  await submit();
  check(R, 'item: empty form shows errors', /title/i.test(await errs()) && /collection/i.test(await errs()), await errs());
  await page.selectOption('select[name=collection_id]', { label: COL2 });
  await page.fill('input[name=title]', T1);
  await page.check('input[name=type][value=pdf]');
  await submit();
  check(R, 'item: pdf with no file and no URL rejected', /pdf|file/i.test(await errs()), await errs());
  await page.setInputFiles('input[name=file]', bad);
  await submit();
  check(R, 'item: non-PDF file rejected', /pdf/i.test(await errs()), await errs());
  await page.setInputFiles('input[name=file]', []);
  await page.fill('input[name=external_url]', 'not a url');
  await submit();
  check(R, 'item: invalid URL rejected', /url/i.test(await errs()), await errs());
  await page.check('input[name=type][value=link]');
  await page.fill('input[name=external_url]', '');
  await submit();
  check(R, 'item: link without URL rejected', /url/i.test(await errs()), await errs());
  check(R, 'item: failed submit kept the typed title', (await page.inputValue('input[name=title]')) === T1);

  // ---- create PDF item (upload), published
  await page.goto(BASE + '/admin/digital-library/items/create');
  await page.selectOption('select[name=collection_id]', { label: COL2 });
  await page.fill('input[name=title]', T1);
  await page.setInputFiles('input[name=file]', pdf1);
  await page.check('input[type=checkbox][name=is_published]');
  await submit();
  await page.waitForURL(/items$/);
  check(R, 'item: pdf created', (await itemRow(T1).count()) === 1);
  let html = await pubHtml();
  check(R, 'public: published item + collection visible', html.includes(T1) && html.includes(COL2));
  const f1 = storageFiles(html).find(Boolean);
  check(R, 'public: pdf download link present and served', f1 && (await page.request.get(f1)).status() === 200, f1);

  // ---- toggle draft / publish
  await itemRow(T1).locator('form[action*=toggle] button').click();
  await page.waitForLoadState();
  check(R, 'toggle: unpublished => gone from public', !(await pubHtml()).includes(T1));
  await itemRow(T1).locator('form[action*=toggle] button').click();
  await page.waitForLoadState();
  check(R, 'toggle: republished => visible again', (await pubHtml()).includes(T1));

  // ---- edit: set external URL then re-upload (upload must win, old file removed)
  await itemRow(T1).locator('a:has-text("Edit")').click();
  await page.fill('input[name=external_url]', 'https://example.test/old.pdf');
  await submit();
  await page.waitForURL(/items$/);
  check(R, 'edit: external URL takes over public link', (await pubHtml()).includes('https://example.test/old.pdf'));
  await itemRow(T1).locator('a:has-text("Edit")').click();
  await page.setInputFiles('input[name=file]', pdf2);
  await submit();
  await page.waitForURL(/items$/);
  html = await pubHtml();
  check(R, 'edit: new upload replaces stale external URL on public page', !html.includes('example.test/old.pdf') && storageFiles(html).length >= 1, 'upload silently ignored while external_url set');
  const f2 = storageFiles(html).find(u => u !== f1);
  check(R, 'edit: re-upload gives a new filename, old file gone', f2 && f2 !== f1 && (await page.request.get(f2)).status() === 200 && (await page.request.get(f1)).status() !== 200, `${f1} -> ${f2}`);

  // ---- convert to link item
  await itemRow(T1).locator('a:has-text("Edit")').click();
  await page.fill('input[name=title]', T2);
  await page.check('input[name=type][value=link]');
  await page.fill('input[name=external_url]', 'https://example.test/page');
  await page.fill('input[name=pdf_url]', 'https://example.test/dl.pdf');
  await submit();
  await page.waitForURL(/items$/);
  html = await pubHtml();
  check(R, 'edit: converted to link shows View + Download PDF', html.includes(T2) && html.includes('https://example.test/page') && html.includes('https://example.test/dl.pdf'));
  check(R, 'edit: uploaded file removed when converted to link', (await page.request.get(f2)).status() !== 200);

  await page.screenshot({ path: path.join(tmp, 'items-index.png'), fullPage: true });

  // ---- delete item, delete collection
  page.once('dialog', d => d.accept());
  await itemRow(T2).locator('button:has-text("Delete")').click();
  await page.waitForLoadState();
  check(R, 'item: deleted', (await itemRow(T2).count()) === 0 && !(await pubHtml()).includes(T2));

  // collection delete removes uploaded files of its items
  await page.goto(BASE + '/admin/digital-library/items/create');
  await page.selectOption('select[name=collection_id]', { label: COL2 });
  await page.fill('input[name=title]', T1);
  await page.setInputFiles('input[name=file]', pdf1);
  await page.check('input[type=checkbox][name=is_published]');
  await submit();
  await page.waitForURL(/items$/);
  const f3 = storageFiles(await pubHtml()).find(Boolean);
  await page.goto(BASE + '/admin/digital-library/collections');
  page.once('dialog', d => d.accept());
  await colRow(COL2).locator('button:has-text("Delete")').click();
  await page.waitForLoadState();
  check(R, 'collection: deleted with its items', (await colRow(COL2).count()) === 0 && !(await pubHtml()).includes(T1));
  check(R, 'collection: delete removes uploaded PDF from disk', f3 && (await page.request.get(f3)).status() !== 200, f3);
} finally {
  await cleanup();
}
finish(R, errors);
await browser.close();
