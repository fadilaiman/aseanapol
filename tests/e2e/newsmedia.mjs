// Group test for News & Media CMS pages. node tests/e2e/newsmedia.mjs  (CMS_BASE=...)
import { launch, login, check, finish, openEditor, save, revertPage, BASE } from './lib.mjs';
const { browser, page, errors } = await launch();
const R = [];
const get = async u => (await page.request.get(BASE + u)).text();
await login(page);
for (const k of ['index', 'news-list', 'news-search', 'press-releases', 'gallery', 'video-gallery', 'newsletters']) await revertPage(page, 'newsmedia.' + k);

// ---- hub: edit a card, hide a card, change route target
await openEditor(page, 'newsmedia.index');
await page.fill('input[name="f[hero][title]"]', 'Media Centre QA');
const rows = page.locator('[data-repeater] .cms-rows > [data-row]');
check(R, 'hub: 5 cards in editor', (await rows.count()) === 5, await rows.count());
await rows.nth(0).locator('input[name$="[title]"]').fill('Articles QA');
await rows.nth(0).locator('select[name$="[route]"]').selectOption('news-media.newsletters');
await rows.nth(1).locator('input[name$="[_hide]"]').check();
await save(page);
let html = await get('/en/news-media');
check(R, 'hub: title, card title shown', html.includes('Media Centre QA') && html.includes('Articles QA'));
check(R, 'hub: hidden card gone', !html.includes('Read Releases'));
check(R, "hub: card link retargeted", /^[^"]*\/news-media\/newsletters"/.test(html.split('<a href="').find(x => x.includes("Articles QA")) || ""));
check(R, 'sub-page breadcrumb follows hub title', (await get('/en/news-media/gallery')).includes('Media Centre QA'));
await revertPage(page, 'newsmedia.index');

// ---- press releases: add / edit / hide / reorder a release
await openEditor(page, 'newsmedia.press-releases');
check(R, 'press: 6 default releases', (await rows.count()) === 6, await rows.count());
await page.click('[data-act=add-row]');
const last = rows.last();
await last.locator('input[name$="[date]"]').fill('01 Jan 2027');
await last.locator('input[name$="[category]"]').fill('QA Cat');
await last.locator('input[name$="[title]"]').fill('QA Release Title');
await last.locator('textarea[name$="[summary]"]').fill('QA release summary text');
await last.locator('input[name$="[slug]"]').fill('qa-release-slug');
await rows.nth(2).locator('input[name$="[_hide]"]').check();
await page.fill('input[name="f[releases][read_more]"]', 'Open It');
await save(page);
html = await get('/en/news-media/press-releases');
check(R, 'press: new release rendered with link + label', html.includes('QA Release Title') && html.includes('/news-media/news/qa-release-slug') && html.includes('QA Cat') && html.includes('Open It'));
check(R, 'press: hidden release gone', !html.includes('13th ASEANAPOL Training Cooperation Meeting'));
check(R, 'press: other releases intact', html.includes('42nd ASEANAPOL Conference'));
await page.goto(`${BASE}/admin/site-content/newsmedia.press-releases`);
await page.uncheck('[data-testid=vis-releases]');
await page.uncheck('[data-testid=vis-banner]');
await page.uncheck('[data-testid=vis-more]');
await save(page);
html = await get('/en/news-media/press-releases');
check(R, 'press: all three blocks can be hidden', !html.includes('QA Release Title') && !html.includes('media enquiries') && !html.includes('Browse All News'));
await revertPage(page, 'newsmedia.press-releases');
html = await get('/en/news-media/press-releases');
check(R, 'press: revert restores defaults', html.includes('13th ASEANAPOL Training Cooperation Meeting') && !html.includes('QA Release'));

// ---- news list search page + labels
await openEditor(page, 'newsmedia.news-list');
await page.fill('input[name="f[labels][showing]"]', 'Displaying');
await save(page);
html = await get('/en/news-media/news');
check(R, 'news-list: result-count label editable', /Displaying 1–12 of \d+ articles/.test(html.replace(/\s+/g, ' ')));
await revertPage(page, 'newsmedia.news-list');
await openEditor(page, 'newsmedia.news-search');
await page.fill('input[name="f[empty][message]"]', 'Nothing here QA.');
await save(page);
html = await get('/en/news-media/news?q=zzqxnomatch');
check(R, 'news-search: empty-state text editable', html.includes('Nothing here QA.'));
await revertPage(page, 'newsmedia.news-search');

// ---- gallery album label
await openEditor(page, 'newsmedia.gallery');
await page.fill('input[name="f[albums][news]"]', 'Story Photos QA');
await page.fill('input[name="f[labels][all]"]', 'Everything QA');
await save(page);
html = await get('/en/news-media/gallery');
check(R, 'gallery: album + tab labels editable', html.includes('Story Photos QA') && html.includes('Everything QA'));
await revertPage(page, 'newsmedia.gallery');

// ---- apostrophes in JS-embedded gallery label must not break the script
await openEditor(page, 'newsmedia.gallery');
await page.fill('input[name="f[labels][load_more]"]', "More o'photos");
await save(page);
html = await get('/en/news-media/gallery');
check(R, 'gallery: apostrophe in JS label is escaped', html.includes("More o\\'photos"));
await revertPage(page, 'newsmedia.gallery');

// ---- screenshots for usability review
if (process.env.SHOTS) {
  for (const k of ['index', 'press-releases', 'gallery']) {
    await openEditor(page, 'newsmedia.' + k);
    await page.screenshot({ path: `${process.env.SHOTS}/newsmedia-${k}.png`, fullPage: true });
  }
}
finish(R, errors);
await browser.close();
