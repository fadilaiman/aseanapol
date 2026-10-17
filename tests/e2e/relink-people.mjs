// Relink test: leadership / LME / dialogue / observers pages READ shared.roster, shared.partners, shared.dialogue.
//   CMS_BASE=http://127.0.0.1:<port> node tests/e2e/relink-people.mjs
import { launch, login, check, finish, openEditor, save, revertPage, BASE } from './lib.mjs';

const { browser, page, errors } = await launch();
const R = [];
await login(page);
const get = async (p) => await (await page.request.get(BASE + p)).text();
const LEAD = '/en/about-aseanapol/leadership';
const DPS = LEAD + '/director-for-police-services';
const LME = '/en/about-aseanapol/lme', DLG = '/en/about-aseanapol/dialogue-partners', OBS = '/en/about-aseanapol/observers';
const rows = () => page.locator('[data-repeater] .cms-rows > [data-row]');
const field = (i, k) => rows().nth(i).locator(`[name$="[${k}]"]`).first();
const tbody = (h) => h.slice(h.indexOf('<tbody'));
const SH = ['shared.roster', 'shared.partners', 'shared.dialogue'];

for (const k of SH) await revertPage(page, k);

let h;
try {
  // ───────── roster: name + term of a PAST ED (8th = row 7) -> leadership past table
  await openEditor(page, 'shared.roster');
  await field(7, 'name').fill('QA Renamed Vinluan');
  await field(7, 'term_start').fill('2031');
  await field(7, 'term_end').fill('2032');
  await save(page);
  h = await get(LEAD);
  check(R, 'roster: edited name shown in leadership past table', tbody(h).includes('Pol. Colonel QA Renamed Vinluan'));
  check(R, 'roster: edited term shown', tbody(h).includes('2031–2032'));
  check(R, 'roster: old name gone', !h.includes('David Martinez Vinluan'));

  // ───────── roster: edit the CURRENT DPS (row 15): name -> current card AND directors card on /leadership
  await openEditor(page, 'shared.roster');
  await field(15, 'name').fill('QA Current Dps');
  await field(15, 'bio').fill('QA dps bio text.');
  await save(page);
  h = await get(DPS);
  check(R, 'roster: DPS page current card reads name+bio', h.includes('Police Senior Superintendent QA Current Dps') && h.includes('QA dps bio text.'));
  check(R, 'roster: DPS past table first row is newest', tbody(h).indexOf('QA Current Dps') > 0 && tbody(h).indexOf('QA Current Dps') < tbody(h).indexOf('Joni Getamala'));
  h = await get(LEAD);
  check(R, 'roster: leadership directors card reads DPS name', h.includes('Police Senior Superintendent QA Current Dps'));

  // ───────── roster: hide a row (ED 5th = row 4)
  await openEditor(page, 'shared.roster');
  await rows().nth(4).locator('input[name$="[_hide]"]').first().check();
  await save(page);
  h = await get(LEAD);
  check(R, 'roster: hidden row gone from leadership', !h.includes('Kenechanh'));
  await revertPage(page, 'shared.roster');
  h = await get(LEAD);
  check(R, 'roster: revert restores names', h.includes('David Martinez Vinluan') && h.includes('Kenechanh') && !h.includes('QA Renamed'));

  // ───────── partners: LME website + name
  await openEditor(page, 'shared.partners');
  const apmla = 5; // interpol, europol, accbp, iafi, interpa, apmla
  await field(apmla, 'website').fill('https://example.org/qa-apmla');
  await field(apmla, 'full_name').fill('QA APMLA Full Name');
  await save(page);
  h = await get(LME);
  check(R, 'partners: LME website + full name read from shared', h.includes('https://example.org/qa-apmla') && h.includes('QA APMLA Full Name'));
  await revertPage(page, 'shared.partners');
  check(R, 'partners: revert', (await get(LME)).includes('The Asia Pacific Medico Legal Agencies'));

  // ───────── dialogue: website of AFP (row 1) -> dialogue page; q -> news search link
  await openEditor(page, 'shared.dialogue');
  await field(1, 'website').fill('https://example.org/qa-afp');
  await field(1, 'q').fill('QAFPKEY');
  await save(page);
  h = await get(DLG);
  check(R, 'dialogue: website edit shown', h.includes('https://example.org/qa-afp'));
  check(R, 'dialogue: q keyword drives See Activities link', h.includes('q=QAFPKEY'));

  // ───────── add a new observer in Shared -> appears on observers page only
  await openEditor(page, 'shared.dialogue');
  await page.locator('[data-repeater] [data-act=add-row]').first().click();
  const n = (await rows().count()) - 1;
  await field(n, 'key').fill('qa-observer');
  await field(n, 'name').fill('QA New Observer');
  await field(n, 'observer_since').fill('2040');
  await field(n, 'observer_conference').fill('99th AC, Nowhere');
  await field(n, 'badge').fill('🇶🇦');
  await rows().nth(n).locator('input[type=checkbox][name$="[is_observer]"]').check();
  await save(page);
  h = await get(OBS);
  check(R, 'observer: new row shown on observers page', ['QA New Observer', 'Since 2040', '99th AC, Nowhere', '🇶🇦'].every(t => h.includes(t)));
  check(R, 'observer: not listed as dialogue partner', !(await get(DLG)).includes('QA New Observer'));

  // ───────── RCMP is one entity on both pages with its own "since" fields
  h = await get(DLG);
  const o = await get(OBS);
  check(R, 'rcmp: on dialogue page', h.includes('Royal Canadian Mounted Police (RCMP)'));
  check(R, 'rcmp: on observers page', o.includes('Royal Canadian Mounted Police (RCMP)') && o.includes('39th AC, Viet Nam'));
  await openEditor(page, 'shared.dialogue');
  const rc = 9;
  await field(rc, 'website').fill('https://example.org/qa-rcmp');
  await save(page);
  check(R, 'rcmp: one edit updates BOTH pages', (await get(DLG)).includes('https://example.org/qa-rcmp') && (await get(OBS)).includes('https://example.org/qa-rcmp'));
} finally {
  for (const k of SH) await revertPage(page, k);
}
h = await get(OBS);
check(R, 'reverted: observers page back to default', !h.includes('QA New Observer') && h.includes('Argentine Federal Police'));
finish(R, errors);
await browser.close();
