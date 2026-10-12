// Targeted tests for group about-people: image upload (+re-upload => new filename), add/hide/remove/reorder rows.
import { launch, login, check, finish, openEditor, save, revertPage, BASE } from './lib.mjs';
import fs from 'node:fs';
import os from 'node:os';
import path from 'node:path';

const { browser, page, errors } = await launch();
const R = [];
await login(page);

// 1x1 red PNG
const png = path.join(os.tmpdir(), 'about-people-qa.png');
fs.writeFileSync(png, Buffer.from('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8DwHwAFBQIAX8jx0gAAAABJRU5ErkJggg==', 'base64'));

const get = async (p) => await (await page.request.get(BASE + p)).text();
const rep = (name) => page.locator('[data-repeater]').filter({ has: page.locator(`[name^="f[${name}]"]`) }).first();
const rowsOf = (name) => rep(name).locator('.cms-rows > [data-row]');

// ───────── Leadership: photo upload, re-upload, past rows add/hide/remove/reorder
{
  const KEY = 'about-people.leadership', URL_ = '/en/about-aseanapol/leadership';
  await revertPage(page, KEY);
  await openEditor(page, KEY);
  await page.locator('input[type=file]').first().setInputFiles(png);
  await save(page);
  let html = await get(URL_);
  const m1 = html.match(/src="([^"]*storage\/cms\/[^"]+\.png)"/);
  check(R, 'leadership: uploaded photo appears on live page', !!m1, 'no storage/cms img');
  if (m1) check(R, 'leadership: uploaded file is served', (await page.request.get(m1[1])).status() === 200);

  await openEditor(page, KEY);
  await page.locator('input[type=file]').first().setInputFiles(png);
  await save(page);
  html = await get(URL_);
  const m2 = html.match(/src="([^"]*storage\/cms\/[^"]+\.png)"/);
  check(R, 're-upload produces a NEW filename', m1 && m2 && m1[1] !== m2[1], `${m1 && m1[1]} vs ${m2 && m2[1]}`);

  // past rows
  await openEditor(page, KEY);
  const before = await rowsOf('past').count();
  check(R, 'leadership: 9 past directors in editor', before === 9, before);
  await rep('past').locator('[data-act=add-row]').click();
  const n = rowsOf('past');
  check(R, 'leadership: row added', (await n.count()) === 10);
  const last = n.last();
  await last.locator('input[name$="[no]"]').fill('10th');
  await last.locator('input[name$="[name]"]').fill('QA Director Name');
  await last.locator('input[name$="[force]"]').fill('QA Police Force');
  await last.locator('input[name$="[flag]"]').fill('QQ');
  await last.locator('input[name$="[term]"]').fill('2028-2029');
  await last.locator('input[type=file]').setInputFiles(png);
  // hide 9th-listed first row (Kongkrissada), remove "DAC Jim Wee" (row 3)
  await n.nth(0).locator('input[name$="[_hide]"]').check();
  page.once('dialog', d => d.accept());
  await n.nth(3).locator('[data-act=remove]').click();
  // reorder: move Zaw Lin Tun (index 2) down below Pol. Col. Kenechanh (now index 3)
  await rowsOf('past').nth(2).locator('[data-act=down]').click();
  await save(page);
  html = await get(URL_);
  const tbl = html.slice(html.indexOf('<tbody'));
  check(R, 'leadership: new row shows all fields', ['10th', 'QA Director Name', 'QA Police Force', 'QQ', '2028–2029'.replace('–', '-')].every(t => tbl.includes(t)));
  check(R, 'leadership: new row photo uploaded', /<img src="[^"]*storage\/cms\/[^"]+"[^>]*alt="QA Director Name"/.test(tbl));
  check(R, 'leadership: hidden row gone from table', !tbl.includes('Police Senior Colonel Kongkrissada') || (tbl.match(/Kongkrissada/g) || []).length === 0);
  check(R, 'leadership: removed row gone', !tbl.includes('DAC Jim Wee'));
  check(R, 'leadership: reorder applied', tbl.indexOf('Kenechanh') < tbl.indexOf('Zaw Lin Tun') && tbl.indexOf('Zaw Lin Tun') > 0);
  check(R, 'leadership: current-ED card still shows (other section untouched)', html.includes('Current Executive Director'));

  // toggle sections
  await openEditor(page, KEY);
  await page.uncheck('[data-testid=vis-directors]');
  await save(page);
  html = await get(URL_);
  check(R, 'leadership: directors section hidden', !html.includes('Secretariat Directors'));
  await revertPage(page, KEY);
  html = await get(URL_);
  check(R, 'leadership: revert restores', html.includes('Secretariat Directors') && html.includes('DAC Jim Wee') && !html.includes('QA Director Name'));
}

// ───────── DPS: edit current holder text
{
  const KEY = 'about-people.leadership-dps', URL_ = '/en/about-aseanapol/leadership/director-for-police-services';
  await revertPage(page, KEY);
  await openEditor(page, KEY);
  await page.fill('input[name="f[current][name]"]', 'QA Current Director');
  await page.fill('textarea[name="f[current][bio]"]', 'QA biography text.');
  await save(page);
  const html = await get(URL_);
  check(R, 'dps: name and bio edited', html.includes('QA Current Director') && html.includes('QA biography text.'));
  await revertPage(page, KEY);
  check(R, 'dps: revert', (await get(URL_)).includes('Huntal Tambunan'));
}

// ───────── LME: add entity with logo upload + website
{
  const KEY = 'about-people.ob-lme', URL_ = '/en/about-aseanapol/lme';
  await revertPage(page, KEY);
  await openEditor(page, KEY);
  await rep('entities').locator('[data-act=add-row]').click();
  const last = rowsOf('entities').last();
  await last.locator('input[name$="[short]"]').fill('QAENT');
  await last.locator('input[name$="[name]"]').fill('QA Entity Full Name');
  await last.locator('input[name$="[website]"]').fill('https://example.org/qa');
  await last.locator('input[type=file]').setInputFiles(png);
  await rowsOf('entities').nth(0).locator('input[name$="[_hide]"]').check();
  await save(page);
  let html = await get(URL_);
  check(R, 'lme: new entity fields shown', html.includes('QAENT') && html.includes('QA Entity Full Name') && html.includes('https://example.org/qa'));
  check(R, 'lme: logo uploaded', /src="[^"]*storage\/cms\/[^"]+"\s+alt="QAENT logo"/.test(html));
  check(R, 'lme: hidden entity gone', !html.includes('International Association for Forensic Institutes'));
  await revertPage(page, KEY);
  check(R, 'lme: revert', (await get(URL_)).includes('International Association for Forensic Institutes'));
}

// ───────── Dialogue partners: add partner (all fields), badge icon/flag, reorder
{
  const KEY = 'about-people.dialogue-partners', URL_ = '/en/about-aseanapol/dialogue-partners';
  await revertPage(page, KEY);
  await openEditor(page, KEY);
  await rep('partners').locator('[data-act=add-row]').click();
  const last = rowsOf('partners').last();
  const set = async (k, v) => { const l = last.locator(`[name$="[${k}]"]`); await l.fill(v); };
  await set('name', 'QA Partner Agency'); await set('full', 'QA Partner Full'); await set('badge', 'hub');
  await set('since', '2030'); await set('website', 'https://example.org/partner'); await set('q', 'QAKEY'); await set('desc', 'QA partner description.');
  await last.locator('[data-act=up]').click();   // move to position 11 of 13
  await save(page);
  let html = await get(URL_);
  check(R, 'dp: partner fields shown', ['QA Partner Agency', 'QA Partner Full', 'Since 2030', 'https://example.org/partner', 'q=QAKEY', 'QA partner description.'].every(t => html.includes(t)));
  check(R, 'dp: badge icon rendered', />hub<\/span>/.test(html));
  check(R, 'dp: reordered before Europol', html.indexOf('QA Partner Agency') < html.indexOf('European Union Agency for Law'));
  await revertPage(page, KEY);
  check(R, 'dp: revert', !(await get(URL_)).includes('QA Partner Agency'));
}

// ───────── Observers: add / hide / remove / reorder
{
  const KEY = 'about-people.observers', URL_ = '/en/about-aseanapol/observers';
  await revertPage(page, KEY);
  await openEditor(page, KEY);
  await rep('list').locator('[data-act=add-row]').click();
  const rows = rowsOf('list');
  const last = rows.last();
  for (const [k, v] of [['name', 'QA Observer'], ['flag', 'QF'], ['since', '2031'], ['conference', '99th AC, Nowhere'], ['q', 'QAOBS'], ['url', 'https://example.org/obs']])
    await last.locator(`[name$="[${k}]"]`).fill(v);
  await rows.nth(0).locator('input[name$="[_hide]"]').check();
  page.once('dialog', d => d.accept());
  await rows.nth(1).locator('[data-act=remove]').click();
  await rows.last().locator('[data-act=up]').click();
  await save(page);
  let html = await get(URL_);
  check(R, 'observers: new observer fields shown', ['QA Observer', 'QF', 'Since 2031', '99th AC, Nowhere', 'q=QAOBS', 'https://example.org/obs'].every(t => html.includes(t)));
  check(R, 'observers: hidden gone', !html.includes('Argentine Federal Police'));
  check(R, 'observers: removed gone', !html.includes('Bangladesh Police'));
  check(R, 'observers: reordered (new row moved up from bottom)', html.indexOf('QA Observer') < html.indexOf('Gulf Cooperation Council Police'));
  await page.screenshot({ path: path.join(os.tmpdir(), 'observers-public.png'), fullPage: false });
  await revertPage(page, KEY);
  check(R, 'observers: revert', (await get(URL_)).includes('Argentine Federal Police'));
}

for (const k of ['leadership', 'leadership-dps', 'leadership-dpp', 'ob-lme', 'dialogue-partners', 'observers']) {
  await openEditor(page, 'about-people.' + k);
  await page.screenshot({ path: path.join(os.tmpdir(), `editor-${k}.png`), fullPage: false });
}
finish(R, errors);
await browser.close();
