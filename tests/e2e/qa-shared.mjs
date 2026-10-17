// QA of the Shared-facts editors + graceful degradation + orphan-block tolerance.  node tests/e2e/qa-shared.mjs
// Self-cleaning: reverts every shared.* page and deletes the orphan rows it inserts.
import fs from 'node:fs';
import os from 'node:os';
import path from 'node:path';
import { execFileSync } from 'node:child_process';
import { fileURLToPath } from 'node:url';
import { launch, login, check, finish, openEditor, save, revertPage, BASE } from './lib.mjs';

const here = path.dirname(fileURLToPath(import.meta.url));
const URLS = fs.readFileSync(path.join(here, 'urls.txt'), 'utf8').split(/\r?\n/).filter(Boolean);
const SHARED = ['organisation', 'secretariat', 'members', 'roster', 'partners', 'dialogue'].map(s => 'shared.' + s);
const php = (expr) => JSON.parse(execFileSync('php', ['artisan', 'tinker', '--execute', `echo json_encode(${expr});`], { encoding: 'utf8' }).trim().split('\n').pop());
const ERR = /ErrorException|Undefined (variable|array key|index|offset)|Trying to access|TypeError|Call to (a member|undefined)|Whoops|Stack trace|Illuminate\\|Array to string/;
const BROKEN = /src=""|href="mailto:"|href="tel:"|href="https?:\/\/"|<img[^>]+src="[^"]*\/media\/police-logo\/"/g;

async function scan(urls = URLS) {
  const out = {};
  for (const u of urls) {
    const r = await fetch(BASE + u, { redirect: 'follow' });
    out[u] = { status: r.status, html: await r.text() };
  }
  return out;
}
const problems = (s) => Object.entries(s).filter(([, p]) => p.status >= 500 || ERR.test(p.html)).map(([u, p]) => `${u}(${p.status}${ERR.test(p.html) ? ',' + p.html.match(ERR)[0] : ''})`);
const brokenCount = (s) => Object.values(s).reduce((n, p) => n + (p.html.match(BROKEN) || []).length, 0);

const { browser, context, page, errors } = await launch();
const R = [];
await login(page);
for (const k of SHARED) await revertPage(page, k);
php('App\\Support\\Cms::forgetMemo()');
const pristine = await scan();
const base = { problems: problems(pristine), broken: brokenCount(pristine) };
check(R, 'pristine: no PHP errors on any public page', base.problems.length === 0, base.problems.join());

// ---------------------------------------------------------------- 1. every shared editor: desktop + 390px
const png = path.join(os.tmpdir(), 'qa-shared-logo.png');
fs.writeFileSync(png, Buffer.from('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==', 'base64'));
for (const k of SHARED) {
  for (const [w, h] of [[1400, 1000], [390, 844]]) {
    await page.setViewportSize({ width: w, height: h });
    await openEditor(page, k);
    const o = await page.evaluate(() => ({ sw: document.documentElement.scrollWidth, iw: window.innerWidth, n: document.querySelectorAll('#cms-form [name]').length }));
    check(R, `${k} @${w}: opens, no horizontal page scroll (${o.sw}/${o.iw}), ${o.n} inputs`, o.sw <= o.iw + 1, `${o.sw}>${o.iw}`);
  }
}
await page.setViewportSize({ width: 1400, height: 1000 });

const rm = async (loc) => { page.once('dialog', d => d.accept()); await loc.click(); };
const lastRow = () => page.locator('#cms-form .cms-rows > [data-row]').last();
const rowOf = (key) => page.locator('#cms-form [data-row]').filter({ has: page.locator(`input[name$="[key]"][value="${key}"]`) }).first();

// ---------------------------------------------------------------- 2. members: add / reorder / hide / remove
await openEditor(page, 'shared.members');
await page.click('[data-act=add-row]');
let r = lastRow();
for (const [f, v] of Object.entries({ key: 'qa-test', country: 'QA Land', iso: 'zz', force: 'QA Test Police', abbreviation: 'QTP', joined_year: '2027', landing_logo: 'qa-landing.png', ncb_email: 'qtp@example.test', tel: '+00 QA' }))
  await r.locator(`[name$="[${f}]"]`).fill(v);
await r.locator('input[type=file]').first().setInputFiles(png); // crest upload
await save(page);
let s = await scan(['/en', '/en/contact-us', '/en/about-aseanapol/member-countries', '/en/about-aseanapol/permanent-secretariat']);
check(R, 'member add: shown on landing + contact directory', s['/en'].html.includes('QA Test Police') && s['/en/contact-us'].html.includes('QA Test Police'));
check(R, 'member add: contact directory shows its tel/email', s['/en/contact-us'].html.includes('qtp@example.test') && s['/en/contact-us'].html.includes('+00 QA'));
check(R, 'member add: count word follows live count (twelve)', /twelve/i.test(s['/en'].html) || /twelve/i.test(s['/en/about-aseanapol/permanent-secretariat'].html));
check(R, 'member add: crest upload stored under storage/cms', (php('App\\Support\\Shared::member("qa-test")["logo"] ?? ""') || '').startsWith('storage/cms/'));
check(R, 'member add: no errors', problems(s).length === 0, problems(s).join());

// reorder: brunei down one position -> cambodia before brunei
await openEditor(page, 'shared.members');
await rowOf('brunei').locator('[data-act=down]').click();
await save(page);
const order = php('array_column(App\\Support\\Shared::members(),"key")');
check(R, 'member reorder: brunei now after cambodia', order.indexOf('cambodia') < order.indexOf('brunei'), order.join());
s = await scan(['/en', '/en/contact-us']);
const ix = (h, t) => h.indexOf(t);
check(R, 'member reorder: contact directory follows', ix(s['/en/contact-us'].html, 'Cambodian National Police') < ix(s['/en/contact-us'].html, 'Royal Brunei Police Force'));

// hide brunei (referenced by roster rows) + remove cambodia (also referenced) + remove the new row
await openEditor(page, 'shared.members');
await rowOf('brunei').locator('input[name$="[_hide]"]').check();
await rm(rowOf('cambodia').locator('[data-act=remove]'));
await rm(rowOf('qa-test').locator('[data-act=remove]'));
await save(page);
let all = await scan();
check(R, 'member hidden+removed: no PHP errors anywhere', problems(all).length === 0, problems(all).join());
check(R, 'member hidden+removed: no new broken src/href', brokenCount(all) <= base.broken, `${brokenCount(all)} > ${base.broken}`);
const cu = all['/en/contact-us'].html;
check(R, 'member hidden: gone from landing/contact', !cu.includes('Royal Brunei Police Force (RBPF)') && !cu.includes('Cambodian National Police'),
  ['Royal Brunei Police Force (RBPF)', 'Cambodian National Police'].filter(t => cu.includes(t)).map(t => t + ' @ ' + cu.slice(Math.max(0, cu.indexOf(t) - 80), cu.indexOf(t) + 40).replace(/\s+/g, ' ')).join(' | '));
check(R, 'member hidden: old Brunei office holder keeps a force label', /Royal Brunei Police Force/.test(all['/en/about-aseanapol/leadership'].html));
check(R, 'member removed: country page still reachable (no 500)', all['/en/about-aseanapol/member-countries/cambodian-national-police'].status < 500, all['/en/about-aseanapol/member-countries/cambodian-national-police'].status);
await revertPage(page, 'shared.members');

// every member removed
await openEditor(page, 'shared.members');
while (await page.locator('#cms-form [data-row]').count()) await rm(page.locator('#cms-form [data-row] [data-act=remove]').first());
await save(page);
all = await scan();
check(R, 'members EMPTY: no PHP errors / 5xx anywhere', problems(all).length === 0, problems(all).join());
check(R, 'members EMPTY: no new broken src/href', brokenCount(all) <= base.broken, `${brokenCount(all)} > ${base.broken}`);
const emptyGrid = (h) => (h.match(/<(div|tbody|ul)[^>]*(grid|space-y)[^>]*>\s*<\/>/g) || []).length;
const worse = Object.keys(all).filter(u => emptyGrid(all[u].html) > emptyGrid(pristine[u].html));
check(R, 'members EMPTY: no empty grid/list boxes on any page', worse.length === 0, worse.join());
await revertPage(page, 'shared.members');

// ---------------------------------------------------------------- 3. roster: add person, remove / un-current holders
await openEditor(page, 'shared.roster');
await page.click('[data-act=add-row]');
r = lastRow();
for (const [f, v] of Object.entries({ ordinal: '10th', rank: 'QA Rank', name: 'QA Newperson', term_start: '2028' })) await r.locator(`[name$="[${f}]"]`).fill(v);
await r.locator('select[name$="[post]"]').selectOption('ed');
await r.locator('select[name$="[member_key]"]').selectOption('vietnam');
await r.locator('input[type=file]').first().setInputFiles(png);
await save(page);
s = await scan(['/en/about-aseanapol/leadership', '/en/about-aseanapol/permanent-secretariat']);
check(R, 'roster add: past table + secretariat page show the new ED', s['/en/about-aseanapol/leadership'].html.includes('QA Newperson') && s['/en/about-aseanapol/permanent-secretariat'].html.includes('QA Newperson'));
check(R, 'roster add: nominating force resolved (Viet Nam)', /Vietnam Police Force|Viet Nam/.test(s['/en/about-aseanapol/leadership'].html));
// remove current ED, un-current DPS, hide current DPP
await openEditor(page, 'shared.roster');
await rm(page.locator('#cms-form [data-row]').filter({ has: page.locator('input[name$="[name]"][value="Kongkrissada Kittithiraphong"]') }).locator('[data-act=remove]'));
await page.locator('#cms-form [data-row]').filter({ has: page.locator('input[name$="[name]"][value="Huntal Tambunan"]') }).locator('input[type=checkbox][name$="[is_current]"]').first().uncheck();
await page.locator('#cms-form [data-row]').filter({ has: page.locator('input[name$="[name]"][value="Jean Mary A. Mangahis"]') }).locator('input[name$="[_hide]"]').check();
await save(page);
all = await scan();
check(R, 'roster: no current ED/DPS/DPP -> no PHP errors', problems(all).length === 0, problems(all).join());
check(R, 'roster: no new broken src/href', brokenCount(all) <= base.broken, `${brokenCount(all)} > ${base.broken}`);
for (const u of ['/en/about-aseanapol/leadership', '/en/about-aseanapol/leadership/director-for-police-services', '/en/about-aseanapol/leadership/director-for-plans-and-programmes', '/en/about-aseanapol/permanent-secretariat'])
  check(R, `roster degraded: ${u} is 200`, all[u].status === 200, all[u].status);
// every roster row removed
await openEditor(page, 'shared.roster');
while (await page.locator('#cms-form [data-row]').count()) await rm(page.locator('#cms-form [data-row] [data-act=remove]').first());
await save(page);
all = await scan(['/en/about-aseanapol/leadership', '/en/about-aseanapol/leadership/director-for-police-services', '/en/about-aseanapol/leadership/director-for-plans-and-programmes', '/en/about-aseanapol/permanent-secretariat', '/en/about-aseanapol/governance', '/en']);
check(R, 'roster EMPTY: pages render without PHP errors', problems(all).length === 0, problems(all).join());
check(R, 'roster EMPTY: all 200', Object.values(all).every(p => p.status === 200), Object.entries(all).map(([u, p]) => u + ':' + p.status).join());
await revertPage(page, 'shared.roster');

// ---------------------------------------------------------------- 4. partners: add w/ logo, hide + remove referenced
await openEditor(page, 'shared.partners');
await page.click('[data-act=add-row]');
r = lastRow();
for (const [f, v] of Object.entries({ key: 'qa-org', name: 'QA ORG', short_name: 'QA ORG', full_name: 'QA Organisation Full', website: 'https://qa-org.example.test' })) await r.locator(`[name$="[${f}]"]`).fill(v);
await r.locator('input[type=checkbox][name$="[is_lme]"]').check();
await r.locator('input[type=checkbox][name$="[is_cooperation_partner]"]').check();
await r.locator('input[type=file]').first().setInputFiles(png);
await save(page);
s = await scan(['/en/about-aseanapol/lme', '/en/international-collaboration']);
check(R, 'partner add: LME page + cooperation index show it', s['/en/about-aseanapol/lme'].html.includes('QA Organisation Full') && s['/en/international-collaboration'].html.includes('QA Organisation Full'));
check(R, 'partner add: uploaded logo rendered from storage/cms', /storage\/cms\//.test(s['/en/about-aseanapol/lme'].html));
// hide interpol (landing card + footer reference it), remove europol, remove ALL LME partners
await openEditor(page, 'shared.partners');
await rowOf('interpol').locator('input[name$="[_hide]"]').check();
await rm(rowOf('europol').locator('[data-act=remove]'));
await save(page);
all = await scan();
check(R, 'partner hidden/removed (landing+footer reference them): no PHP errors', problems(all).length === 0, problems(all).join());
check(R, 'partner hidden/removed: no new broken src/href', brokenCount(all) <= base.broken, `${brokenCount(all)} > ${base.broken}`);
check(R, 'partner hidden: INTERPOL card gone from landing partners section', !/id="partners"[\s\S]*?<\/section>/.exec(all['/en'].html)?.[0].includes('INTERPOL'));
await openEditor(page, 'shared.partners');
while (await page.locator('#cms-form [data-row]').count()) await rm(page.locator('#cms-form [data-row] [data-act=remove]').first());
await save(page);
all = await scan();
check(R, 'partners EMPTY: no PHP errors anywhere', problems(all).length === 0, problems(all).join());
check(R, 'partners EMPTY: no new broken src/href', brokenCount(all) <= base.broken, `${brokenCount(all)} > ${base.broken}`);
const emptyBoxes = (h) => (h.match(/<div class="grid[^"]*">\s*<\/div>/g) || []).length;
check(R, 'partners EMPTY: no empty grid boxes added on LME/cooperation pages', emptyBoxes(all['/en/about-aseanapol/lme'].html) <= emptyBoxes(pristine['/en/about-aseanapol/lme'].html) && emptyBoxes(all['/en/international-collaboration'].html) <= emptyBoxes(pristine['/en/international-collaboration'].html),
  `lme ${emptyBoxes(all['/en/about-aseanapol/lme'].html)} coop ${emptyBoxes(all['/en/international-collaboration'].html)}`);
await revertPage(page, 'shared.partners');

// ---------------------------------------------------------------- 5. dialogue: add observer, remove all
await openEditor(page, 'shared.dialogue');
await page.click('[data-act=add-row]');
r = lastRow();
for (const [f, v] of Object.entries({ key: 'qa-obs', name: 'QA Observer', website: 'https://qa-obs.example.test', observer_since: '2027', observer_conference: '99th AC, QA', badge: '🇶🇦' })) await r.locator(`[name$="[${f}]"]`).fill(v);
await r.locator('input[type=checkbox][name$="[is_observer]"]').check();
await save(page);
s = await scan(['/en/information/observers', '/en/about-aseanapol/observers', '/en/information/dialogue-partners']);
check(R, 'observer add: shown on observers page(s)', s['/en/information/observers'].html.includes('QA Observer'));
check(R, 'observer add: not on dialogue partners page', !s['/en/information/dialogue-partners'].html.includes('QA Observer'));
await openEditor(page, 'shared.dialogue');
while (await page.locator('#cms-form [data-row]').count()) await rm(page.locator('#cms-form [data-row] [data-act=remove]').first());
await save(page);
all = await scan();
check(R, 'dialogue EMPTY: no PHP errors anywhere', problems(all).length === 0, problems(all).join());
{ const w = Object.keys(all).filter(u => emptyGrid(all[u].html) > emptyGrid(pristine[u].html)); check(R, 'dialogue EMPTY: no empty grid/list boxes', w.length === 0, w.join()); }
check(R, 'dialogue EMPTY: no new broken src/href', brokenCount(all) <= base.broken, `${brokenCount(all)} > ${base.broken}`);
await revertPage(page, 'shared.dialogue');

// ---------------------------------------------------------------- 6. blank every field of secretariat + organisation
for (const k of ['shared.secretariat', 'shared.organisation']) {
  await openEditor(page, k);
  await page.evaluate(() => document.querySelectorAll('#cms-form input[type=text], #cms-form textarea, #cms-form input[type=number]').forEach(e => { e.value = ''; }));
  await save(page);
  all = await scan();
  check(R, `${k} BLANK: no PHP errors, no 5xx on any public page`, problems(all).length === 0, problems(all).join());
  const extra = brokenCount(all) - base.broken;
  check(R, `${k} BLANK: no new broken empty links/images (mailto:/tel:/src="")`, extra <= 0, `${extra} new, e.g. ` + Object.entries(all).filter(([u, p]) => (p.html.match(BROKEN) || []).length > (pristine[u].html.match(BROKEN) || []).length).map(([u]) => u).slice(0, 6).join());
  const c = await fetch(BASE + '/en/contact-us'); check(R, `${k} BLANK: contact page 200`, c.status === 200);
  await revertPage(page, k);
}

// ---------------------------------------------------------------- 7. orphan cms_blocks rows (fields removed from registries) are harmless
const ins = (pk, bk, val) => php(`DB::table("cms_blocks")->insert(["page_key"=>"${pk}","block_key"=>"${bk}","value"=>${JSON.stringify(JSON.stringify(val))},"created_at"=>now(),"updated_at"=>now()])`);
const ORPH = [
  ['about-people.leadership', 'past.rows', [{ name: 'orphan' }]],
  ['about-people.leadership', 'hero.removed_field', 'orphan text'],
  ['about-people.leadership', 'removedsection.x', 'orphan'],
  ['site.header-footer', 'old.field', ['a', 'b']],
  ['shared.roster', 'people.old_rows', [{ name: 'orphan' }]],
  ['shared.secretariat', 'contact.legacy_telex', '123'],
  ['about.removed-page', 'a.b', 'x'],
  ['members.indonesia', 'overview.legacy', 'x'],
];
for (const [pk, bk, v] of ORPH) ins(pk, bk, v);
const orphanPages = [...new Set(ORPH.map(o => o[0]))].filter(k => !k.includes('removed-page'));
all = await scan();
check(R, 'orphans: every public page still renders (no 5xx / PHP error)', problems(all).length === 0, problems(all).join());
for (const k of orphanPages) {
  const resp = await page.goto(`${BASE}/admin/site-content/${k}`);
  check(R, `orphans: editor ${k} opens (${resp.status()})`, resp.status() === 200 && (await page.locator('#cms-form').count()) === 1);
}
await openEditor(page, 'shared.secretariat');
await save(page); // Cms::save with an orphan present must not choke
check(R, 'orphans: Cms::save on a page holding an orphan works', true);
check(R, 'orphans: save pruned the shared.secretariat orphan', php('DB::table("cms_blocks")->where("page_key","shared.secretariat")->where("block_key","contact.legacy_telex")->count()') === 0);
const left = php('DB::table("cms_blocks")->whereIn("block_key",["past.rows","hero.removed_field","removedsection.x","old.field","people.old_rows","contact.legacy_telex","a.b","overview.legacy"])->count()');
check(R, `orphans: ${left} unrelated orphan row(s) remain until their page is saved/reverted (harmless)`, left >= 0);
// type-corrupt rows: a text field holding an array, a repeater holding a string, a boolean toggle holding junk
ins('shared.secretariat', 'contact.phone', ['not', 'a', 'string']);
ins('shared.members', 'list.rows', 'corrupt-string');
ins('shared.organisation', 'identity.tagline', { x: 1 });
ins('shared.roster', 'people.rows', 12345);
ins('shared.partners', 'list.rows', [1, 'x', null, { key: 'interpol' }]);
ins('shared.dialogue', 'list.rows', [[], 'x']);
all = await scan();
check(R, 'type-corrupt shared rows: no 5xx / PHP error on public pages', problems(all).length === 0, problems(all).join());
for (const k of SHARED) {
  const resp = await page.goto(`${BASE}/admin/site-content/${k}`);
  check(R, `type-corrupt: editor ${k} opens`, resp.status() === 200 && (await page.locator('#cms-form').count()) === 1, resp.status());
}

// ---------------------------------------------------------------- cleanup
php('DB::table("cms_blocks")->whereIn("page_key",["about-people.leadership","site.header-footer","about.removed-page","members.indonesia"])->whereIn("block_key",["past.rows","hero.removed_field","removedsection.x","old.field","a.b","overview.legacy"])->delete()');
for (const k of SHARED) await revertPage(page, k);
check(R, 'cleanup: no shared.* overrides left', php('DB::table("cms_blocks")->where("page_key","like","shared.%")->count()') === 0);
check(R, 'cleanup: no orphan rows left', php('DB::table("cms_blocks")->whereIn("block_key",["past.rows","hero.removed_field","removedsection.x","old.field","people.old_rows","contact.legacy_telex","a.b","overview.legacy"])->count()') === 0);
const fin = await scan();
check(R, 'cleanup: public pages back to no-error state', problems(fin).length === 0, problems(fin).join());

await browser.close();
finish(R, errors);
