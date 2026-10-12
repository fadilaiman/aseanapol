# ASEANAPOL CMS — developer guide

Goal: every piece of public content is editable by an admin at `/admin/site-content`
(page tree) or in a dedicated module (lists/records), with **zero visual change** until an admin edits something.

## 1. How it works
* Registry: `config/cms_pages/<group>.php` returns `['_group'=>[label,icon,order], '<slug>'=>pageDef, ...]`.
  Page key = `<group>.<slug>` (e.g. `about.vision-mission`). One registry file per group — a file is owned by exactly one developer.
* The registry **default values are the original hardcoded content** (copied verbatim from the old blade/controller).
* Overrides live in table `cms_blocks` (page_key, block_key, JSON value). Only values that differ from the default are stored.
  Empty table => site renders exactly as before. "Revert" deletes rows.
* Blade helpers (app/Support/cms_helpers.php):
  * `cms('about.x', 'section.field')` → value (escape with `{{ }}`); repeater → array of rows (hidden rows already removed)
  * `cms_on('about.x', 'section')` → bool, for sections with `'toggle' => true`
  * `cms_img('about.x', 'section.field')` → public URL ('' when empty → render nothing)
  * `cms_html('about.x', 'section.field')` → HtmlString for `richtext`/`html` fields (`{!! !!}` or `{{ }}` both fine, no double escaping)
  * `cms_url($path)` → asset()-resolved URL for a raw path
* Admin UI is generated from the registry: nothing to hand-build for page content.
* Reference implementation: `config/cms_pages/about.php` (`vision-mission`) + `resources/views/about/vision-and-mission.blade.php`.

## 2. Page definition
```php
'slug' => [
  'label' => 'Human name',
  'route' => 'about.vision-mission',       // public route name (needs {locale}); or 'url' => '/en/...'
  'route_params' => [],                     // extra route params
  'sections' => [
    'hero' => ['label' => 'Page header', 'fields' => [
        'title'    => ['type'=>'text',     'label'=>'Title',    'default'=>'...'],
        'subtitle' => ['type'=>'textarea', 'label'=>'Subtitle', 'rows'=>2, 'default'=>'...'],
    ]],
    'extra' => ['label'=>'Some block', 'toggle'=>true, 'help'=>'...', 'columns'=>2, 'fields'=>[ ... ]],
  ],
],
```
Field types: `text`, `textarea`, `richtext` (Quill WYSIWYG, sanitized HTML), `html` (raw HTML textarea, sanitized),
`url`, `icon` (Material Symbols name), `image`, `file` (value = path relative to /public or full URL; uploads become `storage/cms/<unique>`),
`number`, `date`, `boolean`, `select` (`'options'=>[value=>label]`), `list` (array of strings, one per line),
`repeater` (`'fields'=>[...sub fields...]`, `'default'=>[rows]`, `'item_label'=>'<subfield used as row title>'`, `'add_label'`, `'columns'`; repeaters can nest).
Every key used in a repeater default row MUST be declared in its `fields` (extra keys are dropped on save).
Per row the admin gets Hide / Remove / Up / Down / Add automatically. Toggle sections get a "Show this section" switch.
Common keys: `label`, `help`, `default`.
Sanitizer note: `richtext`/`html` allow p, br, strong, b, em, i, u, ul, ol, li, h2-h4, blockquote, hr, span, div, sup, sub, a(href,target,rel,title),
img(src,alt,title,width,height), table/thead/tbody/tr/th/td. Classes/styles are stripped on save — so a field whose default needs
class/style markup (Tailwind) must stay structured (text fields/repeater), not a free html blob.

## 3. Converting a page (rules)
1. Read the blade (and the PageController method). List every editable thing: headings, paragraphs, lists, cards, tables, images, links, buttons, contact details, dates, icons.
   Do not leave human-readable static content hardcoded. Do not make functional code (routes, CSRF, scripts) editable.
2. Add a `hero` section (title, subtitle) for pages that use `partials.page-hero`; breadcrumb label should reuse `hero.title`.
3. Move text VERBATIM into registry defaults; replace in the blade with `cms(...)`. Keep markup/classes identical.
   * Plain text → `{{ cms(...) }}`; text with inline HTML (links, strong, br) → `html`/`richtext` field + `{{ cms_html(...) }}`.
   * Repeated cards/rows/list items → `repeater`; replace the old inline `@php $x=[...]` array with `cms(...)`.
   * Blocks an admin may want gone → `'toggle'=>true` + wrap in `@if(cms_on(...))`.
   * Images/PDF links → `image`/`file` field; guard `@if(cms_img(...))`.
   * An empty value should render nothing sensible (hide the element) instead of an empty box.
4. Controller-supplied arrays (PageController): move them into the registry and read via `cms()` in the controller or view.
   Edit PageController with small targeted Edit operations ONLY inside the methods you own (other agents edit other methods concurrently). Never rewrite the whole file.
5. DB-driven parts (news lists, newsletters, digital library, events, publications) keep their own modules; their static headings/intro text on the page are still CMS fields.
6. Locales: DB/CMS content is English; the site translates at render time. Do not touch lang files.
7. Long lists (> ~25 rows × several fields — PHP `max_input_vars` is 1000 per form) must NOT be repeaters: make a dedicated Eloquent model + admin CRUD module (see §5).
8. Beware of `@php` blocks or helper calls in the blade using the old array variable after the registry swap; grep for leftovers.

## 4. Verification you MUST run
Each developer has their own server port: `php artisan serve --port=<port>` (run in background) and `CMS_BASE=http://127.0.0.1:<port>` in the environment for node scripts.
Admin login for tests: `qa-admin` / `QaAdmin#2026` (local DB only).
* `node tests/e2e/regress.mjs check <url-substring...>` — default render must be IDENTICAL to the baseline snapshot of the original site
  (normalizer ignores quote/entity encoding, csrf, visitor counters). NEVER run `baseline` mode. If an intentional difference is unavoidable, stop and explain.
  (Footer/header are shared layout: if they differ, someone else's change is in flight — ignore diffs located inside the header/footer unless you own them.)
* `node tests/e2e/cms-audit.mjs <page-key-substring>` — proves every field is wired (marker test), toggles & hidden rows change the page, no-op save is a no-op, revert works. Must be all PASS.
* Write one targeted Playwright test per group, `tests/e2e/<group>.mjs` (see `about-vision-mission.mjs` and `lib.mjs`) for behaviour the audit cannot cover (Quill rich text typing, image upload, adding rows, dedicated-module CRUD).
* NEVER truncate `cms_blocks` or revert pages you do not own — other agents are testing concurrently on the same DB.
* Migrations: run ONLY your own file: `php artisan migrate --path=database/migrations/<file> --force`.
* Keep artisan/tinker commands short and non-interactive (never `artisan tinker <file>`).
* Shell: bash heredocs break on some quoting; prefer the Write tool to create files.

## 5. Dedicated modules (records/lists)
Pattern = existing `Admin\NewsletterController` + `resources/views/admin/newsletter/*` (Tailwind, `@extends('admin.layout')`).
* Model + migration (the migration itself must seed the existing hardcoded rows, only when the table is empty, so `migrate` on prod reproduces today's content).
* `app/Http/Controllers/Admin/<X>Controller.php`, views in `resources/views/admin/<x>/`.
* Routes: create `routes/admin/<x>.php` (auto-included inside the authenticated admin group; do NOT edit routes/web.php). Route names `admin.<x>.index|create|store|edit|update|destroy` (+ `toggle` for published flag).
  The sidebar/page-tree already link `admin.events.index` and `admin.publications.index` when those routes exist; for any other new module tell the lead (do not edit admin/layout.blade.php or cms/index.blade.php yourself).
* Fields: is_published (hide), sort_order, plus validation. File uploads on the `public` disk with a NEW unique filename each time (Cloudflare caches media forever).
* The public page must read from the DB and render identically to today.
* Test CRUD with Playwright: create → appears on public page; edit; hide (toggle) → gone; delete → gone; validation errors.

## 6. Reporting
Finish with: pages/modules done, files touched, test results (regress + audit summary), anything deliberately left hardcoded and why.
