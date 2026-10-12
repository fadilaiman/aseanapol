// Targeted tests for group about-people. People/partner/dialogue DATA now lives in "shared.*"
// (see relink-people.mjs); this file covers the page-local wording, toggles and badge placeholders.
import { launch, login, check, finish, openEditor, save, revertPage, BASE } from './lib.mjs';

const { browser, page, errors } = await launch();
const R = [];
await login(page);
const get = async (p) => await (await page.request.get(BASE + p)).text();

// ───────── Leadership: local wording only; moved fields are gone from the editor
{
  const KEY = 'about-people.leadership', URL_ = '/en/about-aseanapol/leadership';
  await revertPage(page, KEY);
  await openEditor(page, KEY);
  for (const gone of ['f[current][name]', 'f[current][photo]', 'f[current][bio]', 'f[current][force]', 'f[past][rows]'])
    check(R, `leadership: ${gone} no longer an editor field`, (await page.locator(`[name^="${gone}"]`).count()) === 0);
  await page.fill('input[name="f[past][heading]"]', 'QA Past Heading');
  await page.fill('input[name="f[past][col_term]"]', 'QA Term Col');
  await page.fill('input[name="f[current][badge]"]', 'QA {ordinal} badge / {term}');
  await save(page);
  let html = await get(URL_);
  check(R, 'leadership: local headings edited', html.includes('QA Past Heading') && html.includes('QA Term Col'));
  check(R, 'leadership: badge placeholders filled from roster', html.includes('QA 9th badge / 2026–2027'));
  await openEditor(page, KEY);
  await page.uncheck('[data-testid=vis-directors]');
  await page.uncheck('[data-testid=vis-past]');
  await save(page);
  html = await get(URL_);
  check(R, 'leadership: directors + past sections hidden', !html.includes('Secretariat Directors') && !html.includes('<tbody'));
  await revertPage(page, KEY);
  html = await get(URL_);
  check(R, 'leadership: revert restores', html.includes('Secretariat Directors') && html.includes('Kongkrissada') && !html.includes('QA Past Heading'));
}

// ───────── DPS: local heading/badge
{
  const KEY = 'about-people.leadership-dps', URL_ = '/en/about-aseanapol/leadership/director-for-police-services';
  await revertPage(page, KEY);
  await openEditor(page, KEY);
  await page.fill('input[name="f[current][heading]"]', 'QA Current Heading');
  await page.fill('input[name="f[current][alt]"]', 'QA alt text');
  await save(page);
  const html = await get(URL_);
  check(R, 'dps: heading + alt edited', html.includes('QA Current Heading') && html.includes('alt="QA alt text"'));
  check(R, 'dps: holder still read from shared', html.includes('Huntal Tambunan'));
  await revertPage(page, KEY);
  check(R, 'dps: revert', (await get(URL_)).includes('Current Director for Police Services'));
}

// ───────── LME / dialogue / observers: labels only
{
  let KEY = 'about-people.ob-lme', URL_ = '/en/about-aseanapol/lme';
  await revertPage(page, KEY);
  await openEditor(page, KEY);
  await page.fill('input[name="f[entities][website_label]"]', 'QA Site Link');
  await save(page);
  check(R, 'lme: website label edited', (await get(URL_)).includes('QA Site Link'));
  await revertPage(page, KEY);

  KEY = 'about-people.dialogue-partners'; URL_ = '/en/about-aseanapol/dialogue-partners';
  await revertPage(page, KEY);
  await openEditor(page, KEY);
  await page.fill('input[name="f[partners][since_label]"]', 'QASince');
  await page.fill('input[name="f[partners][activities_label]"]', 'QA Activities');
  await save(page);
  let html = await get(URL_);
  check(R, 'dp: labels edited', html.includes('QASince 2008') && html.includes('QA Activities'));
  await revertPage(page, KEY);

  KEY = 'about-people.observers'; URL_ = '/en/about-aseanapol/observers';
  await revertPage(page, KEY);
  await openEditor(page, KEY);
  await page.fill('input[name="f[list][since_label]"]', 'QASince');
  await page.fill('input[name="f[list][heading]"]', 'QA Observers Heading');
  await save(page);
  html = await get(URL_);
  check(R, 'observers: labels edited', html.includes('QASince 2022') && html.includes('QA Observers Heading'));
  await revertPage(page, KEY);
  check(R, 'observers: revert', (await get(URL_)).includes('Current Observers'));
}

finish(R, errors);
await browser.close();
