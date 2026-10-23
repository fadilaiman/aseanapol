<?php

/**
 * CMS registry — group "about" (About ASEANAPOL).
 * Page key = "about.<slug>". DEFAULT values below ARE the original hardcoded content.
 * See resources/cms/CMS_GUIDE.md for the field/section format.
 */

// Compact row builders (defaults only — the admin always edits full structured rows).
$conf = fn (array $r) => ['num' => (string) $r[0], 'dates' => $r[1], 'year' => (string) $r[2], 'city' => $r[3], 'country' => $r[4]];
$callout = fn (string $title, array $lines, int $gap, int $x1, int $y1, int $x2, int $y2, int $ty) => [
    'title' => $title, 'lines' => $lines, 'gap' => $gap, 'x1' => $x1, 'y1' => $y1, 'x2' => $x2, 'y2' => $y2, 'ty' => $ty,
];
$calloutFields = [
    'title' => ['type' => 'text',   'label' => 'Label (bold)'],
    'lines' => ['type' => 'list',   'label' => 'Description lines (one per line)', 'rows' => 3],
    'gap'   => ['type' => 'number', 'label' => 'Gap between label and first description line (px, layout)'],
    'x1'    => ['type' => 'number', 'label' => 'Arrow start X (layout)'],
    'y1'    => ['type' => 'number', 'label' => 'Arrow start Y (layout)'],
    'x2'    => ['type' => 'number', 'label' => 'Arrow end X (layout)'],
    'y2'    => ['type' => 'number', 'label' => 'Arrow end Y (layout)'],
    'ty'    => ['type' => 'number', 'label' => 'Label Y position (layout)'],
];
$backSection = fn (string $label = 'Back to About ASEANAPOL') => [
    'label' => 'Back link', 'toggle' => true, 'fields' => [
        'label' => ['type' => 'text', 'label' => 'Link text', 'default' => $label],
    ],
];

return [
    '_group' => ['label' => 'About ASEANAPOL', 'icon' => 'info', 'order' => 10],

    // ------------------------------------------------------------------ index
    'index' => [
        'label' => 'About ASEANAPOL (landing)',
        'route' => 'about.index',
        'sections' => [
            'hero' => [
                'label' => 'Page header',
                'fields' => [
                    'title'    => ['type' => 'text',     'label' => 'Title',    'default' => 'About ASEANAPOL'],
                    'subtitle' => ['type' => 'textarea', 'label' => 'Subtitle', 'rows' => 2, 'default' => 'The ASEAN Chiefs of Police — promoting police professionalism and regional cooperation since {founded_year}.'],
                ],
            ],
            'intro' => [
                'label' => 'Introduction', 'toggle' => true,
                'help' => 'Shared facts can be inserted with tokens: {founded_year}, {founded_date}, {first_conference_date}, {first_conference_place}, {secretariat_established}, {member_count_word}, {member_count_num}, {conference_count}; they are filled from Shared facts > Organisation.',
                'fields' => [
                    'text' => ['type' => 'textarea', 'label' => 'Intro paragraph', 'rows' => 5, 'default' => 'ASEANAPOL (Association of Southeast Asian Nations Chiefs of Police) is a regional police organisation established in {founded_year}. It serves as the primary platform for police cooperation among the {member_count_word} ASEAN member states, working to combat transnational crime and promote public safety across the region.'],
                ],
            ],
            'cards' => [
                'label' => 'Sub-page cards', 'toggle' => true,
                'help' => 'Shared facts can be inserted with tokens: {founded_year}, {founded_date}, {first_conference_date}, {first_conference_place}, {secretariat_established}, {member_count_word}, {member_count_num}, {conference_count}; they are filled from Shared facts > Organisation.',
                'fields' => [
                    'learn_more' => ['type' => 'text', 'label' => 'Card link text', 'default' => 'Learn more'],
                    'items' => [
                        'type' => 'repeater', 'label' => 'Cards', 'item_label' => 'title', 'add_label' => 'Add card',
                        'fields' => [
                            'title'  => ['type' => 'text',     'label' => 'Title'],
                            'icon'   => ['type' => 'icon',     'label' => 'Icon'],
                            'route'  => ['type' => 'select',   'label' => 'Links to page', 'options' => [
                                'about.chronology' => 'History (Chronology)',
                                'about.vision-mission' => 'Vision and Mission',
                                'about.governance' => 'Governance',
                                'about.permanent-secretariat' => 'Permanent Secretariat',
                                'about.objectives-and-functions' => 'Objectives and Functions',
                                'about.member-countries' => 'Member Countries',
                                'about.dialogue-partners' => 'Dialogue Partners',
                                'about.observers' => 'Observers',
                                'about.logo' => 'ASEANAPOL Logo',
                                'about.leadership' => 'Leadership',
                                'about.ob-lme' => 'Like-Minded Entities',
                            ]],
                            'desc'   => ['type' => 'textarea', 'label' => 'Description', 'rows' => 3],
                        ],
                        'default' => [
                            ['title' => 'History', 'icon' => 'history', 'route' => 'about.chronology', 'desc' => "A timeline of ASEANAPOL's history from its founding in {founded_year} to the present day."],
                            ['title' => 'Vision and Mission', 'icon' => 'visibility', 'route' => 'about.vision-mission', 'desc' => "ASEANAPOL's vision as the premier regional police organisation and our guiding mission."],
                            ['title' => 'Governance', 'icon' => 'account_balance', 'route' => 'about.governance', 'desc' => "ASEANAPOL's governance structure — the Conference, Executive Committee, and Permanent Secretariat."],
                            ['title' => 'Permanent Secretariat', 'icon' => 'domain', 'route' => 'about.permanent-secretariat', 'desc' => 'Learn about the ASEANAPOL Secretariat based in Kuala Lumpur, Malaysia.'],
                            ['title' => 'Objectives and Functions', 'icon' => 'flag', 'route' => 'about.objectives-and-functions', 'desc' => "The core objectives and functions that guide ASEANAPOL's work across the region."],
                            ['title' => 'Member Countries', 'icon' => 'public', 'route' => 'about.member-countries', 'desc' => 'The {member_count_word} ASEAN member police forces that form the ASEANAPOL network.'],
                            ['title' => 'Dialogue Partners', 'icon' => 'forum', 'route' => 'about.dialogue-partners', 'desc' => 'Countries and organisations that maintain formal dialogue partner status with ASEANAPOL.'],
                            ['title' => 'Observers', 'icon' => 'groups', 'route' => 'about.observers', 'desc' => 'International organisations and countries with observer status in ASEANAPOL.'],
                            ['title' => 'ASEANAPOL Logo', 'icon' => 'stars', 'route' => 'about.logo', 'desc' => 'The symbolism and design elements behind the official ASEANAPOL logo.'],
                            ['title' => 'Leadership', 'icon' => 'person_pin', 'route' => 'about.leadership', 'desc' => 'The Executive Director and senior leadership of the ASEANAPOL Permanent Secretariat.'],
                            ['title' => 'Like-Minded Entities', 'icon' => 'folder_special', 'route' => 'about.ob-lme', 'desc' => 'International organisations that collaborate with ASEANAPOL as Like-Minded Entities (LME).'],
                        ],
                    ],
                ],
            ],
        ],
    ],

    // ------------------------------------------------------------------ permanent-secretariat
    'permanent-secretariat' => [
        'label' => 'Permanent Secretariat',
        'route' => 'about.permanent-secretariat',
        'sections' => [
            'hero' => [
                'label' => 'Page header',
                'fields' => [
                    'title'    => ['type' => 'text',     'label' => 'Title',    'default' => 'Permanent Secretariat'],
                    'subtitle' => ['type' => 'textarea', 'label' => 'Subtitle', 'rows' => 2, 'default' => 'The administrative hub of ASEANAPOL, based in Kuala Lumpur, Malaysia.'],
                ],
            ],
            'intro' => [
                'label' => 'Introduction', 'toggle' => true,
                'help' => 'The logo shown here is the organisation logo from Shared facts > Organisation (identity). Shared facts can be inserted with tokens: {founded_year}, {founded_date}, {first_conference_date}, {first_conference_place}, {secretariat_established}, {member_count_word}, {member_count_num}, {conference_count}; they are filled from Shared facts > Organisation.',
                'fields' => [
                    'heading'     => ['type' => 'text',  'label' => 'Heading', 'default' => 'Introduction'],
                    'preface'     => ['type' => 'text',  'label' => 'Sub-heading', 'default' => 'Preface'],
                    'body_top'    => ['type' => 'richtext', 'label' => 'Opening paragraphs', 'default' => '<p>The first formal meeting of the Chiefs of ASEAN Police was held in {first_conference_place} on {first_conference_date}, to discuss matters of law enforcement and crime control. This annual meeting was called the <strong>ASEANAPOL Conference</strong>.</p><p>The basic requirement for a country to become a member of ASEANAPOL is that the country shall first be a member of ASEAN and the application shall be tabled at the conference for approval.</p><p>The founding members were <strong>Malaysia, Singapore, Thailand, Indonesia and the Philippines</strong>. Membership grew over the years:</p>'],
                    'milestones'  => [
                        'type' => 'repeater', 'label' => 'Membership timeline', 'item_label' => 'year', 'add_label' => 'Add milestone',
                        'fields' => [
                            'year' => ['type' => 'text',     'label' => 'Year'],
                            'text' => ['type' => 'textarea', 'label' => 'Text', 'rows' => 2],
                        ],
                        'default' => [
                            ['year' => '1984', 'text' => 'Royal Brunei Police joined the conference for the first time.'],
                            ['year' => '1996', 'text' => 'The Republic of Vietnam National Police joined the conference.'],
                            ['year' => '1998', 'text' => 'Lao General Department of Police and Myanmar Police Force joined the conference.'],
                            ['year' => '2000', 'text' => 'Cambodia National Police joined the conference.'],
                        ],
                    ],
                    'body_bottom' => ['type' => 'richtext', 'label' => 'Closing paragraph', 'default' => '<p>The current members of ASEANAPOL are <strong>Brunei, Cambodia, Indonesia, Lao People Democratic Republic, Malaysia, Myanmar, The Philippines, Singapore, Thailand</strong> and <strong>Vietnam</strong>.</p>'],
                ],
            ],
            'about' => [
                'label' => 'About the Secretariat', 'toggle' => true,
                'fields' => [
                    'heading' => ['type' => 'text', 'label' => 'Heading', 'default' => 'About the Secretariat'],
                    'body'    => ['type' => 'richtext', 'label' => 'Body', 'default' => "<p>The ASEANAPOL Permanent Secretariat serves as the administrative and operational hub of the Association of Southeast Asian Nations Chiefs of Police. Established to provide continuity of organisational functions between annual conferences, the Secretariat ensures the smooth coordination of ASEANAPOL's programmes, initiatives, and communications.</p><p>The Secretariat is hosted by the Royal Malaysia Police (RMP) and is located in Kuala Lumpur, Malaysia. It is staffed by officers from ASEAN member states and is headed by an Executive Director, a senior officer nominated by the host country.</p><p>The Permanent Secretariat is responsible for maintaining ASEANAPOL's records, facilitating inter-agency communication, coordinating working committees, and supporting the planning of annual conferences and other cooperative activities.</p>"],
                ],
            ],
            'history' => [
                'label' => 'History', 'toggle' => true,
                'help' => 'Shared facts can be inserted with tokens: {founded_year}, {founded_date}, {first_conference_date}, {first_conference_place}, {secretariat_established}, {member_count_word}, {member_count_num}, {conference_count}; they are filled from Shared facts > Organisation.',
                'fields' => [
                    'heading'     => ['type' => 'text', 'label' => 'Heading', 'default' => 'History'],
                    'intro'       => ['type' => 'richtext', 'label' => 'Opening paragraphs', 'default' => '<p>Prior to the establishment of the Permanent Secretariat, the ASEANAPOL Secretariat operated on a rotational basis, with member countries taking turns to host the ASEANAPOL Conference and automatically assuming the role of the Secretariat for that year.</p><p>The 25th Joint Communiqué signed by the ASEAN Chiefs of Police during the 25th ASEANAPOL Conference held in Bali, Indonesia, expressly stated the need to establish a Permanent ASEANAPOL Secretariat.</p>'],
                    'obj_title'   => ['type' => 'text', 'label' => 'Objectives lead-in (bold)', 'default' => 'Objectives of the establishment of a Permanent Secretariat:'],
                    'obj_items'   => ['type' => 'list', 'label' => 'Objectives (bullets)', 'rows' => 6, 'default' => [
                        'To harmonise and standardise coordination and communication mechanisms amongst ASEAN police institutions;',
                        'To conduct a comprehensive and integrative study concerning the resolutions agreed in the ASEANAPOL Joint Communiqués;',
                        'To establish a mechanism with responsibility to monitor and follow up the implementation of resolutions in the Joint Communiqués; and',
                        'To transform the resolutions adopted in the Joint Communiqués into the ASEANAPOL Plan of Action and its work programme.',
                    ]],
                    'wg_text'     => ['type' => 'textarea', 'label' => 'Working group lead-in', 'rows' => 2, 'default' => 'The working group set up to consider the viability of the Permanent ASEANAPOL Secretariat finalised that:'],
                    'wg_items'    => ['type' => 'list', 'label' => 'Working group conclusions (numbered)', 'rows' => 4, 'default' => [
                        'The Secretariat shall be administered based on the Terms of Reference;',
                        'The Head of the Secretariat is an Executive Director and he is assisted by 2 Directors.',
                    ]],
                    'closing'     => ['type' => 'richtext', 'label' => 'Closing paragraphs', 'default' => '<p>The tenure of services are: <strong>Executive Director — 2 years</strong>; <strong>Directors — 2 to 3 years</strong>.</p><p>During the 29th ASEANAPOL Conference in Hanoi, Vietnam in 2009, the Terms of Reference on the establishment of the ASEANAPOL Secretariat was endorsed. Kuala Lumpur, Malaysia was made the permanent seat for the Secretariat.</p><p>The ASEANAPOL Secretariat was fully operational from <strong>{secretariat_established}</strong>.</p>'],
                ],
            ],
            'functions' => [
                'label' => 'Functions', 'toggle' => true,
                'fields' => [
                    'heading' => ['type' => 'text', 'label' => 'Heading', 'default' => 'Functions'],
                    'items' => [
                        'type' => 'repeater', 'label' => 'Functions', 'item_label' => 'text', 'add_label' => 'Add function',
                        'fields' => [
                            'icon' => ['type' => 'icon',     'label' => 'Icon'],
                            'text' => ['type' => 'textarea', 'label' => 'Text', 'rows' => 2],
                        ],
                        'default' => [
                            ['icon' => 'admin_panel_settings', 'text' => 'Coordinate and implement decisions made at the ASEANAPOL Conference'],
                            ['icon' => 'groups', 'text' => 'Facilitate communication between member police forces'],
                            ['icon' => 'folder_open', 'text' => 'Maintain records, archives, and documentation of ASEANAPOL activities'],
                            ['icon' => 'calendar_month', 'text' => 'Plan and organise the annual ASEANAPOL Conference'],
                            ['icon' => 'hub', 'text' => 'Coordinate working committees and cooperative programmes'],
                            ['icon' => 'public', 'text' => 'Manage external relations with partner organisations and dialogue partners'],
                        ],
                    ],
                ],
            ],
            'back' => $backSection(),
            'directors' => [
                'label' => 'Directors section heading', 'toggle' => true,
                'help' => 'Hiding this hides the whole "Executive Director and Directors" block, including the three lists below.',
                'fields' => [
                    'heading' => ['type' => 'text', 'label' => 'Heading', 'default' => 'Executive Director and Directors'],
                ],
            ],
            'dir_ps' => [
                'label' => 'Directors: Police Services', 'toggle' => true,
                'help' => 'The names, forces and terms in this column are NOT edited here: they come from Shared facts > Secretariat office holders (roster), newest first. Only the column heading is local.',
                'fields' => [
                    'heading' => ['type' => 'text', 'label' => 'Column heading', 'default' => 'Director for Police Services'],
                ],
            ],
            'dir_ed' => [
                'label' => 'Directors: Executive Director', 'toggle' => true,
                'help' => 'The names, forces and terms in this column are NOT edited here: they come from Shared facts > Secretariat office holders (roster), newest first. Only the column heading is local.',
                'fields' => [
                    'heading' => ['type' => 'text', 'label' => 'Column heading', 'default' => 'Executive Director'],
                ],
            ],
            'dir_plans' => [
                'label' => 'Directors: Plans and Programmes', 'toggle' => true,
                'help' => 'The names, forces and terms in this column are NOT edited here: they come from Shared facts > Secretariat office holders (roster), newest first. Only the column heading is local.',
                'fields' => [
                    'heading' => ['type' => 'text', 'label' => 'Column heading', 'default' => 'Director for Plans and Programmes'],
                ],
            ],
        ],
    ],

    // ------------------------------------------------------------------ governance
    'governance' => [
        'label' => 'Governance',
        'route' => 'about.governance',
        'sections' => [
            'hero' => [
                'label' => 'Page header',
                'fields' => [
                    'title'    => ['type' => 'text',     'label' => 'Title',    'default' => 'Governance'],
                    'subtitle' => ['type' => 'textarea', 'label' => 'Subtitle', 'rows' => 2, 'default' => 'The organisational structure and decision-making bodies of ASEANAPOL.'],
                ],
            ],
            'chart' => [
                'label' => 'Organisational chart', 'toggle' => true,
                'fields' => [
                    'heading' => ['type' => 'text',  'label' => 'Heading', 'default' => 'Organisational Structure of ASEANAPOL'],
                    'image'   => ['type' => 'image', 'label' => 'Chart image', 'default' => 'media/governance/governance/organisational-chart-2026-v3.jpg'],
                    'alt'     => ['type' => 'text',  'label' => 'Image alt text', 'default' => 'Organisational Structure of ASEANAPOL'],
                ],
            ],
            'conference' => [
                'label' => 'ASEANAPOL Conference', 'toggle' => true,
                'fields' => [
                    'heading' => ['type' => 'text', 'label' => 'Heading', 'default' => 'ASEANAPOL Conference'],
                    'body'    => ['type' => 'richtext', 'label' => 'Body', 'default' => '<p>The Conference shall be held annually on a rotational basis amongst member countries. The Conference will be attended by the Chiefs of Police.</p>'],
                ],
            ],
            'exco' => [
                'label' => 'ASEANAPOL Executive Committee', 'toggle' => true,
                'fields' => [
                    'heading' => ['type' => 'text', 'label' => 'Heading', 'default' => 'ASEANAPOL Executive Committee'],
                    'body'    => ['type' => 'richtext', 'label' => 'Body', 'default' => '<p>The Executive Committee shall comprise of Deputy Heads of delegation attending the annual Conference.</p><p>The Executive Committee shall meet annually, immediately before the Conference.</p><p>The Executive Director of the ASEANAPOL Secretariat shall present a report of its activities, including amongst others, issues on financial performance, procurement of works, supplies and services and control and management of contracts, to the Executive Committee.</p><p>The Executive Committee shall provide a summary report of the activities of the Secretariat to the Heads of Delegation at the Conference and at the Closing Plenary Session of the Conference.</p>'],
                ],
            ],
            'directors' => [
                'label' => 'ASEANAPOL Secretariat Directors', 'toggle' => true,
                'fields' => [
                    'heading' => ['type' => 'text', 'label' => 'Heading', 'default' => 'ASEANAPOL Secretariat Directors'],
                    'body'    => ['type' => 'richtext', 'label' => 'Body', 'default' => '<p>The Executive Director of the Secretariat shall be appointed by the ASEANAPOL Conference upon nomination on a rotational basis in alphabetical order for a term of two years. The Executive Director of the Secretariat shall be a Senior Police Officer of the rank of Brigadier General and above or its equivalent. The Executive Director will be assisted by Director for Police Services and Director for Plans and Programmes shall be a Senior Police Officer of the rank of Colonel and above or its equivalent.</p>'],
                ],
            ],
            'conferences' => [
                'label' => 'Conference history table', 'toggle' => true,
                'help' => 'The heading accepts the tokens {founded_year} and {conference_count} (from Shared facts > Organisation).',
                'fields' => [
                    'heading'     => ['type' => 'text', 'label' => 'Heading', 'default' => 'ASEANAPOL Conference (since {founded_year})'],
                    'col_num'     => ['type' => 'text', 'label' => 'Column title: number', 'default' => '#'],
                    'col_dates'   => ['type' => 'text', 'label' => 'Column title: dates', 'default' => 'Dates'],
                    'col_year'    => ['type' => 'text', 'label' => 'Column title: year', 'default' => 'Year'],
                    'col_city'    => ['type' => 'text', 'label' => 'Column title: city', 'default' => 'City'],
                    'col_country' => ['type' => 'text', 'label' => 'Column title: country', 'default' => 'Country'],
                    'rows' => [
                        'type' => 'repeater', 'label' => 'Conferences', 'item_label' => 'city', 'add_label' => 'Add conference',
                        'fields' => [
                            'num'     => ['type' => 'text', 'label' => '#'],
                            'dates'   => ['type' => 'text', 'label' => 'Dates'],
                            'year'    => ['type' => 'text', 'label' => 'Year'],
                            'city'    => ['type' => 'text', 'label' => 'City'],
                            'country' => ['type' => 'text', 'label' => 'Country'],
                        ],
                        'default' => array_map($conf, [
                            [1, '21 - 23 October', 1981, 'Manila', 'The Philippines'],
                            [2, '18 - 20 June', 1982, 'Pattaya', 'Thailand'],
                            [3, '16 - 19 November', 1983, 'Jakarta', 'Indonesia'],
                            [4, '21 - 24 November', 1984, 'Kuala Lumpur', 'Malaysia'],
                            [5, '7 - 9 November', 1985, 'Singapore', 'Singapore'],
                            [6, '11 - 13 November', 1986, 'Bandar Seri Begawan', 'Brunei'],
                            [7, '11 - 18 September', 1987, 'Manila', 'The Philippines'],
                            [8, '14 - 16 November', 1988, 'Chiang Mai', 'Thailand'],
                            [9, '1 - 6 November', 1989, 'Bali', 'Indonesia'],
                            [10, '25 - 28 July', 1990, 'Kuala Lumpur', 'Malaysia'],
                            [11, '5 - 9 May', 1991, 'Singapore', 'Singapore'],
                            [12, '3 - 5 August', 1992, 'Brunei', 'Brunei'],
                            [13, '19 - 21 April', 1993, 'Manila', 'The Philippines'],
                            [14, '1 - 5 May', 1994, 'Phuket', 'Thailand'],
                            [15, '21 - 25 May', 1995, 'Jakarta', 'Indonesia'],
                            [16, '26 - 30 May', 1996, 'Kuala Lumpur', 'Malaysia'],
                            [17, '1 - 5 June', 1997, 'Singapore', 'Singapore'],
                            [18, '24 - 27 May', 1998, 'Brunei', 'Brunei'],
                            [19, '26 - 28 April', 1999, 'Hanoi', 'Viet Nam'],
                            [20, '8 - 10 May', 2000, 'Yangon', 'Myanmar'],
                            [21, '23 - 25 May', 2001, 'Vientiane', 'Lao PDR'],
                            [22, '28 - 30 May', 2002, 'Phnom Penh', 'Cambodia'],
                            [23, '8 - 12 September', 2003, 'Manila', 'The Philippines'],
                            [24, '7 - 11 June', 2004, 'Chiang Mai', 'Thailand'],
                            [25, '16 - 20 May', 2005, 'Bali', 'Indonesia'],
                            [26, '22 - 26 May', 2006, 'Kuala Lumpur', 'Malaysia'],
                            [27, '2 - 7 June', 2007, 'Singapore', 'Singapore'],
                            [28, '25 - 29 May', 2008, 'Bandar Seri Begawan', 'Brunei'],
                            [29, '12 - 16 May', 2009, 'Hanoi', 'Viet Nam'],
                            [30, '24 - 28 May', 2010, 'Phnom Penh', 'Cambodia'],
                            [31, '31 May - 2 June', 2011, 'Vientiane', 'Lao PDR'],
                            [32, '22 - 24 May', 2012, 'Nay Pyi Taw', 'Myanmar'],
                            [33, '21 - 23 February', 2013, 'Pattaya', 'Thailand'],
                            [34, '12 - 16 May', 2014, 'Manila', 'The Philippines'],
                            [35, '3 - 7 August', 2015, 'Jakarta', 'Indonesia'],
                            [36, '24 - 29 July', 2016, 'Kuala Lumpur', 'Malaysia'],
                            [37, '12 - 14 September', 2017, 'Singapore', 'Singapore'],
                            [38, '3 - 5 September', 2018, 'Bandar Seri Begawan', 'Brunei'],
                            [39, '14 - 16 September', 2019, 'Hanoi', 'Viet Nam'],
                            [40, '1 - 5 March', 2022, 'Phnom Penh', 'Cambodia'],
                            [41, '16 - 20 October', 2023, 'Vientiane', 'Lao PDR'],
                            [42, '21 - 25 October', 2024, 'Nay Pyi Taw', 'Myanmar'],
                            [43, '3 - 7 November', 2025, 'Bangkok', 'Thailand'],
                        ]),
                    ],
                ],
            ],
            'back' => $backSection(),
        ],
    ],

    // ------------------------------------------------------------------ objectives-and-functions
    'objectives-and-functions' => [
        'label' => 'Objectives and Functions',
        'route' => 'about.objectives-and-functions',
        'sections' => [
            'hero' => [
                'label' => 'Page header',
                'fields' => [
                    'title'    => ['type' => 'text',     'label' => 'Title',    'default' => 'Objectives and Functions'],
                    'subtitle' => ['type' => 'textarea', 'label' => 'Subtitle', 'rows' => 2, 'default' => "The core mandate driving ASEANAPOL's work across the region."],
                ],
            ],
            'objectives' => [
                'label' => 'Objectives', 'toggle' => true,
                'fields' => [
                    'icon'    => ['type' => 'icon', 'label' => 'Heading icon', 'default' => 'flag'],
                    'heading' => ['type' => 'text', 'label' => 'Heading', 'default' => 'Objectives'],
                    'items' => [
                        'type' => 'repeater', 'label' => 'Objectives (numbered automatically)', 'item_label' => 'text', 'add_label' => 'Add objective',
                        'fields' => [
                            'text' => ['type' => 'textarea', 'label' => 'Objective', 'rows' => 3],
                        ],
                        'default' => [
                            ['text' => 'Promote and enhance police cooperation among ASEAN member states to combat transnational and cross-border crime.'],
                            ['text' => 'Facilitate the exchange of criminal intelligence and information among member police forces.'],
                            ['text' => 'Strengthen the capacity and professional standards of police forces in the ASEAN region.'],
                            ['text' => 'Foster mutual assistance in training, education, and the development of police expertise.'],
                            ['text' => 'Coordinate joint operations and investigations targeting organised crime, terrorism, and other serious threats to regional security.'],
                            ['text' => 'Build and maintain strategic partnerships with international law enforcement organisations.'],
                        ],
                    ],
                ],
            ],
            'functions' => [
                'label' => 'Functions', 'toggle' => true,
                'fields' => [
                    'icon'    => ['type' => 'icon', 'label' => 'Heading icon', 'default' => 'settings'],
                    'heading' => ['type' => 'text', 'label' => 'Heading', 'default' => 'Functions'],
                    'items' => [
                        'type' => 'repeater', 'label' => 'Functions', 'item_label' => 'text', 'add_label' => 'Add function',
                        'fields' => [
                            'icon' => ['type' => 'icon',     'label' => 'Icon'],
                            'text' => ['type' => 'textarea', 'label' => 'Text', 'rows' => 3],
                        ],
                        'default' => [
                            ['icon' => 'forum', 'text' => 'Convene the annual ASEANAPOL Conference for policy discussion and decision-making.'],
                            ['icon' => 'hub', 'text' => 'Coordinate working committees on specific crime types and cooperation areas.'],
                            ['icon' => 'share', 'text' => 'Facilitate secure information and intelligence sharing among member states.'],
                            ['icon' => 'school', 'text' => 'Organise training programmes, seminars, and workshops for police professionals.'],
                            ['icon' => 'handshake', 'text' => 'Develop and maintain bilateral and multilateral cooperative frameworks.'],
                            ['icon' => 'description', 'text' => 'Publish the ASEANAPOL Bulletin/Magazine to disseminate information on activities and best practices.'],
                            ['icon' => 'travel_explore', 'text' => 'Represent ASEAN police interests in international forums and organisations.'],
                        ],
                    ],
                ],
            ],
            'back' => $backSection(),
        ],
    ],

    // ------------------------------------------------------------------ chronology
    'chronology' => [
        'label' => 'Chronology (History)',
        'route' => 'about.chronology',
        'sections' => [
            'hero' => [
                'label' => 'Page header',
                'fields' => [
                    'title'    => ['type' => 'text',     'label' => 'Title',    'default' => 'Chronology'],
                    'subtitle' => ['type' => 'textarea', 'label' => 'Subtitle', 'rows' => 2, 'default' => 'The history of ASEANAPOL from its founding in {founded_year} to the present day.'],
                ],
            ],
            'timeline' => [
                'label' => 'Timeline', 'toggle' => true,
                'help' => 'Shared facts can be inserted with tokens: {founded_year}, {founded_date}, {first_conference_date}, {first_conference_place} (from Shared facts > Organisation).',
                'fields' => [
                    'dot_icon' => ['type' => 'icon', 'label' => 'Timeline marker icon', 'default' => 'radio_button_checked'],
                    'items' => [
                        'type' => 'repeater', 'label' => 'Milestones', 'item_label' => 'title', 'add_label' => 'Add milestone',
                        'fields' => [
                            'year'  => ['type' => 'text',     'label' => 'Year / period'],
                            'title' => ['type' => 'text',     'label' => 'Title'],
                            'desc'  => ['type' => 'textarea', 'label' => 'Description', 'rows' => 4],
                        ],
                        'default' => [
                            ['year' => '1981', 'title' => '1st ASEANAPOL Conference — {first_conference_place}', 'desc' => 'ASEANAPOL was founded on {founded_date} at the 1st ASEANAPOL Conference held in {first_conference_place}. The founding member states were Brunei Darussalam, Indonesia, Malaysia, the Philippines, Singapore, and Thailand.'],
                            ['year' => '1984', 'title' => 'Expansion of Membership', 'desc' => "ASEANAPOL continued to grow in membership as additional ASEAN nations formalised their participation in the organisation's cooperative framework."],
                            ['year' => '1997', 'title' => 'Admission of New Members', 'desc' => "Following ASEAN's expansion, Myanmar, Lao PDR, and Vietnam joined ASEANAPOL, broadening the regional police cooperation network across mainland Southeast Asia."],
                            ['year' => '1999', 'title' => 'Cambodia Joins ASEANAPOL', 'desc' => 'The Cambodian National Police formally joined ASEANAPOL, completing the full ten-member configuration of the organisation aligned with all ASEAN member states.'],
                            ['year' => '2010', 'title' => 'Permanent Secretariat Established', 'desc' => "The ASEANAPOL Permanent Secretariat was formally established in Kuala Lumpur, Malaysia, hosted by the Royal Malaysia Police. This marked a significant step in institutionalising ASEANAPOL's administrative capacity."],
                            ['year' => '2010s', 'title' => 'Expanded International Partnerships', 'desc' => 'ASEANAPOL significantly expanded its network of dialogue partners and collaborative frameworks with international organisations including INTERPOL, UNODC, and various bilateral partners to enhance capacity building and joint operations.'],
                            ['year' => '2020s', 'title' => 'Modernisation and Digital Cooperation', 'desc' => 'ASEANAPOL embraced digital transformation in police cooperation, enhancing the ASEANAPOL Database system (e-ADS), strengthening cybercrime cooperation frameworks, and adapting to emerging transnational threats.'],
                            ['year' => '2025', 'title' => '43rd ASEANAPOL Conference', 'desc' => 'ASEANAPOL continues to grow with the 43rd Annual Conference and ongoing cooperation programmes. The organisation remains the premier platform for police cooperation in the ASEAN region.'],
                        ],
                    ],
                ],
            ],
            'back' => $backSection(),
        ],
    ],

    // ------------------------------------------------------------------ logo
    'logo' => [
        'label' => 'ASEANAPOL Logo',
        'route' => 'about.logo',
        'sections' => [
            'hero' => [
                'label' => 'Page header',
                'fields' => [
                    'title'    => ['type' => 'text',     'label' => 'Title',    'default' => 'ASEANAPOL Logo'],
                    'subtitle' => ['type' => 'textarea', 'label' => 'Subtitle', 'rows' => 2, 'default' => 'The official ASEANAPOL logogramme — created and endorsed at the 3rd ASEANAPOL Conference, Jakarta, 1983.'],
                ],
            ],
            'diagram' => [
                'label' => 'Annotated logogramme diagram', 'toggle' => true,
                'help' => 'The logo in the centre is the organisation logo from Shared facts > Organisation. Arrow/label positions are SVG coordinates (layout). When adding a callout copy the numbers from an existing one and adjust.',
                'fields' => [
                    'heading' => ['type' => 'text',  'label' => 'Heading', 'default' => 'Logogramme'],
                    'left' => [
                        'type' => 'repeater', 'label' => 'Callouts — left side', 'item_label' => 'title', 'add_label' => 'Add left callout',
                        'fields' => $calloutFields,
                        'default' => [
                            $callout('SHIELD', ['Symbol of community protection'], 16, 218, 88, 320, 108, 78),
                            $callout('FLAGS', ['10 Member Countries'], 16, 218, 178, 328, 192, 170),
                            $callout('YELLOW', ['Color of Honesty'], 16, 218, 248, 332, 248, 240),
                            $callout('SPRAY OF RICE & COTTON', ['Symbol of prosperity'], 16, 218, 368, 312, 368, 358),
                        ],
                    ],
                    'right' => [
                        'type' => 'repeater', 'label' => 'Callouts — right side', 'item_label' => 'title', 'add_label' => 'Add right callout',
                        'fields' => $calloutFields,
                        'default' => [
                            $callout('3 STEPS ON TOP OF PILLAR', ["Police's Motto:", 'Fight Crimes', 'Love Humanity', 'Help Delinquents'], 14, 662, 108, 556, 112, 98),
                            $callout('10 STEPS AT THE BOTTOM OF PILLAR', ['10 ASEAN Countries'], 16, 662, 318, 568, 335, 310),
                            $callout('BLACK', ['Firm Determination'], 16, 662, 388, 580, 378, 380),
                        ],
                    ],
                    'caption_title' => ['type' => 'textarea', 'label' => 'Caption title (line break allowed)', 'rows' => 2, 'default' => "Logogramme, 3rd ASEANAPOL Meeting, Jakarta,\n16–19th November 1983"],
                    'caption_text'  => ['type' => 'textarea', 'label' => 'Caption paragraph', 'rows' => 3, 'default' => 'Created & endorsed during the 3rd ASEANAPOL Conference, 16–19th November 1983 in Jakarta, Indonesia.'],
                    'caption_note'  => ['type' => 'textarea', 'label' => 'Caption note (italic)', 'rows' => 2, 'default' => '(Originally with the 5 steps at the bottom of the pillar representing the then 5 member countries)'],
                ],
            ],
            'motto' => [
                'label' => 'Police Motto', 'toggle' => true,
                'fields' => [
                    'label' => ['type' => 'text', 'label' => 'Label', 'default' => 'Police Motto'],
                    'items' => [
                        'type' => 'repeater', 'label' => 'Motto phrases', 'item_label' => 'text', 'add_label' => 'Add phrase',
                        'fields' => ['text' => ['type' => 'text', 'label' => 'Phrase']],
                        'default' => [['text' => 'Fight Crimes'], ['text' => 'Love Humanity'], ['text' => 'Help Delinquents']],
                    ],
                ],
            ],
            'guidelines' => [
                'label' => 'Usage guidelines box', 'toggle' => true,
                'fields' => [
                    'heading' => ['type' => 'text',     'label' => 'Heading', 'default' => 'Usage Guidelines'],
                    'text'    => ['type' => 'textarea', 'label' => 'Text', 'rows' => 2, 'default' => 'For rules on proper display and reproduction of the ASEANAPOL Flag and Logo.'],
                    'button'  => ['type' => 'text',     'label' => 'Button label', 'default' => 'View Guidelines'],
                    'icon'    => ['type' => 'icon',     'label' => 'Button icon', 'default' => 'description'],
                ],
            ],
            'back' => $backSection(),
        ],
    ],

    // ------------------------------------------------------------------ vision-mission (reference conversion)
    'vision-mission' => [
        'label' => 'Vision and Mission',
        'route' => 'about.vision-mission',
        'sections' => [
            'hero' => [
                'label' => 'Page header',
                'fields' => [
                    'title'    => ['type' => 'text',     'label' => 'Title',    'default' => 'Vision and Mission'],
                    'subtitle' => ['type' => 'textarea', 'label' => 'Subtitle', 'rows' => 2, 'default' => "The guiding principles behind ASEANAPOL's work and aspirations."],
                ],
            ],
            'vision' => [
                'label'  => 'Vision statement',
                'toggle' => true,
                'help'   => 'The tagline under the quote comes from Shared facts > Organisation (identity), shown in quotation marks.',
                'fields' => [
                    'heading' => ['type' => 'text',     'label' => 'Heading',        'default' => 'Vision'],
                    'quote'   => ['type' => 'textarea', 'label' => 'Vision quote',   'rows' => 3, 'default' => '"To be the premier regional police organisation that fosters cooperation in policing among ASEAN Member States and beyond, ensuring a safe and secure region for all."'],
                ],
            ],
            'mission' => [
                'label'  => 'Mission',
                'toggle' => true,
                'fields' => [
                    'heading' => ['type' => 'text', 'label' => 'Heading', 'default' => 'Mission'],
                    'items'   => [
                        'type' => 'repeater', 'label' => 'Mission points', 'item_label' => 'title', 'add_label' => 'Add mission point',
                        'fields' => [
                            'icon'  => ['type' => 'icon',     'label' => 'Icon'],
                            'title' => ['type' => 'text',     'label' => 'Title'],
                            'desc'  => ['type' => 'textarea', 'label' => 'Description', 'rows' => 3],
                        ],
                        'default' => [
                            ['icon' => 'shield',    'title' => 'Combat Transnational Crime',        'desc' => 'Enhance regional police cooperation in identifying, investigating, and combating transnational and cross-border crime threats across ASEAN.'],
                            ['icon' => 'school',    'title' => 'Strengthen Institutional Capacity', 'desc' => 'Build the professional standards, skills, and institutional capacity of ASEAN police forces through targeted training and knowledge exchange programmes.'],
                            ['icon' => 'share',     'title' => 'Facilitate Intelligence Sharing',   'desc' => 'Enable timely and secure sharing of criminal intelligence and operational information among member states to support joint investigations.'],
                            ['icon' => 'handshake', 'title' => 'Build Strategic Partnerships',      'desc' => 'Develop and strengthen partnerships with international law enforcement organisations, dialogue partners, and observer states.'],
                        ],
                    ],
                ],
            ],
            'back' => $backSection(),
        ],
    ],
];
