<?php

namespace App\Support;

/**
 * Builds the view data for the member-country pages from the CMS registry
 * (config/cms_pages/members.php). Page keys are "members.<slug>", labels are "members.labels",
 * the list page is "members.index".
 */
class MemberPages
{
    /** Display order of the member countries (also the order of the cards on the list page). */
    public const SLUGS = [
        'brunei', 'cambodia', 'indonesia', 'lao', 'malaysia', 'myanmar',
        'philippines', 'singapore', 'thailand', 'vietnam', 'timor-leste',
    ];

    /**
     * Replaces the count tokens editors can use in wording: {count} => "eleven", {Count} => "Eleven",
     * {count_num} => "11" (from Shared facts > Organisation > Counts).
     */
    public static function fill(string $text): string
    {
        if (!str_contains($text, '{count')  && !str_contains($text, '{Count')) {
            return $text;
        }
        // live count of the visible rows in Shared facts > Member countries (the stored word/number are only used while they still match it)
        $word = SiteCms::memberCountWord();
        $num = (string) SiteCms::memberCount();

        return str_replace(['{count_num}', '{count}', '{Count}'], [$num, $word, ucfirst($word)], $text);
    }

    /** Shared member row for a slug; unlike Shared::member() it also returns a row hidden in Shared (the country page stays reachable). */
    private static function row(string $slug): array
    {
        if ($m = Shared::member($slug)) {
            return $m;
        }
        foreach ((array) Cms::raw('shared.members', 'list.rows', []) as $r) {
            if (is_array($r) && ($r['key'] ?? null) === $slug) {
                $r['tels'] = array_values(array_filter([$r['tel'] ?? '', $r['tel2'] ?? ''], fn ($v) => trim((string) $v) !== ''));

                return $r;
            }
        }

        return [];
    }

    /** True for a member that has no built-in page/route: it is served by the generic data-driven route. */
    public static function isGeneric(string $key): bool
    {
        return !in_array($key, self::SLUGS, true);
    }

    /** Public URL of a member country page (built-in named route, or the generic one). */
    public static function url(string $key, ?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();

        return self::isGeneric($key)
            ? route('about.member.generic', ['locale' => $locale, 'slug' => $key])
            : route('about.member.' . $key, ['locale' => $locale]);
    }

    /** Cards for the Member Countries list page: EVERY visible row of Shared facts > Member countries (hidden ones are skipped). */
    public static function cards(): array
    {
        $out = [];
        foreach (Shared::members() as $m) {
            $key = (string) ($m['key'] ?? '');
            if ($key === '' || !preg_match('/^[a-z0-9][a-z0-9-]{0,60}$/', $key)) {
                continue;
            }
            $out[] = [
                'name'    => (string) $m['name'],
                'country' => (string) ($m['country'] ?? ''),
                'iso'     => (string) ($m['iso'] ?? ''),
                'since'   => (string) ($m['joined_year'] ?? ''),
                'badge'   => (string) ($m['badge'] ?? ''),
                'url'     => self::url($key),
            ];
        }

        return $out;
    }

    /** Data for information/member.blade.php. */
    public static function data(string $slug): array
    {
        // built-in countries always resolve; a generic (added) member only while it is visible in Shared facts
        abort_unless(preg_match('/^[a-z0-9][a-z0-9-]{0,60}$/', $slug) && (!self::isGeneric($slug) || Shared::member($slug)), 404);
        $p = 'members.' . $slug;
        abort_unless(Cms::page($p), 404);
        $rows = fn (string $section, string $field) => Cms::visible($p, $section) ? (array) Cms::get($p, "$section.$field", []) : [];

        $m = self::row($slug);
        $labels = [];
        foreach (['members.labels', 'members.labels-pending'] as $labelPage) {
            foreach (Cms::page($labelPage)['sections'] as $sKey => $section) {
                foreach (array_keys($section['fields']) as $fKey) {
                    $labels[$fKey] = self::fill((string) Cms::get($labelPage, "$sKey.$fKey"));
                }
            }
        }

        return [
            'country'           => (string) ($m['country'] ?? ''),
            'force'             => (string) ($m['force'] ?? ''),
            'abbreviation'      => (string) ($m['abbreviation'] ?? ''),
            'iso'               => (string) ($m['iso'] ?? ''),
            'badge_label'       => (string) Cms::get($p, 'identity.badge_label'),
            'observer_note'     => (string) Cms::get($p, 'identity.note'),
            'logo'              => Cms::url((string) ($m['logo'] ?? '')),
            'description'       => (string) Cms::get($p, 'overview.description'),
            'history'           => $rows('history', 'items'),
            'chief_name'        => (string) Cms::get($p, 'chief.name'),
            'chief_rank'        => (string) Cms::get($p, 'chief.rank'),
            'chief_designation' => (string) Cms::get($p, 'chief.designation'),
            'chief_photo'       => Cms::url((string) Cms::get($p, 'chief.photo')),
            'org_chart'         => Cms::visible($p, 'org_chart') ? Cms::url((string) Cms::get($p, 'org_chart.file')) : '',
            'official_positions' => $rows('positions', 'items'),
            'rank_structure'    => $rows('ranks', 'items'),
            'contacts'          => array_map(
                fn ($c) => $c + ['photo_url' => Cms::url((string) ($c['photo'] ?? ''))],
                $rows('contacts', 'items')
            ),
            'tel'               => (array) ($m['tels'] ?? []),
            'fax'               => (string) ($m['fax'] ?? ''),
            'email'             => (string) ($m['ncb_email'] ?? ''),
            'website'           => (string) ($m['website'] ?? ''),
            'L'                 => $labels,
            'list_title'        => (string) Cms::get('members.index', 'hero.title'),
        ];
    }
}
