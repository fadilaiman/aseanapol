// Relink test for the "site" group: footer / contact / landing READ their facts from shared.*.  node tests/e2e/relink-site.mjs
// Edits shared values, asserts the public pages follow, then restores every original value (no page revert, so it is safe
// next to other agents' edits). Works on whatever DB the target server uses.
import { launch, login, check, finish, openEditor, save, BASE } from './lib.mjs';

const { browser, page, errors } = await launch();
const R = [];
await login(page);
const get = async (u) => (await page.request.get(BASE + u)).text();
const has = (h, s) => h.includes(s);
const restore = [];   // [pageKey, selector, original value]

async function setField(key, selector, value) {
  await openEditor(page, key);
  const orig = await page.inputValue(selector);
  restore.push([key, selector, orig]);
  await page.fill(selector, value);
  await save(page);
  return orig;
}
async function rowIndex(key, wantedKey) {
  await openEditor(page, key);
  return page.evaluate((k) => {
    for (const el of document.querySelectorAll('input[name$="[key]"]')) if (el.value === k) return +el.name.match(/\[rows\]\[(\d+)\]/)[1];
    return -1;
  }, wantedKey);
}
const M = (i, f) => `input[name="f[list][rows][${i}][${f}]"]`;

// ---------- 1. Secretariat facts -> footer (every page) + contact page
const secMail0 = await setField('shared.secretariat', 'input[name="f[contact][email_secretariat]"]', 'qa-sec@example.test');
await setField('shared.secretariat', 'input[name="f[contact][phone]"]', '+60 QA 11111');
await setField('shared.secretariat', 'input[name="f[contact][fax]"]', '+60 QA 22222');
await setField('shared.secretariat', 'input[name="f[contact][address_oneline]"]', 'QA ONE LINE ADDRESS');
await setField('shared.secretariat', 'textarea[name="f[contact][address]"]', 'QA ADDR LINE 1\nQA ADDR LINE 2');
await setField('shared.secretariat', 'textarea[name="f[contact][office_hours]"], input[name="f[contact][office_hours]"]', 'QA OFFICE HOURS');
await setField('shared.secretariat', 'input[name="f[contact][map_embed_src]"]', 'https://maps.google.com/maps?q=qa-map&output=embed');
for (const u of ['/en', '/en/news-media/news', '/en/contact-us']) {
  const h = await get(u);
  check(R, `footer shows shared phone/fax/e-mail/address on ${u}`, has(h, '<p>+60 QA 11111</p>') && has(h, '<p>+60 QA 22222</p>') && has(h, 'mailto:qa-sec@example.test') && has(h, '<p>QA ONE LINE ADDRESS</p>'));
}
const ch = await get('/en/contact-us');
check(R, 'contact page: phone, fax, e-mail follow shared', has(ch, '+60 QA 11111') && has(ch, '+60 QA 22222') && (ch.match(/qa-sec@example\.test/g) || []).length >= 4);
check(R, 'contact page: 2-line address follows shared (br)', has(ch, 'QA ADDR LINE 1<br>') && has(ch, 'QA ADDR LINE 2'));
check(R, 'contact page: office hours + map follow shared', has(ch, 'QA OFFICE HOURS') && has(ch, 'q=qa-map'));

// ---------- 2. A member's e-mail / tel / fax edited in Shared -> landing directory + contact directory
const idxId = await rowIndex('shared.members', 'indonesia');
await setField('shared.members', M(idxId, 'ncb_email'), 'qa-inp@example.test');
await setField('shared.members', M(idxId, 'tel'), '+62 QA 333');
await setField('shared.members', M(idxId, 'force'), 'Qa Indonesian Force');
let lh = await get('/en'); const ch2 = await get('/en/contact-us');
check(R, 'landing directory shows the edited e-mail/tel/force', has(lh, 'mailto:qa-inp@example.test') && has(lh, '+62 QA 333') && has(lh, 'Qa Indonesian Force (INP)'));
check(R, 'contact directory shows the edited e-mail/tel/force', has(ch2, 'mailto:qa-inp@example.test') && has(ch2, '+62 QA 333') && has(ch2, 'Qa Indonesian Force (INP)'));

// ---------- 3. Hide a member (brunei) in Shared -> gone from strip, directory, contact; counts follow
const idxBn = await rowIndex('shared.members', 'brunei');
await openEditor(page, 'shared.members');
await page.locator(M(idxBn, 'key')).locator('xpath=ancestor::*[@data-row][1]').locator('input[name$="[_hide]"]').first().check();
await save(page);
lh = await get('/en'); const ch3 = await get('/en/contact-us'); const fh = await get('/en/news-media/news');
check(R, 'hidden member gone from landing strip (both loop copies)', !has(lh, 'Brunei Darussalam police logo') && !has(lh, 'brunei-rbpf.png') && !has(lh, 'RBPF'));
check(R, 'hidden member gone from landing directory', !has(lh, 'rbpf@aseanapol.org') && !has(lh, '>Brunei Darussalam<'));
check(R, 'hidden member gone from contact directory', !has(ch3, 'rbpf@aseanapol.org') && !has(ch3, 'Brunei Darussalam'));
check(R, 'member counts follow: stat = 10, footer "ten", landing subtitle "Ten", contact "all 10"', /text-4xl[^>]*>10</.test(lh) && has(fh, 'forces of the ten ASEAN') && has(lh, 'Ten ASEAN member states') && has(ch3, 'all 10 member states'));
await openEditor(page, 'shared.members');
await page.locator(M(idxBn, 'key')).locator('xpath=ancestor::*[@data-row][1]').locator('input[name$="[_hide]"]').first().uncheck();
await save(page);
lh = await get('/en');
check(R, 'un-hiding brings the member and the counts back', has(lh, 'brunei-rbpf.png') && /text-4xl[^>]*>11</.test(lh) && has(await get('/en/news-media/news'), 'forces of the eleven ASEAN'));

// ---------- 4. Partner website in Shared -> landing partner card + footer link
const idxIp = await rowIndex('shared.partners', 'interpol');
await setField('shared.partners', M(idxIp, 'website'), 'https://qa-interpol.example.test/');
await setField('shared.partners', M(idxIp, 'full_name'), 'QA Interpol Full Name');
lh = await get('/en'); const fh2 = await get('/en/contact-us');
check(R, 'landing partner card follows the shared website (bare host: no trailing slash) and full name', has(lh, 'href="https://qa-interpol.example.test"') && has(lh, 'QA Interpol Full Name'));
check(R, 'footer partner link follows the shared website', has(fh2, 'href="https://qa-interpol.example.test"'));
// name edit propagates to both too
await setField('shared.partners', M(idxIp, 'short_name'), 'QAPOL');
check(R, 'short name edit shows in footer + landing card', has(await get('/en/contact-us'), '</span> QAPOL</a>') && has(await get('/en'), '>QAPOL</div>'));

// ---------- 5. Organisation facts -> vision quote, brand alt, stat numbers
await setField('shared.organisation', 'input[name="f[identity][tagline]"]', 'QA tagline here');
await setField('shared.organisation', 'input[name="f[identity][logo_alt]"]', 'QA Logo Alt');
await setField('shared.organisation', 'input[name="f[key_dates][founded_year]"]', '1999');
await setField('shared.organisation', 'input[name="f[key_dates][conference_count_label]"]', '77+');
lh = await get('/en');
check(R, 'vision quote follows shared tagline (quoted)', has(lh, '&quot;QA tagline here&quot;') || has(lh, '"QA tagline here"'));
check(R, 'header logo alt follows shared', has(lh, 'alt="QA Logo Alt"'));
check(R, 'stats founded year + conference count follow shared', /text-4xl[^>]*>77\+</.test(lh) && /text-4xl[^>]*>1999</.test(lh));

// ---------- 6. Non-English fallback still translates untouched shared-derived text (nothing edited in that sentence)
const idH = await get('/id');
check(R, 'Indonesian landing still shows translated member-count sentence', has(idH, 'Sebelas negara anggota ASEAN'));

// ---------- restore every original value (reverse order)
for (const [key, sel, orig] of restore.reverse()) {
  await openEditor(page, key);
  await page.fill(sel, orig);
  await save(page);
}
const fin = await get('/en'); const fin2 = await get('/en/contact-us');
check(R, 'restored: no QA text left on landing / contact', !/QA |qa-/.test(fin.replace(/qa-hero[^"']*/g, '')) && !/QA |qa-sec/.test(fin2));
finish(R, errors);
await browser.close();
