# ASEANAPOL CVE / Supply-Chain Audit — 2026-10-12

Scope: Composer + npm dependencies, front-end CDN includes, Docker base images (read from repo files only; no server access).
Advisory data: `composer audit` / `npm audit` (live Packagist + GitHub advisory DBs, 2026-10-12) plus public web lookups for PHP/MySQL.

## 1. Findings

| # | Component | Installed | Advisory | Severity | Reachable in this app? | Fixed in | Action |
|---|-----------|-----------|----------|----------|------------------------|----------|--------|
| 1 | laravel/framework | 12.68.0 | CVE-2026-102279 / GHSA-jh5r-qr3c-85q8 — XSS in debug page information | Low | Only if `APP_DEBUG=true` on a reachable host. Prod must have `APP_DEBUG=false` (see ops list). | 12.69.0 | FIXED -> 12.69.3 |
| 2 | league/commonmark | 2.10.0 | GHSA-3q6v-r5mr-hxv8 — quadratic-time DoS in GFM Table extension | High | Low. App code never calls `Str::markdown`/CommonMark (grep: none); only Laravel mail markdown pulls it in. Theoretical. | 2.10.2+ | FIXED -> 2.10.3 |
| 3 | league/commonmark | 2.10.0 | GHSA-97jj-33gv-5xf9 — DisallowedRawHtml bypass | Medium | Not reachable (no user markdown rendering). | 2.10.2+ | FIXED -> 2.10.3 |
| 4 | axios (npm, bundled into `public/build` JS via resources/js/app.js) | 1.13.6 | npm audit: high, range 1.0.0-1.19.0 | High (mostly Node/server-side paths) | Browser bundle only sets a default header; no user-controlled axios calls. Low. | >1.19 | FIXED by `npm audit fix` (lockfile only, ^1.11.0 constraint unchanged). Bundle JS grew 37 kB -> 51 kB. |
| 5 | vite 7.3.1 | dev-server vulns (fs.deny bypass, WS file read, .map traversal) | High | Dev-only: only if `vite` dev server is run and exposed. Not run in prod (prod uses `npm run build`). | 7.3.4+ | FIXED (audit fix) |
| 6 | source-map-js, postcss, nanoid, esbuild, follow-redirects, form-data, launch-editor | various | High/Moderate/Low | Build-machine only, transitive. | n/a | FIXED (audit fix) |
| 7 | concurrently 9.2.1 -> shell-quote <=1.8.4 | Critical (npm rating) | Dev script runner (`composer dev`); parses only local command strings. Theoretical. | n/a | FIXED (audit fix) |
| 8 | sharp 0.34.5 (libvips, libheif, librsvg CVE-2026-96889) | High | Only used by ad-hoc image scripts on dev machine; not in runtime, not in `public/build`. Only dangerous if run on untrusted images. | 0.35.5 (breaking major-ish) | NOT changed. Accepted risk: never run sharp on untrusted/uploaded files. |
| 9 | Quill 2.0.3 (admin editor, jsdelivr) | 2.0.3 | No open advisory known to me for 2.0.3 (XSS CVE-2021-3163 was Quill 1.3.6). Output is still HTML written to DB — sanitise server-side (already handled by CMS). | n/a | SRI added. |
| 10 | Chart.js 4.4.0 (public layout, jsdelivr) | 4.4.0 | No open advisory (old prototype-pollution CVE affected <2.9.4). | n/a | SRI added. |
| 11 | Tailwind Play CDN (`cdn.tailwindcss.com`) in admin layout + login | unpinned, floating | Supply-chain: remote script executes in the authenticated admin origin; cannot carry SRI (content changes). | Medium (supply-chain) | Accepted risk, see section 3. |
| 12 | Playwright 1.58.2 | dev-only tests | none open | n/a | none |

No other Composer advisories after the update: `composer audit --locked` => "No security vulnerability advisories found."

Package inventory reviewed (all clean post-update): symfony/* (http-foundation/http-kernel/routing/process/mime/mailer 7.4.19-7.4.20, var-dumper 7.4.18, yaml 8.1.6 dev), guzzlehttp/guzzle 7.15.5, psr7 2.13.1, flysystem 3.36.0, monolog 3.12.1, nesbot/carbon 3.14.2, ramsey/uuid 4.9.4, brick/math 0.14.8, vlucas/phpdotenv 5.7.0, psy/psysh 0.12.21 + laravel/tinker 2.11.1, voku/portable-ascii 2.1.1, league/uri 7.8.1, egulias/email-validator 4.0.4. No dompdf/phpmailer/swiftmailer present. Dev: phpunit 11.5.55, filp/whoops 2.18.4 (dev only; must not be installed with `--no-dev` omitted on prod).

## 2. Actions taken

- `composer update laravel/framework league/commonmark --with-dependencies` (patch/minor, within constraints): laravel/framework 12.68.0 -> 12.69.3, league/commonmark 2.10.0 -> 2.10.3, plus patch bumps of symfony/*, monolog 3.10->3.12.1, carbon 3.13->3.14.2, flysystem 3.35->3.36, ramsey/uuid, laravel/prompts, serializable-closure. Backups of original composer.json/lock/package*.json kept in the session scratchpad.
- `npm audit fix` (non-forced): lockfile-only; `npm run build` OK; CSS bundle hash unchanged, JS bundle changed (axios). Public pages `/en` and gallery load with zero console errors.
- Added SRI (`integrity` sha384 + `crossorigin="anonymous"`) to exact-pinned files:
  - `resources/views/admin/cms/edit.blade.php`: quill@2.0.3 `quill.js` and `quill.snow.css`
  - `resources/views/layouts/app.blade.php`: chart.js@4.4.0 `chart.umd.min.js`
- Not changed: sharp (breaking), Docker images (no server access), Tailwind Play CDN.

Test results (local, after changes): `php artisan test` 13 passed; `route:list` 149 routes; `editors-smoke.mjs` 82/82; Playwright opened the CMS editor (cooperation.interpol) with SRI, Quill loaded, typing works, 0 console errors; `regress.mjs check` 82/84 identical (the 2 diffs are content, `leadership` "9th/10th" and `permanent-secretariat` flag — from uncommitted content/DB work, not from the dependency updates; after `npm run build` all pages differ only by the hashed asset filename in `<link>` tags, which is expected and means the regression baseline should be re-recorded). Note: the first editors-smoke run hit a timeout right after server start; rerun passed.

## 3. Accepted risks and mitigations

- **Tailwind Play CDN in admin (login + layout)**: the script is an unversioned, mutable JS file so SRI is impossible, and the compiled admin CSS depends on dynamic classes in ~all admin templates; vendoring was judged not low-risk for an unattended session. Mitigations: (a) recommended follow-up — compile an admin stylesheet with the existing Vite/Tailwind pipeline (add an `admin.css` entry scanning `resources/views/admin/**`) and drop the CDN; (b) interim — add a CSP on `/admin*` in nginx (`script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src https://fonts.gstatic.com`), which at least prevents exfiltration to arbitrary hosts via `connect-src 'self'`. Admin sessions are the exposure, so keep admin access limited.
- Google Fonts stylesheet (Material Symbols) cannot carry SRI (UA-dependent CSS). Low risk (CSS only). Self-hosting the font is optional.
- sharp advisories: dev-machine only; do not process untrusted images.
- Quill content is trusted-admin-only HTML.

## 4. Ops follow-up (server side, not verified here)

Run on the production host as the sudoer and compare against latest releases:

```
docker exec aseanapol_app php -v                 # newest 8.4 seen publicly: 8.4.26 (late Sep 2026, includes SOAP CVE-2026-91765, OpenSSL verify and FPM fixes). Dockerfile uses floating tag php:8.4-fpm, so the image is only as new as the last build.
docker exec aseanapol_app php -m | head -50
docker exec aseanapol_mysql mysqld --version     # docker-compose pins mysql:8.0 (floating). Oracle CPU July 2026 fixed MySQL issues; next CPU is expected 2026-10-20 — re-check then. Note MySQL 8.0 reaches end of life in April 2026 (already past): plan migration to 8.4 LTS.
nginx -v                                         # system nginx 1.24 (Ubuntu); check Ubuntu security updates
apt list --upgradable 2>/dev/null | grep -Ei "nginx|openssl|libssl|curl|libxml|zlib|docker|containerd|linux-image"
docker exec aseanapol_app apt list --upgradable 2>/dev/null | grep -Ei "openssl|libssl|curl|libxml|zlib|libpng|libjpeg|freetype|icu"
docker images --format '{{.Repository}}:{{.Tag}} {{.CreatedSince}}'
docker exec aseanapol_app grep -E "^(APP_DEBUG|APP_ENV)=" .env        # must be APP_DEBUG=false, APP_ENV=production (CVE-2026-102279 only matters with debug on)
docker exec aseanapol_app composer audit --locked
docker exec aseanapol_app php artisan tinker --execute="echo DB::table('users')->where('username','qa-admin')->count();"   # must print 0
```

Rebuild/redeploy plan: after merge, on the server run `composer install --no-dev --optimize-autoloader` (inside the app container) so whoops/phpunit are not installed, `npm ci && npm run build`, then `php artisan config:cache view:cache route:cache`. Rebuild the PHP image with `--pull` to take current base-image OS and PHP patches. The `docker-compose.yml` references `nginx:1.25-alpine`, but that container is disabled; ignore or bump it. Dockerfile uses `composer:latest` (floating) — consider pinning a version.

Cloudflare: media is served `immutable` (see CLAUDE.md); no WAF/CSP header is set by the app (`server_tokens off`, `expose_php=Off`, `display_errors=Off` are already set). Consider enabling Cloudflare managed WAF rules and "Always use HTTPS", and adding the CSP above for `/admin`.
