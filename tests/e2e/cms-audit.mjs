// Generic CMS wiring audit.  node tests/e2e/cms-audit.mjs [filter ...]
// For each registered page (optionally filtered by key substring):
//   1. noop    : open editor, save untouched -> must create ZERO override rows and leave the public page unchanged
//   2. marker  : put a unique marker in every scalar field + first row of each repeater -> every marker must appear on the live page
//   3. toggle  : hide each toggleable section -> live HTML must change; show again
//   4. repeater: hide / remove the first row of each repeater -> live HTML must change
//   5. revert  : revert page -> live HTML equals the original default HTML
import { execSync } from 'node:child_process';
import { launch, login, check, finish, openEditor, save, revertPage, BASE } from './lib.mjs';

const filters = process.argv.slice(2);
const pages = JSON.parse(execSync('php artisan cms:pages --json', { encoding: 'utf8' }).trim().split('\n').pop())
  .filter(p => p.url && (!filters.length || filters.some(f => p.key.includes(f))));
const norm = h => h.replace(/tracking-tight">\s*[\d,]+\s*</g, 'tracking-tight">N<').replace(/\bdata = \[[^\]]*\]/g, 'data = []').replace(/\blabels = \[[^\]]*\]/g, 'labels = []').replace(/<meta name="csrf-token"[^>]*>/g, '').replace(/name="_token" value="[^"]*"/g, '')
  .replace(/(Total Visitors|Today(?:'|&#039;)s Visitors)<\/p>\s*<p class="[^"]*">[\d,]+<\/p>/g, '$1 N')
  .replace(/const labels = \[[^\]]*\];\s*const data = \[[^\]]*\];/g, 'CHART').replace(/\s+/g, ' ');
const get = async (page, url) => norm(await (await page.request.get(BASE + url)).text());
const SCALAR = new Set(['text', 'textarea', 'url', 'icon', 'html', 'list', 'image', 'file', 'richtext']);

const { browser, page, errors } = await launch();
const R = [];
await login(page);

for (const p of pages) { try {
  console.log(`\n=== ${p.key}  (${p.url})`);
  await revertPage(page, p.key);
  const original = await get(page, p.url);

  // 1. noop save
  await openEditor(page, p.key);
  await save(page);
  check(R, `${p.key}: no-op save leaves page unchanged`, (await get(page, p.url)) === original, 'public HTML changed after saving untouched form');
  await openEditor(page, p.key);
  const badge = await page.locator('text=/\d+ customised/').count();
  check(R, `${p.key}: no-op save stores no override rows`, badge === 0, 'override rows were created by an untouched save');

  // 2. markers
  await openEditor(page, p.key);
  const markers = await page.evaluate(({ schema }) => {
    const out = [];
    let n = 0;
    const setVal = (el, v) => {
      if (el.type === 'hidden' && el.nextElementSibling && el.nextElementSibling.classList.contains('cms-rt')) {
        el.value = '<p>' + v + '</p>';
      } else el.value = v;
    };
    for (const [sec, fields] of Object.entries(schema)) {
      for (const [f, def] of Object.entries(fields)) {
        if (def.skip) continue;
        if (def.sub === null && ['text', 'textarea', 'url', 'icon', 'html', 'list', 'image', 'file', 'richtext'].includes(def.type)) {
          const el = document.querySelector(`[name="f[${sec}][${f}]"]`);
          if (!el) continue;
          const m = 'QAM' + (++n) + 'X';
          const val = def.type === 'image' || def.type === 'file' ? 'media/qa-audit/' + m + '.png' : def.type === 'url' ? 'https://example.test/' + m : m;
          setVal(el, val);
          out.push({ name: `${sec}.${f}`, marker: m });
        } else if (def.sub) {
          // first row of repeater
          const rep = document.querySelector(`[name="f[${sec}][${f}][__present]"]`)?.closest('[data-repeater]');
          if (!rep) continue;
          let row = rep.querySelector(':scope > .cms-rows > [data-row]');
          if (!row) { rep.querySelector('[data-act=add-row]').click(); row = rep.querySelector(':scope > .cms-rows > [data-row]'); }
          for (const [k, t] of Object.entries(def.sub)) {
            if ((def.subskip || []).includes(k)) continue;
            if (!['text', 'textarea', 'url', 'icon', 'html', 'list', 'image', 'file', 'richtext'].includes(t)) continue;
            const el = row.querySelector(`[name$="[${k}]"]`);
            if (!el) continue;
            const m = 'QAM' + (++n) + 'X';
            setVal(el, t === 'image' || t === 'file' ? 'media/qa-audit/' + m + '.png' : t === 'url' ? 'https://example.test/' + m : m);
            out.push({ name: `${sec}.${f}[0].${k}`, marker: m });
          }
        }
      }
    }
    return out;
  }, { schema: p.schema });
  await save(page);
  const marked = await get(page, p.url);
  for (const m of markers) check(R, `${p.key}: field ${m.name} is wired to the page`, marked.includes(m.marker), 'marker not found on public page (view does not use this field)');
  await revertPage(page, p.key);

  // 3. toggles
  for (const sec of p.toggles) {
    await openEditor(page, p.key);
    await page.uncheck(`[data-testid=vis-${sec}]`);
    await save(page);
    const hid = await get(page, p.url);
    check(R, `${p.key}: hiding section '${sec}' changes the page`, hid !== original, 'page identical with section hidden');
    await revertPage(page, p.key);
  }

  // 4. repeater first row hide
  for (const [sec, fields] of Object.entries(p.schema)) {
    for (const [f, def] of Object.entries(fields)) {
      if (!def.sub || !def.rows) continue;
      await openEditor(page, p.key);
      const rep = page.locator(`[name="f[${sec}][${f}][__present]"]`).locator('xpath=ancestor::*[@data-repeater][1]');
      await rep.locator(':scope > .cms-rows > [data-row]').first().locator('input[name$="[_hide]"]').first().check();
      await save(page);
      check(R, `${p.key}: hiding first '${sec}.${f}' row changes the page`, (await get(page, p.url)) !== original, 'page identical with row hidden');
      await revertPage(page, p.key);
    }
  }

  // 5. revert
  check(R, `${p.key}: revert restores the default page`, (await get(page, p.url)) === original, 'page differs from original after revert');
} catch (e) { check(R, `${p.key}: audit crashed`, false, String(e.message).split(/\r?\n/)[0]); try { await page.goto(BASE + "/admin"); if (page.url().includes("login")) await login(page); } catch {} } }
finish(R, errors);
await browser.close();
