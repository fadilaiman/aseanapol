// Events & Training dedicated module: CRUD, validation, filters, pagination. Run: CMS_BASE=... node tests/e2e/events.mjs
import { launch, login, check, finish, BASE } from './lib.mjs';

const SHOT = process.env.SHOT_DIR || '';
const R = [];
const { browser, page, errors } = await launch();
await login(page);

const stamp = Date.now().toString().slice(-6);
const T1 = `QA Calendar Event ${stamp}`;
const T1b = `QA Calendar Event EDITED ${stamp}`;
const TC = `QA Conference ${stamp}`;
const TT = `QA Training Programme ${stamp}`;
const pub = async (path) => await (await page.request.get(BASE + '/en/events-training' + path)).text();
const shot = async (name) => { if (SHOT) await page.screenshot({ path: `${SHOT}/${name}.png`, fullPage: true }); };

// ---- list page baseline
await page.goto(BASE + '/admin/events');
const total0 = parseInt((await page.textContent('[data-testid=events-total]')).trim(), 10);
check(R, 'list shows seeded rows (>=115)', total0 >= 115, total0);
check(R, 'list paginated (20 rows)', (await page.locator('[data-testid=events-table] tbody tr').count()) === 20);
await shot('admin-events-list');

// ---- pagination
check(R, 'pagination links present', (await page.locator('[data-testid=pagination] a[href*="page=2"]').count()) > 0);
await page.goto(BASE + '/admin/events?page=2');
check(R, 'page 2 renders rows', (await page.locator('[data-testid=events-table] tbody tr[data-row-title]').count()) === 20);
await page.goto(BASE + '/admin/events?page=6');
check(R, 'last page renders remaining rows', (await page.locator('[data-testid=events-table] tbody tr[data-row-title]').count()) >= 1);

// ---- validation: missing title / end before start
await page.goto(BASE + '/admin/events/create');
await shot('admin-events-create');
await page.fill('input[name=start_date]', '2031-05-06');
await page.fill('input[name=end_date]', '2031-05-04');
await page.click('[data-testid=save-event]');
let errText = await page.textContent('[data-testid=form-errors]');
check(R, 'missing title rejected', /title field is required/i.test(errText), errText);
check(R, 'end before start rejected', /end date cannot be before the start date/i.test(errText), errText);
check(R, 'entered values kept after error', (await page.inputValue('input[name=start_date]')) === '2031-05-06');
await shot('admin-events-create-errors');

// missing start date for calendar
await page.fill('input[name=title]', 'x');
await page.fill('input[name=start_date]', '');
await page.fill('input[name=end_date]', '');
await page.click('[data-testid=save-event]');
errText = await page.textContent('[data-testid=form-errors]');
check(R, 'calendar start date required', /start date field is required/i.test(errText), errText);

// bad link rejected
await page.fill('input[name=start_date]', '2031-05-04');
await page.fill('input[name=link]', 'not a url');
await page.click('[data-testid=save-event]');
errText = await page.textContent('[data-testid=form-errors]');
check(R, 'invalid link rejected', /link/i.test(errText), errText);

// ---- create calendar event
await page.goto(BASE + '/admin/events/create');
await page.fill('input[name=title]', T1);
await page.fill('input[name=start_date]', '2031-05-04');
await page.fill('input[name=end_date]', '2031-05-06');
await page.selectOption('select[name=type]', 'Training');
await page.fill('input[name=city]', 'Qatown');
await page.fill('input[name=country]', 'Qaland');
await page.fill('input[name=link]', 'https://example.org/qa');
await page.fill('input[name=link_label]', 'QA Details');
await page.click('[data-testid=save-event]');
await page.waitForURL(/\/admin\/events$/);
check(R, 'create flash shown', (await page.content()).includes('Event added successfully'));

let html = await pub('/calendar');
const i2031 = html.indexOf('>2031<');
const i2026 = html.indexOf('>2026<');
const iEv = html.indexOf(T1);
check(R, 'created event on calendar', iEv > 0);
check(R, '2031 group sits above 2026', i2031 > 0 && i2031 < i2026 && i2031 < iEv && iEv < i2026);
check(R, 'location + range + link rendered', html.includes('Qatown, Qaland') && html.includes('to 06 May') && html.includes('QA Details'));
check(R, 'event NOT on conferences page', !(await pub('/conferences')).includes(T1));

// ---- filters / search
await page.goto(BASE + '/admin/events?q=' + stamp);
check(R, 'search finds new event only', (await page.locator('tr[data-row-title]').count()) === 1);
await page.goto(BASE + '/admin/events?q=Qatown');
check(R, 'search by city', (await page.locator('tr[data-row-title]').count()) === 1);
await page.goto(BASE + '/admin/events?kind=conference');
check(R, 'kind filter conference (46 seeded)', (await page.textContent('[data-testid=events-total]')).includes('46') || parseInt(await page.textContent('[data-testid=events-total]'), 10) >= 46);
await page.goto(BASE + '/admin/events?year=2031');
check(R, 'year filter', (await page.locator('tr[data-row-title]').count()) === 1);
await page.goto(BASE + '/admin/events?type=Training&q=' + stamp);
check(R, 'type filter matches', (await page.locator('tr[data-row-title]').count()) === 1);
await page.goto(BASE + '/admin/events?type=Meeting&q=' + stamp);
check(R, 'type filter excludes', (await page.locator('tr[data-row-title]').count()) === 0);
await page.goto(BASE + '/admin/events?q=' + stamp);
await shot('admin-events-filtered');
// UI-driven filter form
await page.goto(BASE + '/admin/events');
await page.fill('[data-testid=filters] input[name=q]', stamp);
await page.selectOption('[data-testid=filters] select[name=kind]', 'calendar');
await Promise.all([page.waitForURL(/q=/), page.click('[data-testid=filters] button[type=submit]')]);
check(R, 'filter form works', (await page.locator('tr[data-row-title]').count()) === 1);
await page.goto(BASE + '/admin/events?q=zzzz-nothing-here');
check(R, 'empty filter state', (await page.content()).includes('Nothing matches these filters'));

// ---- edit
await page.goto(BASE + '/admin/events?q=' + stamp);
await page.click('tr[data-row-title] >> text=Edit');
await page.fill('input[name=title]', T1b);
await page.fill('input[name=end_date]', '2031-05-04');
await page.click('[data-testid=save-event]');
await page.waitForURL(/\/admin\/events/);
html = await pub('/calendar');
check(R, 'edit: new title shown, old gone', html.includes(T1b) && !html.includes(T1 + '<'));
check(R, 'edit: single-day no longer shows range', !html.includes('to 06 May'));

// ---- hide
await page.goto(BASE + '/admin/events?q=' + stamp);
await page.click('tr[data-row-title] [data-testid^=toggle-]');
check(R, 'hide: flash', (await page.content()).includes('hidden from the site'));
check(R, 'hide: gone from calendar', !(await pub('/calendar')).includes(T1b));
check(R, 'hide: listed as Hidden', (await page.locator('tr[data-row-title]').first().textContent()).includes('Hidden'));
await page.goto(BASE + '/admin/events?status=hidden');
check(R, 'visibility filter hidden', (await page.locator('tr[data-row-title]', { hasText: stamp }).count()) === 1);
await page.goto(BASE + '/admin/events?q=' + stamp);
await page.click('tr[data-row-title] [data-testid^=toggle-]');
check(R, 'unhide: back on calendar', (await pub('/calendar')).includes(T1b));

// ---- delete
await page.goto(BASE + '/admin/events?q=' + stamp);
page.once('dialog', d => d.accept());
await page.click('tr[data-row-title] >> text=Delete');
await page.waitForURL(/\/admin\/events/);
check(R, 'delete: gone from calendar', !(await pub('/calendar')).includes(T1b));
await page.goto(BASE + '/admin/events?q=' + stamp);
check(R, 'delete: gone from list', (await page.locator('tr[data-row-title]').count()) === 0);

// ---- conference
await page.goto(BASE + '/admin/events/create?kind=conference');
check(R, 'kind preselected from query', (await page.inputValue('#kind')) === 'conference');
check(R, 'calendar-only fields hidden', !(await page.locator('select[name=type]').isVisible()));
await page.fill('input[name=title]', TC);
await page.fill('input[name=conf_number]', '99th');
await page.fill('input[name=conf_year]', '2031');
await page.fill('input[name=dates_label]', '1–3 June 2031');
await page.fill('input[name=city]', 'Confville');
await page.fill('input[name=country]', 'Confland');
await page.fill('input[name=flag]', '🏳');
await page.fill('input[name=theme]', 'QA theme ' + stamp);
await page.selectOption('select[name=status]', 'upcoming');
await page.click('[data-testid=save-event]');
await page.waitForURL(/\/admin\/events$/);
html = await pub('/conferences');
const iC = html.indexOf('99th Conference');
check(R, 'conference shown first with number/dates/theme', iC > 0 && iC < html.indexOf('46th Conference') && html.includes('1–3 June 2031') && html.includes('QA theme ' + stamp) && html.includes('Confville, Confland'));
check(R, 'conference flagged Forthcoming', html.slice(iC, iC + 900).includes('Forthcoming'));
// conference validation
await page.goto(BASE + '/admin/events/create?kind=conference');
await page.fill('input[name=title]', 'x');
await page.click('[data-testid=save-event]');
errText = await page.textContent('[data-testid=form-errors]');
check(R, 'conference number/year required', /conf number/i.test(errText) && /year/i.test(errText), errText);

// ---- training
await page.goto(BASE + '/admin/events/create?kind=training');
await page.fill('input[name=title]', TT);
await page.fill('input[name=category]', 'Wildlife');
await page.fill('input[name=abbr]', 'QAT');
await page.fill('input[name=icon]', 'forest');
await page.fill('input[name=frequency]', 'Annual');
await page.fill('input[name=latest]', 'QA latest ' + stamp);
await page.fill('textarea[name=description]', 'QA description ' + stamp);
await page.click('[data-testid=save-event]');
await page.waitForURL(/\/admin\/events$/);
html = await pub('/training-programs');
check(R, 'training shown after the existing six', html.includes(TT) && html.indexOf(TT) > html.indexOf('Wildlife &amp; Environmental Crime Training') && html.includes('QA description ' + stamp) && html.includes('QA latest ' + stamp));
await page.goto(BASE + '/admin/events/create?kind=training');
await page.fill('input[name=title]', 'x');
await page.click('[data-testid=save-event]');
errText = await page.textContent('[data-testid=form-errors]');
check(R, 'training description/category required', /description/i.test(errText) && /category/i.test(errText), errText);

// ---- cleanup: delete conference + training created
for (const t of [TC, TT]) {
  await page.goto(BASE + '/admin/events?q=' + stamp);
  const row = page.locator('tr[data-row-title]', { hasText: t });
  if (await row.count()) { page.once('dialog', d => d.accept()); await row.locator('text=Delete').click(); await page.waitForURL(/\/admin\/events/); }
}
check(R, 'cleanup: seeded count restored', (await (async () => { await page.goto(BASE + '/admin/events'); return parseInt(await page.textContent('[data-testid=events-total]'), 10); })()) === total0);

finish(R, errors);
await browser.close();
