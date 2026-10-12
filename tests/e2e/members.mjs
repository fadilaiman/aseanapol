// Targeted CMS test for the "members" group (Member Countries).  node tests/e2e/members.mjs
import { launch, login, check, finish, openEditor, save, revertPage, BASE } from './lib.mjs';

const SG = 'members.singapore', SG_URL = BASE + '/en/about-aseanapol/member-countries/singapore-police-force';
const MY = 'members.malaysia',   MY_URL = BASE + '/en/about-aseanapol/member-countries/royal-malaysia-police';
const BN = 'members.brunei',     BN_URL = BASE + '/en/about-aseanapol/member-countries/royal-brunei-police-force';
const LA = 'members.lao',        LA_URL = BASE + '/en/about-aseanapol/member-countries/lao-police-force';
const KH = 'members.cambodia',   KH_URL = BASE + '/en/about-aseanapol/member-countries/cambodian-national-police';
const TL = 'members.timor-leste', TL_URL = BASE + '/en/about-aseanapol/member-countries/national-police-of-timor-leste';
const IDX = 'members.index',     IDX_URL = BASE + '/en/about-aseanapol/member-countries';
const SHOT = process.env.SHOT_DIR || '.';

// 1x1 PNGs (different bytes) for upload tests
const PNG1 = Buffer.from('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR4nGP4z8DwHwAFAAH/q842iQAAAABJRU5ErkJggg==', 'base64');
const PNG2 = Buffer.from('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR4nGNgYPj/HwADAgH/Hq4rngAAAABJRU5ErkJggg==', 'base64');

const { browser, page, errors } = await launch();
const R = [];
const getHtml = async u => await (await page.request.get(u)).text();
const rep = (sec, f) => page.locator('[data-repeater]').filter({ has: page.locator(`[name="f[${sec}][${f}][__present]"]`) });
const rowsOf = (sec, f) => rep(sec, f).locator(':scope > .cms-rows > [data-row]');

await login(page);
for (const k of [SG, MY, BN, LA, KH, IDX, 'members.labels', 'members.labels-pending']) await revertPage(page, k);

// ---- form size (max_input_vars = 1000)
for (const k of [SG, MY]) {
  await openEditor(page, k);
  const n = await page.evaluate(() => Array.from(new FormData(document.getElementById('cms-form')).keys()).length);
  check(R, `${k}: editor form has ${n} inputs (< 400, far below max_input_vars)`, n < 400, n);
  if (k === SG) await page.screenshot({ path: `${SHOT}/members-singapore-editor.png`, fullPage: true });
}

// ---- 1. chief name + contact person live
await openEditor(page, SG);
await page.fill('input[name="f[chief][name]"]', 'QA Chief Name');
await rowsOf('contacts', 'items').nth(0).locator('input[name$="[name]"]').fill('QA Contact One');
await save(page);
let html = await getHtml(SG_URL);
check(R, 'chief name edited live', html.includes('QA Chief Name') && !html.includes('How Kwang Hwee'));
check(R, 'contact person edited live', html.includes('QA Contact One') && !html.includes('Superintendent Ivan Ler'));
check(R, 'other contacts untouched', html.includes('Julian Teu') && html.includes('Mila Alyssa'));

// ---- 2. chief photo upload + re-upload => new filename
const photoSrc = h => (h.match(/<img src="([^"]*)"\s+alt="QA Chief Name"/) || [])[1];
await openEditor(page, SG);
await page.setInputFiles('input[name="files[chief][photo]"]', { name: 'chief.png', mimeType: 'image/png', buffer: PNG1 });
await save(page);
const src1 = photoSrc(await getHtml(SG_URL));
check(R, 'uploaded chief photo is used', src1 && /storage\/cms\/chief-/.test(src1), src1);
const st1 = await page.request.get(src1);
check(R, 'uploaded photo is served (200, image)', st1.status() === 200 && /image\/png/.test(st1.headers()['content-type'] || ''), st1.status());
await openEditor(page, SG);
await page.setInputFiles('input[name="files[chief][photo]"]', { name: 'chief.png', mimeType: 'image/png', buffer: PNG2 });
await save(page);
const src2 = photoSrc(await getHtml(SG_URL));
check(R, 're-upload with same name produces a NEW filename', src2 && src2 !== src1 && /storage\/cms\/chief-/.test(src2), src1 + ' vs ' + src2);

// contact photo upload inside a repeater row
await openEditor(page, SG);
await page.setInputFiles('input[name="files[contacts][items][1][photo]"]', { name: 'julian.png', mimeType: 'image/png', buffer: PNG1 });
await save(page);
html = await getHtml(SG_URL);
check(R, 'contact photo uploaded in a repeater row', /storage\/cms\/julian-/.test(html));

// ---- 3. history rows: add / hide / remove / reorder
await openEditor(page, SG);
let rows = rowsOf('history', 'items');
const n0 = await rows.count();
check(R, 'singapore starts with 13 history rows', n0 === 13, n0);
await rep('history', 'items').locator('[data-act=add-row]').click();
rows = rowsOf('history', 'items');
check(R, 'add row -> 14 rows', (await rows.count()) === 14);
await rows.last().locator('input[name$="[year]"]').fill('2099');
await rows.last().locator('textarea[name$="[text]"]').fill('QA future event');
await rows.nth(0).locator('input[name$="[_hide]"]').check();           // hide 1819
page.once('dialog', d => d.accept());
await rows.nth(1).locator('[data-act=remove]').click();                // remove 1826
await rows.nth(2).locator('[data-act=up]').click();                    // 1862 above 1856
await save(page);
html = await getHtml(SG_URL);
check(R, 'new history row shown', html.includes('2099') && html.includes('QA future event'));
check(R, 'hidden history row not shown', !html.includes('first police team comprised'));
check(R, 'removed history row not shown', !html.includes('Straits Settlements Police Force'));
check(R, 'reordered rows swap order', html.indexOf('Detective Branch was established') < html.indexOf('Thomas Dunman became'));
await openEditor(page, SG);
check(R, 'hidden row stays in editor (checkbox kept)', await rowsOf('history', 'items').nth(0).locator('input[name$="[_hide]"]').first().isChecked());

// ---- 4. section toggles
await revertPage(page, SG);
await openEditor(page, SG);
await page.uncheck('[data-testid=vis-history]');
await page.uncheck('[data-testid=vis-contacts]');
await save(page);
html = await getHtml(SG_URL);
check(R, 'history toggle hides block', !/mb-6">History<\/h3>/.test(html) && !html.includes('Thomas Dunman'));
check(R, 'contacts toggle hides all contact cards', !html.includes('Contact Person</span>'));
await revertPage(page, SG);

await openEditor(page, MY);
await page.uncheck('[data-testid=vis-positions]');
await save(page);
html = await getHtml(MY_URL);
check(R, 'official positions toggle off hides list', !html.includes('Official Positions') && html.includes('Rank Structure'));
await openEditor(page, MY);
await page.check('[data-testid=vis-positions]');
await page.uncheck('[data-testid=vis-ranks]');
await save(page);
html = await getHtml(MY_URL);
check(R, 'rank structure toggle off hides table', !html.includes('Rank Structure') && html.includes('Official Positions'));
await openEditor(page, MY);
await page.uncheck('[data-testid=vis-positions]');
await save(page);
html = await getHtml(MY_URL);
check(R, 'both off -> "pending" placeholders return', html.includes('Rank &amp; Uniforms') && html.includes('Pending'));
await revertPage(page, MY);

// ---- 5. second contact person on a single-contact country
await openEditor(page, BN);
await rep('contacts', 'items').locator('[data-act=add-row]').click();
const last = rowsOf('contacts', 'items').last();
await last.locator('input[name$="[name]"]').fill('QA Second Contact');
await last.locator('input[name$="[designation]"]').fill('QA Designation');
await last.locator('input[name$="[mobile]"]').fill('+60 12 345 6789');
await last.locator('input[name$="[email]"]').fill('qa@example.test');
await save(page);
html = await getHtml(BN_URL);
check(R, 'second contact person added', (html.match(/Contact Person<\/span>/g) || []).length === 2 && html.includes('QA Second Contact') && html.includes('tel:+60123456789') && html.includes('mailto:qa@example.test'));
check(R, 'contact without photo renders (no broken img)', !/<img src=""/.test(html));
await revertPage(page, BN);

// ---- 6. add a missing block (history for Lao)
html = await getHtml(LA_URL);
check(R, 'lao has no History block by default', !/mb-6">History<\/h3>/.test(html));
await openEditor(page, LA);
check(R, 'lao history has no toggle (nothing to hide yet)', (await page.locator('[data-testid=vis-history]').count()) === 0);
await rep('history', 'items').locator('[data-act=add-row]').click();
await rowsOf('history', 'items').last().locator('input[name$="[year]"]').fill('1975');
await rowsOf('history', 'items').last().locator('textarea[name$="[text]"]').fill('QA lao history');
await save(page);
html = await getHtml(LA_URL);
check(R, 'admin-added history block appears for lao', /mb-6">History<\/h3>/.test(html) && html.includes('QA lao history'));
await revertPage(page, LA);
check(R, 'revert removes added block', !/mb-6">History<\/h3>/.test(await getHtml(LA_URL)));

// ---- 7. placeholders (Timor-Leste has no phone in Shared facts; phone/email/fax live in shared.members now)
html = await getHtml(TL_URL);
check(R, 'timor-leste default shows "Telephone: pending"', html.includes('Telephone: pending'));
await openEditor(page, KH);
check(R, 'country editor no longer has the shared identity/contact fields', (await page.locator('input[name="f[identity][force]"], input[name="f[contact][email]"], input[name="f[identity][logo]"]').count()) === 0);
await openEditor(page, 'members.labels-pending');
await page.fill('input[name="f[placeholders][email_pending]"]', 'QA email missing');
await page.fill('input[name="f[placeholders][tel_pending]"]', 'QA tel missing');
await save(page);
html = await getHtml(TL_URL);
check(R, 'placeholder wording editable', html.includes('QA tel missing'));
await revertPage(page, 'members.labels-pending');

await openEditor(page, 'members.labels');
await page.fill('textarea[name="f[general][membership_note]"]', 'QA note about {country}.');
await save(page);
html = await getHtml(SG_URL);
check(R, 'membership note label with {country} placeholder', html.includes('QA note about Singapore.'));
await revertPage(page, 'members.labels');

// ---- 8. list page: cards come from Shared facts; page-level wording stays local
await openEditor(page, IDX);
await page.fill('input[name="f[hero][title]"]', 'QA Members Title');
await save(page);
html = await getHtml(IDX_URL);
check(R, 'list: hero title edited', html.includes('QA Members Title'));
check(R, 'list: all 11 cards still present', (html.match(/card-hover group/g) || []).length >= 11 && html.includes('Brunei Darussalam'));
check(R, 'country page breadcrumb follows list title', (await getHtml(SG_URL)).includes('QA Members Title'));
await revertPage(page, IDX);

// ---- 9. everything reverted to the baseline default
html = await getHtml(SG_URL);
check(R, 'revert restores singapore defaults', html.includes('How Kwang Hwee') && html.includes('Ivan Ler') && html.includes('Thomas Dunman'));

finish(R, errors);
await browser.close();
