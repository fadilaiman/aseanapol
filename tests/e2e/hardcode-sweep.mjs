// Hardcode sweep: finds visible text / image src / link href on public pages that is NOT in the editable set
// (registry defaults + DB module text). Usage:
//   php tests/e2e/hardcode-dump.php <editable.json>
//   CMS_BASE=http://127.0.0.1:8314 EDITABLE=<editable.json> OUT=<report.json> node tests/e2e/hardcode-sweep.mjs [url-substring...]
// Short strings (<=60 chars) must equal a whole editable string/fragment (STRICT=0 relaxes to substring match).
import { chromium } from 'playwright';
import fs from 'fs';

const BASE = process.env.CMS_BASE || 'http://127.0.0.1:8314';
const EDITABLE = process.env.EDITABLE;
const OUT = process.env.OUT || 'hardcode-report.json';
const STRICT = process.env.STRICT !== '0';
const filter = process.argv.slice(2);

const decode = s => s.replace(/<[^>]*>/g, ' ').replace(/&nbsp;/g, ' ').replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace(/&gt;/g, '>')
  .replace(/&quot;/g, '"').replace(/&#0?39;|&apos;/g, "'").replace(/&#(\d+);/g, (_, n) => String.fromCharCode(+n)).replace(/&hellip;/g, '...');
const norm = s => decode(String(s)).normalize('NFKD').replace(/[̀-ͯ]/g, '').toLowerCase().replace(/[^\p{L}\p{N}]+/gu, ' ').trim();

const raw = JSON.parse(fs.readFileSync(EDITABLE, 'utf8'));
const items = raw.map(([s, src]) => [norm(s), src, s]);
const big = '\u0001' + items.map(i => i[0]).join('\u0001') + '\u0001';
const rawBig = raw.map(r => r[0]).join('\n');
const shorts = [...new Set(items.map(i => i[0]).filter(s => s.length >= 8 && s.length <= 400))];

const frags = new Set();
const splitter = /\{[^}]*\}|<\/?(?:p|li|br|h\d|div|tr|td|th|span|strong|em|a|b|i)\b[^>]*>|\r?\n|\||·/i;
for (const [, , r] of items) for (const part of String(r).split(splitter)) { const f = norm(part); if (f) frags.add(f); }

function editable(n) {
  if (!n) return true;
  if (n.length <= 60) { if (frags.has(n)) return true; if (STRICT) return false; }
  if (big.includes(n)) return true;
  for (const e of shorts) if (n.includes(e) && e.length >= n.length * 0.5) return true;
  return false;
}

let urls = fs.readFileSync('tests/e2e/urls.txt', 'utf8').split(/\r?\n/).map(s => s.trim()).filter(Boolean);
urls.push('/en/news-media/news/aseanapol-6p-ecosystem-approach-counter-scams-2026-10-06', '/en/news-media/news?q=zzqxnomatch',
  '/en/news-media/newsletters', '/en/news-media/video-gallery', '/en/news-media/gallery');
urls = [...new Set(urls)];
if (filter.length) urls = urls.filter(u => filter.some(f => u.includes(f)));

const extract = () => {
  const out = { text: [], imgs: [], links: [], meta: [] };
  const skip = new Set(['SCRIPT', 'STYLE', 'NOSCRIPT', 'TEMPLATE', 'HEAD']);
  const w = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT);
  let n;
  while ((n = w.nextNode())) {
    let p = n.parentElement, bad = false;
    while (p) { if (skip.has(p.tagName)) { bad = true; break; } p = p.parentElement; }
    const t = n.nodeValue.replace(/\s+/g, ' ').trim();
    if (n.parentElement && n.parentElement.closest('[class*="material-symbols"],[class*="material-icons"]')) bad = true;
    if (!bad && t) out.text.push(t);
  }
  document.querySelectorAll('img').forEach(i => out.imgs.push({ src: i.getAttribute('src') || '', alt: i.getAttribute('alt') || '' }));
  document.querySelectorAll('[style*="background-image"]').forEach(e => { const m = e.getAttribute('style').match(/url\(['"]?([^'")]+)/); if (m) out.imgs.push({ src: m[1], alt: '(bg)' }); });
  const lt = c => c.nodeType === 3 ? c.nodeValue : (c.nodeType === 1 && !/material-/.test(c.className || '') ? [...c.childNodes].map(lt).join(' ') : '');
  document.querySelectorAll('a[href]').forEach(a => out.links.push({ href: a.getAttribute('href'), text: [...a.childNodes].map(lt).join(' ').replace(/\s+/g, ' ').trim() }));
  document.querySelectorAll('[placeholder],[title],[aria-label],input[type=submit],input[type=button]').forEach(e => {
    for (const a of ['placeholder', 'title', 'aria-label', 'value']) { const v = e.getAttribute(a); if (v && (a !== 'value' || /submit|button/.test(e.type))) out.meta.push({ attr: a, v }); }
  });
  out.title = document.title;
  const d = document.querySelector('meta[name=description]'); out.desc = d ? d.content : '';
  const og = document.querySelector('meta[property="og:title"]'); out.og = og ? og.content : '';
  return out;
};

const LANGS = /^(English|Melayu|Indonesia|ภาษาไทย|Tiếng Việt|ភាសាខ្មែរ|ພາສາລາວ|မြန်မာ|Filipino|中文|Español|Русский)/;
const browser = await chromium.launch({ headless: true });
const ctx = await browser.newContext({ viewport: { width: 1400, height: 1000 } });
const page = await ctx.newPage();
const report = {};
for (const u of urls) {
  const resp = await page.goto(BASE + u, { waitUntil: 'domcontentloaded', timeout: 60000 }).catch(() => null);
  await page.waitForTimeout(250);
  if (!resp) { report[u] = { error: 'nav failed' }; continue; }
  const fin = new URL(page.url());
  const finalPath = fin.pathname + fin.search;
  if (resp.status() !== 200) { report[u] = { status: resp.status() }; console.log(u, 'STATUS', resp.status()); continue; }
  if (finalPath !== u && !u.includes('?')) { report[u] = { redirect: finalPath }; continue; }
  const d = await page.evaluate(extract);
  const seen = new Set(), text = [];
  for (const t of d.text) { const n = norm(t); if (!n || seen.has(n)) continue; seen.add(n); if (!editable(n)) text.push(t); }
  const meta = [];
  for (const [k, v] of [['title', d.title], ['description', d.desc], ['og:title', d.og], ...d.meta.map(m => [m.attr, m.v])]) { const n = norm(v || ''); if (n && !editable(n)) meta.push(k + ': ' + v); }
  const imgs = [], si = new Set();
  for (const i of d.imgs) {
    if (/flagcdn/.test(i.src)) continue;
    if (si.has(i.src)) continue; si.add(i.src);
    const path = i.src.replace(/^https?:\/\/[^/]+/, '').replace(/^\//, '').replace(/\?.*$/, '');
    const inSet = path && rawBig.includes(path);
    const altBad = i.alt && !editable(norm(i.alt));
    if (!inSet || altBad) imgs.push({ src: i.src, alt: i.alt, srcEditable: !!inSet });
  }
  const links = [], sl = new Set();
  for (const l of d.links) {
    const k = l.href + '|' + l.text; if (sl.has(k)) continue; sl.add(k);
    if (/^(#|javascript:)/.test(l.href)) continue;
    if (/^https?:\/\/[^/]+\/(en|ms|id|th|vi|lo|km|my|tl|zh|es|ru)(\/|$)/.test(l.href) && LANGS.test(l.text)) continue;
    const mt = /^(mailto|tel):/.test(l.href);
    const href = l.href.replace(/^(mailto|tel):/, '');
    const ext = /^(https?:)?\/\//.test(href) && !href.startsWith(BASE);
    const isFile = /\.(pdf|docx?|xlsx?|pptx?|zip|png|jpe?g)(\?|$)/i.test(href) || mt;
    const textBad = l.text && !editable(norm(l.text));
    if (!ext && !isFile) { if (textBad) links.push({ href, text: l.text, hrefEditable: true }); continue; }
    const path = href.replace(/^https?:\/\/[^/]+/, '').replace(/^\//, '');
    const hrefOk = !path || rawBig.includes(path) || rawBig.includes(href);
    if (!hrefOk || textBad) links.push({ href, text: l.text, hrefEditable: hrefOk });
  }
  report[u] = { text, meta, imgs, links };
  console.log(`${u}  text:${text.length} meta:${meta.length} imgs:${imgs.length} links:${links.length}`);
}
await browser.close();
fs.writeFileSync(OUT, JSON.stringify(report, null, 1));
