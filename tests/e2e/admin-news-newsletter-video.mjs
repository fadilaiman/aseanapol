// CRUD QA for the existing admin modules: News, Newsletters, Video Gallery.
// node tests/e2e/admin-news-newsletter-video.mjs   (CMS_BASE=http://127.0.0.1:<port>)
// Creates records prefixed "QAE2E" and deletes them again (also on failure, via the cleanup pass).
import fs from 'node:fs';
import os from 'node:os';
import path from 'node:path';
import { launch, login, check, finish, BASE } from './lib.mjs';

const TAG = 'QAE2E' + Date.now().toString(36);
const tmp = fs.mkdtempSync(path.join(os.tmpdir(), 'cms-qa-'));
// 1x1 png
const PNG = Buffer.from('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==', 'base64');
const png = path.join(tmp, 'qa-thumb.png'); fs.writeFileSync(png, PNG);
const PDF = '%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj\n2 0 obj<</Type/Pages/Kids[3 0 R]/Count 1>>endobj\n3 0 obj<</Type/Page/Parent 2 0 R/MediaBox[0 0 10 10]>>endobj\ntrailer<</Root 1 0 R>>\n%%EOF\n';
const pdf1 = path.join(tmp, 'qa-one.pdf'); fs.writeFileSync(pdf1, PDF);
const pdf2 = path.join(tmp, 'qa-two.pdf'); fs.writeFileSync(pdf2, PDF + '% second\n');
const txt = path.join(tmp, 'qa-not-pdf.txt'); fs.writeFileSync(txt, 'hello');

const { browser, page, errors } = await launch();
const R = [];
const SUBMIT = 'form:has(input[name=title]) button[type=submit]'; // the layout also has a logout submit button
const pub = async (p) => page.request.get(BASE + p);
const body = async (p) => (await pub(p)).text();
page.on('dialog', d => d.accept());

async function cleanup() {
  // News
  for (let i = 0; i < 10; i++) {
    await page.goto(`${BASE}/admin/news?q=${TAG}`);
    const del = page.locator('form[action*="/admin/news/"] button:has-text("Delete")').first();
    if (!(await del.count())) break;
    await Promise.all([page.waitForNavigation(), del.click()]);
  }
  for (const [mod, label] of [['newsletters', 'Delete'], ['video-gallery', 'Delete']]) {
    for (let i = 0; i < 10; i++) {
      await page.goto(`${BASE}/admin/${mod}`);
      const row = page.locator(`tr:has-text("${TAG}")`).first();
      if (!(await row.count())) break;
      await Promise.all([page.waitForNavigation(), row.locator(`button:has-text("${label}")`).click()]);
    }
  }
}

try {
  await login(page);
  await cleanup();

  // ================================================================ NEWS
  // validation: empty title (bypass browser `required`)
  await page.goto(`${BASE}/admin/news/create`);
  await page.evaluate(() => document.querySelector('input[name=title]').removeAttribute('required'));
  await page.click(SUBMIT);
  check(R, 'news: empty title shows validation error', (await page.locator('.text-red-500:has-text("title")').count()) > 0 || /title field is required/i.test(await page.content()));

  // validation: title over the DB column length
  await page.goto(`${BASE}/admin/news/create`);
  await page.fill('input[name=title]', TAG + ' ' + 'x'.repeat(300));
  await page.click(SUBMIT);
  check(R, 'news: 300-char title rejected with validation error (not a 500)', /may not be greater than 255|must not be greater than 255/i.test(await page.content()));

  // create with upload, upcoming flag, XSS content
  await page.goto(`${BASE}/admin/news/create`);
  await page.fill('input[name=title]', `${TAG} First Article`);
  await page.fill('input[name=author]', 'QA Bot');
  await page.fill('input[name=published_at]', '2026-01-15T09:30');
  await page.click('#tab-file');
  await page.setInputFiles('input[name=thumbnail_file]', png);
  await page.fill('textarea[name=summary]', 'QA summary <b>not bold</b>');
  await page.fill('textarea[name=content]', '<p class="qa-keep" onclick="alert(1)">Hello QA <img src="x" onerror="alert(2)"></p><script>alert(3)</script><a href="javascript:alert(4)">bad</a>');
  await page.check('#is_upcoming_event');
  await Promise.all([page.waitForURL(/\/admin\/news$/), page.click(SUBMIT)]);
  check(R, 'news: create redirects to list with success flash', (await page.content()).includes('Article created'));
  await page.goto(`${BASE}/admin/news?q=${TAG}`);
  check(R, 'news: new article listed (search)', (await page.locator(`text=${TAG} First Article`).count()) > 0);

  // find slug of created article through the edit link
  await page.goto(`${BASE}/admin/news?q=${TAG}`);
  await Promise.all([page.waitForURL(/\/edit$/), page.locator(`tr:has-text("${TAG} First Article") a:has-text("Edit")`).click()]);
  const editUrl = page.url();
  let slug = await page.inputValue('input[name=slug]');
  check(R, 'news: slug auto-generated from title', slug === (TAG + '-first-article').toLowerCase(), slug);
  check(R, 'news: upcoming flag persisted', await page.isChecked('#is_upcoming_event'));
  check(R, 'news: published_at persisted', (await page.inputValue('input[name=published_at]')) === '2026-01-15T09:30');
  const thumbSrc = await page.getAttribute('img[alt="current thumbnail"]', 'src');
  check(R, 'news: uploaded thumbnail previews on edit page', !!thumbSrc && (await page.request.get(thumbSrc)).ok(), thumbSrc);

  let html = await body(`/en/news-media/news/${slug}`);
  check(R, 'news: public page renders', html.includes(`${TAG} First Article`) && html.includes('Hello QA'));
  check(R, 'news: script tag stripped from content', !html.includes('alert(3)'));
  check(R, 'news: inline event handlers stripped', !/onclick="alert\(1\)"|onerror="alert\(2\)"/.test(html));
  check(R, 'news: javascript: links neutralised', !html.includes('javascript:alert(4)'));
  check(R, 'news: harmless markup (class) kept', html.includes('class="qa-keep"'));
  check(R, 'news: summary is escaped', !html.includes('<b>not bold</b>'));
  check(R, 'news: appears in public list', (await body(`/en/news-media/news?q=${TAG}`)).includes(`${TAG} First Article`));

  // slug uniqueness (same title -> -1 suffix; explicit duplicate slug -> suffix)
  await page.goto(`${BASE}/admin/news/create`);
  await page.fill('input[name=title]', `${TAG} First Article`);
  await Promise.all([page.waitForURL(/\/admin\/news$/), page.click(SUBMIT)]);
  await page.goto(`${BASE}/admin/news/create`);
  await page.fill('input[name=title]', `${TAG} Third`);
  await page.fill('input[name=slug]', slug);
  await Promise.all([page.waitForURL(/\/admin\/news$/), page.click(SUBMIT)]);
  await page.goto(`${BASE}/admin/news?q=${TAG}`);
  check(R, '3 QA articles exist after duplicates', (await page.locator(`tr:has-text("${TAG}")`).count()) === 3, await page.locator(`tr:has-text("${TAG}")`).count());
  await page.locator(`tr:has-text("${TAG} First Article")`).first().locator('a:has-text("Edit")').click();
  const dupSlug = await page.inputValue('input[name=slug]');
  check(R, 'news: duplicate title got unique slug', dupSlug !== slug && dupSlug.startsWith(slug), dupSlug);
  await page.goto(`${BASE}/admin/news?q=${TAG}%20Third`);
  await page.locator('a:has-text("Edit")').first().click();
  const thirdSlug = await page.inputValue('input[name=slug]');
  check(R, 'news: duplicate explicit slug got unique slug', thirdSlug !== slug && thirdSlug.startsWith(slug), thirdSlug);

  // edit first article: change title/content, keep thumbnail (relative path prefilled) and untick upcoming
  await page.goto(editUrl);
  await page.fill('input[name=title]', `${TAG} First Article Edited`);
  await page.fill('textarea[name=content]', '<p>Edited body QA</p>');
  await page.uncheck('#is_upcoming_event');
  await Promise.all([page.waitForURL(/\/admin\/news$/), page.click(SUBMIT)]);
  check(R, 'news: update succeeded (thumbnail field no longer blocks edit)', (await page.content()).includes('Article updated'));
  html = await body(`/en/news-media/news/${slug}`);
  check(R, 'news: edit visible on public page', html.includes('Edited body QA') && html.includes(`${TAG} First Article Edited`));
  await page.goto(editUrl);
  check(R, 'news: upcoming flag cleared', !(await page.isChecked('#is_upcoming_event')));
  check(R, 'news: thumbnail kept after edit', !!(await page.getAttribute('img[alt="current thumbnail"]', 'src')));
  // slug change + invalid thumbnail value
  await page.fill('input[name=slug]', 'QA Renamed Slug!! ' + TAG);
  await Promise.all([page.waitForURL(/\/admin\/news$/), page.click(SUBMIT)]);
  const newSlug = ('qa-renamed-slug-' + TAG).toLowerCase();
  check(R, 'news: slug rename is slugified and routable', (await pub(`/en/news-media/news/${newSlug}`)).status() === 200);
  check(R, 'news: old slug no longer routable', (await pub(`/en/news-media/news/${slug}`)).status() === 404);
  await page.goto(editUrl);
  await page.fill('input[name=thumbnail_url]', 'javascript:alert(1)');
  await page.click(SUBMIT);
  check(R, 'news: javascript: thumbnail URL rejected', /thumbnail url field format is invalid/i.test(await page.content()));

  // list: search + delete
  await page.goto(`${BASE}/admin/news?q=${TAG}%20Third`);
  check(R, 'news: admin search filters', (await page.locator(`tr:has-text("${TAG}")`).count()) === 1);
  await Promise.all([page.waitForNavigation(), page.locator(`tr:has-text("${TAG} Third") button:has-text("Delete")`).click()]);
  check(R, 'news: delete removes article', (await page.locator(`tr:has-text("${TAG} Third")`).count()) === 0);
  check(R, 'news: deleted article 404s publicly', (await pub(`/en/news-media/news/${thirdSlug}`)).status() === 404);

  // ================================================================ NEWSLETTERS
  await page.goto(`${BASE}/admin/newsletters/create`);
  await page.evaluate(() => document.querySelectorAll('[required]').forEach(e => e.removeAttribute('required')));
  await page.click(SUBMIT);
  const c = await page.content();
  check(R, 'newsletter: required-field validation errors shown', /title field is required/i.test(c) && /edition field is required/i.test(c) && /issue date field is required/i.test(c));

  await page.goto(`${BASE}/admin/newsletters/create`);
  await page.fill('input[name=title]', `${TAG} Newsletter`);
  await page.fill('input[name=edition]', 'QA 1');
  await page.fill('input[name=issue_date]', '2026-03-01');
  await page.setInputFiles('input[name=file]', txt);
  await page.click(SUBMIT);
  check(R, 'newsletter: non-PDF upload rejected', /must be a file of type: pdf/i.test(await page.content()));

  await page.goto(`${BASE}/admin/newsletters/create`);
  await page.fill('input[name=title]', `${TAG} Newsletter`);
  await page.fill('input[name=edition]', 'QA 1');
  await page.fill('input[name=issue_date]', '2026-03-01');
  await page.fill('textarea[name=topics]', 'Topic Alpha QA\r\n\nTopic Beta QA\n');
  await page.setInputFiles('input[name=file]', pdf1);
  await Promise.all([page.waitForURL(/\/admin\/newsletters$/), page.click(SUBMIT)]);
  check(R, 'newsletter: created (draft) and listed', (await page.locator(`tr:has-text("${TAG} Newsletter")`).count()) === 1 && (await page.locator(`tr:has-text("${TAG}") >> text=Draft`).count()) === 1);
  check(R, 'newsletter: topics counted (blank lines ignored)', (await page.locator(`tr:has-text("${TAG}") >> text=2 topic(s)`).count()) === 1);
  check(R, 'newsletter: draft hidden on public page', !(await body('/en/news-media/newsletters')).includes(TAG));
  await Promise.all([page.waitForNavigation(), page.locator(`tr:has-text("${TAG}") button:has-text("Draft")`).click()]);
  check(R, 'newsletter: toggle publishes', (await page.locator(`tr:has-text("${TAG}") >> text=Published`).count()) === 1);
  html = await body('/en/news-media/newsletters');
  check(R, 'newsletter: published shows on public page with topics + download', html.includes(`${TAG} Newsletter`) && html.includes('Topic Alpha QA') && html.includes('Download PDF'));
  const pdfHref = (await page.locator(`tr:has-text("${TAG}") a:has-text("PDF")`).getAttribute('href'));
  check(R, 'newsletter: uploaded PDF reachable', (await page.request.get(pdfHref.startsWith('http') ? pdfHref : BASE + pdfHref)).ok(), pdfHref);

  await page.locator(`tr:has-text("${TAG}") a:has-text("Edit")`).click();
  check(R, 'newsletter: edit form pre-fills topics', (await page.inputValue('textarea[name=topics]')) === 'Topic Alpha QA\nTopic Beta QA');
  await page.fill('input[name=title]', `${TAG} Newsletter Edited`);
  await page.setInputFiles('input[name=file]', pdf2);
  await Promise.all([page.waitForURL(/\/admin\/newsletters$/), page.click(SUBMIT)]);
  const pdfHref2 = (await page.locator(`tr:has-text("${TAG}") a:has-text("PDF")`).getAttribute('href'));
  check(R, 'newsletter: edit replaces file with a new filename', pdfHref2 !== pdfHref && (await page.request.get(BASE + pdfHref2.replace(/^https?:\/\/[^/]+/, ''))).ok() && !(await page.request.get(BASE + pdfHref.replace(/^https?:\/\/[^/]+/, ''))).ok(), pdfHref + ' -> ' + pdfHref2);
  check(R, 'newsletter: edited title public', (await body('/en/news-media/newsletters')).includes(`${TAG} Newsletter Edited`));
  await Promise.all([page.waitForNavigation(), page.locator(`tr:has-text("${TAG}") button:has-text("Delete")`).click()]);
  check(R, 'newsletter: delete removes row + public entry', (await page.locator(`tr:has-text("${TAG}")`).count()) === 0 && !(await body('/en/news-media/newsletters')).includes(TAG));

  // ================================================================ VIDEO GALLERY
  await page.goto(`${BASE}/admin/video-gallery/create`);
  await page.fill('input[name=title]', `${TAG} Video`);
  await page.fill('input[name=embed_url]', 'not-a-url');
  await page.evaluate(() => document.querySelector('input[name=embed_url]').type = 'text');
  await page.click(SUBMIT);
  check(R, 'video: invalid embed URL rejected', /embed url field must be a valid URL/i.test(await page.content()));

  await page.goto(`${BASE}/admin/video-gallery/create`);
  await page.fill('input[name=title]', `${TAG} Video`);
  await page.fill('textarea[name=description]', 'QA <i>video</i> description');
  await page.fill('input[name=embed_url]', 'https://www.youtube.com/embed/qa123');
  await page.selectOption('select[name=category]', 'Training');
  await page.fill('input[name=event_date]', '2026-02-10');
  await page.fill('input[name=event_location]', 'QA City');
  await Promise.all([page.waitForURL(/\/admin\/video-gallery$/), page.click(SUBMIT)]);
  check(R, 'video: created and listed as draft', (await page.locator(`tr:has-text("${TAG}")`).count()) === 1);
  const vr = () => page.locator(`tr:has-text("${TAG}")`);
  check(R, 'video: draft hidden publicly', !(await body('/en/news-media/video-gallery')).includes(TAG));
  await Promise.all([page.waitForNavigation(), vr().locator('form[action*="toggle"] button').click()]);
  html = await body('/en/news-media/video-gallery');
  check(R, 'video: publish shows embed, location, month, escaped description', html.includes(`${TAG} Video`) && html.includes('youtube.com/embed/qa123') && html.includes('QA City') && html.includes('February 2026') && !html.includes('<i>video</i>'));
  await vr().locator('a:has-text("Edit")').click();
  check(R, 'video: edit pre-fills category/date', (await page.inputValue('select[name=category]')) === 'Training' && (await page.inputValue('input[name=event_date]')) === '2026-02-10');
  await page.fill('input[name=title]', `${TAG} Video Edited`);
  await page.fill('input[name=event_location]', '');
  await Promise.all([page.waitForURL(/\/admin\/video-gallery$/), page.click(SUBMIT)]);
  html = await body('/en/news-media/video-gallery');
  check(R, 'video: edit reflected publicly (location cleared)', html.includes(`${TAG} Video Edited`) && !html.includes('QA City'));
  await Promise.all([page.waitForNavigation(), vr().locator('form[action*="toggle"] button').click()]);
  check(R, 'video: unpublish hides again', !(await body('/en/news-media/video-gallery')).includes(TAG));
  await Promise.all([page.waitForNavigation(), vr().locator('button:has-text("Delete")').click()]);
  check(R, 'video: delete removes row', (await page.locator(`tr:has-text("${TAG}")`).count()) === 0);
} catch (e) {
  check(R, 'test run completed without exception', false, e.stack || String(e));
} finally {
  try { await cleanup(); } catch (e) { console.log('cleanup failed: ' + e.message); }
  fs.rmSync(tmp, { recursive: true, force: true });
}
await page.goto(`${BASE}/admin/news?q=${TAG}`);
check(R, 'cleanup: no QA news left', (await page.locator(`tr:has-text("${TAG}")`).count()) === 0);
finish(R, errors);
await browser.close();
