<?php

/**
 * CMS registry — group "about-people" (Leadership, Like-Minded Entities, Dialogue Partners, Observers).
 * Page key = "about-people.<slug>". DEFAULT values are the original hardcoded content.
 */

$hero = fn (string $title, string $subtitle) => [
    'label'  => 'Page header',
    'fields' => [
        'title'    => ['type' => 'text',     'label' => 'Title',    'default' => $title],
        'subtitle' => ['type' => 'textarea', 'label' => 'Subtitle', 'rows' => 2, 'default' => $subtitle],
    ],
];

/** "Current post holder" card shared by leadership pages. The person (photo, name, force, country, bio, term) is read from Shared facts. */
$current = fn (string $heading, string $alt, string $badge) => [
    'label'  => 'Current post holder',
    'toggle' => true,
    'help'   => 'The person (photo, name and rank, force, country, biography, term) is edited once under "Shared facts (used across pages)" > Secretariat office holders (roster). Only the wording below is local to this page.',
    'fields' => [
        'heading' => ['type' => 'text', 'label' => 'Section heading', 'default' => $heading],
        'alt'     => ['type' => 'text', 'label' => 'Photo alt text', 'default' => $alt],
        'badge'   => ['type' => 'text', 'label' => 'Label above name', 'help' => 'Placeholders: {ordinal} (e.g. 9th) and {term} (e.g. 2026–2027) are filled from the roster.', 'default' => $badge],
    ],
];

/** Past post holders table (rows come from Shared::roster()). */
$past = function (string $heading, string $intro, bool $withTerm) {
    $cols = [
        'col_no'      => ['type' => 'text', 'label' => 'Column heading: number', 'default' => '#'],
        'col_name'    => ['type' => 'text', 'label' => 'Column heading: name', 'default' => 'Name & Rank'],
        'col_country' => ['type' => 'text', 'label' => 'Column heading: country', 'default' => 'Country'],
    ];
    if ($withTerm) {
        $cols['col_term'] = ['type' => 'text', 'label' => 'Column heading: term', 'default' => 'Term'];
    }
    return [
        'label'  => 'Past post holders',
        'toggle' => true,
        'help'   => 'The people (number, photo, name and rank, force, flag, term; newest first) are edited once under "Shared facts (used across pages)" > Secretariat office holders (roster). Only the wording below is local to this page.',
        'fields' => array_merge([
            'heading' => ['type' => 'text',     'label' => 'Section heading', 'default' => $heading],
            'intro'   => ['type' => 'textarea', 'label' => 'Introduction',    'rows' => 2, 'default' => $intro],
        ], $cols),
    ];
};

return [
    '_group' => ['label' => 'About — Leadership & Partners', 'icon' => 'groups', 'order' => 11],

    // ───────────────────────────── Leadership
    'leadership' => [
        'label' => 'Leadership',
        'route' => 'about.leadership',
        'sections' => [
            'hero' => $hero('Leadership', 'The ASEANAPOL Secretariat is led by an Executive Director nominated by a member country on a rotational two-year basis.'),
            'current' => $current('Current Executive Director', 'Executive Director', '{ordinal} Executive Director · {term}'),
            'directors' => [
                'label'  => 'Secretariat Directors cards',
                'toggle' => true,
                'help'   => 'Cards linking to the Director for Police Services / Plans and Programmes pages. The current holder (name and rank, force, country) is read from Shared facts > Secretariat office holders (roster).',
                'fields' => [
                    'heading' => ['type' => 'text', 'label' => 'Section heading', 'default' => 'Secretariat Directors'],
                    'cards' => [
                        'type' => 'repeater', 'label' => 'Director cards', 'item_label' => 'role', 'add_label' => 'Add card',
                        'fields' => [
                            'target'  => ['type' => 'select', 'label' => 'Links to page', 'options' => ['dps' => 'Director for Police Services page', 'dpp' => 'Director for Plans and Programmes page']],
                            'icon'    => ['type' => 'icon',   'label' => 'Icon'],
                            'role'    => ['type' => 'text',   'label' => 'Post title'],
                            'link'    => ['type' => 'text',   'label' => 'Link text'],
                        ],
                        'default' => [
                            ['target' => 'dps', 'icon' => 'shield_person', 'role' => 'Director for Police Services',       'link' => 'View all directors →'],
                            ['target' => 'dpp', 'icon' => 'analytics',     'role' => 'Director for Plans and Programmes', 'link' => 'View all directors →'],
                        ],
                    ],
                ],
            ],
            'past' => $past(
                'Past Executive Directors',
                'The Executive Director post rotates among ASEANAPOL member countries on a two-year term, beginning with the establishment of the Secretariat on 1 January 2010.',
                true
            ),
        ],
    ],

    // ───────────────────────────── Director for Police Services
    'leadership-dps' => [
        'label' => 'Director for Police Services',
        'route' => 'about.leadership.dps',
        'sections' => [
            'hero' => $hero('Director for Police Services', 'The Director for Police Services oversees police cooperation and operational matters within the ASEANAPOL Secretariat.'),
            'current' => $current('Current Director for Police Services', 'Director for Police Services', 'Director for Police Services · {term}'),
            'past' => $past(
                'Past Directors for Police Services',
                'The Director for Police Services post rotates among ASEANAPOL member countries within the Secretariat.',
                false
            ),
        ],
    ],

    // ───────────────────────────── Director for Plans and Programmes
    'leadership-dpp' => [
        'label' => 'Director for Plans and Programmes',
        'route' => 'about.leadership.dpp',
        'sections' => [
            'hero' => $hero('Director for Plans and Programmes', 'The Director for Plans and Programmes oversees strategic planning and programme implementation within the ASEANAPOL Secretariat.'),
            'current' => $current('Current Director for Plans and Programmes', 'Director for Plans and Programmes', 'Director for Plans and Programmes · {term}'),
            'past' => $past(
                'Past Directors for Plans and Programmes',
                'The Director for Plans and Programmes post rotates among ASEANAPOL member countries within the Secretariat.',
                false
            ),
        ],
    ],

    // ───────────────────────────── Like-Minded Entities
    'ob-lme' => [
        'label' => 'Like-Minded Entities',
        'route' => 'about.ob-lme',
        'sections' => [
            'hero' => $hero('Like-Minded Entities', 'International organisations that collaborate with ASEANAPOL as Like-Minded Entities (LME).'),
            'intro' => [
                'label'  => 'Explainer text',
                'toggle' => true,
                'fields' => [
                    'p1' => ['type' => 'textarea', 'label' => 'Paragraph 1', 'rows' => 4, 'default' => 'Like-Minded Entities (LMEs) are international organisations, agencies, and bodies that share common goals and values with ASEANAPOL in combating transnational crime, promoting regional security, and strengthening law enforcement cooperation across borders.'],
                    'p2' => ['type' => 'textarea', 'label' => 'Paragraph 2', 'rows' => 4, 'default' => 'Through these collaborative relationships, ASEANAPOL and its LME partners exchange information, coordinate joint initiatives, and build capacity across a wide range of policing and security domains.'],
                ],
            ],
            'entities' => [
                'label'  => 'Entity grid',
                'toggle' => true,
                'help'   => 'The entities (short name, full name, logo, website) are edited once under "Shared facts (used across pages)" > Partner organisations (tick "Like-Minded Entity").',
                'fields' => [
                    'website_label' => ['type' => 'text', 'label' => 'Website link text', 'default' => 'Official Website'],
                ],
            ],
        ],
    ],

    // ───────────────────────────── Dialogue Partners
    'dialogue-partners' => [
        'label' => 'Dialogue Partners',
        'route' => 'about.dialogue-partners',
        'sections' => [
            'hero' => $hero('Dialogue Partners', 'International law enforcement organisations with formal dialogue status with ASEANAPOL.'),
            'intro' => [
                'label'  => 'Introduction',
                'toggle' => true,
                'help'   => 'Sentence is rendered as: text before + highlighted text + text after.',
                'fields' => [
                    'before'    => ['type' => 'textarea', 'label' => 'Text before highlight', 'rows' => 4, 'default' => 'Dialogue Partners are international law enforcement organisations and agencies that maintain formal cooperative relationships with ASEANAPOL. They participate in annual conferences and meetings, contribute to capacity-building programmes, and engage in joint initiatives targeting transnational crime. ASEANAPOL currently has'],
                    'highlight' => ['type' => 'text',     'label' => 'Highlighted text', 'default' => '12 Dialogue Partners'],
                    'after'     => ['type' => 'text',     'label' => 'Text after highlight', 'default' => '.'],
                ],
            ],
            'partners' => [
                'label'  => 'Partner cards',
                'toggle' => true,
                'help'   => 'The partners (name, full name, badge, since year, website, news keyword, description) are edited once under "Shared facts (used across pages)" > Dialogue partners & observers. Only the labels below are local to this page.',
                'fields' => [
                    'since_label'    => ['type' => 'text', 'label' => 'Badge prefix', 'default' => 'Since'],
                    'activities_label' => ['type' => 'text', 'label' => 'Activities link text', 'default' => 'See Activities'],
                    'website_label'  => ['type' => 'text', 'label' => 'Website link text', 'default' => 'Official Website'],
                ],
            ],
            'back' => [
                'label'  => 'Back link',
                'fields' => [
                    'label' => ['type' => 'text', 'label' => 'Link text', 'default' => 'Back to About ASEANAPOL'],
                ],
            ],
        ],
    ],

    // ───────────────────────────── Observers
    'observers' => [
        'label' => 'Observers',
        'route' => 'about.observers',
        'sections' => [
            'hero' => $hero('Observers', 'Organisations and countries with observer status in ASEANAPOL.'),
            'intro' => [
                'label'  => 'Introduction',
                'toggle' => true,
                'fields' => [
                    'text' => ['type' => 'textarea', 'label' => 'Text', 'rows' => 4, 'default' => 'Observer status in ASEANAPOL allows organisations and countries to participate in ASEANAPOL activities and conferences as observers, facilitating broader engagement and information exchange while fostering future cooperative relationships.'],
                ],
            ],
            'admission' => [
                'label'  => 'Observer Admission box',
                'toggle' => true,
                'fields' => [
                    'heading' => ['type' => 'text',     'label' => 'Heading', 'default' => 'Observer Admission'],
                    'text'    => ['type' => 'textarea', 'label' => 'Text', 'rows' => 4, 'default' => 'The admission of observers is governed by the Policy Guidelines for Accepting Observers and Dialogue Partners with ASEANAPOL. Interested organisations must meet eligibility criteria and receive approval from the ASEANAPOL Conference.'],
                    'button'  => ['type' => 'text',     'label' => 'Button text', 'default' => 'View Policy Guidelines'],
                ],
            ],
            'list' => [
                'label'  => 'Current Observers',
                'toggle' => true,
                'help'   => 'The observers (name, flag, since year, conference, news keyword, website) are edited once under "Shared facts (used across pages)" > Dialogue partners & observers (tick "Observer"). Only the labels below are local to this page.',
                'fields' => [
                    'heading'          => ['type' => 'text', 'label' => 'Heading', 'default' => 'Current Observers'],
                    'since_label'      => ['type' => 'text', 'label' => 'Badge prefix', 'default' => 'Since'],
                    'activities_label' => ['type' => 'text', 'label' => 'Activities link text', 'default' => 'See Activities'],
                    'website_label'    => ['type' => 'text', 'label' => 'Website link text', 'default' => 'Website'],
                ],
            ],
            'back' => [
                'label'  => 'Back link',
                'fields' => [
                    'label' => ['type' => 'text', 'label' => 'Link text', 'default' => 'Back to Information'],
                ],
            ],
        ],
    ],
];
