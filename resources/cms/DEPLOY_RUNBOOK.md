# Production deploy runbook: CMS admin module (branch `cms-admin-module`)

Target: Alibaba Cloud `47.250.166.174`, web root `/var/www/aseanapol/`, PHP only inside the
`aseanapol_app` container, system nginx (shared with `eads.aseanapol.org`) in front.
SSH as `asnpladmin` (sudoer). Root password SSH is refused. Credentials live in `CLAUDE.md`; do not copy them here.

This release is **additive**: 3 new tables (`cms_blocks`, `publications`, `events`) and 1 new nullable
column (`news_items.sponsors`). No existing column or row is altered except the 11 Anti-Scam Study Visit news rows
(they get `sponsors` JSON). Rehearsed on a copy of production-like data (see end of file).

## 0. Before you start (dev machine)

1. Merge `cms-admin-module` into `main` (or push the branch and merge via GitHub), then `git push origin main`.
2. Confirm `git log -1 --oneline` on main shows the merge you intend to ship.
3. Nothing in `public/media/` changes in this release, so no SFTP media upload is needed.
   (All CMS uploads go to `storage/app/public/`, created by the admin UI on the server.)

## 1. Pull the code on the server

```bash
ssh asnpladmin@47.250.166.174
cd /var/www/aseanapol
git config --global --add safe.directory /var/www/aseanapol   # only if git complains
git pull origin main
git log -1 --oneline          # MUST show the new commit. A plain pull can silently no-op (stale root/www-data .git files)
```

If `git log -1` still shows the old commit, or the pull printed permission errors:

```bash
sudo git pull origin main
git log -1 --oneline          # verify again
sudo chown -R asnpladmin:asnpladmin /var/www/aseanapol/.git   # only if it keeps happening
```

Check the working tree is clean of surprises: `git status --short` (should be empty apart from gitignored media).

## 2. PHP / nginx limits (do this BEFORE the migration; it is the only step that needs a rebuild)

The Publications form advertises **PDF up to 500 MB**, CMS page forms can post **~400 inputs**, and the old limits
(`upload_max_filesize=20M`, `post_max_size=20M`, nginx `client_max_body_size 20M`) would reject big PDFs and
silently truncate large forms. The repo now ships (`docker/php/php.ini`, `docker/nginx/conf.d/app.conf`):

| Setting | Old | New |
|---|---|---|
| `upload_max_filesize` | 20M | 512M |
| `post_max_size` | 20M | 520M |
| `max_input_vars` | 1000 (default) | 3000 |
| `max_execution_time` / `max_input_time` | 300 | 300 (unchanged) |
| nginx `client_max_body_size` | 20M | 520M |
| nginx `fastcgi_read_timeout` | 300 | 300 (unchanged, keep) |

`php.ini` is COPY-ed into the image at build time (the app dir is bind-mounted, the ini is not), so:

```bash
cd /var/www/aseanapol
sudo docker compose build app && sudo docker compose up -d app     # rebuild + restart only the app container
# (the nginx container stays disabled, restart: "no"; do NOT start it)
```

Faster alternative without a rebuild (lost on the next image rebuild, so also do the rebuild eventually):

```bash
printf 'upload_max_filesize=512M\npost_max_size=520M\nmax_input_vars=3000\n' | \
  sudo docker exec -i aseanapol_app sh -c 'cat > /usr/local/etc/php/conf.d/zz-uploads.ini'
sudo docker restart aseanapol_app
```

Verify (CLI reads the same conf.d; FPM shares it):

```bash
docker exec aseanapol_app php -i | grep -E "upload_max_filesize|post_max_size|max_input_vars|max_execution_time"
```

System nginx (the site served on port 80 for aseanapol.org; edit only this site, `eads` shares the daemon):

```bash
sudo grep -n client_max_body_size /etc/nginx/sites-available/aseanapol
sudo sed -i 's/client_max_body_size .*;/client_max_body_size 520M;/' /etc/nginx/sites-available/aseanapol
sudo nginx -t && sudo systemctl reload nginx
```

Cloudflare note: proxied requests are capped at **100 MB** on Free/Pro plans. A PDF above that cannot be uploaded
through `aseanapol.org/admin`; upload via SFTP like the old workflow, or use a Cloudflare-bypassing hostname/IP
for the admin session. Files under 100 MB are fine.

## 3. Migrate

```bash
docker exec aseanapol_app php artisan migrate:status | tail -8     # expect the 4 new ones as Pending
docker exec aseanapol_app php artisan migrate --force
```

Expected output: 4 lines DONE, in this order:

```
2026_10_12_000001_create_cms_blocks_table
2026_10_12_100000_create_publications_table
2026_10_12_110000_create_events_table      (seeds 115 events)
2026_10_12_200000_add_sponsors_to_news_items   (adds column, tags the 11 Anti-Scam Study Visit rows)
```

Sanity (the DB container is `aseanapol_mysql`; get the root password from the server `.env`, never paste it in tickets):

```bash
docker exec aseanapol_app php artisan tinker --execute="echo DB::table('publications')->count().' pubs, '.DB::table('events')->count().' events, '.DB::table('news_items')->whereNotNull('sponsors')->count().' sponsored news';"
# expect: 8 pubs, 115 events, 11 sponsored news
```

Re-running `migrate --force` must print "Nothing to migrate".

## 4. Caches

```bash
docker exec aseanapol_app php artisan config:cache
docker exec aseanapol_app php artisan route:cache
docker exec aseanapol_app php artisan view:cache
docker exec aseanapol_app php artisan cache:clear        # CMS values are cached; clears stale page-tree entries
docker exec aseanapol_app php artisan gallery:reindex    # optional, the scheduler does it every 15 min
```

## 5. Storage link and write permissions

Uploads go to the `public` disk (`storage/app/public/`), exposed at `/storage/...` through `public/storage`.

```bash
docker exec aseanapol_app php artisan storage:link --force      # idempotent; "already exists" is fine
ls -l /var/www/aseanapol/public/storage                          # must be a symlink to .../storage/app/public

cd /var/www/aseanapol
sudo mkdir -p storage/app/public/{cms,events,publications,news/sponsors,news/thumbnails,newsletters,digital-library}
# PHP-FPM in the container runs as www-data: the dirs must be writable by it
sudo chown -R www-data:www-data storage/app/public storage/framework storage/logs bootstrap/cache
sudo chmod -R ug+rwX storage/app/public
# write test as the real PHP user:
docker exec -u www-data aseanapol_app sh -c 'for d in cms events publications news/sponsors; do touch storage/app/public/$d/.w && rm storage/app/public/$d/.w && echo "$d ok"; done'
```

(`deploy_alicloud.py` uses `chmod 775` on `storage` and `bootstrap/cache`; the chown above matches it.)
`/public/storage` is gitignored, so a `git pull` never removes it.

## 6. What changes visibly on first deploy (approved content reconciliations)

The public pages now read from the CMS defaults, which were reconciled against the old hard-coded text. After deploy
these differ from what visitors saw before; all are intentional and approved:

- Indonesian and Philippine police **force names** corrected to the official names.
- Lao police **telephone typo** fixed.
- **Brunei, Cambodia and Philippines** contact numbers corrected.
- **Leadership name spellings** normalised on the leadership pages.
- **DPP badge** on the Deputy Permanent Secretary page.
- **Partner names and URLs** on the cooperation page corrected.
- "**ten**" ASEAN member countries wording changed to "**eleven**" (Timor-Leste).
- **Observers order** changed.
- **Sponsor strip** ("Supported by" JAIF + Japan logos) now appears on the 11 Anti-Scam Study Visit articles (list card
  and a strip under the article). It replaces the old title-prefix hack and looks the same on the list.
- **Joint-projects page header** fixed.
- Publications (8) and Events (115 incl. conferences) are now editable in the admin; public pages render from the DB with
  the same content as before.

Nothing else should look different. If something does, compare with `git diff main..cms-admin-module -- resources/views`.

## 7. Smoke test (run from anywhere; expect HTTP 200 unless noted)

```bash
H=https://aseanapol.org
for p in /en /en/about-aseanapol /en/about-aseanapol/governance /en/news-media/news \
         /en/news-media/gallery /en/data-resources/publications /en/data-resources/digital-library \
         /en/events-training /en/contact /admin/login; do
  printf '%-55s %s\n' "$p" "$(curl -s -o /dev/null -w '%{http_code}' $H$p)"; done

# legacy redirects stay 301
curl -s -o /dev/null -w '%{http_code}\n' $H/en/news
# sponsors shown on an Anti-Scam article and the list
curl -s $H/en/news-media/news | grep -c 'jaif-cooperation.png'            # expect >= 1
# no PHP errors anywhere
curl -s $H/en | grep -ciE 'Whoops|Exception|SQLSTATE|Undefined'            # expect 0
# admin gate: unauthenticated /admin must redirect to login
curl -s -o /dev/null -w '%{http_code} %{redirect_url}\n' $H/admin
# Facebook ingest + schedule unaffected
docker exec aseanapol_app php artisan news:sync-facebook --dry-run
docker exec aseanapol_app php artisan schedule:list
tail -n 50 /var/www/aseanapol/storage/logs/laravel.log
```

Then in a browser: log in at `/admin/login`, open Pages, Publications, Events, News (edit an article, open the
"Supported by" block), save one trivial edit on a CMS page and confirm it shows on the public page after a refresh.

## 8. Cloudflare and uploaded files

`aseanapol.org` is behind Cloudflare and static files are cached up to a year (`immutable`). The admin always writes
uploads (logos, covers, event images, PDFs, CMS images) under a **new unique filename**, so there is no stale-cache
problem; never overwrite an uploaded file in place by SFTP at the same name. CMS text changes appear immediately
(HTML pages are not edge-cached unless a page rule says otherwise; if one does, purge from the Cloudflare dashboard).

## 9. First-run admin steps

How users are provisioned: `users.username` + `users.is_admin` (migration `*_add_admin_fields_to_users_table`);
login is by **username** (not email) and only `is_admin = true` users get in
(`Admin\AuthController`). `database/seeders/AdminUserSeeder.php` creates username `aseanapoladmin`
(`admin@aseanapol.org`) only if it does not exist, and never overwrites an existing password. The password comes
from `ADMIN_SEED_PASSWORD` in `.env`, or a generated 24-char one is printed once.

Production already has this admin from the earlier release. Check, do not re-seed blindly:

```bash
docker exec aseanapol_app php artisan tinker --execute="echo DB::table('users')->where('is_admin',1)->get(['username','email'])->toJson();"
```

- If `aseanapoladmin` exists: log in with the existing password. To reset it:
  `docker exec -it aseanapol_app php artisan tinker` then
  `$u = App\Models\User::where('username','aseanapoladmin')->first(); $u->password = '<new strong password>'; $u->save();`
  (the model hashes it). Choose the password interactively; do not paste it into chat or commit it.
- If missing: `docker exec aseanapol_app php artisan db:seed --class=AdminUserSeeder` (prints the generated password
  once; save it in the team password manager).
- Create one personal admin per editor instead of sharing, via tinker: `User::create(['name'=>..., 'username'=>..., 'email'=>..., 'password'=>..., 'is_admin'=>true])`.
- **The local test account `qa-admin` (qa-admin@aseanapol.test) must NOT exist on production.** It only exists in dev
  databases. Verify after deploy:
  `docker exec aseanapol_app php artisan tinker --execute="echo DB::table('users')->where('username','qa-admin')->count();"` must print `0`.
  Delete if present: `DB::table('users')->where('username','qa-admin')->delete();`
- First login checklist: change the admin password, open `Pages` and read `resources/cms/CMS_GUIDE.md`, verify the
  Publications list shows 8 editions and Events shows 115.

## 10. Rollback

The migrations are additive; code and DB can be rolled back independently.

Code only (fast, DB tables simply sit unused; the extra column is nullable and harmless to old code):

```bash
cd /var/www/aseanapol
git log --oneline -5                       # find the merge commit to undo
git revert -m 1 <merge-sha>                # on dev, push, then git pull on the server (step 1)
# or emergency: sudo git reset --hard <previous-good-sha>    (server only, then caches below)
docker exec aseanapol_app php artisan config:cache && docker exec aseanapol_app php artisan route:cache && docker exec aseanapol_app php artisan view:cache
```

Schema too (only if you really need the tables gone; the code must be reverted FIRST, otherwise the site errors):

```bash
docker exec aseanapol_app php artisan migrate:rollback --step=4 --force
# drops events, publications, cms_blocks and news_items.sponsors. Uploaded files remain in storage/app/public.
```

Data loss warning: rolling back after editors used the admin discards their CMS edits, publications and events
(uploads stay on disk). Take a dump first when in doubt:
`docker exec aseanapol_mysql mysqldump -uroot -p news_items cms_blocks publications events > /home/asnpladmin/pre-rollback.sql`.

## 11. Rehearsal record (2026-10-12, local scratch DB `aseanapol_rehearsal`)

- Baseline = the 17 migrations on `main`, loaded with a dump of 663 news items, 110 translations, 6486 gallery rows, 2 users.
- One `php artisan migrate --force` applied exactly the 4 new migrations; row counts and md5 checksums of
  news title/summary/content/thumbnail/slug (663 rows) and translations (110) identical before/after.
- Seeds: publications 8, events 115, cms_blocks 0 (filled only when an editor saves a page).
- 11 of 20 "Anti-Scam" titled articles got sponsors: exactly those whose title starts with
  "ASEANAPOL Anti-Scam Study Visit" (same rule as the old hack); the other 9 untouched.
- Re-run: "Nothing to migrate". `migrate:rollback --step=4` removed all 4 and restored the old checksum; re-migrate restored 8 / 115 / 11.
- App on the migrated DB: 85 public URLs (`tests/e2e/urls.txt` + `/admin/login`) no 5xx and no error text
  (17 are intentional 301 legacy redirects); 154 admin pages crawled after login, all 200, no error text.
- `config:cache`, `route:cache`, `view:cache` all succeed (no closure routes).


## 12. Admin hardening (production settings)

Admin is invisible to guests: every /admin URL except `/admin/login` returns the standard 404 (never a redirect),
all /admin responses carry `X-Robots-Tag: noindex, nofollow, noarchive, nosnippet`, robots.txt disallows /admin,
and `/sitemap.xml` lists only public English URLs.

`.env` on prod (not touched by the repo):
```
APP_ENV=production
APP_DEBUG=false
SESSION_SECURE_COOKIE=true
SESSION_LIFETIME=60            # idle timeout, minutes (recommended)
ADMIN_ALLOWED_IPS=203.0.113.10,198.51.100.0/24   # office/VPN egress; empty = open. Others get 404.
```
`ADMIN_ALLOWED_IPS` uses `$request->ip()`, which honours the Cloudflare trusted-proxy list in bootstrap/app.php.
Login throttle: 5 failed attempts/min per username+IP, 20 failed/hour per IP (`ADMIN_LOGIN_THROTTLE`,
`ADMIN_LOGIN_IP_HOURLY`). Then `docker exec aseanapol_app php artisan config:cache`.

The system nginx site on prod (e.g. `/etc/nginx/sites-enabled/aseanapol`) is a separate file from
`docker/nginx/conf.d/app.conf` and must be updated by the operator. Add inside the `server { }` block, then
`sudo nginx -t && sudo systemctl reload nginx`:
```nginx
server_tokens off;
autoindex off;
fastcgi_hide_header X-Powered-By;           # inside the php location
location ~ /\.(?!well-known) { return 404; }
location ~* ^/(vendor|storage/logs|storage/framework|bootstrap/cache|node_modules|database|config|tests)/ { return 404; }
location ~* \.(env|log|sqlite|sql|bak|lock)$ { return 404; }
```
Rebuild the app image to apply `expose_php = Off` (php.ini is COPY-ed at build time).
The footer "Sitemap" link default stays `#` (CMS-editable under site.header-footer); set it to `/sitemap.xml` in the CMS if wanted.
