// Targeted tests for the "site" CMS group (header/footer, landing, contact).  node tests/e2e/site.mjs
import fs from 'node:fs';
import { launch, login, check, finish, openEditor, save, revertPage, BASE } from './lib.mjs';

const HF = 'site.header-footer', LAND = 'site.landing', CON = 'site.contact', CF = 'site.contact-form';
const { browser, page, errors } = await launch();
const R = [];
await login(page);
for (const k of [HF, LAND, CON, CF]) await revertPage(page, k);
const get = async (u) => (await page.request.get(BASE + u)).text();
const count = (h, s) => h.split(s).length - 1;

// 1x1 PNG, two different byte-strings so re-upload is a real change
const png = (extra) => Buffer.from('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==' + extra, 'base64');
const tmp = (n, extra) => { const f = `${process.env.TEMP || '.'}/${n}`; fs.writeFileSync(f, png(extra)); return f; };

// ---------- footer link + phone on two public pages
await openEditor(page, HF);
await page.fill('input[name="f[footer_links][quick_links][0][link]"]', '/en/qa-footer-target');
await page.fill('input[name="f[footer_links][quick_links][0][label]"]', 'QA Footer Label');
await save(page);
// footer phone is a shared fact (shared.secretariat) - edited there, restored at the end of this file
await openEditor(page, 'shared.secretariat');
const SEC_PHONE0 = await page.inputValue('input[name="f[contact][phone]"]');
const SEC_EMAIL0 = await page.inputValue('input[name="f[contact][email_secretariat]"]');
await page.fill('input[name="f[contact][phone]"]', '+60 QA 12345');
await save(page);
for (const u of ['/en/news-media/news', '/en/contact-us']) {
  const h = await get(u);
  check(R, `footer link edited on ${u}`, h.includes('href="/en/qa-footer-target"') && h.includes('QA Footer Label'));
  check(R, `footer phone edited on ${u}`, h.includes('<p>+60 QA 12345</p>'));
}

// ---------- hide a social icon (footer only): facebook appears once (sidebar) instead of twice
let h = await get('/en');
const fb0 = count(h, 'href="https://www.facebook.com/ASEANAPOL"');
await openEditor(page, HF);
await page.uncheck('input[type=checkbox][name="f[social][facebook_footer]"]');
await save(page);
h = await get('/en');
check(R, 'default facebook link appears in footer + sidebar (2)', fb0 === 2, fb0);
check(R, 'hiding footer facebook icon removes exactly one', count(h, 'href="https://www.facebook.com/ASEANAPOL"') === 1);
// add YouTube url -> shows in footer and sidebar
await openEditor(page, HF);
await page.fill('input[name="f[social][youtube_url]"]', 'https://www.youtube.com/@aseanapol-qa');
await save(page);
h = await get('/en');
check(R, 'youtube url renders in footer + sidebar', count(h, 'href="https://www.youtube.com/@aseanapol-qa"') === 2);

// ---------- reorder menu: move first top-level item down
h = await get('/en');
const desk = (x) => x.slice(x.indexOf('<nav class="hidden xl:flex'), x.indexOf('</nav>'));
const before = desk(h);
check(R, 'menu default: About before News', before.indexOf('About ASEANAPOL') < before.indexOf('News &amp; Media'));
await openEditor(page, HF);
const rep = page.locator('[name="f[menu][items][__present]"]').locator('xpath=ancestor::*[@data-repeater][1]');
await rep.locator(':scope > .cms-rows > [data-row]').first().locator('[data-act=down]').first().click();
await save(page);
const after = desk(await get('/en'));
check(R, 'menu reorder: News now before About', after.indexOf('News &amp; Media') < after.indexOf('About ASEANAPOL'));
// edit a dropdown link label + hide a dropdown child
await openEditor(page, HF);
await page.fill('input[name="f[menu][items][1][children][0][label]"]', 'QA Child Label');
await save(page);
check(R, 'dropdown child label edited (desktop + mobile)', count(await get('/en'), 'QA Child Label') === 2);

// ---------- landing: banner headline + image upload + re-upload
await openEditor(page, LAND);
await page.fill('input[name="f[banner][headline]"]', 'QA Hero Headline');
await page.setInputFiles('input[type=file][name="files[banner][image]"]', tmp('qa-hero.png', ''));
await save(page);
await openEditor(page, LAND);
const img1 = await page.inputValue('input[name="f[banner][image]"]');
h = await get('/en');
check(R, 'hero headline shown on landing', h.includes('QA Hero Headline'));
check(R, 'hero image uploaded to storage/cms and used', /^storage\/cms\/qa-hero-/.test(img1) && h.includes(img1));
const imgRes = await page.request.get(BASE + '/' + img1);
check(R, 'uploaded hero image is served', imgRes.status() === 200, imgRes.status());
await page.setInputFiles('input[type=file][name="files[banner][image]"]', tmp('qa-hero.png', 'AAAA'.slice(0, 0)));
await save(page);
await openEditor(page, LAND);
const img2 = await page.inputValue('input[name="f[banner][image]"]');
check(R, 're-upload gets a NEW filename', img2 && img2 !== img1, `${img1} vs ${img2}`);
// hide slider toggle
await page.uncheck('[data-testid=vis-slider]');
await save(page);
check(R, 'hiding slider removes hero slides', !(await get('/en')).includes('id="hero"'));

// ---------- contact: show form, submit it
await openEditor(page, CON);
await page.fill('input[name="f[form][heading]"]', 'Send an inquiry');
await save(page);
await openEditor(page, 'shared.secretariat');
await page.fill('input[name="f[contact][phone]"]', '+603 QA PHONE');
await page.fill('input[name="f[contact][email_secretariat]"]', 'qa-recipient@example.test');
await save(page);
check(R, 'contact-form page has no recipient field any more (shared)', (await (async () => { await openEditor(page, CF); return page.locator('input[name="f[settings][recipient]"]').count(); })()) === 0);
await page.goto(BASE + '/en/contact-us');
check(R, 'contact form visible once heading set', (await page.locator('form[action*="contact-us"] textarea[name=message]').count()) === 1);
check(R, 'contact phone edit visible', (await page.content()).includes('+603 QA PHONE'));
await page.fill('input[name=name]', 'QA Tester');
await page.fill('input[name=email]', 'qa@example.test');
await page.fill('input[name=subject]', 'QA subject');
await page.fill('textarea[name=message]', 'This is a QA test inquiry message.');
await Promise.all([page.waitForURL(/contact-us/), page.click('form[action*="contact-us"] button[type=submit]')]);
const ok = await page.locator('[data-testid=contact-success]').innerText().catch(() => '');
check(R, 'form submit shows success message', ok.includes('Thank you, QA Tester'), ok);
const mailer = (fs.readFileSync('.env', 'utf8').match(/^MAIL_MAILER=(.*)$/m) || [])[1];
check(R, 'mail driver is not a real transport (log)', mailer === 'log', mailer);
const log = fs.readdirSync('storage/logs').filter(f => f.endsWith('.log')).map(f => fs.readFileSync('storage/logs/' + f, 'utf8')).join('\n');
check(R, 'mail went to the CMS recipient', log.includes('qa-recipient@example.test'));

// ---------- screenshots for usability review
fs.mkdirSync('tests/e2e/shots', { recursive: true });
for (const k of [HF, LAND, CON]) {
  await openEditor(page, k);
  await page.screenshot({ path: `tests/e2e/shots/${k}.png`, fullPage: false });
}

for (const k of [HF, LAND, CON, CF]) await revertPage(page, k);
await openEditor(page, 'shared.secretariat');
await page.fill('input[name="f[contact][phone]"]', SEC_PHONE0);
await page.fill('input[name="f[contact][email_secretariat]"]', SEC_EMAIL0);
await save(page);
check(R, 'revert restores default footer', !(await get('/en')).includes('QA Footer Label') && !(await get('/en')).includes('+60 QA 12345'));

// ---------- landing stats: derived numbers + a custom row keeps its own value
h = await get('/en');
check(R, 'stats derive 11 / 12 / 44+ / 1981 from shared', /text-4xl[^>]*>11<[\s\S]*?>12<[\s\S]*?>44\+<[\s\S]*?>1981</.test(h));
await openEditor(page, LAND);
const kindSel = 'select[name="f[stats][items][0][kind]"]';
await page.selectOption(kindSel, 'custom');
await page.fill('input[name="f[stats][items][0][value]"]', '99');
await save(page);
check(R, 'stats row switched to Custom shows its typed value', /text-4xl[^>]*>99</.test(await get('/en')));
await revertPage(page, LAND);
finish(R, errors);
await browser.close();
