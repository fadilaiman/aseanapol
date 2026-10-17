// Snapshot the live site's public pages (status + normalized text) into a folder, or compare to one.
//   node tests/e2e/prodsnap.mjs save  <dir> [base]
//   node tests/e2e/prodsnap.mjs check <dir> [base]
import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
const here = path.dirname(fileURLToPath(import.meta.url));
const [mode, dir, baseArg] = process.argv.slice(2);
const BASE = baseArg || 'https://aseanapol.org';
const urls = fs.readFileSync(path.join(here, 'urls.txt'), 'utf8').split(/\r?\n/).filter(Boolean);
fs.mkdirSync(dir, { recursive: true });
const norm = h => h.replace(/<meta name="csrf-token"[^>]*>/g, '').replace(/name="_token" value="[^"]*"/g, '')
  .replace(/ integrity="[^"]*"/g, '').replace(/ crossorigin="[^"]*"/g, '')
  .replace(/\/build\/assets\/([a-z0-9-]+?)-[A-Za-z0-9_-]{6,}\.(css|js)/gi, '/build/assets/$1.$2')
  .replace(/<script[\s\S]*?<\/script>/g, '').replace(/<style[\s\S]*?<\/style>/g, '')
  .replace(/<[^>]+>/g, '\n').replace(/&[a-z#0-9]+;/gi, ' ').replace(/[ \t]+/g, ' ').replace(/\n\s*/g, '\n').trim();
const f = u => path.join(dir, u.replace(/[^a-z0-9]+/gi, '_') + '.txt');
let changed = 0, errs = 0, i = 0;
for (const u of urls) {
  i++;
  let status = 0, body = '';
  try {
    const r = await fetch(BASE + u + (u.includes('?') ? '&' : '?') + 'cb=' + Date.now(), { redirect: 'manual', headers: { 'Cache-Control': 'no-cache' } });
    status = r.status; body = norm(await r.text());
  } catch (e) { status = -1; errs++; }
  const cur = `STATUS ${status}\n${body}`;
  if (mode === 'save') { fs.writeFileSync(f(u), cur); continue; }
  const old = fs.existsSync(f(u)) ? fs.readFileSync(f(u), 'utf8') : null;
  if (old === null) { console.log('NOBASE ' + u); continue; }
  if (old === cur) continue;
  changed++;
  const a = old.split('\n'), b = cur.split('\n');
  let k = 0; while (k < a.length && a[k] === b[k]) k++;
  console.log(`CHANGED ${u}  status ${old.split('\n')[0]} -> STATUS ${status}\n   was: ${(a[k] || '').slice(0, 110)}\n   now: ${(b[k] || '').slice(0, 110)}`);
}
console.log(mode === 'save' ? `saved ${urls.length} pages (${errs} fetch errors)` : `\n${changed} of ${urls.length} pages changed (${errs} fetch errors)`);
