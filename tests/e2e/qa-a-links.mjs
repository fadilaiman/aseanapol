// QA-A: dead-link + console-error sweep over site.*, about.*, members.* pages (default render).
//   node tests/e2e/qa-a-links.mjs [prefix ...]
import { execSync } from 'node:child_process';
import { launch, check, finish, BASE } from './lib.mjs';

const prefixes = process.argv.slice(2).length ? process.argv.slice(2) : ['site.', 'about.', 'members.'];
const pages = JSON.parse(execSync('php artisan cms:pages --json', { encoding: 'utf8' }).trim().split('\n').pop())
  .filter(p => p.url && prefixes.some(f => p.key.startsWith(f)));
const { browser, page, errors } = await launch();
const R = [];
const seen = new Map();
const consoleErrs = [];
page.on('console', m => { if (m.type() === 'error') consoleErrs.push(m.text()); });
page.on('response', r => { if (r.url().startsWith(BASE) && r.status() >= 400) consoleErrs.push(`${r.status()} ${r.url()}`); });

for (const p of pages) {
  consoleErrs.length = 0;
  await page.goto(BASE + p.url, { waitUntil: 'networkidle' });
  const hrefs = await page.$$eval('a[href]', as => as.map(a => a.getAttribute('href')));
  for (const h of hrefs) {
    if (!h || h.startsWith('#') || /^(mailto|tel|javascript):/i.test(h)) continue;
    const abs = new URL(h, BASE + p.url).toString();
    const internal = abs.startsWith(BASE);
    if (!internal) continue; // external links are not probed (no network assumptions)
    if (!seen.has(abs)) seen.set(abs, { from: p.key, status: (await page.request.get(abs, { maxRedirects: 5 })).status() });
  }
  check(R, `${p.key}: no console errors / failed local requests`, consoleErrs.filter(e => !/favicon|ERR_|cdn|fonts|googleapis|gstatic|analytics/i.test(e)).length === 0, consoleErrs.join(' | ').slice(0, 300));
}
for (const [u, v] of seen) check(R, `link ${u.replace(BASE, '')} (from ${v.from})`, v.status < 400, 'HTTP ' + v.status);
finish(R, errors);
await browser.close();
