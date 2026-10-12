// Secretariat mailboxes are read from shared.secretariat on events/careers/data/newsmedia/guidelines/publication pages.
// Run: CMS_BASE=http://127.0.0.1:8416 node tests/e2e/relink-emails.mjs
import { launch, login, check, finish, openEditor, save, revertPage, BASE } from './lib.mjs';

const { browser, page, errors } = await launch();
const R = [];
await login(page);
const get = async p => { const r = await page.request.get(BASE + p); return [r.status(), await r.text()]; };

const GENERAL = [
  '/en/careers/vacancies',
  '/en/data-resources/crime-statistics',
  '/en/events-training/training-programs',
  '/en/news-media/press-releases',
  '/en/news-media/newsletters',
];
// video-gallery banner only renders while no video is published; checked only when it is visible
const VIDEO = '/en/news-media/video-gallery';
const VIDEO_TEXT = 'Video recordings of ASEANAPOL events and activities are being compiled';
const SECRETARIAT = [
  '/en/guidelines/guidelines-on-the-use-of-the-aseanapol-flag',
  '/en/guidelines/guidelines-for-accepting-donations-and-sponsorships',
  '/en/guidelines/policy-guidelines-for-accepting-observers-and-dialogue-partners-with-aseanapol',
];
const PUB_SLUG = 'qa-relink-test-bulletin';
const PUB = '/en/publication/' + PUB_SLUG;
const pubRow = () => page.locator(`tr[data-pub="${PUB_SLUG}"]`);
const rmPub = async () => {
  await page.goto(BASE + '/admin/publications');
  while (await pubRow().count()) { page.once('dialog', d => d.accept()); await pubRow().locator('[data-testid=delete]').click(); await page.waitForLoadState(); }
};

try {
  // a no-PDF edition so the "request" mailto renders
  await rmPub();
  await page.goto(BASE + '/admin/publications/create');
  await page.fill('input[name=title]', 'QA Relink Test Bulletin');
  await page.fill('input[name=edition]', '99th');
  await page.selectOption('select[name=kind]', 'bulletin');
  await page.fill('input[name=year]', '2027');
  await page.fill('input[name=slug]', PUB_SLUG);
  await page.fill('input[name=sort_order]', '99');
  await page.click('[data-testid=save]');
  await page.waitForURL(/\/admin\/publications$/);

  const videoBanner = (await get(VIDEO))[1].includes(VIDEO_TEXT);
  console.log('video-gallery banner visible: ' + videoBanner + (videoBanner ? '' : ' (videos exist; page skipped)'));
  if (videoBanner) GENERAL.push(VIDEO);
  // defaults
  for (const u of GENERAL) {
    const [st, h] = await get(u);
    check(R, `default ${u} shows mailto:info@aseanapol.org`, st === 200 && h.includes('mailto:info@aseanapol.org'), st);
  }
  for (const u of [...SECRETARIAT, PUB]) {
    const [st, h] = await get(u);
    check(R, `default ${u} shows mailto:aseanapolsec@aseanapol.org`, st === 200 && h.includes('mailto:aseanapolsec@aseanapol.org'), st);
  }
  let [, tp] = await get('/en/events-training/training-programs');
  check(R, 'training-programs says "all eleven member countries"', tp.includes('all eleven member countries'));

  // the removed registry fields are gone from the page editors
  for (const [key, name] of [
    ['careers.vacancies', 'f[updates][email]'], ['data.crime-statistics', 'f[notice][email]'], ['events.training-programs', 'f[intro][email]'],
    ['newsmedia.press-releases', 'f[banner][email]'], ['newsmedia.video-gallery', 'f[banner][email]'], ['newsmedia.newsletters', 'f[banner][email]'],
    ['guidelines.flag', 'f[foot][email]'], ['guidelines.donations', 'f[foot][email]'], ['guidelines.observers-dialogue', 'f[foot][email]'],
    ['data.publication-edition', 'f[download][request_email]'],
  ]) {
    await openEditor(page, key);
    check(R, `${key}: editor has no ${name}`, (await page.locator(`[name="${name}"]`).count()) === 0);
  }

  // --- edit email_general -> every info@ page changes
  await openEditor(page, 'shared.secretariat');
  await page.fill('input[name="f[contact][email_general]"]', 'qa-general@example.test');
  await save(page);
  for (const u of GENERAL) {
    const [, h] = await get(u);
    check(R, `${u} follows email_general`, h.includes('mailto:qa-general@example.test') && !h.includes('mailto:info@aseanapol.org'));
  }
  for (const u of [...SECRETARIAT, PUB]) {
    const [, h] = await get(u);
    check(R, `${u} unaffected by email_general`, !h.includes('qa-general@example.test') && h.includes('mailto:aseanapolsec@aseanapol.org'));
  }

  // --- edit email_secretariat -> guidelines + publication request change
  await openEditor(page, 'shared.secretariat');
  await page.fill('input[name="f[contact][email_secretariat]"]', 'qa-sec@example.test');
  await save(page);
  for (const u of [...SECRETARIAT, PUB]) {
    const [, h] = await get(u);
    check(R, `${u} follows email_secretariat`, h.includes('mailto:qa-sec@example.test') && !h.includes('mailto:aseanapolsec@aseanapol.org'));
  }
  for (const u of GENERAL) {
    const [, h] = await get(u);
    check(R, `${u} still on email_general after email_secretariat edit`, h.includes('mailto:qa-general@example.test'));
  }

  // --- member count word feeds the training intro
  await openEditor(page, 'shared.organisation');
  await page.fill('input[name="f[counts][member_count_word]"]', 'twelve');
  await save(page);
  [, tp] = await get('/en/events-training/training-programs');
  check(R, 'training intro follows member_count_word', tp.includes('all twelve member countries'));
} catch (e) {
  check(R, 'unexpected error', false, e.stack || e.message);
} finally {
  try {
    await revertPage(page, 'shared.secretariat');
    await revertPage(page, 'shared.organisation');
    await rmPub();
    for (const u of GENERAL) { const [, h] = await get(u); check(R, `revert ${u}`, h.includes('mailto:info@aseanapol.org')); }
    for (const u of SECRETARIAT) { const [, h] = await get(u); check(R, `revert ${u}`, h.includes('mailto:aseanapolsec@aseanapol.org')); }
    const [, tp2] = await get('/en/events-training/training-programs');
    check(R, 'revert: eleven', tp2.includes('all eleven member countries'));
  } catch (e) { check(R, 'revert failed', false, e.message); }
}
await browser.close();
finish(R, errors);
