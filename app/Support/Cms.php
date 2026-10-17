<?php

namespace App\Support;

use App\Models\CmsBlock;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

/**
 * Registry-driven page content store.
 *
 * - Page definitions (sections, fields, DEFAULT values = the original hardcoded content)
 *   live in config/cms_pages/{group}.php. Page key = "{group}.{slug}".
 * - Admin overrides live in the cms_blocks table (only values that differ from the default).
 * - Public views call cms($page, 'section.field'), cms_on($page, 'section'), cms_img(...).
 *   With an empty table the site renders exactly the defaults.
 */
class Cms
{
    /** @var array<string, array> */
    private static array $groups = [];
    /** @var array<string, array<string, mixed>> */
    private static array $blocks = [];

    // ------------------------------------------------------------------ registry

    public static function groupKeys(): array
    {
        $keys = [];
        foreach (glob(config_path('cms_pages/*.php')) ?: [] as $file) {
            $name = basename($file, '.php');
            if ($name[0] !== '_') {
                $keys[] = $name;
            }
        }
        sort($keys);

        return $keys;
    }

    /** @return array{_group: array, pages: array} */
    public static function group(string $group): array
    {
        if (!isset(self::$groups[$group])) {
            $file = config_path('cms_pages/' . basename($group) . '.php');
            $def = is_file($file) ? (require $file) : [];
            $meta = $def['_group'] ?? ['label' => Str::headline($group), 'icon' => 'article', 'order' => 99];
            unset($def['_group']);
            self::$groups[$group] = ['_group' => $meta, 'pages' => $def];
        }

        return self::$groups[$group];
    }

    /** All groups, ordered. @return array<string, array> */
    public static function allGroups(): array
    {
        $out = [];
        foreach (self::groupKeys() as $g) {
            $out[$g] = self::group($g);
        }
        uasort($out, fn ($a, $b) => ($a['_group']['order'] ?? 99) <=> ($b['_group']['order'] ?? 99));

        return $out;
    }

    public static function page(string $pageKey): ?array
    {
        [$group, $slug] = array_pad(explode('.', $pageKey, 2), 2, null);

        return $slug ? (self::group($group)['pages'][$slug] ?? null) : null;
    }

    public static function fieldDef(string $pageKey, string $path): ?array
    {
        [$section, $field] = array_pad(explode('.', $path, 2), 2, null);

        return self::page($pageKey)['sections'][$section]['fields'][$field] ?? null;
    }

    // ------------------------------------------------------------------ reading

    private static function blocks(string $pageKey): array
    {
        if (!isset(self::$blocks[$pageKey])) {
            try {
                self::$blocks[$pageKey] = CmsBlock::where('page_key', $pageKey)->get()
                    ->mapWithKeys(fn ($b) => [$b->block_key => json_decode($b->value ?? 'null', true)])
                    ->all();
            } catch (\Throwable) {
                self::$blocks[$pageKey] = [];
            }
        }

        return self::$blocks[$pageKey];
    }

    public static function forgetMemo(): void
    {
        self::$blocks = [];
        self::$groups = [];
    }

    /** Raw value incl. hidden repeater rows (used by the admin editor). */
    public static function raw(string $pageKey, string $path, mixed $default = null): mixed
    {
        $blocks = self::blocks($pageKey);
        $def = self::fieldDef($pageKey, $path);
        if (array_key_exists($path, $blocks)) {
            // a repeater/list must always be an array (guards against a corrupt stored value)
            if ($def && in_array($def['type'] ?? '', ['repeater', 'list'], true) && !is_array($blocks[$path])) {
                return $def['default'] ?? [];
            }

            // a scalar field (text, url, number, ...) must never hold an array (corrupt row) - it would 500 every page that prints it
            if ($def && is_array($blocks[$path]) && !in_array($def['type'] ?? '', ['repeater', 'list'], true)) {
                return $def['default'] ?? $default;
            }

            return $blocks[$path];
        }

        return $def ? ($def['default'] ?? $default) : $default;
    }

    /** Public value: override else registry default else $default. Hidden repeater rows removed. */
    public static function get(string $pageKey, string $path, mixed $default = null): mixed
    {
        $value = self::raw($pageKey, $path, $default);
        $def = self::fieldDef($pageKey, $path);
        if ($def && ($def['type'] ?? '') === 'repeater' && is_array($value)) {
            return self::visibleRows($value);
        }

        return $value;
    }

    private static function visibleRows(array $rows): array
    {
        $out = [];
        foreach ($rows as $row) {
            if (!is_array($row)) {
                $out[] = $row;
                continue;
            }
            if (!empty($row['_hide'])) {
                continue;
            }
            foreach ($row as $k => $v) {
                if (is_array($v) && array_is_list($v) && $v && is_array($v[0])) {
                    $row[$k] = self::visibleRows($v);
                }
            }
            $out[] = $row;
        }

        return $out;
    }

    public static function visible(string $pageKey, string $section): bool
    {
        $blocks = self::blocks($pageKey);

        return ($blocks[$section . '.__visible'] ?? true) !== false;
    }

    public static function url(?string $path): string
    {
        $path = (string) $path;
        if ($path === '') {
            return '';
        }
        if (preg_match('#^(https?:)?//#i', $path) || str_starts_with($path, '/') || str_starts_with($path, 'data:')) {
            return $path;
        }

        return asset($path);
    }

    /** Changes whenever any override of the page is added/changed/removed (edit-conflict detection). */
    public static function fingerprint(string $pageKey): string
    {
        return md5(CmsBlock::where('page_key', $pageKey)->orderBy('block_key')->get(['block_key', 'value'])
            ->map(fn ($b) => $b->block_key . '@' . md5((string) $b->value))->implode('|'));
    }

    public static function customizedCount(string $pageKey): int
    {
        return count(self::blocks($pageKey));
    }

    // ------------------------------------------------------------------ writing

    public static function save(string $pageKey, Request $request): array
    {
        $page = self::page($pageKey);
        abort_unless($page, 404);

        $input = (array) $request->input('f', []);
        $vis = (array) $request->input('vis', []);
        $files = (array) $request->file('files', []);
        $existing = CmsBlock::where('page_key', $pageKey)->pluck('block_key')->all();
        $keep = [];
        $saved = 0;

        foreach ($page['sections'] as $sKey => $section) {
            // visibility (only when the form rendered the toggle for this section)
            if (!empty($section['toggle']) && array_key_exists($sKey, $vis)) {
                $key = $sKey . '.__visible';
                if ($vis[$sKey] !== '1') {
                    self::put($pageKey, $key, false);
                    $keep[] = $key;
                    $saved++;
                }
            } elseif (!empty($section['toggle']) && in_array($sKey . '.__visible', $existing, true)) {
                $keep[] = $sKey . '.__visible';
            }

            foreach ($section['fields'] as $fKey => $def) {
                $key = "$sKey.$fKey";
                if (!is_array($input[$sKey] ?? [])) {
                    continue; // malformed section payload (f[section]=string) -> treat as absent
                }
                if (!array_key_exists($fKey, $input[$sKey] ?? []) && !isset($files[$sKey][$fKey])) {
                    // field not present in the form -> leave any existing override untouched
                    if (in_array($key, $existing, true)) {
                        $keep[] = $key;
                    }
                    continue;
                }
                $value = self::normalize($def, $input[$sKey][$fKey] ?? null, $files[$sKey][$fKey] ?? null);
                $default = self::normalize($def, $def['default'] ?? null, null);
                if ($value === $default) {
                    continue; // equals default -> no override row
                }
                self::put($pageKey, $key, $value);
                $keep[] = $key;
                $saved++;
            }
        }

        CmsBlock::where('page_key', $pageKey)->whereNotIn('block_key', $keep)->delete();
        self::forgetMemo();

        return ['saved' => $saved];
    }

    public static function reset(string $pageKey, ?string $section = null): void
    {
        $q = CmsBlock::where('page_key', $pageKey);
        if ($section) {
            abort_unless(isset(self::page($pageKey)['sections'][$section]), 404);
            $q->where('block_key', 'like', addcslashes($section, '%_\\') . '.%');
        }
        $q->delete();
        self::forgetMemo();
    }

    private static function put(string $pageKey, string $key, mixed $value): void
    {
        $json = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE);
        if ($json === false) {
            return; // never store an unencodable value (would read back as garbage)
        }
        CmsBlock::updateOrCreate(
            ['page_key' => $pageKey, 'block_key' => $key],
            ['value' => $json]
        );
    }

    /** Normalize a submitted (or default) value according to the field type. */
    public static function normalize(array $def, mixed $raw, mixed $upload): mixed
    {
        $type = $def['type'] ?? 'text';

        // A crafted request can send an array where a scalar is expected: never let it reach (string) casts.
        if (is_array($raw) && !in_array($type, ['repeater', 'list'], true)) {
            $raw = '';
        }

        switch ($type) {
            case 'repeater':
                $rows = [];
                foreach ((array) $raw as $i => $row) {
                    if (!is_array($row)) {
                        continue;
                    }
                    $out = [];
                    $nonEmpty = false;
                    foreach ($def['fields'] as $k => $sub) {
                        $val = self::normalize($sub, $row[$k] ?? ($sub['default'] ?? null), is_array($upload) ? ($upload[$i][$k] ?? null) : null);
                        $out[$k] = $val;
                        if (!in_array($val, [null, '', [], false], true)) {
                            $nonEmpty = true;
                        }
                    }
                    if (!empty($row['_hide']) && $row['_hide'] !== '0') {
                        $out['_hide'] = true;
                    }
                    if ($nonEmpty) {
                        $rows[] = $out;
                    }
                }

                return $rows;

            case 'richtext':
            case 'html':
                return HtmlSanitizer::clean(is_string($raw) ? $raw : '');

            case 'list':
                if (is_array($raw)) {
                    return array_values(array_filter(array_map(fn ($v) => trim((string) $v), $raw), fn ($v) => $v !== ''));
                }

                return array_values(array_filter(array_map('trim', preg_split('/\R/u', (string) $raw) ?: []), fn ($v) => $v !== ''));

            case 'boolean':
                return filter_var($raw, FILTER_VALIDATE_BOOLEAN);

            case 'number':
                if ($raw === null || $raw === '' || !is_numeric($raw)) {
                    return null;
                }
                $n = $raw + 0;

                return (is_float($n) && !is_finite($n)) ? null : $n; // 1e999 -> INF would break json_encode

            case 'image':
            case 'file':
                if ($upload instanceof UploadedFile) {
                    if (!$upload->isValid()) {
                        // e.g. larger than PHP upload_max_filesize: tell the admin instead of silently ignoring it
                        throw ValidationException::withMessages(['files' => 'Upload failed: ' . $upload->getErrorMessage()]);
                    }

                    return self::store($upload, $type);
                }

                return self::safeUrl(trim((string) $raw));

            case 'url':
                return self::safeUrl(trim((string) $raw));

            case 'textarea':
                return str_replace("\r\n", "\n", (string) $raw);

            case 'select':
                $val = trim((string) $raw);
                $opts = [];
                foreach (($def['options'] ?? []) as $k => $v) {
                    $opts[] = (string) (is_int($k) ? $v : $k);
                }

                return in_array($val, $opts, true) ? $val : (string) ($def['default'] ?? '');

            default: // text, icon, date
                $val = trim((string) $raw);
                // Plain text fields are sometimes used as link targets by the views: never keep script-ish schemes.
                if (preg_match('/^(?:(?:javascript|vbscript):|data:[a-z]+\/)/i', preg_replace('/[\x00-\x20]+/', '', $val))) {
                    return '';
                }

                return $val;
        }
    }

    /** Drop dangerous schemes (javascript:, data:, vbscript: …) from admin-entered URLs/paths. */
    private static function safeUrl(string $url): string
    {
        $probe = preg_replace('/[\x00-\x20]+/', '', $url);
        if (preg_match('/^([a-z][a-z0-9+.\-]*):/i', $probe, $m)
            && !in_array(strtolower($m[1]), ['http', 'https', 'mailto', 'tel'], true)) {
            return '';
        }

        return $url;
    }

    private static function need(bool $ok, string $message): void
    {
        if (!$ok) {
            throw ValidationException::withMessages(['files' => $message]);
        }
    }

    private static function store(UploadedFile $file, string $type): string
    {
        $ext = strtolower($file->getClientOriginalExtension());
        $allowed = $type === 'image'
            ? ['jpg', 'jpeg', 'png', 'gif', 'webp']
            : ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'zip', 'jpg', 'jpeg', 'png'];
        self::need(in_array($ext, $allowed, true), 'File type not allowed: .' . $ext);
        self::need($file->getSize() <= 25 * 1024 * 1024, 'File too large (max 25 MB)');
        // The extension is client-controlled: also check the real content type.
        $mime = (string) $file->getMimeType();
        if ($type === 'image') {
            self::need(in_array($mime, ['image/jpeg', 'image/png', 'image/gif', 'image/webp'], true), 'Not a valid image file');
        } else {
            self::need(!(str_starts_with($mime, 'text/') || str_contains($mime, 'xml') || str_contains($mime, 'html') || str_contains($mime, 'php')), 'File content not allowed');
        }

        // New unique filename every time (Cloudflare caches media URLs as immutable).
        $name = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'file';
        $name = Str::limit($name, 40, '') . '-' . now()->format('ymdHis') . '-' . Str::lower(Str::random(5)) . '.' . $ext;
        $file->storeAs('cms', $name, 'public');

        return 'storage/cms/' . $name;
    }
}
