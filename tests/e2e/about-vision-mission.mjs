import { launch, login, check, finish, openEditor, save, revertPage, BASE } from './lib.mjs';
const KEY = 'about.vision-mission';
const URL_ = BASE + '/en/about-aseanapol/vision-and-mission';
const { browser, page, errors } = await launch();
const R = [];
await login(page);
await revertPage(page, KEY);

let html = await (await page.request.get(URL_)).text();
check(R, 'default renders title', html.includes('Vision and Mission'));
check(R, 'default has 4 mission items', (html.match(/Combat Transnational Crime|Build Strategic Partnerships/g) || []).length === 2);

await openEditor(page, KEY);
await page.fill('input[name="f[hero][title]"]', 'Vision & Mission QA');
await page.uncheck('[data-testid=vis-vision]');
await page.click('[data-act=add-row]');
const rows = page.locator('[data-repeater] .cms-rows > [data-row]');
check(R, 'repeater has 5 rows after add', (await rows.count()) === 5, await rows.count());
const last = rows.last();
await last.locator('input[name$="[title]"]').fill('QA Mission Point');
await last.locator('textarea[name$="[desc]"]').fill('Added by Playwright');
// hide first row, remove second
await rows.nth(0).locator('input[name$="[_hide]"]').check();
page.once('dialog', d => d.accept());
await rows.nth(1).locator('[data-act=remove]').click();
await save(page);

html = await (await page.request.get(URL_)).text();
check(R, 'title updated', html.includes('Vision &amp; Mission QA'));
check(R, 'vision section hidden', !html.includes('premier regional police'));
check(R, 'new item shown', html.includes('QA Mission Point') && html.includes('Added by Playwright'));
check(R, 'hidden row not shown', !html.includes('Combat Transnational Crime'));
check(R, 'removed row not shown', !html.includes('Strengthen Institutional Capacity'));
check(R, 'untouched row still shown', html.includes('Facilitate Intelligence Sharing'));

await revertPage(page, KEY);
html = await (await page.request.get(URL_)).text();
check(R, 'revert restores vision', html.includes('premier regional police'));
check(R, 'revert restores items', html.includes('Combat Transnational Crime') && !html.includes('QA Mission Point'));
finish(R, errors);
await browser.close();
