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

/** "Current post holder" card shared by leadership pages. */
$current = fn (string $heading, string $photo, string $alt, string $badge, string $name, string $force, string $country, string $bio) => [
    'label'  => 'Current post holder',
    'toggle' => true,
    'fields' => [
        'heading' => ['type' => 'text',     'label' => 'Section heading', 'default' => $heading],
        'photo'   => ['type' => 'image',    'label' => 'Photo', 'default' => $photo],
        'alt'     => ['type' => 'text',     'label' => 'Photo alt text', 'default' => $alt],
        'badge'   => ['type' => 'text',     'label' => 'Label above name (post / term)', 'default' => $badge],
        'name'    => ['type' => 'text',     'label' => 'Name and rank', 'default' => $name],
        'force'   => ['type' => 'text',     'label' => 'Police force', 'default' => $force],
        'country' => ['type' => 'text',     'label' => 'Country (flag emoji + name)', 'default' => $country],
        'bio'     => ['type' => 'textarea', 'label' => 'Biography', 'rows' => 5, 'default' => $bio],
    ],
];

/** Past post holders table. */
$past = function (string $heading, string $intro, array $rows, bool $withTerm) {
    $fields = [
        'no'      => ['type' => 'text',  'label' => 'Number (e.g. 9th)'],
        'photo'   => ['type' => 'image', 'label' => 'Photo'],
        'name'    => ['type' => 'text',  'label' => 'Name and rank'],
        'force'   => ['type' => 'text',  'label' => 'Police force'],
        'flag'    => ['type' => 'text',  'label' => 'Country flag emoji'],
    ];
    $cols = [
        'col_no'      => ['type' => 'text', 'label' => 'Column heading: number', 'default' => '#'],
        'col_name'    => ['type' => 'text', 'label' => 'Column heading: name', 'default' => 'Name & Rank'],
        'col_country' => ['type' => 'text', 'label' => 'Column heading: country', 'default' => 'Country'],
    ];
    if ($withTerm) {
        $fields['term'] = ['type' => 'text', 'label' => 'Term (e.g. 2024–2025)'];
        $cols['col_term'] = ['type' => 'text', 'label' => 'Column heading: term', 'default' => 'Term'];
    }
    return [
        'label'  => 'Past post holders',
        'toggle' => true,
        'fields' => array_merge([
            'heading' => ['type' => 'text',     'label' => 'Section heading', 'default' => $heading],
            'intro'   => ['type' => 'textarea', 'label' => 'Introduction',    'rows' => 2, 'default' => $intro],
        ], $cols, [
            'rows' => [
                'type' => 'repeater', 'label' => 'People', 'item_label' => 'name', 'add_label' => 'Add person',
                'fields' => $fields, 'default' => $rows,
            ],
        ]),
    ];
};

$r = fn (string $no, string $name, string $force, string $flag, string $photo, ?string $term = null) =>
    array_filter(['no' => $no, 'photo' => $photo, 'name' => $name, 'force' => $force, 'flag' => $flag, 'term' => $term], fn ($v) => $v !== null);

return [
    '_group' => ['label' => 'About — Leadership & Partners', 'icon' => 'groups', 'order' => 11],

    // ───────────────────────────── Leadership
    'leadership' => [
        'label' => 'Leadership',
        'route' => 'about.leadership',
        'sections' => [
            'hero' => $hero('Leadership', 'The ASEANAPOL Secretariat is led by an Executive Director nominated by a member country on a rotational two-year basis.'),
            'current' => $current(
                'Current Executive Director', 'media/ed/9.jpg', 'Executive Director',
                '9th Executive Director · 2026–2027',
                'Police Senior Colonel Kongkrissada Kittithiraphong',
                'Royal Thai Police (RTP)', '🇹🇭 Thailand',
                "Police Senior Colonel Kongkrissada Kittithiraphong serves as the 9th Executive Director of the ASEANAPOL Secretariat, nominated by the Royal Thai Police for the term 2026–2027. He oversees the operations of the Secretariat and leads ASEANAPOL's efforts in strengthening regional cooperation to combat transnational crime."
            ),
            'directors' => [
                'label'  => 'Secretariat Directors cards',
                'toggle' => true,
                'help'   => 'Cards linking to the Director for Police Services / Plans and Programmes pages.',
                'fields' => [
                    'heading' => ['type' => 'text', 'label' => 'Section heading', 'default' => 'Secretariat Directors'],
                    'cards' => [
                        'type' => 'repeater', 'label' => 'Director cards', 'item_label' => 'role', 'add_label' => 'Add card',
                        'fields' => [
                            'target'  => ['type' => 'select', 'label' => 'Links to page', 'options' => ['dps' => 'Director for Police Services page', 'dpp' => 'Director for Plans and Programmes page']],
                            'icon'    => ['type' => 'icon',   'label' => 'Icon'],
                            'role'    => ['type' => 'text',   'label' => 'Post title'],
                            'name'    => ['type' => 'text',   'label' => 'Name and rank'],
                            'force'   => ['type' => 'text',   'label' => 'Police force'],
                            'country' => ['type' => 'text',   'label' => 'Country (flag emoji + name)'],
                            'link'    => ['type' => 'text',   'label' => 'Link text'],
                        ],
                        'default' => [
                            ['target' => 'dps', 'icon' => 'shield_person', 'role' => 'Director for Police Services',       'name' => 'Police Senior Superintendent Huntal Tambunan', 'force' => 'Indonesian National Police', 'country' => '🇮🇩 Indonesia',   'link' => 'View all directors →'],
                            ['target' => 'dpp', 'icon' => 'analytics',     'role' => 'Director for Plans and Programmes', 'name' => 'Police Colonel Jean Mary A. Mangahis',         'force' => 'Philippine National Police', 'country' => '🇵🇭 Philippines', 'link' => 'View all directors →'],
                        ],
                    ],
                ],
            ],
            'past' => $past(
                'Past Executive Directors',
                'The Executive Director post rotates among ASEANAPOL member countries on a two-year term, beginning with the establishment of the Secretariat on 1 January 2010.',
                [
                    $r('9th', 'Police Senior Colonel Kongkrissada Kittithiraphong', 'Royal Thai Police (RTP)', '🇹🇭', 'media/ed/9.jpg', '2026–2027'),
                    $r('8th', 'Pol. Col. David Martinez Vinluan', 'Philippines National Police (PNP)', '🇵🇭', 'media/ed/8.jpg', '2024–2025'),
                    $r('7th', 'Police Brigadier General Zaw Lin Tun', 'Myanmar Police Force (MPF)', '🇲🇲', 'media/ed/7.jpg', '2022–2023'),
                    $r('6th', 'DAC Jim Wee', 'Singapore Police Force (SPF)', '🇸🇬', 'media/ed/6.jpg', '2020–2021'),
                    $r('5th', 'Pol. Col. Kenechanh Phommachack', 'Lao Police Force (LPF)', '🇱🇦', 'media/ed/5.jpg', '2018–2019'),
                    $r('4th', 'Police Inspector General Yohanes Agus Mulyono', 'Indonesia National Police (INP)', '🇮🇩', 'media/ed/4.jpg', '2016–2017'),
                    $r('3rd', "SAC Pengiran Dato' Paduka Hj Abdul Wahab bin Pengiran Hj Omar", 'Royal Brunei Police Force (RBPF)', '🇧🇳', 'media/ed/3.png', '2014–2015'),
                    $r('2nd', 'Lt. Gen. Sar Moline', 'Cambodian National Police (CNP)', '🇰🇭', 'media/ed/2.jpg', '2012–2013'),
                    $r('1st', 'ACP Mohd Nadzri b. Zainal Abidin', 'Royal Malaysia Police (RMP)', '🇲🇾', 'media/ed/1.jpg', '2010–2011'),
                ],
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
            'current' => $current(
                'Current Director for Police Services', 'media/dps/no7.png', 'Director for Police Services',
                'Director for Police Services · 2025–Present',
                'Police Senior Superintendent Huntal Tambunan',
                'Indonesian National Police (INP)', '🇮🇩 Indonesia',
                'Police Senior Superintendent Huntal Tambunan serves as the Director for Police Services of the ASEANAPOL Secretariat, nominated by the Indonesian National Police. He is responsible for overseeing police cooperation and operational programmes under the Secretariat.'
            ),
            'past' => $past(
                'Past Directors for Police Services',
                'The Director for Police Services post rotates among ASEANAPOL member countries within the Secretariat.',
                [
                    $r('7th', 'Pol. Snr. Supt. Huntal Tambunan', 'Indonesian National Police (INP)', '🇮🇩', 'media/dps/no7.png'),
                    $r('6th', 'ACP Dr. Bakri bin Zainal Abidin', 'Royal Malaysia Police (RMP)', '🇲🇾', 'media/dps/no6.png'),
                    $r('5th', 'Pol. Snr. Supt. Joni Getamala', 'Indonesian National Police (INP)', '🇮🇩', 'media/dps/no5.png'),
                    $r('4th', 'DAC Jim Wee', 'Singapore Police Force (SPF)', '🇸🇬', 'media/dps/no4.png'),
                    $r('3rd', 'PSSuPt. Ferdinand R. P. Bartolome', 'Philippine National Police (PNP)', '🇵🇭', 'media/dps/no3.png'),
                    $r('2nd', 'ACP Mohamad Anil Shah bin Abdullah', 'Royal Malaysia Police (RMP)', '🇲🇾', 'media/dps/no2.png'),
                    $r('1st', 'DAC Lim Kim Tak', 'Singapore Police Force (SPF)', '🇸🇬', 'media/dps/no1.png'),
                ],
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
            'current' => $current(
                'Current Director for Plans and Programmes', 'media/dpp/no7.png', 'Director for Plans and Programmes',
                'Director for Plans and Programmes · Present',
                'Police Colonel Jean Mary A. Mangahis',
                'Philippine National Police (PNP)', '🇵🇭 Philippines',
                'Police Colonel Jean Mary A. Mangahis serves as the Director for Plans and Programmes of the ASEANAPOL Secretariat, nominated by the Philippine National Police. She is responsible for strategic planning and overseeing programme implementation within the Secretariat.'
            ),
            'past' => $past(
                'Past Directors for Plans and Programmes',
                'The Director for Plans and Programmes post rotates among ASEANAPOL member countries within the Secretariat.',
                [
                    $r('7th', 'Pol. Col. Jean Mary A. Mangahis', 'Philippine National Police (PNP)', '🇵🇭', 'media/dpp/no7.png'),
                    $r('6th', 'Pol. Snr. Lt. Col. Nguyen Huu Ngoc', 'Office of Investigation Police Agency, Vietnam Police Force (OIPA, VPF)', '🇻🇳', 'media/dpp/no6.png'),
                    $r('5th', 'Supt. Tang Yik Tung', 'Royal Brunei Police Force (RBPF)', '🇧🇳', 'media/dpp/no5.png'),
                    $r('4th', 'ACP Aidah Othman', 'Royal Malaysia Police (RMP)', '🇲🇾', 'media/dpp/no4.png'),
                    $r('3rd', 'Pol. Supt. Yuli Cahyanti', 'Indonesian National Police (INP)', '🇮🇩', 'media/dpp/no3.png'),
                    $r('2nd', 'Pol. Supt. Rienhard Hutagaol', 'Indonesian National Police (INP)', '🇮🇩', 'media/dpp/no2.png'),
                    $r('1st', 'Pol. Supt. Desy Andriany', 'Indonesian National Police (INP)', '🇮🇩', 'media/dpp/no1.png'),
                ],
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
                'fields' => [
                    'website_label' => ['type' => 'text', 'label' => 'Website link text', 'default' => 'Official Website'],
                    'items' => [
                        'type' => 'repeater', 'label' => 'Like-Minded Entities', 'item_label' => 'short', 'add_label' => 'Add entity',
                        'fields' => [
                            'short'   => ['type' => 'text',  'label' => 'Short name / acronym'],
                            'name'    => ['type' => 'text',  'label' => 'Full name'],
                            'logo'    => ['type' => 'image', 'label' => 'Logo'],
                            'website' => ['type' => 'url',   'label' => 'Website (leave empty for none)'],
                        ],
                        'default' => [
                            ['short' => 'IAFI', 'name' => 'International Association for Forensic Institutes', 'logo' => 'media/lme/iafi.png', 'website' => ''],
                            ['short' => 'INTERPA', 'name' => 'International Association of Police Academies', 'logo' => 'media/lme/interpa.png', 'website' => ''],
                            ['short' => 'APMLA', 'name' => 'The Asia Pacific Medico Legal Agencies', 'logo' => 'media/lme/apmla.jpg', 'website' => 'https://www.theapmla.net/'],
                            ['short' => 'BORDERPOL', 'name' => 'The World Border Organization, Canada', 'logo' => 'media/lme/borderpol.png', 'website' => ''],
                            ['short' => 'CARICC', 'name' => 'Central Asian Regional Information and Coordination Centre', 'logo' => 'media/lme/caricc.png', 'website' => 'https://www.caricc.org/en/'],
                            ['short' => 'CITES', 'name' => 'Secretariat of the Convention on International Trade in Endangered Species of Fauna and Flora', 'logo' => 'media/lme/cites.png', 'website' => 'https://cites.org/eng'],
                            ['short' => 'UN CTED', 'name' => 'United Nations Counter-Terrorism Committee Executive Directorate', 'logo' => 'media/lme/un-cted.png', 'website' => 'https://www.un.org/securitycouncil/ctc/'],
                            ['short' => 'SELEC', 'name' => 'Southeast European Law Enforcement Center', 'logo' => 'media/lme/selec.jpg', 'website' => 'https://www.selec.org/'],
                            ['short' => 'PICP', 'name' => 'Pacific Islands Chiefs of Police Secretariat', 'logo' => 'media/lme/picp.png', 'website' => 'https://www.picp.co.nz/'],
                            ['short' => 'EAST', 'name' => 'European Association for Secure Transactions', 'logo' => 'media/lme/east.png', 'website' => 'https://www.association-secure-transactions.eu/'],
                            ['short' => 'UNODC', 'name' => 'United Nations Office on Drugs and Crime', 'logo' => 'media/lme/unodc.png', 'website' => 'https://www.unodc.org/'],
                            ['short' => 'RATS-SCO', 'name' => 'Regional Anti-Terrorism Structure of the Shanghai Cooperation Organisation', 'logo' => 'media/lme/rats-sco.png', 'website' => 'https://ecrats.org/ru/'],
                            ['short' => 'APTA', 'name' => 'Association of Police Training Institutions in Asia', 'logo' => 'media/lme/apta.jpeg', 'website' => 'https://aptahome.org/'],
                            ['short' => 'USAID Wildlife Asia', 'name' => 'U.S. Agency for International Development Wildlife Asia', 'logo' => 'media/lme/usaid.png', 'website' => 'https://www.fhi360.org/projects/wildlife-asia/'],
                            ['short' => 'RRU', 'name' => 'Rashtriya Raksha University', 'logo' => 'media/lme/rru.png', 'website' => 'https://rru.ac.in/'],
                            ['short' => 'Mastercard', 'name' => 'Mastercard Asia Pacific', 'logo' => 'media/lme/mastercard.png', 'website' => 'https://www.mastercard.com/news/ap/en/'],
                            ['short' => 'Freeland', 'name' => 'Freeland', 'logo' => 'media/lme/freeland.jpeg', 'website' => 'https://www.freeland.org/'],
                            ['short' => 'LATF', 'name' => 'Lusaka Agreement Task Force', 'logo' => 'media/lme/latf.png', 'website' => 'https://lusakaagreement.org/'],
                        ],
                    ],
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
                'fields' => [
                    'since_label'    => ['type' => 'text', 'label' => 'Badge prefix', 'default' => 'Since'],
                    'activities_label' => ['type' => 'text', 'label' => 'Activities link text', 'default' => 'See Activities'],
                    'website_label'  => ['type' => 'text', 'label' => 'Website link text', 'default' => 'Official Website'],
                    'items' => [
                        'type' => 'repeater', 'label' => 'Dialogue Partners', 'item_label' => 'name', 'add_label' => 'Add partner',
                        'fields' => [
                            'name'    => ['type' => 'text',     'label' => 'Name'],
                            'full'    => ['type' => 'text',     'label' => 'Full name'],
                            'badge'   => ['type' => 'text',     'label' => 'Badge: flag emoji, or a Material Symbols icon name (e.g. hub, public) for organisations'],
                            'since'   => ['type' => 'text',     'label' => 'Partner since (year)'],
                            'website' => ['type' => 'url',      'label' => 'Website'],
                            'q'       => ['type' => 'text',     'label' => 'News search keyword for "See Activities"'],
                            'desc'    => ['type' => 'textarea', 'label' => 'Description', 'rows' => 4],
                        ],
                        'default' => [
                            ['name' => 'INTERPOL', 'full' => 'International Criminal Police Organization', 'badge' => 'travel_explore', 'since' => '2012', 'website' => 'https://www.interpol.int/', 'q' => 'INTERPOL', 'desc' => "The world's largest international police organisation. ASEANAPOL's e-ADS operates on INTERPOL's I-24/7 secure communications network, enabling seamless criminal intelligence exchange across the region."],
                            ['name' => 'Australian Federal Police (AFP)', 'full' => 'Australian Federal Police', 'badge' => '🇦🇺', 'since' => '2008', 'website' => 'https://www.afp.gov.au/', 'q' => 'Australian Federal Police', 'desc' => 'A founding Dialogue Partner since 2008, AFP provides capacity-building training for ASEANAPOL member countries and participates actively in annual conferences and contact person meetings.'],
                            ['name' => 'Ministry of Public Security (MPS)', 'full' => 'Ministry of Public Security, China', 'badge' => '🇨🇳', 'since' => '2008', 'website' => 'https://www.mps.gov.cn/', 'q' => 'China', 'desc' => "China's national law enforcement ministry contributes expertise in combating transnational crime, sharing intelligence and supporting ASEANAPOL's regional security agenda."],
                            ['name' => 'National Police Agency (NPA)', 'full' => 'National Police Agency, Japan', 'badge' => '🇯🇵', 'since' => '2008', 'website' => 'https://www.npa.go.jp/english/', 'q' => 'Japan', 'desc' => "Japan's NPA engages as a key Dialogue Partner, contributing to cybercrime, drug trafficking, and financial crime cooperation efforts across the ASEAN region."],
                            ['name' => 'Korean National Police Agency (KNPA)', 'full' => 'Korean National Police Agency, Republic of Korea', 'badge' => '🇰🇷', 'since' => '2008', 'website' => 'https://www.police.go.kr/eng/main.do', 'q' => 'Korean National Police', 'desc' => 'KNPA supports ASEANAPOL through shared expertise in cybercrime investigation, digital forensics, and capacity-building programmes for member country police forces.'],
                            ['name' => 'New Zealand Police (NZP)', 'full' => 'New Zealand Police', 'badge' => '🇳🇿', 'since' => '2008', 'website' => 'https://www.police.govt.nz/', 'q' => 'New Zealand', 'desc' => "New Zealand Police actively participates in ASEANAPOL conferences and training cooperation meetings, supporting the region's capacity in serious crime investigation."],
                            ['name' => 'Ministry of Internal Affairs (MIA)', 'full' => 'Ministry of Internal Affairs, Russian Federation', 'badge' => '🇷🇺', 'since' => '2014', 'website' => 'https://mvd.ru/', 'q' => 'Russia', 'desc' => "Elevated to Dialogue Partner status at the 34th ASEANAPOL Conference in 2014. Russia's MIA provides annual training programmes on counter-narcotics and organised crime for AMC officers."],
                            ['name' => 'Turkish National Police (TNP)', 'full' => 'Turkish National Police', 'badge' => '🇹🇷', 'since' => '2015', 'website' => 'https://www.egm.gov.tr/', 'q' => 'Turkish', 'desc' => "Turkey's national police service engages with ASEANAPOL through knowledge exchange and cooperation on counter-terrorism, drug trafficking, and financial crime."],
                            ['name' => 'UK National Crime Agency (NCA)', 'full' => 'National Crime Agency, United Kingdom', 'badge' => '🇬🇧', 'since' => '2019', 'website' => 'https://www.nationalcrimeagency.gov.uk/', 'q' => 'United Kingdom', 'desc' => "The UK's NCA collaborates with ASEANAPOL on combating serious and organised crime, including human trafficking, cybercrime, and drug networks affecting the ASEAN region."],
                            ['name' => 'Royal Canadian Mounted Police (RCMP)', 'full' => 'Royal Canadian Mounted Police', 'badge' => '🇨🇦', 'since' => '2019', 'website' => 'https://rcmp.ca/en', 'q' => 'Canada', 'desc' => 'The RCMP supports ASEANAPOL capacity-building through training and knowledge transfer programmes, particularly through initiatives hosted at the Jakarta Centre for Law Enforcement Cooperation (JCLEC).'],
                            ['name' => 'ASEAN Secretariat', 'full' => 'Secretariat of the Association of Southeast Asian Nations', 'badge' => 'hub', 'since' => '2012', 'website' => 'https://asean.org/', 'q' => 'ASEAN Secretariat', 'desc' => "The ASEAN Secretariat participates as a Dialogue Partner, supporting alignment between ASEANAPOL's work and broader ASEAN frameworks on transnational crime and regional security."],
                            ['name' => 'Europol', 'full' => 'European Union Agency for Law Enforcement Cooperation', 'badge' => 'public', 'since' => '2018', 'website' => 'https://www.europol.europa.eu/', 'q' => 'Europol', 'desc' => 'Europol engages with ASEANAPOL on cross-regional threats including financial fraud, cybercrime, and organised crime affecting both Southeast Asia and Europe.'],
                        ],
                    ],
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
                'fields' => [
                    'heading'          => ['type' => 'text', 'label' => 'Heading', 'default' => 'Current Observers'],
                    'since_label'      => ['type' => 'text', 'label' => 'Badge prefix', 'default' => 'Since'],
                    'activities_label' => ['type' => 'text', 'label' => 'Activities link text', 'default' => 'See Activities'],
                    'website_label'    => ['type' => 'text', 'label' => 'Website link text', 'default' => 'Website'],
                    'items' => [
                        'type' => 'repeater', 'label' => 'Observers', 'item_label' => 'name', 'add_label' => 'Add observer',
                        'fields' => [
                            'name'       => ['type' => 'text', 'label' => 'Name'],
                            'flag'       => ['type' => 'text', 'label' => 'Flag emoji (leave empty for generic icon)'],
                            'since'      => ['type' => 'text', 'label' => 'Observer since (year)'],
                            'conference' => ['type' => 'text', 'label' => 'Admitted at conference'],
                            'q'          => ['type' => 'text', 'label' => 'News search keyword for "See Activities"'],
                            'url'        => ['type' => 'url',  'label' => 'Website (leave empty for none)'],
                        ],
                        'default' => [
                            ['name' => 'Argentine Federal Police (AFP)', 'flag' => '🇦🇷', 'since' => '2022', 'conference' => '40th AC, Cambodia', 'q' => 'Argentina', 'url' => 'https://www.argentina.gob.ar/seguridad/pfa'],
                            ['name' => 'Bangladesh Police', 'flag' => '🇧🇩', 'since' => '2022', 'conference' => '40th AC, Cambodia', 'q' => 'Bangladesh', 'url' => 'https://www.police.gov.bd/'],
                            ['name' => 'Fiji', 'flag' => '🇫🇯', 'since' => '2016', 'conference' => '36th AC, Malaysia', 'q' => 'Fiji', 'url' => ''],
                            ['name' => 'French National Police (FNP)', 'flag' => '🇫🇷', 'since' => '2019', 'conference' => '39th AC, Viet Nam', 'q' => 'French', 'url' => 'https://www.police-nationale.interieur.gouv.fr/'],
                            ['name' => 'Security Department of Italian Interior (PSD IIM)', 'flag' => '🇮🇹', 'since' => '2022', 'conference' => '40th AC, Cambodia', 'q' => 'Italian', 'url' => ''],
                            ['name' => 'Royal Canadian Mounted Police (RCMP)', 'flag' => '🇨🇦', 'since' => '2019', 'conference' => '39th AC, Viet Nam', 'q' => 'Canada', 'url' => 'https://rcmp.ca/en'],
                            ['name' => 'Timor-Leste', 'flag' => '🇹🇱', 'since' => '2014', 'conference' => '34th AC, Philippines', 'q' => 'Timor', 'url' => ''],
                            ['name' => 'United Arab Emirates (UAE)', 'flag' => '🇦🇪', 'since' => '2022', 'conference' => '40th AC, Cambodia', 'q' => 'UAE', 'url' => 'https://moi.gov.ae/ar/default.aspx'],
                            ['name' => 'International Association of Chiefs of Police (IACP)', 'flag' => '', 'since' => '2016', 'conference' => '36th AC, Malaysia', 'q' => 'IACP', 'url' => 'https://www.theiacp.org/'],
                            ['name' => 'International Committee of the Red Cross (ICRC)', 'flag' => '', 'since' => '2015', 'conference' => '35th AC, Indonesia', 'q' => 'ICRC', 'url' => 'https://www.icrc.org/en'],
                            ['name' => 'Gulf Cooperation Council Police (GCCPOL)', 'flag' => '', 'since' => '2019', 'conference' => '39th AC, Viet Nam', 'q' => 'GCCPOL', 'url' => 'https://www.gcc-sg.org/ar/JointGulf/Cooperation/GCCPOL/Pages/default.aspx'],
                        ],
                    ],
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
