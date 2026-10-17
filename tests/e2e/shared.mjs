// Targeted tests for the "shared" CMS group (foundation).  node tests/e2e/shared.mjs
// No public view reads shared.* yet, so the effect is asserted through App\Support\Shared (php artisan tinker, same DB).
import { execFileSync } from 'node:child_process';
import { launch, login, check, finish, openEditor, save, revertPage } from './lib.mjs';

const PAGES = ['organisation', 'secretariat', 'members', 'roster', 'partners', 'dialogue'].map(s => 'shared.' + s);
const php = (expr) => JSON.parse(execFileSync('php', ['artisan', 'tinker', '--execute', `echo json_encode(${expr});`], { encoding: 'utf8' }).trim().split('\n').pop());
const M = (i, f) => `input[name="f[list][rows][${i}][${f}]"]`;

const { browser, page, errors } = await launch();
const R = [];
await login(page);
for (const k of PAGES) await revertPage(page, k);

// ---------- every shared page opens, no truncation guard trip, well under max_input_vars
for (const k of PAGES) {
  await openEditor(page, k);
  const n = await page.evaluate(() => document.querySelectorAll('#cms-form input[name], #cms-form select[name], #cms-form textarea[name]').length);
  check(R, `${k}: editor opens, ${n} inputs (< 400)`, n < 400, n);
  await save(page);
  await openEditor(page, k);
  check(R, `${k}: untouched save stores no overrides`, (await page.locator('text=/\\d+ customised/').count()) === 0);
}

// ---------- defaults visible through Shared::
check(R, 'default: 11 members in order', JSON.stringify(php('array_column(App\\Support\\Shared::members(),"key")'))
  === JSON.stringify(['brunei', 'cambodia', 'indonesia', 'lao', 'malaysia', 'myanmar', 'philippines', 'singapore', 'thailand', 'vietnam', 'timor-leste']));
check(R, 'default: Shared::memberName(indonesia)', php('App\\Support\\Shared::memberName("indonesia")') === 'Indonesian National Police (INP)');

// ---------- edit a member row's e-mail (row 2 = indonesia) + the secretariat phone
await openEditor(page, 'shared.members');
await page.fill(M(2, 'ncb_email'), 'qa-inp@example.test');
await save(page);
check(R, 'Shared::member(indonesia) sees edited e-mail', php('App\\Support\\Shared::member("indonesia")["ncb_email"]') === 'qa-inp@example.test');
check(R, 'other members untouched', php('App\\Support\\Shared::member("malaysia")["ncb_email"]') === 'rmp@aseanapol.org');
check(R, 'Shared::rowIsDefault reports the edited field', php('[App\\Support\\Shared::rowIsDefault("members","indonesia",["ncb_email"]), App\\Support\\Shared::rowIsDefault("members","indonesia",["tel"])]').join() === 'false,true' || JSON.stringify(php('[App\\Support\\Shared::rowIsDefault("members","indonesia",["ncb_email"]), App\\Support\\Shared::rowIsDefault("members","indonesia",["tel"])]')) === '[false,true]');

await openEditor(page, 'shared.secretariat');
await page.fill('input[name="f[contact][phone]"]', '+60 QA 99999');
await save(page);
check(R, 'Shared::secretariat(contact.phone) sees edit', php('App\\Support\\Shared::secretariat("contact.phone")') === '+60 QA 99999');
check(R, 'shared() helper sees edit', php('shared("secretariat","contact.phone")') === '+60 QA 99999');
check(R, 'Shared::isDefault false after edit', php('App\\Support\\Shared::isDefault("secretariat","contact.phone")') === false);
check(R, 'mailboxes stay distinct', php('App\\Support\\Shared::secretariat("contact.email_secretariat") !== App\\Support\\Shared::secretariat("contact.email_general")') === true);

// ---------- hide a member row -> excluded everywhere (members(), member(), memberName())
await openEditor(page, 'shared.members');
await page.locator(M(0, 'key')).locator('xpath=ancestor::*[@data-row][1]').locator('input[name$="[_hide]"]').first().check();
await save(page);
check(R, 'hidden member removed from Shared::members()', php('count(App\\Support\\Shared::members())') === 10
  && !php('array_column(App\\Support\\Shared::members(),"key")').includes('brunei'));
check(R, 'hidden member: member()/memberName() null', php('[App\\Support\\Shared::member("brunei"), App\\Support\\Shared::memberName("brunei")]').join('') === '');
check(R, 'roster still shows a hidden member\'s force for old office holders', php('App\\Support\\Shared::roster("dpp")[4]["force"]') === 'Royal Brunei Police Force (RBPF)');

// ---------- roster: change current holder flag text, term, via editor
await openEditor(page, 'shared.roster');
const idx = php('array_search("dpp|7th", array_map(fn($r)=>$r["post"]."|".$r["ordinal"], App\\Support\\Shared::roster()))');
await page.fill(`input[name="f[people][rows][${idx}][term_start]"]`, '2025');
await save(page);
check(R, 'roster edit visible (dpp current term)', php('App\\Support\\Shared::currentHolder("dpp")["term"]') === '2025–present');

// ---------- partners / dialogue edit
await openEditor(page, 'shared.partners');
const ai = php('array_search("apta", array_column(App\\Support\\Shared::partners(), "key"))');
await page.fill(`input[name="f[list][rows][${ai}][website]"]`, 'https://example.test/apta');
await save(page);
check(R, 'Shared::partner(apta) sees edited website', php('App\\Support\\Shared::partner("apta")["website"]') === 'https://example.test/apta');

// ---------- admin tree lists the group (order 2 = right after Site-wide & Home)
await page.goto((await page.url()).split('/admin')[0] + '/admin/site-content');
const heads = await page.locator('section.cms-group header').allInnerTexts();
check(R, 'page tree: Shared group listed second', /Shared facts/.test(heads[1] || ''), JSON.stringify(heads.slice(0, 3)));

// ---------- revert
for (const k of PAGES) await revertPage(page, k);
check(R, 'revert: 11 members back', php('count(App\\Support\\Shared::members())') === 11);
check(R, 'revert: phone default', php('App\\Support\\Shared::secretariat("contact.phone")') === '+603-22602222');
check(R, 'revert: apta default site', php('App\\Support\\Shared::partner("apta")["website"]') === 'https://aptahome.org/');

await browser.close();
finish(R, errors);
