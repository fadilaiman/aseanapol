<?php

namespace App\Support;

/**
 * Read access to the registry group "shared" (config/cms_pages/shared.php):
 * the single source for organisation identity, Secretariat contact, member forces,
 * Secretariat office holders and partner organisations.
 *
 * - Every read goes through Cms::get(), so admin overrides and hidden repeater rows are honoured.
 * - Nothing here throws: a missing page/key/row yields null (or an empty array for list readers).
 * - Derived row sets are memoised per request, keyed by a hash of the source rows, so they never go stale
 *   after an admin save inside the same request.
 */
class Shared
{
    /** @var array<string, mixed> */
    private static array $memo = [];

    private static function key(string $page): string
    {
        return 'shared.' . $page;
    }

    /** Cms value of shared.<page> at "section.field" (or $default). */
    public static function get(string $page, string $path, mixed $default = null): mixed
    {
        try {
            return Cms::get(self::key($page), $path, $default);
        } catch (\Throwable) {
            return $default;
        }
    }

    /** Every field of a shared page as [section => [field => value]] (with overrides). */
    public static function all(string $page): array
    {
        $def = Cms::page(self::key($page));
        $out = [];
        foreach (($def['sections'] ?? []) as $sKey => $section) {
            foreach (($section['fields'] ?? []) as $fKey => $_) {
                $out[$sKey][$fKey] = self::get($page, "$sKey.$fKey");
            }
        }

        return $out;
    }

    /** Organisation facts. org() = whole tree; org('identity.name_long') = one value. */
    public static function org(?string $path = null, mixed $default = null): mixed
    {
        return $path === null ? self::all('organisation') : self::get('organisation', $path, $default);
    }

    /** Secretariat facts. secretariat('contact.phone'), secretariat('eads.name'); no path = whole tree. */
    public static function secretariat(?string $path = null, mixed $default = null): mixed
    {
        return $path === null ? self::all('secretariat') : self::get('secretariat', $path, $default);
    }

    // ------------------------------------------------------------------ members

    /**
     * Visible member rows, in display order. Each row = the stored fields plus:
     * name (composed "Force (ABBR)"), tels (non-empty [tel, tel2]), flag (emoji from iso).
     *
     * @return array<int, array<string, mixed>>
     */
    public static function members(): array
    {
        $rows = self::rowsOf('members', 'list.rows');

        return self::remember('members', $rows, function () use ($rows) {
            return array_values(array_map(function (array $r) {
                $r['name'] = self::compose($r['force'] ?? '', $r['abbreviation'] ?? '');
                $r['tels'] = array_values(array_filter([$r['tel'] ?? '', $r['tel2'] ?? ''], fn ($v) => trim((string) $v) !== ''));
                $r['flag'] = self::flag($r['iso'] ?? '');

                return $r;
            }, $rows));
        });
    }

    /** One visible member by key (brunei, cambodia, ..., timor-leste), or null (also null when hidden). */
    public static function member(string $key): ?array
    {
        foreach (self::members() as $r) {
            if (($r['key'] ?? null) === $key) {
                return $r;
            }
        }

        return null;
    }

    /** "Indonesian National Police (INP)" for a member key, or null. */
    public static function memberName(string $key): ?string
    {
        $m = self::member($key);

        return $m ? $m['name'] : null;
    }

    // ------------------------------------------------------------------ roster

    /**
     * Visible office holders in stored order (oldest first). Filter by post ('ed'|'dps'|'dpp') and/or is_current.
     * Each row = stored fields plus: full_name ("rank name"), term ("2024–2025" / "2026–present" / "2026"),
     * force (force_label, else the member's composed name, else ''), flag (emoji), iso.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function roster(?string $post = null, ?bool $current = null, bool $newestFirst = false): array
    {
        $rows = self::rowsOf('roster', 'people.rows');
        $all = self::remember('roster', $rows, function () use ($rows) {
            return array_values(array_map(function (array $r) {
                $m = !empty($r['member_key']) ? self::memberAny($r['member_key']) : null;
                $r['full_name'] = trim(trim((string) ($r['rank'] ?? '')) . ' ' . trim((string) ($r['name'] ?? '')));
                $s = trim((string) ($r['term_start'] ?? ''));
                $e = trim((string) ($r['term_end'] ?? ''));
                $r['term'] = $s === '' ? $e : ($e === '' ? "{$s}–present" : "{$s}–{$e}");
                $r['force'] = trim((string) ($r['force_label'] ?? '')) !== '' ? $r['force_label'] : ($m['name'] ?? '');
                $r['iso'] = $m['iso'] ?? '';
                $r['flag'] = $m['flag'] ?? '';

                return $r;
            }, $rows));
        });

        $out = array_values(array_filter($all, function (array $r) use ($post, $current) {
            return ($post === null || ($r['post'] ?? null) === $post)
                && ($current === null || (bool) ($r['is_current'] ?? false) === $current);
        }));

        return $newestFirst ? array_reverse($out) : $out;
    }

    /** The current holder of a post, or null. */
    public static function currentHolder(string $post): ?array
    {
        return self::roster($post, true)[0] ?? null;
    }

    // ------------------------------------------------------------------ partners

    /**
     * Visible international organisations (LME / cooperation / footer). $where filters on stored fields,
     * e.g. partners(['is_lme' => true]) or partners(['is_cooperation_partner' => true]).
     * A plain string is shorthand: 'lme' | 'cooperation'.
     */
    public static function partners(array|string $where = []): array
    {
        if (is_string($where)) {
            $where = match ($where) {
                'lme' => ['is_lme' => true],
                'cooperation' => ['is_cooperation_partner' => true],
                default => [],
            };
        }

        return self::filter(self::rowsOf('partners', 'list.rows'), $where);
    }

    /** One international organisation by key (interpol, europol, accbp, iafi, apta, ...), or null. */
    public static function partner(string $key): ?array
    {
        foreach (self::partners() as $r) {
            if (($r['key'] ?? null) === $key) {
                return $r + ['short' => ($r['short_name'] ?? '') !== '' ? $r['short_name'] : ($r['name'] ?? '')];
            }
        }

        return null;
    }

    /** Dialogue partners / observers. dialogue(['is_dialogue' => true]) or dialogue(['is_observer' => true]); string: 'dialogue'|'observer'. */
    public static function dialogue(array|string $where = []): array
    {
        if (is_string($where)) {
            $where = match ($where) {
                'dialogue' => ['is_dialogue' => true],
                'observer' => ['is_observer' => true],
                default => [],
            };
        }

        return self::filter(array_map([self::class, 'withPartnerFacts'], self::rowsOf('dialogue', 'list.rows')), $where);
    }

    public static function dialogueEntry(string $key): ?array
    {
        foreach (self::dialogue() as $r) {
            if (($r['key'] ?? null) === $key) {
                return $r;
            }
        }

        return null;
    }

    // ------------------------------------------------------------------ i18n fallback helpers

    /**
     * True when the shared field(s) still equal the registry default (no admin override).
     * $paths: "section.field" or an array of them. Use it to keep lang-file translations for non-English
     * locales: SiteCms::t()/rows() compare against the page's OWN registry default, which no longer holds a
     * value that moved into shared — so ask here instead.
     */
    public static function isDefault(string $page, array|string $paths): bool
    {
        try {
            foreach ((array) $paths as $p) {
                if (Cms::get(self::key($page), $p) !== (Cms::fieldDef(self::key($page), $p)['default'] ?? null)) {
                    return false;
                }
            }
        } catch (\Throwable) {
            return false;
        }

        return true;
    }

    /**
     * Like SiteCms::t() for a shared value: in a non-English locale, an un-overridden value is replaced by
     * __($langKey); an admin-edited value always wins. Returns the English value when no lang key is given.
     */
    public static function t(string $page, string $path, ?string $langKey = null, bool $decode = false): mixed
    {
        $value = self::get($page, $path);
        if ($langKey && app()->getLocale() !== 'en' && self::isDefault($page, $path)) {
            $value = __($langKey);
        }

        return ($decode && is_string($value)) ? html_entity_decode($value, ENT_QUOTES | ENT_HTML5) : $value;
    }

    /**
     * True when one row's field(s) (member, roster person, partner, dialogue entry) are unchanged from the default row
     * with the same key. $page = members|roster|partners|dialogue. Roster rows are matched by "post|ordinal".
     */
    public static function rowIsDefault(string $page, string $key, array|string $fields): bool
    {
        try {
            $path = $page === 'members' || $page === 'roster' ? ($page === 'members' ? 'list.rows' : 'people.rows') : 'list.rows';
            $cur = Cms::get(self::key($page), $path) ?? [];
            $def = Cms::fieldDef(self::key($page), $path)['default'] ?? [];
            $id = fn (array $r) => $page === 'roster' ? (($r['post'] ?? '') . '|' . ($r['ordinal'] ?? '')) : ($r['key'] ?? '');
            $find = function (array $rows) use ($id, $key) {
                foreach ($rows as $r) {
                    if (is_array($r) && $id($r) === $key) {
                        return $r;
                    }
                }

                return null;
            };
            $c = $find($cur);
            $d = $find($def);
            if ($c === null || $d === null) {
                return false;
            }
            foreach ((array) $fields as $f) {
                if (($c[$f] ?? null) !== ($d[$f] ?? null)) {
                    return false;
                }
            }
        } catch (\Throwable) {
            return false;
        }

        return true;
    }

    // ------------------------------------------------------------------ internals

    private static function rowsOf(string $page, string $path): array
    {
        $rows = self::get($page, $path, []);

        return is_array($rows) ? array_values(array_filter($rows, 'is_array')) : [];
    }

    private static function filter(array $rows, array $where): array
    {
        return array_values(array_filter($rows, function (array $r) use ($where) {
            foreach ($where as $f => $v) {
                $have = $r[$f] ?? null;
                if (is_bool($v) ? (bool) $have !== $v : (string) $have !== (string) $v) {
                    return false;
                }
            }

            return true;
        }));
    }

    /** A member row by key INCLUDING hidden ones (a hidden member must not blank the force of a past office holder). */
    private static function memberAny(string $key): ?array
    {
        try {
            $rows = Cms::raw('shared.members', 'list.rows', []);
        } catch (\Throwable) {
            return null;
        }
        foreach ((array) $rows as $r) {
            if (is_array($r) && ($r['key'] ?? null) === $key) {
                $r['name'] = self::compose($r['force'] ?? '', $r['abbreviation'] ?? '');
                $r['flag'] = self::flag($r['iso'] ?? '');

                return $r;
            }
        }

        return null;
    }

    /**
     * A dialogue/observer row that shares its key with an international organisation (INTERPOL, Europol) takes
     * its empty name / full_name / website from Partner organisations (hidden partners included), so those facts live once.
     */
    private static function withPartnerFacts(array $r): array
    {
        $key = $r['key'] ?? null;
        if (!$key) {
            return $r;
        }
        try {
            $rows = Cms::raw('shared.partners', 'list.rows', []);
        } catch (\Throwable) {
            return $r;
        }
        foreach ((array) $rows as $p) {
            if (is_array($p) && ($p['key'] ?? null) === $key) {
                foreach (['name', 'full_name', 'website'] as $f) {
                    if (trim((string) ($r[$f] ?? '')) === '' && trim((string) ($p[$f] ?? '')) !== '') {
                        $r[$f] = $p[$f];
                    }
                }
                break;
            }
        }

        return $r;
    }

    private static function compose(string $force, string $abbr): string
    {
        $force = trim($force);
        $abbr = trim($abbr);

        return $abbr === '' ? $force : ($force === '' ? $abbr : "$force ($abbr)");
    }

    private static function flag(string $iso): string
    {
        $iso = strtolower(trim($iso));
        if (!preg_match('/^[a-z]{2}$/', $iso) || !function_exists('mb_chr')) {
            return '';
        }

        return mb_chr(0x1F1E6 + ord($iso[0]) - 97) . mb_chr(0x1F1E6 + ord($iso[1]) - 97);
    }

    private static function remember(string $tag, array $source, \Closure $fn): array
    {
        $k = $tag . ':' . md5((string) json_encode($source));

        return self::$memo[$k] ??= $fn();
    }
}
