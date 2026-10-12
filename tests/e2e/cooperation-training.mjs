// Targeted tests for the "cooperation" and "training" CMS groups.
import fs from 'node:fs';
import os from 'node:os';
import path from 'node:path';
import { launch, login, check, finish, openEditor, save, revertPage, BASE } from './lib.mjs';

const get = async (page, url) => await (await page.request.get(BASE + url)).text();
const { browser, page, errors } = await launch();
const R = [];
await login(page);

// 1x1 PNG generated on the fly
const png = Buffer.from('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==', 'base64');
const tmp = fs.mkdtempSync(path.join(os.tmpdir(), 'cms-png-'));
const pngA = path.join(tmp, 'qa-logo.png');
fs.writeFileSync(pngA, png);

// ---------------------------------------------------------------- partners: upload, re-upload, repeater ops
const IDX = 'cooperation.index', IDX_URL = '/en/international-cooperation';
await revertPage(page, IDX);
await openEditor(page, IDX);
await page.screenshot({ path: path.join(tmp, 'admin-cooperation-index.png'), fullPage: false });
const rows = page.locator('[name="f[partners][items][__present]"]').locator('xpath=ancestor::*[@data-repeater][1]').locator(':scope > .cms-rows > [data-row]');
check(R, 'partners repeater has 17 default rows', (await rows.count()) === 17, await rows.count());
await rows.first().locator('input[type=file]').setInputFiles(pngA);
await save(page);
let html = await get(page, IDX_URL);
const m1 = html.match(/src="[^"]*(storage\/cms\/qa-logo-[^"]+\.png)"/);
check(R, 'uploaded logo is rendered on the page', !!m1, 'no storage/cms img found');
const first = m1 && m1[1];
check(R, 'logo file is served', first && (await page.request.get(BASE + '/' + first)).status() === 200, first);
check(R, 'icon span is kept but hidden when logo set', /text-xl hidden">school</.test(html));

// re-upload -> new unique filename
await openEditor(page, IDX);
await rows.first().locator('input[type=file]').setInputFiles(pngA);
await save(page);
html = await get(page, IDX_URL);
const m2 = html.match(/src="[^"]*(storage\/cms\/qa-logo-[^"]+\.png)"/);
check(R, 're-upload generates a NEW filename', m2 && first && m2[1] !== first, `${first} vs ${m2 && m2[1]}`);
check(R, 'old logo no longer referenced', !html.includes(first));

// add / hide / remove / reorder
await openEditor(page, IDX);
await page.locator('[name="f[partners][items][__present]"]').locator('xpath=ancestor::*[@data-repeater][1]').locator(':scope > [data-act=add-row]').click();
check(R, 'add row -> 18 rows', (await rows.count()) === 18);
const last = rows.last();
await last.locator('input[name$="[name]"]').fill('QA Partner Org');
await last.locator('input[name$="[category]"]').fill('QA Category');
await last.locator('textarea[name$="[desc]"]').fill('QA partner description');
await last.locator('input[name$="[website]"]').fill('https://qa.example.test/partner');
await rows.nth(1).locator('input[name$="[_hide]"]').check(); // APMLA hidden
page.once('dialog', d => d.accept());
await rows.nth(2).locator('[data-act=remove]').click();       // BorderPol removed
await rows.nth(3).locator("[data-act=up]").click();           // CITES above CARICC
await save(page);
html = await get(page, IDX_URL);
check(R, 'new partner shown with website link', html.includes('QA Partner Org') && html.includes('https://qa.example.test/partner'));
check(R, 'hidden partner not shown', !html.includes('Asia Pacific Medico Legal Agencies'));
check(R, 'removed partner not shown', !html.includes('World Border Organization'));
check(R, 'reorder: CITES before CARICC', html.indexOf('CITES Secretariat') < html.indexOf('>CARICC<'));
await openEditor(page, IDX);
check(R, 'hidden row stays editable (still 17 rows after remove+add)', (await rows.count()) === 17, await rows.count());

// toggles
await page.uncheck('[data-testid=vis-quicklinks]');
await page.uncheck('[data-testid=vis-intro]');
await save(page);
html = await get(page, IDX_URL);
check(R, 'quick links + intro sections hidden', !html.includes('arrow_forward') && !html.includes('maintains active collaborative'));
check(R, 'partners still shown', html.includes('Partner Organisations'));

// quick link retargeting via select
await openEditor(page, IDX);
await page.uncheck('[data-testid=vis-quicklinks]').catch(() => {});
await page.check('[data-testid=vis-quicklinks]');
await page.locator('select[name="f[quicklinks][items][0][route]"]').selectOption('international-cooperation.unodc');
await save(page);
html = await get(page, IDX_URL);
check(R, 'quick link retargeted', /href="[^"]*\/en\/international-cooperation\/unodc"[^>]*>\s*<span[^>]*>shield</.test(html));
await revertPage(page, IDX);
html = await get(page, IDX_URL);
check(R, 'revert restores partners & logo gone', html.includes('Asia Pacific Medico Legal Agencies') && !html.includes('storage/cms/qa-logo'));

// ---------------------------------------------------------------- INTERPOL: richtext + table rows
const IP = 'cooperation.interpol', IP_URL = '/en/international-cooperation/interpol';
await revertPage(page, IP);
await openEditor(page, IP);
const ed = page.locator('.cms-rt .ql-editor').first();
await ed.click();
await page.keyboard.press('Control+End');
await page.keyboard.type(' QA typed richtext sentence.');
const igaRep = page.locator('[name="f[iga][rows][__present]"]').locator('xpath=ancestor::*[@data-repeater][1]');
const igaRows = igaRep.locator(':scope > .cms-rows > [data-row]');
await igaRep.locator(':scope > [data-act=add-row]').click();
const lr = igaRows.last();
await lr.locator('input[name$="[session]"]').fill('93rd IGA');
await lr.locator('input[name$="[year]"]').fill('2025');
await lr.locator('input[name$="[city]"]').fill('Marrakech');
await lr.locator('input[name$="[country]"]').fill('Morocco');
await igaRows.nth(0).locator('[data-act=down]').click();
await save(page);
html = await get(page, IP_URL);
check(R, 'richtext typing saved and rendered', html.includes('QA typed richtext sentence.'));
check(R, 'richtext keeps <strong> from default', html.includes('<strong>ADSTC') || html.includes('<strong>ASEANAPOL Database System Technical'));
check(R, 'new IGA row rendered', html.includes('93rd IGA') && html.includes('Marrakech, Morocco'));
check(R, 'reorder: 90th before 92nd', html.indexOf('90th IGA') < html.indexOf('92nd IGA'));
await page.goto(BASE + '/admin/site-content/' + IP);
await page.screenshot({ path: path.join(tmp, 'admin-cooperation-interpol.png') });
await revertPage(page, IP);
html = await get(page, IP_URL);
check(R, 'interpol revert', !html.includes('QA typed') && !html.includes('93rd IGA'));

// ---------------------------------------------------------------- Joint projects: status badge colour
const JP = 'cooperation.joint-projects', JP_URL = '/en/international-cooperation/joint-projects';
await revertPage(page, JP);
await openEditor(page, JP);
const jr = page.locator('[name="f[projects][items][__present]"]').locator('xpath=ancestor::*[@data-repeater][1]').locator(':scope > .cms-rows > [data-row]');
await jr.first().locator('select[name$="[status]"]').selectOption('Past');
await save(page);
html = await get(page, JP_URL);
check(R, 'status change switches badge colour', (html.match(/bg-green-100 text-green-700/g) || []).length === 6 && /bg-gray-100 text-gray-500[^>]*>Past/.test(html), (html.match(/bg-green-100 text-green-700/g) || []).length);
await revertPage(page, JP);

// ---------------------------------------------------------------- Training: table + notice
const OT = 'training.online-training', OT_URL = '/en/training/online-training';
await revertPage(page, OT);
await openEditor(page, OT);
const tr = page.locator('[name="f[table][rows][__present]"]').locator('xpath=ancestor::*[@data-repeater][1]');
const trRows = tr.locator(':scope > .cms-rows > [data-row]');
await tr.locator(':scope > [data-act=add-row]').click();
await trRows.last().locator('textarea[name$="[title]"]').fill('QA Course Title');
await trRows.last().locator('input[name$="[date]"]').fill('1 – 2 July 2026');
await trRows.nth(0).locator('input[name$="[_hide]"]').check();
await save(page);
html = await get(page, OT_URL);
check(R, 'training: new row shown & numbered 6', html.includes('QA Course Title') && />6<\/td>/.test(html));
check(R, 'training: hidden row gone, rest renumbered', !html.includes('Countering Embezzlement') && !/>7<\/td>/.test(html));
await revertPage(page, OT);

const IN = 'training.in-person-training';
await revertPage(page, IN);
await openEditor(page, IN);
await page.fill('input[name="f[soon][heading]"]', 'Opening Soon QA');
await save(page);
html = await get(page, '/en/training/in-person-training');
check(R, 'training: coming-soon heading editable', html.includes('Opening Soon QA'));
await page.uncheck('[data-testid=vis-soon]');
await save(page);
html = await get(page, '/en/training/in-person-training');
check(R, 'training: notice toggle hides it', !html.includes('Opening Soon QA') && !html.includes('model_training') );
await page.screenshot({ path: path.join(tmp, 'admin-training-inperson.png') });
await revertPage(page, IN);

console.log('screenshots in ' + tmp);
finish(R, errors);
await browser.close();
