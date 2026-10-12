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

    /** Cards for the Member Countries list page (hidden cards are skipped). */
    public static function cards(): array
    {
        $out = [];
        foreach (self::SLUGS as $slug) {
            if (!Cms::visible('members.index', $slug)) {
                continue;
            }
            $key = 'members.' . $slug;
            $force = (string) Cms::get($key, 'identity.force');
            $abbr = (string) Cms::get($key, 'identity.abbreviation');
            $name = (string) Cms::get('members.index', $slug . '.name');
            $out[] = [
                'name'    => $name !== '' ? $name : ($abbr !== '' ? $force . ' (' . $abbr . ')' : $force),
                'country' => (string) Cms::get($key, 'identity.country'),
                'iso'     => (string) Cms::get($key, 'identity.iso'),
                'since'   => (string) Cms::get('members.index', $slug . '.since'),
                'badge'   => (string) Cms::get('members.index', $slug . '.badge'),
                'route'   => 'about.member.' . $slug,
            ];
        }

        return $out;
    }

    /** Data for information/member.blade.php. */
    public static function data(string $slug): array
    {
        abort_unless(in_array($slug, self::SLUGS, true), 404);
        $p = 'members.' . $slug;
        $rows = fn (string $section, string $field) => Cms::visible($p, $section) ? (array) Cms::get($p, "$section.$field", []) : [];

        $labels = [];
        foreach (['members.labels', 'members.labels-pending'] as $labelPage) {
            foreach (Cms::page($labelPage)['sections'] as $sKey => $section) {
                foreach (array_keys($section['fields']) as $fKey) {
                    $labels[$fKey] = (string) Cms::get($labelPage, "$sKey.$fKey");
                }
            }
        }

        return [
            'country'           => (string) Cms::get($p, 'identity.country'),
            'force'             => (string) Cms::get($p, 'identity.force'),
            'abbreviation'      => (string) Cms::get($p, 'identity.abbreviation'),
            'iso'               => (string) Cms::get($p, 'identity.iso'),
            'badge_label'       => (string) Cms::get($p, 'identity.badge_label'),
            'observer_note'     => (string) Cms::get($p, 'identity.note'),
            'logo'              => Cms::url((string) Cms::get($p, 'identity.logo')),
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
            'tel'               => (array) Cms::get($p, 'contact.tel', []),
            'fax'               => (string) Cms::get($p, 'contact.fax'),
            'email'             => (string) Cms::get($p, 'contact.email'),
            'website'           => (string) Cms::get($p, 'contact.website'),
            'L'                 => $labels,
            'list_title'        => (string) Cms::get('members.index', 'hero.title'),
        ];
    }
}
