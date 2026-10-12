// QA-A admin-form edge cases (uses about.governance: repeater conferences.rows + image chart.image).
//   node tests/e2e/qa-a-forms.mjs
import fs from 'node:fs';
import os from 'node:os';
import path from 'node:path';
import { launch, login, check, finish, openEditor, save, revertPage, BASE } from './lib.mjs';

const KEY = 'about.governance', URL_ = '/en/about-aseanapol/governance';
const { browser, page, errors } = await launch();
const R = [];
await login(page);
const tmp = fs.mkdtempSync(path.join(os.tmpdir(), 'qaa-'));
// 1x1 png
const PNG = Buffer.from('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==', 'base64');
const rowsSel = '[name="f[conferences][rows][__present]"]';
const rep = () => page.locator(rowsSel).locator('xpath=ancestor::*[@data-repeater][1]');
const rowCount = () => rep().locator(':scope > .cms-rows > [data-row]').count();
const live = async () => (await page.request.get(BASE + URL_)).text();

try {
  await revertPage(page, KEY);
  await openEditor(page, KEY);
  const n0 = await rowCount();
  check(R, 'editor shows default repeater rows', n0 > 3, 'rows=' + n0);

  // unsaved nested/new row is not persisted when navigating away
  await rep().locator('[data-act=add-row]').click();
  check(R, 'add-row adds a row client-side', (await rowCount()) === n0 + 1);
  await page.goto(BASE + '/admin/site-content');
  await openEditor(page, KEY);
  check(R, 'unsaved new row is gone after leaving the page', (await rowCount()) === n0);

  // add row, fill, move to top, save
  await rep().locator('[data-act=add-row]').click();
  await page.evaluate(sel => { const r = document.querySelector(sel).closest('[data-repeater]'); const rows = r.querySelectorAll(':scope > .cms-rows > [data-row]'); rows[rows.length - 1].setAttribute('data-qa-new', '1'); }, rowsSel);
  const last = page.locator('[data-qa-new]');
  await last.locator('input[name$="[city]"]').fill('QA-FIRST-CITY');
  await last.locator('input[name$="[num]"]').fill('999');
  for (let i = 0; i < n0; i++) await last.locator('[data-act=up]').click();
  await save(page);
  await openEditor(page, KEY);
  const firstCity = await rep().locator(':scope > .cms-rows > [data-row]').first().locator('input[name$="[city]"]').inputValue();
  check(R, 'reordered row persists at the top after save', firstCity === 'QA-FIRST-CITY', firstCity);
  const html = await live();
  check(R, 'reordered row is first in live table', html.indexOf('QA-FIRST-CITY') > 0 && html.indexOf('QA-FIRST-CITY') < html.indexOf('</tbody>') - 200);

  // save twice quickly
  const [r1] = await Promise.all([page.waitForResponse(r => r.request().method() === 'POST' || r.status() === 302), page.click('[data-testid=save-page]').then(() => page.click('[data-testid=save-page]').catch(() => {}))]).catch(() => [null]);
  await page.waitForLoadState('load');
  check(R, 'double-click save does not error', !(await page.content()).includes('Whoops') && (await page.request.get(BASE + URL_)).status() === 200);

  // back button after save
  await openEditor(page, KEY); await save(page);
  await page.goBack(); await page.waitForLoadState('load');
  check(R, 'back button after save renders editor/page without error', !/Whoops|Page Expired/.test(await page.content()));

  // remove all rows, save, twice
  await openEditor(page, KEY);
  while (await rowCount()) { page.once('dialog', d => d.accept()); await rep().locator(':scope > .cms-rows > [data-row] [data-act=remove]').first().click(); }
  await save(page);
  check(R, 'live page 200 after removing all rows', (await page.request.get(BASE + URL_)).status() === 200);
  await openEditor(page, KEY);
  check(R, 'editor reopens with 0 rows (override kept, not re-defaulted)', (await rowCount()) === 0, 'rows=' + (await rowCount()));
  await save(page);
  check(R, 'saving again with 0 rows keeps 0 rows', (await (async () => { await openEditor(page, KEY); return rowCount(); })()) === 0);
  const empty = await live();
  check(R, 'no empty table shell left when 0 rows', !/<table[^>]*>\s*(<thead>.*?<\/thead>)?\s*<tbody[^>]*>\s*<\/tbody>/s.test(empty), 'empty <tbody> table rendered');
  await revertPage(page, KEY);

  // uploads
  const fileInput = () => page.locator('input[type=file][name="files[chart][image]"]');
  async function tryUpload(name, buf, label) {
    await openEditor(page, KEY);
    const f = path.join(tmp, name); fs.writeFileSync(f, buf);
    await fileInput().setInputFiles(f);
    const resp = await Promise.all([page.waitForNavigation({ timeout: 60000 }).catch(() => null), page.click('[data-testid=save-page]')]).then(x => x[0]);
    const status = resp ? resp.status() : 0;
    const body = await page.content();
    return { status, body, url: page.url() };
  }
  let u = await tryUpload('notes.txt', Buffer.from('hello'), 'txt');
  check(R, 'non-image upload -> friendly validation (no 500/raw error page)', u.status < 500 && /\/admin\/site-content\//.test(u.url) && /bg-red-50|error|not allowed/i.test(u.body), `status ${u.status} url ${u.url}`);
  u = await tryUpload('big.png', Buffer.concat([PNG, Buffer.alloc(26 * 1024 * 1024)]), 'big');
  check(R, '26MB upload -> friendly error (no 500/419 blank)', u.status < 500 && u.status !== 419 && /\/admin\/site-content\//.test(u.url), `status ${u.status} url ${u.url}`);
  u = await tryUpload('ñandú 图片 my photo.png', PNG, 'unicode');
  check(R, 'unicode+space filename accepted', u.status < 400 && /Page content saved/.test(u.body), `status ${u.status}`);
  await openEditor(page, KEY);
  const stored = await page.locator('[name="f[chart][image]"]').inputValue();
  check(R, 'stored path is URL-safe & unique', /^storage\/cms\/[a-z0-9\-]+-\d{12}-[a-z0-9]{5}\.png$/.test(stored), stored);
  const h = await live();
  const m = h.match(/storage\/cms\/[^"' ]+\.png/);
  check(R, 'uploaded image appears on page and resolves', !!m && (await page.request.get(BASE + '/' + m[0])).status() === 200, m && m[0]);
  await revertPage(page, KEY);
} catch (e) {
  check(R, 'forms test crashed', false, String(e.message).split(/\r?\n/)[0]);
  try { await revertPage(page, KEY); } catch {}
}
fs.rmSync(tmp, { recursive: true, force: true });
finish(R, errors);
await browser.close();
