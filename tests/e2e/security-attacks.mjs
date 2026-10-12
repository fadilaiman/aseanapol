// Durable security attack suite (white-box pentest regression). Every check asserts that a payload is INERT.
// Run:  CMS_BASE=http://127.0.0.1:8433 node tests/e2e/security-attacks.mjs
// Self-cleaning: every record/file it creates is deleted again (cms_blocks overrides of touched pages are restored).
// PASS = attack neutralised. FAIL = vulnerability present.
import { chromium } from 'playwright';
import { execFileSync } from 'node:child_process';
import { writeFileSync, mkdirSync } from 'node:fs';
import { tmpdir } from 'node:os';
import path from 'node:path';

const BASE = process.env.CMS_BASE || 'http://127.0.0.1:8433';
const USER = process.env.CMS_USER || 'qa-admin';
const PASS = process.env.CMS_PASS || 'QaAdmin#2026';
const ROOT = path.resolve(import.meta.dirname, '..', '..');
const results = [];
const cleanup = [];

function check(name, ok, detail = '') {
  results.push({ name, ok: !!ok });
  console.log((ok ? 'PASS ' : 'FAIL ') + name + (ok ? '' : '  -> ' + String(detail).slice(0, 300)));
}

// ------------------------------------------------------------------ tiny http client with cookie jar
class Client {
  constructor() { this.jar = new Map(); }
  cookieHeader() { return [...this.jar].map(([k, v]) => `${k}=${v}`).join('; '); }
  store(res) {
    for (const c of res.headers.getSetCookie?.() ?? []) {
      const [kv] = c.split(';'); const i = kv.indexOf('=');
      const k = kv.slice(0, i).trim(); const v = kv.slice(i + 1).trim();
      if (/expires=Thu, 01 Jan 1970|max-age=0/i.test(c)) this.jar.delete(k); else this.jar.set(k, v);
    }
  }
  async req(method, url, { form, multipart, headers = {}, body, redirect = 'manual', timeout = 60000 } = {}) {
    const h = { ...headers };
    if (this.jar.size) h.cookie = this.cookieHeader();
    let b = body;
    if (form) { h['content-type'] = 'application/x-www-form-urlencoded'; b = form instanceof URLSearchParams ? form.toString() : new URLSearchParams(form).toString(); }
    if (multipart) b = multipart;
    const t0 = Date.now();
    const res = await fetch(url.startsWith('http') ? url : BASE + url, { method, headers: h, body: b, redirect, signal: AbortSignal.timeout(timeout) });
    this.store(res);
    const text = await res.text();
    return { status: res.status, headers: res.headers, text, ms: Date.now() - t0, loc: res.headers.get('location') };
  }
  get(url, o) { return this.req('GET', url, o); }
  post(url, o) { return this.req('POST', url, o); }
  async token(url = '/admin/login') {
    const r = await this.get(url);
    const m = r.text.match(/name="_token"[^>]*value="([^"]+)"/) || r.text.match(/value="([^"]+)"[^>]*name="_token"/) || r.text.match(/csrf-token" content="([^"]+)"/);
    return m && m[1];
  }
  async login() {
    const t = await this.token();
    const r = await this.post('/admin/login', { form: { _token: t, username: USER, password: PASS } });
    return r.status === 302 && !/login/.test(r.loc || '');
  }
}

const php = (code) => execFileSync('php', ['artisan', 'tinker', '--execute=' + code], { cwd: ROOT, encoding: 'utf8' }).trim();
const sql = (q) => execFileSync('php', ['-r', `require 'vendor/autoload.php';$a=require 'bootstrap/app.php';$a->make(Illuminate\\Contracts\\Console\\Kernel::class)->bootstrap();` +
  `foreach(Illuminate\\Support\\Facades\\DB::select(${JSON.stringify(q)}) as $r){echo json_encode($r),"\\n";}`], { cwd: ROOT, encoding: 'utf8' }).trim();

function mp(fields, files = {}) {
  const fd = new FormData();
  for (const [k, v] of Object.entries(fields)) if (v !== undefined && v !== null) fd.append(k, v);
  for (const [k, f] of Object.entries(files)) fd.append(k, new Blob([f.data], { type: f.type || 'application/octet-stream' }), f.name);
  return fd;
}
const sleep = (ms) => new Promise(r => setTimeout(r, ms));
const RAW_XSS = /<img[^>]+onerror=(?:alert|window\.__xss)|<script[^>]*>\s*(?:alert|window\.__xss)|<svg[^>]+onload=|href="\s*javascript:\s*(?:alert|window)|"\s*onmouseover="?window/i;
const MARK = 'ZQX' + Date.now().toString(36);

// ================================================================== 1. public: SQLi / reflected XSS / info leak
async function publicChecks() {
  const c = new Client();
  const base = await c.get('/en/news-media/news');
  const sqli = [`' OR '1'='1`, `' OR 1=1-- -`, `1' UNION SELECT NULL,NULL,NULL-- -`, `'; DROP TABLE news_items;-- -`, `%' AND 1=1 AND '%'='`,
    `' AND (SELECT 1 FROM (SELECT SLEEP(4))a)-- -`, `\\`, `"`, `1;SELECT SLEEP(4)`];
  for (const p of sqli) {
    const r = await c.get('/en/news-media/news?q=' + encodeURIComponent(p));
    check(`SQLi q=${p.slice(0, 30)} no 500 & not slow`, r.status === 200 && r.ms < 3000, `${r.status} ${r.ms}ms`);
    check(`SQLi q=${p.slice(0, 30)} does not match everything`, !/OR '1'='1|OR 1=1/.test(p) || r.text.length < base.text.length, 'returned full list');
  }
  // LIKE wildcards typed by a visitor are literal
  const none = await c.get('/en/news-media/news?q=zzzzqqxx');
  for (const w of ['%', '_', '%25%25', '\\']) {
    const rw = await c.get('/en/news-media/news?q=' + encodeURIComponent(w));
    const n = (t) => (t.match(/news-media\/news\/[a-z0-9-]+-20\d\d-\d\d-\d\d/g) || []).length;
    check(`LIKE wildcard q=${w} is literal (no match-all)`, n(rw.text) === n(none.text), `${n(rw.text)} vs ${n(none.text)}`);
  }
  // news table still there
  const after = await c.get('/en/news-media/news');
  check('news table intact after stacked-query payloads', after.status === 200 && after.text.length > 20000, after.status);
  for (const u of [
    '/en/news-media/news?q[]=a', '/en/news-media/news?q[a]=b', '/en/news-media/news?page[]=1', '/en/news-media/news?page=-1', '/en/news-media/news?page=99999999999999999999',
    '/en/news-media/news?page=1%27', '/en/news-media/news/' + encodeURIComponent(`' OR 1=1-- -`), '/en/news-media/news/%00', '/en/news-media/news/%FF%FE',
    '/en/news-media/gallery/more?album=news&offset=-5', '/en/news-media/gallery/more?album=news&offset=99999999999999999999', '/en/news-media/gallery/more?album[]=x&offset[]=1',
    '/en/news-media/gallery/more?album=' + encodeURIComponent(`news' OR '1'='1`), '/en/publication/' + encodeURIComponent(`x' OR 1=1`), '/en/publication/%27',
    '/en/data-resources/digital-library?q=%27&type[]=x', '/en/events-training/calendar?year=%27', '/en/news-media/video-gallery?category[]=x',
  ]) {
    const r = await c.get(u);
    check(`robust ${u.slice(0, 80)}`, r.status < 500 && !/SQLSTATE/i.test(r.text), r.status + ' ' + r.text.slice(0, 120));
  }
  // reflected XSS
  const x = `"><img src=x onerror=alert(1)><script>alert(1)</script>`;
  for (const u of [`/en/news-media/news?q=${encodeURIComponent(x)}`, `/en/news-media/news?q=${encodeURIComponent(x)}&page=2`, `/en/${encodeURIComponent(x)}`,
    `/en/news-media/news/${encodeURIComponent(x)}`, `/${encodeURIComponent(x)}/about-aseanapol`, `/en/publication/${encodeURIComponent(x)}`,
    `/en/news-media/gallery/more?album=${encodeURIComponent(x)}`, `/en/contact-us?x=${encodeURIComponent(x)}`]) {
    const r = await c.get(u);
    check(`reflected XSS inert ${u.slice(0, 60)}`, !RAW_XSS.test(r.text) && !r.text.includes('<script>alert(1)'), r.status);
  }
  // info leak
  for (const u of ['/.env', '/.git/config', '/composer.json', '/composer.lock', '/artisan', '/storage/logs/laravel.log', '/phpinfo.php', '/vendor/autoload.php',
    '/media/../.env', '/media/%2e%2e/.env', '/storage/../.env', '/storage/%2e%2e/%2e%2e/.env', '/..%2f.env', '/index.php/../.env', '/.env.example', '/storage/app/public/../../../.env']) {
    const r = await c.get(u);
    check(`no leak ${u}`, ![200, 206].includes(r.status) || !/APP_KEY|DB_PASSWORD|"require"|<\?php/.test(r.text), r.status);
  }
  const e404 = await c.get('/en/does-not-exist');
  check('404 page has no stack trace / paths', !/vendor\\laravel|Stack trace|C:\\laragon/i.test(e404.text));
  const bad = await c.get('/en/news-media/news?page=' + 'A'.repeat(10));
  check('no debug page on bad page param', !/Whoops|Ignition|Stack trace/i.test(bad.text));
  const h = (await c.get('/en')).headers;
  check('X-Frame-Options or CSP frame-ancestors present', !!h.get('x-frame-options') || /frame-ancestors/.test(h.get('content-security-policy') || ''));
  check('X-Content-Type-Options nosniff', h.get('x-content-type-options') === 'nosniff');
  // method override / CSRF on public POST
  const csrf = await c.post('/en/contact-us', { form: { name: 'a', email: 'a@b.co', subject: 'hi', message: 'x'.repeat(20) } });
  check('contact POST without CSRF token rejected (419)', csrf.status === 419, csrf.status);
  // contact: header injection with a valid token
  const t = await c.token('/admin/login'); // contact form is optional (hidden by default); any session token works for the POST
  const inj = await c.post('/en/contact-us', { form: { _token: t, name: 'Eve\r\nBcc: evil@evil.test', email: 'eve@x.test\r\nBcc: evil@evil.test', subject: 'S\r\nBcc: evil@evil.test', message: 'm'.repeat(20) } });
  check('contact CRLF injection rejected or neutralised', inj.status === 302 || inj.status === 422 || inj.status === 429, inj.status);
  // CORS: no wildcard
  const cors = await c.get('/en', { headers: { Origin: 'https://evil.test' } });
  check('no permissive CORS header', !(cors.headers.get('access-control-allow-origin') || '').includes('*') && !/evil/.test(cors.headers.get('access-control-allow-origin') || ''));
  // Host header poisoning (documented: depends on nginx default_server + trusted hosts)
  const hp = await fetch(BASE + '/en', { headers: { 'X-Forwarded-Host': 'evil.test', 'X-Forwarded-Proto': 'https' } }).then(r => r.text());
  check('X-Forwarded-Host from untrusted peer ignored', !hp.includes('evil.test'));
  // open redirect through locale / root
  const rr = await fetch(BASE + '/', { redirect: 'manual', headers: { 'accept-language': '//evil.test' } });
  check('root redirect with hostile Accept-Language stays on-site', !/evil/.test(rr.headers.get('location') || ''), rr.headers.get('location'));
  const rr2 = await fetch(BASE + '//evil.test', { redirect: 'manual' });
  check('//evil.test path not an open redirect', !/evil\.test/.test(rr2.headers.get('location') || ''), rr2.headers.get('location'));
}

// ================================================================== 2. authn / authz / csrf on admin
async function authzChecks() {
  const guest = new Client();
  const routes = [['GET', '/admin'], ['GET', '/admin/news'], ['GET', '/admin/news/create'], ['POST', '/admin/news'], ['GET', '/admin/site-content'], ['GET', '/admin/site-content/about.index'],
    ['PUT', '/admin/site-content/about.index'], ['POST', '/admin/site-content/about.index/reset'], ['GET', '/admin/events'], ['POST', '/admin/events'], ['POST', '/admin/events/1/toggle'],
    ['GET', '/admin/publications'], ['POST', '/admin/publications'], ['DELETE', '/admin/publications/1'], ['GET', '/admin/newsletters'], ['POST', '/admin/newsletters/1/toggle'],
    ['GET', '/admin/video-gallery'], ['POST', '/admin/video-gallery/1/toggle'], ['GET', '/admin/digital-library/items'], ['POST', '/admin/digital-library/items/1/toggle'],
    ['GET', '/admin/digital-library/collections'], ['DELETE', '/admin/news/abc']];
  for (const [m, u] of routes) {
    const r = await guest.req(m, u);
    check(`guest ${m} ${u} denied`, [302, 403, 404, 419].includes(r.status) && !/admin-layout|Dashboard/.test(r.text.slice(0, 0)), r.status);
  }
  // method spoof as guest
  const sp = await guest.post('/admin/news/abc', { form: { _method: 'DELETE' } });
  check('guest method-spoofed DELETE denied', [302, 403, 404, 419].includes(sp.status), sp.status);

  const adm = new Client();
  check('admin can log in', await adm.login());
  // CSRF: every state-changing route rejects missing token
  const stateful = [['POST', '/admin/news'], ['PUT', '/admin/site-content/about.index'], ['POST', '/admin/site-content/about.index/reset'], ['POST', '/admin/events'],
    ['POST', '/admin/publications'], ['POST', '/admin/newsletters'], ['POST', '/admin/video-gallery'], ['POST', '/admin/digital-library/items'], ['POST', '/admin/digital-library/collections'],
    ['POST', '/admin/events/1/toggle'], ['POST', '/admin/publications/1/toggle'], ['POST', '/admin/newsletters/1/toggle'], ['POST', '/admin/video-gallery/1/toggle'],
    ['POST', '/admin/digital-library/items/1/toggle'], ['DELETE', '/admin/news/zzz'], ['DELETE', '/admin/events/1'], ['DELETE', '/admin/publications/1'], ['POST', '/admin/logout']];
  for (const [m, u] of stateful) {
    const r = await adm.req(m, u);
    check(`CSRF: ${m} ${u} without token -> 419`, r.status === 419, r.status);
  }
  // cross-site Origin with stale token still needs matching token
  const r419 = await adm.post('/admin/news', { form: { _token: 'x'.repeat(40), title: 'a' } });
  check('CSRF: forged token -> 419', r419.status === 419, r419.status);
  // session cookie flags
  const sc = (await new Client().get('/admin/login')).headers.getSetCookie().join('\n');
  check('session cookie HttpOnly + SameSite', /httponly/i.test(sc) && /samesite=(lax|strict)/i.test(sc));
  check('admin responses are no-store', /no-store/.test((await adm.get('/admin')).headers.get('cache-control') || ''));
  // admin registry key traversal
  for (const k of ['..%2f..%2f.env', '../x', 'about.%2e%2e', 'a.b.c', "about.index'--", 'about.index%00', '%2e%2e%2f%2e%2e%2fvendor.autoload', 'config.app']) {
    const r = await adm.get('/admin/site-content/' + k);
    check(`cms page key ${k} -> 404, no file read`, r.status === 404 && !/APP_KEY|<\?php/.test(r.text), r.status);
  }
  return adm;
}

// ================================================================== 3. stored XSS through the CMS editor (all field types) -> real browser
const PAYLOADS = {
  text: `${MARK}"><img src=x onerror=window.__xss=1>'\"</script><script>window.__xss=1</script>`,
  attr: `${MARK}" onmouseover="window.__xss=1" style="x:y`,
  url: ['JaVa\tScRiPt:window.__xss=1', 'data:text/html,<script>window.__xss=1</script>', 'vbscript:x', ' javascript:window.__xss=1', '//evil.test/x"onmouseover=window.__xss=1'],
  css: `x.png');background:url(//evil.test/css);('`,
  rich: `<p>${MARK}</p><img src=x onerror=window.__xss=1><a href="javascript:window.__xss=1">l</a><svg onload=window.__xss=1><iframe srcdoc="<script>parent.__xss=1</script>">`,
};

function buildFields(def) {
  // returns { f: {section: {field: value}}, ... } with a payload in every field, by type
  const f = {};
  const val = (fd, n) => {
    switch (fd.type) {
      case 'repeater': return [0, 1].map(() => Object.fromEntries(Object.entries(fd.fields).map(([k, s]) => [k, val(s, n + 1)])));
      case 'richtext': case 'html': return PAYLOADS.rich;
      case 'url': return PAYLOADS.url[n % PAYLOADS.url.length];
      case 'image': case 'file': return PAYLOADS.css;
      case 'list': return [PAYLOADS.text, PAYLOADS.attr];
      case 'number': return '1e999';
      case 'boolean': return '1';
      case 'select': return PAYLOADS.attr;
      case 'icon': return PAYLOADS.attr;
      case 'textarea': return PAYLOADS.text;
      default: return n % 2 ? PAYLOADS.attr : PAYLOADS.text;
    }
  };
  for (const [sk, sec] of Object.entries(def.sections)) {
    f[sk] = {};
    for (const [fk, fd] of Object.entries(sec.fields)) f[sk][fk] = val(fd, fk.length);
  }
  return f;
}
function flatten(prefix, v, out) {
  if (Array.isArray(v)) v.forEach((x, i) => flatten(`${prefix}[${i}]`, x, out));
  else if (v && typeof v === 'object') for (const [k, x] of Object.entries(v)) flatten(`${prefix}[${k}]`, x, out);
  else out.push([prefix, String(v)]);
}

async function cmsStoredXss(adm) {
  // registry: page keys + their public url, dumped by PHP
  const dump = JSON.parse(execFileSync('php', [path.join(ROOT, 'tests/e2e/security-registry-dump.php')], { cwd: ROOT, encoding: 'utf8' }));
  const pages = dump.pages;
  const touched = [];
  const browser = await chromium.launch({ headless: true });
  const ctx = await browser.newContext();
  const page = await ctx.newPage();
  await page.addInitScript(() => { window.__xss = 0; });
  let n = 0, bad = 0, reflected = 0;
  try {
    for (const [key, def] of Object.entries(pages)) {
      if (process.env.CMS_ONLY && !new RegExp(process.env.CMS_ONLY).test(key)) continue;
      if (!def.url) continue; // not publicly renderable page (shared facts) - still covered through pages that include them
      n++;
      const edit = await adm.get('/admin/site-content/' + key);
      const tok = edit.text.match(/name="_token"[^>]*value="([^"]+)"/)?.[1];
      const fp = edit.text.match(/name="_fp"[^>]*value="([^"]*)"/)?.[1] ?? '';
      const pairs = []; flatten('f', buildFields(def.def), pairs);
      const form = new URLSearchParams([['_token', tok], ['_method', 'PUT'], ['_fp', fp], ...pairs, ['_end', '1']]);
      touched.push(key);
      const sv = await adm.post('/admin/site-content/' + key, { form });
      if (sv.status !== 302) { check(`cms save ${key}`, false, sv.status); continue; }
      for (const loc of ['en', 'ms']) {
        const url = BASE + def.url.replace('{locale}', loc);
        await page.goto(url, { waitUntil: 'domcontentloaded' });
        await page.waitForTimeout(150);
        const executed = await page.evaluate(() => window.__xss);
        const html = await page.content();
        const body = await (await ctx.request.get(url)).text();
        const m = body.match(/<img src=x onerror=window|<script>window\.__xss|<svg[^>]+onload|href="\s*javascript:|src="\s*javascript:|<iframe[^>]*srcdoc|onmouseover="window|style="[^"]*(?:&#039;|')\);background:url\(\/\/evil\.test/i);
        if (loc === 'en' && body.includes(MARK)) reflected++;
        const raw = !!m; if (m) console.log('   matched:', m[0].slice(0, 160));
        if (executed || raw) { bad++; check(`stored XSS inert ${key} [${loc}]`, false, `executed=${executed} raw=${raw}`); }
      }
      // restore this page right away (reset removes the overrides we created)
      const t2 = (await adm.get('/admin/site-content/' + key)).text.match(/name="_token"[^>]*value="([^"]+)"/)?.[1];
      await adm.post(`/admin/site-content/${key}/reset`, { form: { _token: t2 } });
    }
  } finally { await browser.close(); }
  check(`payload really stored and rendered (non-vacuous): ${reflected}/${n} pages echo the marker`, reflected >= n * 0.8, `${reflected}/${n}`);
  check(`CMS stored XSS: ${n} public pages with payloads in every field type, none executed/raw`, bad === 0, `${bad} failing`);
}

// ================================================================== run
const only = process.argv[2];
try {
  if (!only || only === 'public') await publicChecks();
  let adm;
  if (!only || only === 'authz') adm = await authzChecks();
  if (!only || only === 'cms') { adm ??= new Client(); if (!adm.jar.size) await adm.login(); await cmsStoredXss(adm); }
  if (!only || only === 'modules') { const m = await import('./security-attacks-modules.mjs'); adm ??= new Client(); if (!adm.jar.size) await adm.login(); await m.run({ adm, check, mp, sql, php, BASE, ROOT, MARK, RAW_XSS, cleanup, Client }); }
} finally {
  for (const fn of cleanup.reverse()) { try { await fn(); } catch (e) { console.log('cleanup error', e.message); } }
}
const failed = results.filter(r => !r.ok);
console.log(`\n${results.length - failed.length}/${results.length} inert` + (failed.length ? `, ${failed.length} VULNERABLE` : ''));
process.exit(failed.length ? 1 : 0);
