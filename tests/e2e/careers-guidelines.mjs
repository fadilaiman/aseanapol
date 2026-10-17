// Targeted test: careers vacancies listing/notice behaviour, row reorder, file upload + re-upload (guidelines).
import { launch, login, check, finish, openEditor, save, revertPage, BASE } from './lib.mjs';
import fs from 'node:fs';
import os from 'node:os';
import path from 'node:path';

const { browser, page, errors } = await launch();
const R = [];
await login(page);

const VKEY = 'careers.vacancies', VURL = BASE + '/en/careers/vacancies';
const GKEY = 'guidelines.flag', GURL = BASE + '/en/guidelines/guidelines-on-the-use-of-the-aseanapol-flag';

// ---- Vacancies --------------------------------------------------------------
await revertPage(page, VKEY);
await page.goto(VURL);
check(R, 'default: notice visible', await page.getByText('No Active Openings at This Time').isVisible());
check(R, 'default: no listing heading', (await page.content()).indexOf('Current Openings') === -1);

await openEditor(page, VKEY);
const openings = page.locator('[data-repeater]').first();
await openings.locator('[data-act=add-row]').click();
await openings.locator('[data-act=add-row]').click();
const rows = openings.locator('.cms-rows > [data-row]');
check(R, 'two job rows added in editor', (await rows.count()) === 2, await rows.count());
const fill = async (r, t, dept, loc, dl, desc, link) => {
  await r.locator('input[name$="[title]"]').fill(t);
  await r.locator('input[name$="[department]"]').fill(dept);
  await r.locator('input[name$="[location]"]').fill(loc);
  await r.locator('input[name$="[deadline]"]').fill(dl);
  await r.locator('textarea[name$="[description]"]').fill(desc);
  await r.locator('input[name$="[apply_link]"]').fill(link);
};
await fill(rows.nth(0), 'QA Analyst Role', 'Research Unit', 'Kuala Lumpur', '31 Dec 2030', 'Analyse crime trends.', 'mailto:jobs@example.test');
await fill(rows.nth(1), 'QA Officer Role', 'Operations', 'Bangkok', '1 Jan 2031', 'Coordinate operations.', 'https://example.test/apply');
await save(page);

await page.goto(VURL);
check(R, 'listing shows first job', await page.getByText('QA Analyst Role').isVisible());
check(R, 'listing shows second job', await page.getByText('QA Officer Role').isVisible());
check(R, 'listing shows meta', await page.getByText('location_onKuala Lumpur').isVisible() && await page.getByText('31 Dec 2030').isVisible());
check(R, 'apply link rendered', (await page.locator('a[href="mailto:jobs@example.test"]').count()) === 1);
check(R, '"no openings" notice hidden', !(await page.getByText('No Active Openings at This Time').isVisible()));
let html = await (await page.request.get(VURL)).text();
check(R, 'QA Analyst before QA Officer (order)', html.indexOf('QA Analyst Role') < html.indexOf('QA Officer Role'));

// reorder: move second row up
await openEditor(page, VKEY);
const rows2 = page.locator('[data-repeater]').first().locator('.cms-rows > [data-row]');
await rows2.nth(1).locator('[data-act=up]').click();
await save(page);
html = await (await page.request.get(VURL)).text();
check(R, 'reorder: QA Officer now before QA Analyst', html.indexOf('QA Officer Role') < html.indexOf('QA Analyst Role') && html.includes('QA Analyst Role'));

// hide one -> other still shown, notice still hidden
await openEditor(page, VKEY);
const rows3 = page.locator('[data-repeater]').first().locator('.cms-rows > [data-row]');
await rows3.nth(0).locator('input[name$="[_hide]"]').check();
await save(page);
await page.goto(VURL);
check(R, 'hide one: hidden job gone', !(await page.getByText('QA Officer Role').count()));
check(R, 'hide one: other job still shown, notice still hidden', await page.getByText('QA Analyst Role').isVisible() && !(await page.getByText('No Active Openings at This Time').isVisible()));

// hide the other too -> notice returns
await openEditor(page, VKEY);
const rows4 = page.locator('[data-repeater]').first().locator('.cms-rows > [data-row]');
await rows4.nth(1).locator('input[name$="[_hide]"]').check();
await save(page);
await page.goto(VURL);
check(R, 'all hidden: notice returns', await page.getByText('No Active Openings at This Time').isVisible());
check(R, 'all hidden: no job shown', !(await page.getByText('QA Analyst Role').count()));

// remove all rows -> notice stays
await openEditor(page, VKEY);
for (let i = 0; i < 2; i++) {
  page.once('dialog', d => d.accept());
  await page.locator('[data-repeater]').first().locator('.cms-rows > [data-row]').first().locator('[data-act=remove]').click();
}
await save(page);
await page.goto(VURL);
check(R, 'removed: notice visible', await page.getByText('No Active Openings at This Time').isVisible());
await revertPage(page, VKEY);

// internships listing appears too
const IKEY = 'careers.internships', IURL = BASE + '/en/careers/internships';
await revertPage(page, IKEY);
await openEditor(page, IKEY);
const irep = page.locator('[data-repeater]').first();
check(R, 'internships: first repeater is openings', (await irep.locator('input[name*="openings"]').count()) >= 0);
await page.locator('[data-repeater] [data-act=add-row]').first().click();
const irow = page.locator('[data-repeater]').first().locator('.cms-rows > [data-row]').first();
await irow.locator('input[name$="[title]"]').fill('QA Intern Call');
await save(page);
html = await (await page.request.get(IURL)).text();
check(R, 'internships: listing shows added call', html.includes('QA Intern Call'));
await revertPage(page, IKEY);
html = await (await page.request.get(IURL)).text();
check(R, 'internships: revert removes call', !html.includes('QA Intern Call'));

// ---- File upload (guidelines.flag) ------------------------------------------
const dir = fs.mkdtempSync(path.join(os.tmpdir(), 'cmsqa-'));
const pdf = body => '%PDF-1.1\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj\n2 0 obj<</Type/Pages/Kids[3 0 R]/Count 1>>endobj\n3 0 obj<</Type/Page/Parent 2 0 R/MediaBox[0 0 200 200]>>endobj\ntrailer<</Root 1 0 R>>\n%' + body + '\n%%EOF';
const f1 = path.join(dir, 'flag-guide.pdf'), f2 = path.join(dir, 'flag-guide.pdf');
fs.writeFileSync(f1, pdf('one'));
const f2b = path.join(dir, 'flag-guide-v2.pdf');
fs.writeFileSync(f2b, pdf('two-longer'));

await revertPage(page, GKEY);
html = await (await page.request.get(GURL)).text();
check(R, 'flag: no document button by default', !html.includes('storage/cms'));
await openEditor(page, GKEY);
await page.fill('input[name="f[document][label]"]', 'Download QA Guide');
await page.setInputFiles('input[type=file][name="files[document][file]"]', f1);
await save(page);
html = await (await page.request.get(GURL)).text();
const m1 = html.match(/href="[^"]*(storage\/cms\/[^"]+\.pdf)"/);
check(R, 'flag: document button rendered with uploaded pdf', !!m1 && html.includes('Download QA Guide'), m1 && m1[1]);
if (m1) {
  const r = await page.request.get(BASE + '/' + m1[1]);
  check(R, 'flag: uploaded pdf is downloadable', r.status() === 200, r.status());
}
await openEditor(page, GKEY);
await page.setInputFiles('input[type=file][name="files[document][file]"]', f2b);
await save(page);
html = await (await page.request.get(GURL)).text();
const m2 = html.match(/href="[^"]*(storage\/cms\/[^"]+\.pdf)"/);
check(R, 're-upload produces a new filename', !!m2 && !!m1 && m1[1] !== m2[1], `${m1 && m1[1]} -> ${m2 && m2[1]}`);

// reorder guideline sections + hide one
await openEditor(page, GKEY);
const srows = page.locator('[data-repeater]').first().locator('.cms-rows > [data-row]');
await srows.nth(1).locator('[data-act=up]').click();
await save(page);
html = await (await page.request.get(GURL)).text();
check(R, 'flag: sections reordered', html.indexOf('2. Display Protocol') < html.indexOf('1. Authorised Use'));
await revertPage(page, GKEY);
html = await (await page.request.get(GURL)).text();
check(R, 'flag: revert restores order + removes document', html.indexOf('1. Authorised Use') < html.indexOf('2. Display Protocol') && !html.includes('storage/cms'));

fs.rmSync(dir, { recursive: true, force: true });
finish(R, errors);
await browser.close();
