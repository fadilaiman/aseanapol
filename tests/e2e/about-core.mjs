// Targeted tests for the about.* core pages: Quill richtext, image upload (+ re-upload = new filename), repeater add/hide/remove/reorder.
import fs from 'node:fs';
import os from 'node:os';
import path from 'node:path';
import zlib from 'node:zlib';
import { launch, login, check, finish, openEditor, save, revertPage, BASE } from './lib.mjs';

// --- tiny valid PNG generator (solid colour) ---
function png(file, r, g, b) {
  const crcT = Array.from({ length: 256 }, (_, n) => { let c = n; for (let k = 0; k < 8; k++) c = c & 1 ? 0xedb88320 ^ (c >>> 1) : c >>> 1; return c >>> 0; });
  const crc = buf => { let c = 0xffffffff; for (const x of buf) c = crcT[(c ^ x) & 255] ^ (c >>> 8); return (c ^ 0xffffffff) >>> 0; };
  const chunk = (t, d) => { const len = Buffer.alloc(4); len.writeUInt32BE(d.length); const td = Buffer.concat([Buffer.from(t), d]); const c = Buffer.alloc(4); c.writeUInt32BE(crc(td)); return Buffer.concat([len, td, c]); };
  const w = 8, h = 8, ihdr = Buffer.alloc(13); ihdr.writeUInt32BE(w, 0); ihdr.writeUInt32BE(h, 4); ihdr[8] = 8; ihdr[9] = 2;
  const raw = Buffer.concat(Array.from({ length: h }, () => Buffer.concat([Buffer.from([0]), Buffer.from(Array.from({ length: w }, () => [r, g, b]).flat())])));
  fs.writeFileSync(file, Buffer.concat([Buffer.from([137, 80, 78, 71, 13, 10, 26, 10]), chunk('IHDR', ihdr), chunk('IDAT', zlib.deflateSync(raw)), chunk('IEND', Buffer.alloc(0))]));
}
const dir = fs.mkdtempSync(path.join(os.tmpdir(), 'about-core-'));
const png1 = path.join(dir, 'qa-chart.png'), png2 = path.join(dir, 'qa-chart.png'.replace('.png', '-b.png'));
png(png1, 200, 30, 30); png(png2, 30, 30, 200);

const { browser, page, errors } = await launch();
const R = [];
await login(page);
const get = async u => await (await page.request.get(BASE + u)).text();

// ============ 1. Quill richtext (permanent secretariat closing paragraph) ============
{
  const KEY = 'about.permanent-secretariat', U = '/en/about-aseanapol/permanent-secretariat';
  await revertPage(page, KEY);
  await openEditor(page, KEY);
  const ed = page.locator('.cms-rt:near(input[name="f[intro][body_bottom]"])').first();
  const hidden = page.locator('input[name="f[intro][body_bottom]"]');
  const quill = hidden.locator('xpath=following-sibling::div[contains(@class,"cms-rt")]').locator('.ql-editor');
  await quill.click();
  await page.keyboard.press('Control+A');
  await page.keyboard.type('QA richtext paragraph typed in Quill');
  await page.keyboard.press('Control+A');
  await page.keyboard.press('Control+B');
  await save(page);
  const html = await get(U);
  check(R, 'richtext: typed text is on the live page', html.includes('QA richtext paragraph typed in Quill'));
  check(R, 'richtext: bold formatting kept', /<strong>QA richtext paragraph typed in Quill<\/strong>/.test(html));
  check(R, 'richtext: old paragraph replaced', !html.includes('The current members of ASEANAPOL are'));
  // hide/show a section toggle too
  await openEditor(page, KEY);
  await page.uncheck('[data-testid=vis-directors]');
  await save(page);
  check(R, 'toggle: directors block hidden', !(await get(U)).includes('Director for Police Services'));
  await revertPage(page, KEY);
  const back = await get(U);
  check(R, 'richtext: revert restores default', back.includes('The current members of ASEANAPOL are') && back.includes('Director for Police Services'));
}

// ============ 2. Image upload + re-upload (governance org chart) ============
{
  const KEY = 'about.governance', U = '/en/about-aseanapol/governance';
  await revertPage(page, KEY);
  const def = await get(U);
  check(R, 'image: default chart present', def.includes('organisational-chart-2026-v2.jpg'));
  const upload = async f => {
    await openEditor(page, KEY);
    await page.setInputFiles('input[type=file][name="files[chart][image]"]', f);
    await save(page);
    const html = await get(U);
    const m = html.match(/media\/governance\/governance\/organisational-chart-2026-v2\.jpg|storage\/cms\/[a-z0-9._-]+\.png/g);
    const imgs = html.match(/src="([^"]*storage\/cms\/[^"]+\.png)"/);
    return imgs ? imgs[1] : null;
  };
  const u1 = await upload(png1);
  check(R, 'image: uploaded file shows on live page', !!u1, 'no storage/cms img on page');
  if (u1) {
    const r = await page.request.get(u1);
    check(R, 'image: uploaded URL serves a PNG', r.ok() && (r.headers()['content-type'] || '').includes('image/png'), r.status() + ' ' + r.headers()['content-type']);
  }
  const u2 = await upload(png2);
  check(R, 'image: re-upload produces a NEW filename', !!u2 && u2 !== u1, `${u1} vs ${u2}`);
  check(R, 'image: old default gone from page after upload', !(await get(U)).includes('organisational-chart-2026-v2.jpg'));
  await openEditor(page, KEY);
  await page.click('.cms-upload [data-act=clear-file]');
  await save(page);
  check(R, 'image: clearing removes the <img> (no empty box)', !(await get(U)).includes('mx-auto w-full max-w-4xl rounded-lg shadow'));
  await revertPage(page, KEY);
  check(R, 'image: revert restores default chart', (await get(U)).includes('organisational-chart-2026-v2.jpg'));
}

// ============ 3. Repeater: add / hide / remove / reorder (chronology) ============
{
  const KEY = 'about.chronology', U = '/en/about-aseanapol/chronology';
  await revertPage(page, KEY);
  await openEditor(page, KEY);
  const rows = page.locator('[data-repeater] .cms-rows > [data-row]');
  const n0 = await rows.count();
  check(R, 'repeater: 8 default milestones', n0 === 8, n0);
  await page.click('[data-act=add-row]');
  check(R, 'repeater: add row increases count', (await rows.count()) === 9);
  const last = rows.last();
  await last.locator('input[name$="[year]"]').fill('2099');
  await last.locator('input[name$="[title]"]').fill('QA Added Milestone');
  await last.locator('textarea[name$="[desc]"]').fill('Added via Playwright');
  // hide row 0 (1981), remove row 1 (1984)
  await rows.nth(0).locator('input[name$="[_hide]"]').check();
  page.once('dialog', d => d.accept());
  await rows.nth(1).locator('[data-act=remove]').click();
  // reorder: move the new (now last) row to the top via "up" x6 clicks, and row 3 (was 1999) down once
  for (let i = (await rows.count()) - 1; i > 0; i--) await rows.nth(i).locator('[data-act=up]').click();
  await save(page);
  let html = await get(U);
  check(R, 'repeater: added row shown', html.includes('QA Added Milestone') && html.includes('Added via Playwright'));
  check(R, 'repeater: hidden row not shown', !html.includes('1st ASEANAPOL Conference'));
  check(R, 'repeater: removed row not shown', !html.includes('Expansion of Membership'));
  check(R, 'repeater: untouched rows shown', html.includes('Admission of New Members') && html.includes('43rd ASEANAPOL Conference'));
  const idxNew = html.indexOf('QA Added Milestone'), idxFirst = html.indexOf('Admission of New Members');
  check(R, 'repeater: reorder moved new row above an older one', idxNew > 0 && idxNew < idxFirst, `${idxNew} / ${idxFirst}`);
  // hidden row survives in the editor (still editable) and can be un-hidden
  await openEditor(page, KEY);
  const rows2 = page.locator('[data-repeater] .cms-rows > [data-row]');
  check(R, 'repeater: hidden row still in editor', (await rows2.count()) === 8, await rows2.count());
  await revertPage(page, KEY);
  html = await get(U);
  check(R, 'repeater: revert restores original rows', html.includes('1st ASEANAPOL Conference') && html.includes('Expansion of Membership') && !html.includes('QA Added Milestone'));
}

// ============ 4. Down-reorder + index cards (select field) ============
{
  const KEY = 'about.index', U = '/en/about-aseanapol';
  await revertPage(page, KEY);
  await openEditor(page, KEY);
  const rows = page.locator('[data-repeater] .cms-rows > [data-row]');
  await rows.nth(0).locator('[data-act=down]').click(); // History moves below Vision and Mission
  await rows.nth(0).locator('select[name$="[route]"]').selectOption('about.logo'); // (row 0 is now Vision) point it to the logo page
  await save(page);
  const html = await get(U);
  check(R, 'index: reordered cards (Vision before History)', html.indexOf('Vision and Mission') < html.indexOf('>History<') || html.indexOf('Vision and Mission</h3>') < html.indexOf('History</h3>'));
  check(R, 'index: select route changes link target', html.includes('/aseanapol-logo"'));
  await revertPage(page, KEY);
}

finish(R, errors);
await browser.close();
