// QA-A exploratory/regression tests for site.*, about.*, members.*
//   node tests/e2e/qa-a.mjs [prefix ...]      (default prefixes: site. about. members.)
// Modes per page: blank (all scalars empty, all repeater rows removed), hostile (special chars/long words),
// hide-all-toggles. Asserts: HTTP 200, no PHP error text, no script execution, no horizontal overflow at 390px
// (relative to the default render). Always reverts the pages it touched.
import { execSync } from 'node:child_process';
import fs from 'node:fs';
import { launch, login, check, finish, openEditor, save, revertPage, BASE } from './lib.mjs';

const prefixes = process.argv.slice(2).length ? process.argv.slice(2) : ['site.', 'about.', 'members.'];
const SHOTS = process.env.QA_SHOTS || '';
if (SHOTS) fs.mkdirSync(SHOTS, { recursive: true });
const pages = JSON.parse(execSync('php artisan cms:pages --json', { encoding: 'utf8' }).trim().split('\n').pop())
  .filter(p => p.url && prefixes.some(f => p.key.startsWith(f)));

const HOSTILE = `<script>window.__xss=1</script><img src=x onerror="window.__xss=1"> & "dq" 'sq' 😀 مرحبا بالعالم สวัสดีครับ ` + 'W'.repeat(240);
const ERR_RE = /Whoops|Undefined (variable|array key|index)|Trying to access array offset|Call to undefined|ErrorException|Attempt to read property|Illuminate\\|htmlspecialchars\(\)/;

const { browser, context, page, errors } = await launch();
const R = [];
await login(page);

const SCALAR = ['text', 'textarea', 'url', 'icon', 'html', 'list', 'richtext', 'image', 'file', 'number', 'date'];

async function fillAll(p, mode) {
  await openEditor(page, p.key);
  await page.evaluate(({ mode, HOSTILE }) => {
    const val = mode === 'blank' ? '' : null;
    if (mode === 'blank') {
      // remove all repeater rows (top level; nested go with them)
      document.querySelectorAll('.cms-rows > [data-row]').forEach(r => r.remove());
    }
    document.querySelectorAll('#cms-form .cms-field').forEach(f => {
      if (f.closest('template')) return;
      const t = f.dataset.type;
      const el = f.querySelector('input[name^="f["]:not([type=checkbox]):not([type=hidden]):not([type=file]), textarea[name^="f["], select[name^="f["], input[type=hidden].x-rt');
      if (t === 'repeater' || t === 'boolean' || t === 'select' || t === 'number' || t === 'date') return;
      if (t === 'richtext') {
        const h = f.querySelector('input[type=hidden]');
        if (h) h.value = mode === 'blank' ? '' : '<p>' + HOSTILE.replace(/<[^>]*>/g, '') + '</p>';
        return;
      }
      if (!el) return;
      if (t === 'image' || t === 'file') { el.value = mode === 'blank' ? '' : 'media/none-' + Math.random().toString(36).slice(2) + '.png'; return; }
      if (t === 'url') { el.value = mode === 'blank' ? '' : 'https://example.test/?a=1&b="2"'; return; }
      if (t === 'icon') { el.value = mode === 'blank' ? '' : 'nonexistent_icon_zz'; return; }
      el.value = mode === 'blank' ? '' : HOSTILE;
    });
  }, { mode, HOSTILE });
  await save(page);
}

async function inspect(p, tag, baseOverflow) {
  const out = {};
  for (const [w, h] of [[1400, 900], [390, 800]]) {
    const pg = await context.newPage();
    await pg.setViewportSize({ width: w, height: h });
    const errs = [];
    pg.on('pageerror', e => errs.push(e.message));
    const resp = await pg.goto(BASE + p.url, { waitUntil: 'load' });
    const html = await pg.content();
    check(R, `${p.key} [${tag} @${w}]: HTTP 200`, resp.status() === 200, 'status ' + resp.status());
    check(R, `${p.key} [${tag} @${w}]: no PHP error text`, !ERR_RE.test(html), (html.match(ERR_RE) || [])[0]);
    check(R, `${p.key} [${tag} @${w}]: no script executed`, !(await pg.evaluate(() => window.__xss)), 'XSS executed');
    check(R, `${p.key} [${tag} @${w}]: no page JS errors`, errs.length === 0, errs.join(' | '));
    const ov = await pg.evaluate(() => document.documentElement.scrollWidth - window.innerWidth);
    out[w] = ov;
    if (w === 390) check(R, `${p.key} [${tag} @390]: no new horizontal overflow`, ov <= Math.max(1, (baseOverflow ?? 0) + 1), `overflow ${ov}px (default ${baseOverflow})`);
    if (SHOTS) await pg.screenshot({ path: `${SHOTS}/${p.key}-${tag}-${w}.png`, fullPage: true });
    await pg.close();
  }
  return out;
}

if (process.env.QA_LEAVE) { await revertPage(page, pages[0].key); await fillAll(pages[0], 'hostile'); await browser.close(); process.exit(0); }
for (const p of pages) {
  try {
    console.log(`\n=== ${p.key} (${p.url})`);
    await revertPage(page, p.key);
    const base = await inspect(p, 'default');
    for (const mode of ['blank', 'hostile']) {
      await fillAll(p, mode);
      await inspect(p, mode, base[390]);
      await revertPage(page, p.key);
    }
    if (p.toggles.length) {
      await openEditor(page, p.key);
      for (const t of p.toggles) await page.uncheck(`[data-testid=vis-${t}]`);
      await save(page);
      await inspect(p, 'hide-all-sections', base[390]);
      await revertPage(page, p.key);
    }
  } catch (e) {
    check(R, `${p.key}: crashed`, false, String(e.message).split(/\r?\n/)[0]);
    try { await revertPage(page, p.key); } catch {}
  }
}
finish(R, errors);
await browser.close();
