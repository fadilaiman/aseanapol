<?php

/**
 * CMS registry — group "careers" (Careers & Opportunities).
 * Page key = "careers.<slug>". DEFAULT values below ARE the original hardcoded content.
 */

// Reusable "job/opportunity listing" section (rendered by careers/_openings.blade.php).
$openings = fn (string $label, string $help, string $heading, string $addLabel) => [
    'label'  => $label,
    'help'   => $help,
    'fields' => [
        'heading' => ['type' => 'text', 'label' => 'Listing heading', 'default' => $heading],
        'items'   => [
            'type' => 'repeater', 'label' => 'Openings', 'item_label' => 'title', 'add_label' => $addLabel,
            'fields' => [
                'title'       => ['type' => 'text',     'label' => 'Title'],
                'department'  => ['type' => 'text',     'label' => 'Department / unit'],
                'location'    => ['type' => 'text',     'label' => 'Location'],
                'deadline'    => ['type' => 'text',     'label' => 'Application deadline (e.g. 31 December 2026)'],
                'description' => ['type' => 'textarea', 'label' => 'Description', 'rows' => 4],
                'apply_label' => ['type' => 'text',     'label' => 'Apply button label (default "Apply")'],
                'apply_link'  => ['type' => 'url',      'label' => 'Apply link or mailto: address'],
            ],
            'default' => [],
        ],
    ],
];

return [
    '_group' => ['label' => 'Careers & Opportunities', 'icon' => 'work', 'order' => 70],

    'index' => [
        'label' => 'Careers & Opportunities',
        'route' => 'careers.index',
        'sections' => [
            'hero' => ['label' => 'Page header', 'fields' => [
                'title'    => ['type' => 'text',     'label' => 'Title',    'default' => 'Careers & Opportunities'],
                'subtitle' => ['type' => 'textarea', 'label' => 'Subtitle', 'rows' => 2, 'default' => 'Join ASEANAPOL or explore exchange and development opportunities within the network.'],
            ]],
            'notice' => ['label' => 'Intro notice', 'toggle' => true, 'fields' => [
                'icon'        => ['type' => 'icon', 'label' => 'Icon', 'default' => 'info'],
                'text_before' => ['type' => 'textarea', 'label' => 'Text before link', 'rows' => 2, 'default' => 'Opportunities will be posted here as they become available. For current enquiries, please contact the'],
                'link_label'  => ['type' => 'text', 'label' => 'Link text', 'default' => 'ASEANAPOL Permanent Secretariat'],
                'link_url'    => ['type' => 'url',  'label' => 'Link URL (blank = Contact Us page)', 'default' => ''],
                'text_after'  => ['type' => 'text', 'label' => 'Text after link', 'default' => '.'],
            ]],
            'cards' => ['label' => 'Opportunity cards', 'fields' => [
                'items' => [
                    'type' => 'repeater', 'label' => 'Cards', 'item_label' => 'title', 'add_label' => 'Add card',
                    'fields' => [
                        'icon'       => ['type' => 'icon',     'label' => 'Icon'],
                        'title'      => ['type' => 'text',     'label' => 'Title'],
                        'desc'       => ['type' => 'textarea', 'label' => 'Description', 'rows' => 3],
                        'label'      => ['type' => 'text',     'label' => 'Link label'],
                        'target'     => ['type' => 'select',   'label' => 'Links to', 'options' => [
                            ''                          => '(custom URL below)',
                            'careers.vacancies'         => 'Vacancies page',
                            'careers.internships'       => 'Internships page',
                            'careers.exchange-programs' => 'Exchange Programs page',
                        ]],
                        'custom_url' => ['type' => 'url',      'label' => 'Custom URL (used when "Links to" is custom)'],
                    ],
                    'default' => [
                        ['icon' => 'work',       'title' => 'Vacancies',         'desc' => 'Current job openings at the ASEANAPOL Permanent Secretariat and associated positions within the ASEANAPOL network.', 'label' => 'View Vacancies', 'target' => 'careers.vacancies', 'custom_url' => ''],
                        ['icon' => 'co_present', 'title' => 'Internships',       'desc' => 'Internship and attachment opportunities for students and early-career professionals interested in regional policing cooperation.', 'label' => 'Learn More', 'target' => 'careers.internships', 'custom_url' => ''],
                        ['icon' => 'swap_horiz', 'title' => 'Exchange Programs', 'desc' => 'Officer exchange and secondment programmes between ASEANAPOL member police forces to strengthen inter-agency relationships.', 'label' => 'Learn More', 'target' => 'careers.exchange-programs', 'custom_url' => ''],
                    ],
                ],
            ]],
        ],
    ],

    'vacancies' => [
        'label' => 'Vacancies',
        'route' => 'careers.vacancies',
        'sections' => [
            'hero' => ['label' => 'Page header', 'fields' => [
                'title'        => ['type' => 'text',     'label' => 'Title',    'default' => 'Vacancies'],
                'subtitle'     => ['type' => 'textarea', 'label' => 'Subtitle', 'rows' => 2, 'default' => 'Current job openings at the ASEANAPOL Permanent Secretariat.'],
                'parent_label' => ['type' => 'text',     'label' => 'Breadcrumb: parent page label', 'default' => 'Careers & Opportunities'],
            ]],
            'openings' => $openings(
                'Current job openings',
                'Add a row for each open position. As soon as at least one visible row exists, the listing appears and the "No Active Openings" notice below is hidden automatically. Hide or remove all rows to bring the notice back.',
                'Current Openings',
                'Add job opening'
            ),
            'notice' => ['label' => '"No active openings" notice', 'toggle' => true, 'help' => 'Shown only while there are no visible job openings above.', 'fields' => [
                'icon'    => ['type' => 'icon',     'label' => 'Icon', 'default' => 'work_outline'],
                'heading' => ['type' => 'text',     'label' => 'Heading', 'default' => 'No Active Openings at This Time'],
                'text'    => ['type' => 'textarea', 'label' => 'Text', 'rows' => 3, 'default' => 'There are currently no open positions at the ASEANAPOL Permanent Secretariat. New vacancies will be posted here as they become available.'],
            ]],
            'types' => ['label' => 'Types of positions', 'toggle' => true, 'fields' => [
                'heading' => ['type' => 'text', 'label' => 'Heading', 'default' => 'Types of Positions'],
                'items' => [
                    'type' => 'repeater', 'label' => 'Position types', 'item_label' => 'title', 'add_label' => 'Add position type',
                    'fields' => [
                        'icon'  => ['type' => 'icon',     'label' => 'Icon'],
                        'title' => ['type' => 'text',     'label' => 'Title'],
                        'desc'  => ['type' => 'textarea', 'label' => 'Description', 'rows' => 3],
                    ],
                    'default' => [
                        ['icon' => 'manage_accounts', 'title' => 'Seconded Officers',    'desc' => 'Police officers seconded from member countries to serve at the ASEANAPOL Permanent Secretariat in Kuala Lumpur.'],
                        ['icon' => 'groups_2',        'title' => 'Secretariat Staff',    'desc' => 'Administrative, communications, and operational support roles within the ASEANAPOL Permanent Secretariat.'],
                        ['icon' => 'analytics',       'title' => 'Research & Analysis',  'desc' => 'Analysts working on transnational crime intelligence, reporting, and capacity-building programme support.'],
                        ['icon' => 'laptop_mac',      'title' => 'Technology & Systems', 'desc' => 'ICT and information management specialists supporting ASEANAPOL\'s digital infrastructure and systems.'],
                    ],
                ],
            ]],
            'updates' => ['label' => 'Stay updated box', 'toggle' => true, 'fields' => [
                'icon'        => ['type' => 'icon', 'label' => 'Icon', 'default' => 'notifications'],
                'heading'     => ['type' => 'text', 'label' => 'Heading', 'default' => 'Stay Updated'],
                'text_before' => ['type' => 'textarea', 'label' => 'Text before email', 'rows' => 2, 'default' => 'To be notified of future openings or to submit a general enquiry, contact the ASEANAPOL Permanent Secretariat at'],
                'email'       => ['type' => 'text', 'label' => 'Email address', 'default' => 'info@aseanapol.org'],
                'text_after'  => ['type' => 'textarea', 'label' => 'Text after email', 'rows' => 2, 'default' => '. Nominations for seconded officer positions are coordinated through member country focal points.'],
            ]],
        ],
    ],

    'internships' => [
        'label' => 'Internships',
        'route' => 'careers.internships',
        'sections' => [
            'hero' => ['label' => 'Page header', 'fields' => [
                'title'        => ['type' => 'text',     'label' => 'Title',    'default' => 'Internships'],
                'subtitle'     => ['type' => 'textarea', 'label' => 'Subtitle', 'rows' => 2, 'default' => 'Attachment and internship opportunities for students and early-career professionals in regional policing cooperation.'],
                'parent_label' => ['type' => 'text',     'label' => 'Breadcrumb: parent page label', 'default' => 'Careers & Opportunities'],
            ]],
            'overview' => ['label' => 'Programme overview', 'toggle' => true, 'fields' => [
                'icon'     => ['type' => 'icon',     'label' => 'Icon', 'default' => 'co_present'],
                'heading'  => ['type' => 'text',     'label' => 'Heading', 'default' => 'ASEANAPOL Internship Programme'],
                'subhead'  => ['type' => 'text',     'label' => 'Sub-heading', 'default' => 'Short-term attachment opportunities at the ASEANAPOL Permanent Secretariat'],
                'para1'    => ['type' => 'textarea', 'label' => 'Paragraph 1', 'rows' => 4, 'default' => 'The ASEANAPOL Internship Programme offers students and early-career professionals the opportunity to work alongside the Permanent Secretariat on regional policing cooperation, transnational crime analysis, communications, and administrative functions.'],
                'para2'    => ['type' => 'textarea', 'label' => 'Paragraph 2', 'rows' => 4, 'default' => 'Internships are unpaid attachments typically lasting 8–12 weeks, based in Kuala Lumpur, Malaysia. Candidates are expected to be enrolled in or recently graduated from an accredited university programme in criminology, law, international relations, public policy, or a related field.'],
            ]],
            'openings' => $openings(
                'Current internship openings',
                'Optional. Add a row per open internship/attachment call; the listing appears on the page as soon as one visible row exists. Empty by default (nothing shown).',
                'Current Internship Openings',
                'Add internship opening'
            ),
            'areas' => ['label' => 'Areas of work', 'toggle' => true, 'fields' => [
                'heading' => ['type' => 'text', 'label' => 'Heading', 'default' => 'Areas of Work'],
                'items' => [
                    'type' => 'repeater', 'label' => 'Areas', 'item_label' => 'title', 'add_label' => 'Add area',
                    'fields' => [
                        'icon'  => ['type' => 'icon',     'label' => 'Icon'],
                        'title' => ['type' => 'text',     'label' => 'Title'],
                        'desc'  => ['type' => 'textarea', 'label' => 'Description', 'rows' => 3],
                    ],
                    'default' => [
                        ['icon' => 'analytics',      'title' => 'Research & Intelligence', 'desc' => 'Supporting transnational crime analysis, trend reports, and ASEANAPOL Bulletin contributions.'],
                        ['icon' => 'campaign',       'title' => 'Communications',          'desc' => 'Assisting with press releases, website content, social media, and event documentation.'],
                        ['icon' => 'event',          'title' => 'Events & Training',       'desc' => 'Supporting the ATCM, ASEANAPOL Conference logistics, and training coordination.'],
                        ['icon' => 'folder_managed', 'title' => 'Administration',          'desc' => 'General secretariat support including records management and stakeholder correspondence.'],
                    ],
                ],
            ]],
            'apply' => ['label' => 'How to apply box', 'toggle' => true, 'fields' => [
                'heading'      => ['type' => 'text',     'label' => 'Heading', 'default' => 'How to Apply'],
                'text'         => ['type' => 'textarea', 'label' => 'Text', 'rows' => 3, 'default' => 'To apply for an internship or attachment, send your CV and a brief cover letter indicating your area of interest and preferred dates to the ASEANAPOL Permanent Secretariat.'],
                'button_icon'  => ['type' => 'icon', 'label' => 'Button icon', 'default' => 'mail'],
                'button_label' => ['type' => 'text', 'label' => 'Button label', 'default' => 'Contact the Secretariat'],
                'button_url'   => ['type' => 'url',  'label' => 'Button URL (blank = Contact Us page)', 'default' => ''],
            ]],
        ],
    ],

    'exchange-programs' => [
        'label' => 'Exchange Programs',
        'route' => 'careers.exchange-programs',
        'sections' => [
            'hero' => ['label' => 'Page header', 'fields' => [
                'title'        => ['type' => 'text',     'label' => 'Title',    'default' => 'Exchange Programs'],
                'subtitle'     => ['type' => 'textarea', 'label' => 'Subtitle', 'rows' => 2, 'default' => 'Officer exchange and secondment programmes between ASEANAPOL member police forces.'],
                'parent_label' => ['type' => 'text',     'label' => 'Breadcrumb: parent page label', 'default' => 'Careers & Opportunities'],
            ]],
            'intro' => ['label' => 'Programme intro', 'toggle' => true, 'fields' => [
                'icon'    => ['type' => 'icon',     'label' => 'Icon', 'default' => 'swap_horiz'],
                'heading' => ['type' => 'text',     'label' => 'Heading', 'default' => 'Officer Exchange & Secondment'],
                'subhead' => ['type' => 'text',     'label' => 'Sub-heading', 'default' => 'Building inter-agency relationships across ASEAN police forces'],
                'para1'   => ['type' => 'textarea', 'label' => 'Paragraph 1', 'rows' => 4, 'default' => 'ASEANAPOL\'s exchange and secondment programmes facilitate the placement of police officers from member countries into partner agencies and the ASEANAPOL Permanent Secretariat. These programmes strengthen inter-agency trust, build shared operational knowledge, and deepen personal networks across the region.'],
                'para2'   => ['type' => 'textarea', 'label' => 'Paragraph 2', 'rows' => 3, 'default' => 'Exchange assignments typically run from six months to two years and are coordinated bilaterally between member police forces or through the ASEANAPOL Secretariat.'],
            ]],
            'openings' => $openings(
                'Current exchange / secondment calls',
                'Optional. Add a row per open exchange or secondment call; the listing appears on the page as soon as one visible row exists. Empty by default (nothing shown).',
                'Current Exchange Opportunities',
                'Add exchange opportunity'
            ),
            'programs' => ['label' => 'Programme types', 'toggle' => true, 'fields' => [
                'heading' => ['type' => 'text', 'label' => 'Heading', 'default' => 'Programme Types'],
                'items' => [
                    'type' => 'repeater', 'label' => 'Programmes', 'item_label' => 'title', 'add_label' => 'Add programme type',
                    'fields' => [
                        'icon'  => ['type' => 'icon',     'label' => 'Icon'],
                        'title' => ['type' => 'text',     'label' => 'Title'],
                        'badge' => ['type' => 'text',     'label' => 'Badge'],
                        'desc'  => ['type' => 'textarea', 'label' => 'Description', 'rows' => 4],
                    ],
                    'default' => [
                        ['icon' => 'domain', 'title' => 'Secretariat Secondment', 'badge' => 'Secretariat', 'desc' => 'Officers from member police forces are seconded to serve at the ASEANAPOL Permanent Secretariat in Kuala Lumpur, contributing to daily operations, intelligence sharing, and programme coordination. Positions are allocated to member countries on a rotational basis.'],
                        ['icon' => 'share', 'title' => 'Bilateral Police Exchange', 'badge' => 'Bilateral', 'desc' => 'Direct officer exchange agreements between two ASEANAPOL member police forces, enabling officers to experience policing practices, investigative techniques, and cultural contexts in partner countries.'],
                        ['icon' => 'school', 'title' => 'Training Attachments', 'badge' => 'Training', 'desc' => 'Short-term attachments to partner police training academies or specialist units. Participants gain practical exposure to specialised areas such as forensic investigation, cybercrime, counter-trafficking, and financial crime.'],
                        ['icon' => 'public', 'title' => 'International Partner Placements', 'badge' => 'International', 'desc' => 'Placements with ASEANAPOL\'s international partner organisations — including INTERPOL, Europol, and UNODC — arranged for senior officers and specialists to broaden their global policing perspective.'],
                    ],
                ],
            ]],
            'nominations' => ['label' => 'Nominations & enquiries box', 'toggle' => true, 'fields' => [
                'heading'      => ['type' => 'text',     'label' => 'Heading', 'default' => 'Nominations & Enquiries'],
                'text'         => ['type' => 'textarea', 'label' => 'Text', 'rows' => 4, 'default' => 'Nominations for exchange and secondment positions are made through each member country\'s designated ASEANAPOL national focal point. For general enquiries about programme availability and eligibility, contact the ASEANAPOL Permanent Secretariat.'],
                'button_icon'  => ['type' => 'icon', 'label' => 'Button icon', 'default' => 'mail'],
                'button_label' => ['type' => 'text', 'label' => 'Button label', 'default' => 'Contact the Secretariat'],
                'button_url'   => ['type' => 'url',  'label' => 'Button URL (blank = Contact Us page)', 'default' => ''],
            ]],
        ],
    ],
];
