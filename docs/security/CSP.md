# Content-Security-Policy rollout

`app/Http/Middleware/SecurityHeaders.php` sends two headers:

1. **Enforced** (`Content-Security-Policy`): `object-src 'none'; base-uri 'self'; frame-ancestors 'self'`.
   Safe for all pages; blocks plugin embeds, `<base>` hijacking and framing by other sites.
2. **Report-Only** (`Content-Security-Policy-Report-Only`): the full allow-list. Blocks nothing.

## Tightening procedure
1. Open each public page type (home, news article, gallery, events, publications, contact, member pages) with
   DevTools console open; note "[Report Only] Refused to ..." lines.
2. Add legitimate hosts to the report-only list; repeat until the console is clean.
3. Move directives (`frame-src`, `connect-src`, `form-action`, `font-src`, `img-src`) into the enforced header.
4. `script-src` still carries `'unsafe-inline'` (4 inline blocks + ~21 inline handlers, Tailwind CDN): it gives
   **no protection against injected `<script>`**. The real XSS defence is sanitising HTML on save
   (`App\Support\HtmlSanitizer`), including AI-translated article bodies.
   To drop `'unsafe-inline'`: move inline scripts to files/nonces, replace `onclick=` with listeners, and
   replace the Tailwind Play CDN with the compiled build.
