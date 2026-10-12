// about.* pages read Shared facts (roster, tagline, logo, counts, dates).  node tests/e2e/relink-about.mjs
// Edits are made in shared.roster / shared.organisation only and restored by writing the original values back
// (no page revert: shared pages are used by other suites too).
import { launch, login, check, finish, openEditor, save, BASE } from './lib.mjs';

const SEC = '/en/about-aseanapol/permanent-secretariat';
const VIS = '/en/about-aseanapol/vision-and-mission';
const LOGO = '/en/about-aseanapol/aseanapol-logo';
const IDX = '/en/about-aseanapol';
const GOV = '/en/about-aseanapol/governance';

const { browser, page, errors } = await launch();
const R = [];
await login(page);
const get = async u => await (await page.request.get(BASE + u)).text();
const decode = s => s.replace(/&middot;/g, '·').replace(/&quot;/g, '"').replace(/&#039;|&#x27;/g, "'").replace(/&amp;/g, '&');

// locate a roster row by "post|ordinal" in the shared.roster editor
async function rosterIndex(post, ordinal) {
  const n = await page.locator('input[name^="f[people][rows]"][name$="[ordinal]"]').count();
  for (let i = 0; i < n; i++) {
    const o = await page.inputValue(`input[name="f[people][rows][${i}][ordinal]"]`);
    const p = await page.inputValue(`select[name="f[people][rows][${i}][post]"]`);
    if (p === post && o === ordinal) return i;
  }
  return -1;
}
const F = (i, f) => `[name="f[people][rows][${i}][${f}]"]`;

// ---- defaults: the Secretariat page renders the roster
let html = await get(SEC);
check(R, 'default: roster names on Secretariat page', html.includes('Kongkrissada Kittithiraphong') && html.includes('Jean Mary A. Mangahis') && html.includes('Huntal Tambunan'));
check(R, 'default: force shown as abbreviation only', /RTP &middot; 2026–2027|RTP · 2026–2027/.test(html));
check(R, 'default: newest ED first', html.indexOf('Kongkrissada') < html.indexOf('Mohd Nadzri'));

// ---- 1. roster edit: term, name, hide
await openEditor(page, 'shared.roster');
const ed9 = await rosterIndex('ed', '9th');
const dpp7 = await rosterIndex('dpp', '7th');
check(R, 'roster editor: rows located', ed9 >= 0 && dpp7 >= 0, `${ed9} ${dpp7}`);
const origTerm = await page.inputValue(F(ed9, 'term_end'));
const origName = await page.inputValue(F(ed9, 'name'));
await page.fill(F(ed9, 'term_end'), '2031');
await page.fill(F(ed9, 'name'), 'Qa Relink Name');
await save(page);
html = decode(await get(SEC));
check(R, 'roster: edited term appears on Secretariat page', html.includes('RTP · 2026–2031'));
check(R, 'roster: edited name appears', html.includes('Qa Relink Name') && !html.includes('Kongkrissada Kittithiraphong'));

// hide the DPP 7th row
await openEditor(page, 'shared.roster');
await page.locator(F(dpp7, 'name')).locator('xpath=ancestor::*[@data-row][1]').locator('input[name$="[_hide]"]').first().check();
await save(page);
html = await get(SEC);
check(R, 'roster: hidden row disappears', !html.includes('Jean Mary A. Mangahis') && html.includes('Nguyen Huu Ngoc'));

// restore roster
await openEditor(page, 'shared.roster');
await page.fill(F(ed9, 'term_end'), origTerm);
await page.fill(F(ed9, 'name'), origName);
await page.locator(F(dpp7, 'name')).locator('xpath=ancestor::*[@data-row][1]').locator('input[name$="[_hide]"]').first().uncheck();
await save(page);
html = decode(await get(SEC));
check(R, 'roster: restored', html.includes('RTP · 2026–2027') && html.includes('Kongkrissada Kittithiraphong') && html.includes('Jean Mary A. Mangahis'));

// ---- 2. organisation: tagline, logo, counts, dates
await openEditor(page, 'shared.organisation');
const orig = {};
for (const [k, n] of Object.entries({
  tagline: 'f[identity][tagline]', logo: 'f[identity][logo]', alt: 'f[identity][logo_alt]',
  year: 'f[key_dates][founded_year]', sec: 'f[key_dates][secretariat_established_label]', word: 'f[counts][member_count_word]',
  conf: 'f[key_dates][first_conference_label]',
})) orig[k] = [n, await page.inputValue(`[name="${n}"]`)];
const setOrg = async (vals) => {
  await openEditor(page, 'shared.organisation');
  for (const [k, v] of Object.entries(vals)) await page.fill(`[name="${orig[k][0]}"]`, v);
  await save(page);
};
await setOrg({ tagline: 'QA relinked tagline', logo: 'images/qa-relink-logo.png', alt: 'QA logo alt', year: '1999', sec: '9 September 2099', word: 'twelve', conf: '1 to 2 May 1999, Testville, Nowhere' });
html = decode(await get(VIS));
check(R, 'tagline: vision page shows edited tagline in quotes', html.includes('>"QA relinked tagline"<'));
html = await get(LOGO);
check(R, 'logo: logo page diagram uses shared logo', html.includes('images/qa-relink-logo.png'));
html = decode(await get(SEC));
check(R, 'logo: Secretariat intro uses shared logo + alt', html.includes('images/qa-relink-logo.png') && html.includes('alt="QA logo alt"'));
check(R, 'dates: secretariat operational date', html.includes('<strong>9 September 2099</strong>'));
check(R, 'dates: first conference place/date', html.includes('held in Testville, Nowhere on 1 to 2 May 1999,'));
html = decode(await get(IDX));
check(R, 'counts/year: About landing intro + subtitle + card', html.includes('established in 1999') && html.includes('among the twelve ASEAN') && html.includes('since 1999.') && html.includes('The twelve ASEAN member police forces') && html.includes('founding in 1999'));
html = decode(await get(GOV));
check(R, 'year: governance conference heading', html.includes('ASEANAPOL Conference (since 1999)'));

// restore organisation
await setOrg(Object.fromEntries(Object.keys(orig).map(k => [k, orig[k][1]])));
html = decode(await get(VIS));
check(R, 'restore: tagline back', html.includes('>"Toward a peaceful and crime-free ASEAN region"<'));
html = await get(LOGO);
check(R, 'restore: logo back', html.includes('images/aseanapol-logo.png') && !html.includes('qa-relink-logo'));
html = decode(await get(IDX));
check(R, 'restore: landing back', html.includes('established in 1981') && html.includes('among the eleven ASEAN'));

await browser.close();
finish(R, errors);
