// Members pages read Shared facts.  node tests/e2e/relink-members.mjs
// Editing shared.members changes the country page + the Member Countries card; hiding removes the card only.
import { launch, login, check, finish, openEditor, save, revertPage, BASE } from './lib.mjs';

const SH = 'shared.members', ORG = 'shared.organisation';
const IDX_URL = BASE + '/en/about-aseanapol/member-countries';
const SG_URL = BASE + '/en/about-aseanapol/member-countries/singapore-police-force';
const BN_URL = BASE + '/en/about-aseanapol/member-countries/royal-brunei-police-force';
const KH_URL = BASE + '/en/about-aseanapol/member-countries/cambodian-national-police';
const TL_URL = BASE + '/en/about-aseanapol/member-countries/national-police-of-timor-leste';
const M = (i, f) => `input[name="f[list][rows][${i}][${f}]"]`;   // 0 brunei, 1 cambodia, 7 singapore, 10 timor-leste

const { browser, page, errors } = await launch();
const R = [];
const getHtml = async u => await (await page.request.get(u)).text();

await login(page);
for (const k of [SH, ORG, 'members.index', 'members.labels', 'members.singapore']) await revertPage(page, k);

// ---- defaults / approved reconciliations
let idx = await getHtml(IDX_URL), sg = await getHtml(SG_URL);
check(R, 'index card: Indonesian National Police (INP)', idx.includes('>Indonesian National Police (INP)<') || idx.includes('Indonesian National Police (INP)'));
check(R, 'index card: Philippine National Police (PNP)', idx.includes('Philippine National Police (PNP)'));
check(R, 'brunei page now shows fax', (await getHtml(BN_URL)).includes('+673 245 9527'));
check(R, 'cambodia page now shows tel + fax', (await getHtml(KH_URL)).includes('+855 23216585') && (await getHtml(KH_URL)).includes('+855 2321 6585'));
check(R, 'index wording uses the shared count ("eleven")', idx.includes('Eleven ASEAN Member States'));

// ---- edit singapore force / abbreviation / email / tel / website / since in Shared facts
await openEditor(page, SH);
await page.fill(M(7, 'force'), 'QA Force Name');
await page.fill(M(7, 'abbreviation'), 'QAF');
await page.fill(M(7, 'ncb_email'), 'qa-sg@example.test');
await page.fill(M(7, 'tel'), '+65 QA 111');
await page.fill(M(7, 'tel2'), '+65 QA 222');
await page.fill(M(7, 'fax'), '+65 QA FAX');
await page.fill(M(7, 'website'), 'https://qa-sg.example.test');
await page.fill(M(7, 'joined_year'), '1999');
await save(page);
sg = await getHtml(SG_URL); idx = await getHtml(IDX_URL);
check(R, 'country page: force/abbreviation from shared', sg.includes('QA Force Name') && sg.includes('>QAF<') && !sg.includes('Singapore Police Force</h2>'));
check(R, 'country page: e-mail, tels, fax, website from shared', sg.includes('qa-sg@example.test') && sg.includes('+65 QA 111') && sg.includes('+65 QA 222') && sg.includes('+65 QA FAX') && sg.includes('qa-sg.example.test'));
check(R, 'index card: composed name + since from shared', idx.includes('QA Force Name (QAF)') && idx.includes('1999'));

// ---- logo path edit
await openEditor(page, SH);
await page.fill(M(7, 'logo'), 'media/police-logo/qa-logo-test.png');
await save(page);
sg = await getHtml(SG_URL);
check(R, 'country page crest follows shared logo path', sg.includes('media/police-logo/qa-logo-test.png'));

// ---- timor-leste: clear e-mail -> placeholder; badge edit -> card
await openEditor(page, SH);
await page.fill(M(10, 'ncb_email'), '');
await page.fill(M(10, 'badge'), 'QABADGE');
await save(page);
check(R, 'empty shared e-mail => "Email: pending"', (await getHtml(TL_URL)).includes('Email: pending'));
check(R, 'index corner badge from shared', (await getHtml(IDX_URL)).includes('QABADGE'));

// ---- hide a member in Shared: card gone, page still reachable
await openEditor(page, SH);
await page.locator(M(0, 'key')).locator('xpath=ancestor::*[@data-row][1]').locator('input[name$="[_hide]"]').first().check();
await save(page);
idx = await getHtml(IDX_URL);
check(R, 'hidden shared member: card gone', !idx.includes('Brunei Darussalam') && idx.includes('Cambodia'));
const bn = await page.request.get(BN_URL);
const bnHtml = await bn.text();
check(R, 'hidden shared member: country page still 200 with its data', bn.status() === 200 && bnHtml.includes('Royal Brunei Police Force') && bnHtml.includes('rbpf@aseanapol.org'));

// ---- member count wording follows Organisation > Counts
await openEditor(page, ORG);
await page.fill('input[name="f[counts][member_count_word]"]', 'twelve');
await save(page);
check(R, 'index wording follows shared member_count_word', (await getHtml(IDX_URL)).includes('Twelve ASEAN Member States') && (await getHtml(IDX_URL)).includes('twelve national police forces'));
check(R, 'country page membership note follows count word', (await getHtml(SG_URL)).includes('one of the twelve member states'));

// ---- country page keeps page-specific content local
check(R, 'chief / history stay local on the country page', sg.includes('How Kwang Hwee') && sg.includes('Thomas Dunman'));

// ---- revert
for (const k of [SH, ORG]) await revertPage(page, k);
idx = await getHtml(IDX_URL); sg = await getHtml(SG_URL);
check(R, 'revert: brunei card back, singapore default restored', idx.includes('Brunei Darussalam') && sg.includes('Singapore Police Force') && !sg.includes('QA Force Name'));
check(R, 'revert: count wording back', idx.includes('Eleven ASEAN Member States'));

finish(R, errors);
await browser.close();
