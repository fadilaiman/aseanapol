// Default-render regression guard.
//   node tests/e2e/regress.mjs baseline            -> (re)write baseline snapshots from the current code
//   node tests/e2e/regress.mjs check [substr...]   -> compare current render with the baseline (all urls, or urls containing any substr)
// IMPORTANT: run `php artisan tinker --execute="DB::table('cms_blocks')->truncate();"` first so pages render pure defaults.
import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
const here = path.dirname(fileURLToPath(import.meta.url));
const BASE = process.env.CMS_BASE || 'http://127.0.0.1:8123';
const dir = path.join(here, 'baseline');
const mode = process.argv[2] || 'check';
const filters = process.argv.slice(3);
const urls = fs.readFileSync(path.join(here, 'urls.txt'), 'utf8').split(/\r?\n/).filter(Boolean)
  .filter(u => !filters.length || filters.some(f => u.includes(f)));
fs.mkdirSync(dir, { recursive: true });

export function normalize(html) {
  return html
    .replace(/<meta name="csrf-token"[^>]*>/g, '')
    .replace(/name="_token" value="[^"]*"/g, '')
    .replace(/http:\/\/127\.0\.0\.1:\d+/g, 'HOST').replace(/http:\/\/aseanapol\.test/g, 'HOST')
    .replace(/&mdash;/g, '—').replace(/&ndash;/g, '–').replace(/&rsquo;/g, '’').replace(/&lsquo;/g, '‘').replace(/&ldquo;/g, '“').replace(/&rdquo;/g, '”')
    .replace(/&nbsp;/g, ' ').replace(/&copy;/g, '©').replace(/&hellip;/g, '…').replace(/&middot;/g, '·').replace(/&times;/g, '×').replace(/&bull;/g, '•').replace(/&#x27;/g, "'")
    .replace(/&quot;/g, '"').replace(/&#039;/g, "'").replace(/&#39;/g, "'").replace(/&amp;/g, '&')
    .replace(/(Total Visitors|Today's Visitors)<\/p>\s*<p class="[^"]*">[\d,]+<\/p>/g, '$1 N')
    .replace(/const labels = \[[^\]]*\];\s*const data = \[[^\]]*\];/g, 'CHART')
    .replace(/As of \d+ \w+ \d{4}/g, 'As of D')
    .replace(/\s+/g, ' ').replace(/> </g, '>\n<').trim();
}
const fname = u => u.replace(/[^a-z0-9]+/gi, '_') + '.html';

let bad = 0;
for (const u of urls) {
  const r = await fetch(BASE + u, { redirect: 'manual' });
  const bodyRaw = normalize(`STATUS ${r.status} ${r.headers.get('location') || ''}\n` + (await r.text()));
  const f = path.join(dir, fname(u));
  if (mode === 'baseline') { fs.writeFileSync(f, bodyRaw); continue; }
  if (!fs.existsSync(f)) { console.log('NOBASE ' + u); continue; }
  // Vite asset hashes change on every front-end rebuild; they are not content.
  const sb = t => t.replace(/ integrity="[^"]*"/g, '').replace(/ crossorigin="[^"]*"/g, '').replace(/\/build\/assets\/([a-z0-9-]+?)-[A-Za-z0-9_-]{6,}\.(css|js)/gi, '/build/assets/$1.$2');
  const base = sb(fs.readFileSync(f, 'utf8'));
  const body = sb(bodyRaw);
  if (base === body) { console.log('SAME   ' + u); continue; }
  bad++;
  const a = base.split('\n'), b = body.split('\n');
  let i = 0; while (i < a.length && a[i] === b[i]) i++;
  console.log(`DIFF   ${u}  (first difference at line ${i})\n   base: ${(a[i] || '').slice(0, 160)}\n   now : ${(b[i] || '').slice(0, 160)}`);
}
if (mode === 'baseline') console.log(`baseline written for ${urls.length} urls`);
else console.log(`\n${urls.length - bad}/${urls.length} identical to baseline`);
process.exit(bad ? 1 : 0);
