// Admin hardening checks. Run: CMS_BASE=http://127.0.0.1:8432 node tests/e2e/security-admin.mjs
// Uses plain fetch (no browser) so it never logs in and cannot trip the QA login throttle with qa-admin.
import fs from 'node:fs';
import { BASE, check, finish } from './lib.mjs';

const results = [];
const get = (p, opts = {}) => fetch(BASE + p, { redirect: 'manual', ...opts });
const norm = s => s.replace(/<meta name="csrf-token"[^>]*>/g, '').replace(/name="_token" value="[^"]*"/g, '')
  .replace(/\s+/g, ' ').replace(/\/admin\/[^\s"'<>]*/g, '/admin/X');

// 1. robots.txt
const robots = await (await get('/robots.txt')).text();
check(results, 'robots disallows /admin', /Disallow:\s*\/admin\b/.test(robots), robots);
check(results, 'robots lists sitemap', /Sitemap:\s*\S+\/sitemap\.xml/.test(robots), robots);
check(results, 'robots does not block everything', !/Disallow:\s*\/\s*$/m.test(robots));

// 2. guests get a plain 404 identical to a non-existent admin URL
const ref = await get('/admin/no-such-page-xyz');
const refBody = norm(await ref.text());
check(results, 'unknown /admin path is 404', ref.status === 404, ref.status);
const adminUrls = ['/admin', '/admin/', '/admin/news', '/admin/news/create', '/admin/newsletters', '/admin/video-gallery',
  '/admin/site-content', '/admin/site-content/about.vision-mission', '/admin/site-content/nope.nope', '/admin/events',
  '/admin/publications', '/admin/digital-library/items', '/admin/does/not/exist'];
for (const u of adminUrls) {
  const r = await get(u);
  const body = norm(await r.text());
  check(results, `guest ${u} -> 404, no redirect`, r.status === 404 && !r.headers.get('location'), `${r.status} ${r.headers.get('location')}`);
  check(results, `guest ${u} body == unknown-path body`, body === refBody, 'body differs');
  check(results, `guest ${u} X-Robots-Tag + no-store`, /noindex/.test(r.headers.get('x-robots-tag') || '') && /no-store/.test(r.headers.get('cache-control') || ''),
    `${r.headers.get('x-robots-tag')} | ${r.headers.get('cache-control')}`);
}
const post = await get('/admin/news', { method: 'POST' });
const postRef = await get('/admin/no-such-page-xyz', { method: 'POST' });
check(results, 'guest POST /admin/news same status as POST to unknown path', post.status === postRef.status && !post.headers.get('location'), `${post.status} vs ${postRef.status}`);
const head = await get('/admin/news', { method: 'HEAD' });
check(results, 'HEAD /admin/news -> 404', head.status === 404, head.status);
const opt = await get('/admin/news', { method: 'OPTIONS' });
check(results, 'OPTIONS /admin/news is not 5xx', opt.status < 500, opt.status);

// 3. login page
const lp = await get('/admin/login');
const lhtml = await lp.text();
check(results, 'login 200', lp.status === 200, lp.status);
check(results, 'login noindex header', /noindex/.test(lp.headers.get('x-robots-tag') || ''));
check(results, 'login no-store', /no-store/.test(lp.headers.get('cache-control') || ''));
check(results, 'login robots meta', /<meta name="robots" content="noindex,nofollow,noarchive">/.test(lhtml));
const sc = lp.headers.get('set-cookie') || '';
check(results, 'session cookie HttpOnly + SameSite=lax', /httponly/i.test(sc) && /samesite=lax/i.test(sc), sc.replace(/=[^;]{40,}/, '=…'));

// 4. throttling & enumeration (disposable usernames; qa-admin is never used here)
async function attempt(username, jar) {
  const page = await get('/admin/login', { headers: { cookie: jar.c } });
  jar.c = (page.headers.getSetCookie?.() || []).map(c => c.split(';')[0]).join('; ') || jar.c;
  const token = (await page.text()).match(/name="_token" value="([^"]+)"/)[1];
  const t0 = Date.now();
  const r = await get('/admin/login', {
    method: 'POST', headers: { cookie: jar.c, 'content-type': 'application/x-www-form-urlencoded' },
    body: new URLSearchParams({ _token: token, username, password: 'wrong-password-xyz' }),
  });
  const ms = Date.now() - t0;
  let text = '';
  if (r.status === 302) {
    const f = await get(new URL(r.headers.get('location')).pathname, { headers: { cookie: (r.headers.getSetCookie?.() || []).map(c => c.split(';')[0]).join('; ') || jar.c } });
    text = await f.text();
    const nc = (r.headers.getSetCookie?.() || []).map(c => c.split(';')[0]).join('; ');
    if (nc) jar.c = nc;
  } else text = await r.text();
  const msg = (text.match(/bg-red-50[^>]*>\s*([^<]+?)\s*</) || [])[1] || '';
  return { status: r.status, ms, msg };
}
const stamp = Date.now();
const jar1 = { c: '' }, jar2 = { c: '' };
const known = [], unknown = [];
for (let i = 0; i < 3; i++) {
  known.push(await attempt('admin', jar1));
  unknown.push(await attempt('nouser-' + stamp, jar2));
}
check(results, 'wrong-password message identical for known/unknown user', known[0].msg && known[0].msg === unknown[0].msg, `${known[0].msg} / ${unknown[0].msg}`);
const avg = a => a.reduce((s, x) => s + x.ms, 0) / a.length;
check(results, 'timing roughly equal (unknown vs known)', Math.abs(avg(known) - avg(unknown)) < Math.max(150, avg(known) * 1.0), `${avg(known).toFixed(0)}ms vs ${avg(unknown).toFixed(0)}ms`);
let throttled = null;
const jar3 = { c: '' };
for (let i = 0; i < 8 && !throttled; i++) {
  const r = await attempt('throttle-' + stamp, jar3);
  if (r.status === 429) throttled = r;
}
check(results, 'repeated wrong logins -> 429', !!throttled);
check(results, '429 message generic (no user/lockout detail)', throttled && /too many attempts/i.test(throttled.msg) && !/throttle-/.test(throttled.msg), throttled && throttled.msg);

// 5. sitemap
const sm = await get('/sitemap.xml');
const xml = await sm.text();
check(results, 'sitemap 200 xml', sm.status === 200 && /xml/.test(sm.headers.get('content-type') || ''), sm.status);
check(results, 'sitemap is urlset', /^<\?xml[^>]*\?>\s*<urlset [^>]*sitemaps\.org/.test(xml));
const locs = [...xml.matchAll(/<loc>([^<]+)<\/loc>/g)].map(m => m[1].replace(/&apos;/g, "'").replace(/&amp;/g, '&'));
check(results, 'sitemap has URLs', locs.length > 50, locs.length);
check(results, 'sitemap contains no /admin', !/\/admin/i.test(xml));
check(results, 'sitemap only English public URLs', locs.every(l => /^https?:\/\/[^/]+\/en(\/|$)/.test(l)));
let bad = [];
// All static pages + a sample of every 10th news article (full crawl is slow on `artisan serve`).
const news = locs.filter(l => l.includes('/news-media/news/'));
const sample = locs.filter(l => !news.includes(l) || news.indexOf(l) % 10 === 0);
for (const l of sample) {
  const r = await fetch(BASE + new URL(l).pathname, { redirect: 'manual' });
  await r.arrayBuffer();
  if (r.status !== 200) bad.push(l + ' ' + r.status);
}
check(results, 'all sitemap URLs return 200', bad.length === 0, bad.slice(0, 5).join('; '));

// 6. public site never links to admin
for (const u of fs.readFileSync(new URL('./urls.txt', import.meta.url), 'utf8').split(/\r?\n/).filter(Boolean).slice(0, 25)) {
  const h = await (await get(u)).text();
  if (/href=["'][^"']*\/admin/i.test(h)) check(results, `public ${u} links to /admin`, false, 'found');
}
check(results, 'public pages (sample) never link to /admin', !results.some(r => /links to \/admin/.test(r.name)));

finish(results);
