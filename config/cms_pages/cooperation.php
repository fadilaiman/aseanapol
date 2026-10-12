<?php

/**
 * CMS registry — group "cooperation" (International Cooperation).
 * Page key = "cooperation.<slug>". DEFAULT values below ARE the original hardcoded content.
 */

/** Internal pages an admin can point a quick-link card at (route name => label). */
$linkOptions = [
    'international-cooperation'                => 'International Cooperation (overview)',
    'international-cooperation.interpol'       => 'INTERPOL',
    'international-cooperation.europol'        => 'Europol',
    'international-cooperation.unodc'          => 'UNODC',
    'international-cooperation.joint-projects' => 'Joint Projects',
];

/** Field set shared by the sub-pages' engagement/timeline cards. */
$engagementFields = [
    'date'  => ['type' => 'text',     'label' => 'Date badge (e.g. 2018, Aug 2024)'],
    'title' => ['type' => 'text',     'label' => 'Title'],
    'desc'  => ['type' => 'textarea', 'label' => 'Description', 'rows' => 4],
];

return [
    '_group' => ['label' => 'International Cooperation', 'icon' => 'public', 'order' => 50],

    // ------------------------------------------------------------------ overview
    'index' => [
        'label' => 'International Cooperation (overview)',
        'route' => 'international-cooperation',
        'sections' => [
            'hero' => [
                'label'  => 'Page header',
                'fields' => [
                    'title'    => ['type' => 'text',     'label' => 'Title',    'default' => 'International Cooperation'],
                    'subtitle' => ['type' => 'textarea', 'label' => 'Subtitle', 'rows' => 2, 'default' => 'ASEANAPOL\'s network of international partner organisations working together to combat transnational crime.'],
                ],
            ],
            'intro' => [
                'label'  => 'Introduction paragraph',
                'toggle' => true,
                'fields' => [
                    'text' => ['type' => 'textarea', 'label' => 'Paragraph', 'rows' => 4, 'default' => 'ASEANAPOL maintains active collaborative relationships with a broad range of international organisations across law enforcement, crime prevention, wildlife protection, and financial security. These partnerships strengthen ASEANAPOL\'s capacity to address complex transnational threats.'],
                ],
            ],
            'quicklinks' => [
                'label'  => 'Quick links (4 icon cards)',
                'toggle' => true,
                'fields' => [
                    'items' => [
                        'type' => 'repeater', 'label' => 'Cards', 'item_label' => 'title', 'add_label' => 'Add quick link',
                        'fields' => [
                            'icon'  => ['type' => 'icon',   'label' => 'Icon'],
                            'title' => ['type' => 'text',   'label' => 'Title'],
                            'route' => ['type' => 'select', 'label' => 'Links to page', 'options' => $linkOptions],
                        ],
                        'default' => [
                            ['icon' => 'shield',    'title' => 'INTERPOL',       'route' => 'international-cooperation.interpol'],
                            ['icon' => 'public',    'title' => 'Europol',        'route' => 'international-cooperation.europol'],
                            ['icon' => 'balance',   'title' => 'UNODC',          'route' => 'international-cooperation.unodc'],
                            ['icon' => 'handshake', 'title' => 'Joint Projects', 'route' => 'international-cooperation.joint-projects'],
                        ],
                    ],
                ],
            ],
            'partners' => [
                'label'  => 'Partner organisations',
                'toggle' => true,
                'help'   => 'Each card shows an icon (or an optional logo image instead), name, category badge, full name, description and an optional website link.',
                'fields' => [
                    'heading'       => ['type' => 'text', 'label' => 'Heading', 'default' => 'Partner Organisations'],
                    'website_label' => ['type' => 'text', 'label' => 'Website link label', 'default' => 'Visit Website'],
                    'items' => [
                        'type' => 'repeater', 'label' => 'Partners', 'item_label' => 'name', 'add_label' => 'Add partner', 'columns' => 2,
                        'fields' => [
                            'name'     => ['type' => 'text',     'label' => 'Name'],
                            'category' => ['type' => 'text',     'label' => 'Category badge'],
                            'full'     => ['type' => 'text',     'label' => 'Full name'],
                            'icon'     => ['type' => 'icon',     'label' => 'Icon'],
                            'logo'     => ['type' => 'image',    'label' => 'Logo (optional, replaces the icon)'],
                            'website'  => ['type' => 'url',      'label' => 'Website (leave empty for no link)'],
                            'desc'     => ['type' => 'textarea', 'label' => 'Description', 'rows' => 3],
                        ],
                        'default' => [
                            ['name' => 'INTERPA', 'full' => 'International Association of Police Academies', 'icon' => 'school', 'website' => '', 'category' => 'Law Enforcement Training', 'desc' => 'Fosters cooperation among police academies worldwide, supporting training, educational exchange, and the development of police professionalism.'],
                            ['name' => 'APMLA', 'full' => 'Asia Pacific Medico Legal Agencies', 'icon' => 'medical_services', 'website' => '', 'category' => 'Forensics & Science', 'desc' => 'Promotes cooperation among medico-legal agencies in the Asia Pacific region, supporting forensic science development and criminal investigations.'],
                            ['name' => 'BorderPol', 'full' => 'World Border Organization', 'icon' => 'border_style', 'website' => '', 'category' => 'Border Security', 'desc' => 'A global network of border management agencies promoting cooperation, information exchange, and best practices in border security and control (Canada).'],
                            ['name' => 'CARICC', 'full' => 'Central Asian Regional Information and Coordination Centre', 'icon' => 'hub', 'website' => '', 'category' => 'Drug Trafficking', 'desc' => 'Facilitates international cooperation in combating illicit drug trafficking, organised crime, and terrorism in Central Asia.'],
                            ['name' => 'CITES Secretariat', 'full' => 'Convention on International Trade in Endangered Species', 'icon' => 'nature', 'website' => 'https://cites.org', 'category' => 'Wildlife Protection', 'desc' => 'Regulates international trade in wild animals and plants to prevent overexploitation and ensure species survival.'],
                            ['name' => 'UN CTED', 'full' => 'UN Counter Terrorism Committee Executive Directorate', 'icon' => 'security', 'website' => 'https://www.un.org/sc/ctc', 'category' => 'Counter-Terrorism', 'desc' => 'Supports the UN Security Council in monitoring and facilitating the implementation of counter-terrorism resolutions by member states.'],
                            ['name' => 'SELEC', 'full' => 'Southeast European Law Enforcement Center', 'icon' => 'gavel', 'website' => 'https://www.selec.org', 'category' => 'Law Enforcement', 'desc' => 'Enhances cooperation in combating transnational crime including terrorism, organised crime, and corruption in Southeast Europe.'],
                            ['name' => 'Pacific Islands Chiefs of Police', 'full' => 'Pacific Islands Chiefs of Police Secretariat', 'icon' => 'local_police', 'website' => 'https://picp.net', 'category' => 'Regional Police', 'desc' => 'The regional police organisation for the Pacific Islands, promoting police cooperation and capacity building across Pacific island nations.'],
                            ['name' => 'EAST', 'full' => 'European Association for Secure Transactions', 'icon' => 'credit_card', 'website' => 'https://east.eu.com', 'category' => 'Financial Crime', 'desc' => 'Works to reduce payment and transaction fraud through information sharing, research, and cooperation between law enforcement and the financial sector.'],
                            ['name' => 'UNODC', 'full' => 'United Nations Office on Drugs and Crime', 'icon' => 'balance', 'website' => 'https://www.unodc.org', 'category' => 'Drugs & Crime', 'desc' => 'Assists member states in the fight against illicit drugs, crime and terrorism, and promotes the rule of law and justice.'],
                            ['name' => 'RATS-SCO', 'full' => 'Regional Anti-Terrorist Structure of the Shanghai Cooperation Organisation', 'icon' => 'policy', 'website' => '', 'category' => 'Counter-Terrorism', 'desc' => 'A permanent body of the SCO facilitating coordination among member states in the fight against terrorism, separatism, and extremism.'],
                            ['name' => 'APTA', 'full' => 'ASEAN Police Training Association', 'icon' => 'school', 'website' => '', 'category' => 'Training', 'desc' => 'Supports professional development and training initiatives for police officers across ASEAN member states.'],
                            ['name' => 'USAID Wildlife Asia', 'full' => 'USAID Wildlife Asia Programme', 'icon' => 'pets', 'website' => '', 'category' => 'Wildlife Protection', 'desc' => 'Supports wildlife crime enforcement in Asia, working with governments and organisations to reduce the illegal trade in wildlife.'],
                            ['name' => 'RRU', 'full' => 'Regional Response Unit', 'icon' => 'emergency', 'website' => '', 'category' => 'Crisis Response', 'desc' => 'Supports rapid regional response capabilities and coordination in addressing emerging transnational security threats.'],
                            ['name' => 'Mastercard', 'full' => 'Mastercard International', 'icon' => 'payments', 'website' => 'https://www.mastercard.com', 'category' => 'Financial Security', 'desc' => 'Collaboration focused on combating financial fraud, cybercrime, and digital payment security threats affecting the region.'],
                            ['name' => 'Freeland', 'full' => 'Freeland Foundation', 'icon' => 'forest', 'website' => 'https://www.freeland.org', 'category' => 'Wildlife & Human Trafficking', 'desc' => 'Combats wildlife trafficking and human trafficking in Asia through partnerships with law enforcement, including the TRIPOD II programme with ASEANAPOL.'],
                            ['name' => 'LATF', 'full' => 'ASEAN Law Enforcement Task Force', 'icon' => 'task', 'website' => '', 'category' => 'Law Enforcement', 'desc' => 'Coordinates law enforcement task force activities addressing specific transnational crime priorities across ASEAN.'],
                        ],
                    ],
                ],
            ],
        ],
    ],

    // ------------------------------------------------------------------ INTERPOL
    'interpol' => [
        'label' => 'INTERPOL',
        'route' => 'international-cooperation.interpol',
        'sections' => [
            'hero' => [
                'label'  => 'Page header',
                'fields' => [
                    'title'    => ['type' => 'text',     'label' => 'Title',    'default' => 'ASEANAPOL & INTERPOL'],
                    'subtitle' => ['type' => 'textarea', 'label' => 'Subtitle', 'rows' => 2, 'default' => 'A strategic partnership strengthening the use of global police intelligence and joint operations to combat transnational crime.'],
                    'crumb'    => ['type' => 'text',     'label' => 'Breadcrumb label', 'default' => 'INTERPOL'],
                ],
            ],
            'overview' => [
                'label'  => 'Overview paragraph',
                'toggle' => true,
                'fields' => [
                    'text' => ['type' => 'html', 'label' => 'Paragraph', 'rows' => 5, 'help' => 'Use <strong>…</strong> for bold text.',
                        'default' => 'ASEANAPOL has maintained a close strategic partnership with <strong>INTERPOL</strong> (International Criminal Police Organization) since its establishment. This partnership is most visibly embodied in the <strong>Electronic ASEANAPOL Database System (e-ADS)</strong>, which operates on INTERPOL\'s secure global communication network, I-24/7. ASEANAPOL\'s Executive Director attends the INTERPOL General Assembly annually as part of ongoing engagement.'],
                ],
            ],
            'eads' => [
                'label'  => 'e-ADS system box',
                'toggle' => true,
                'fields' => [
                    'icon'    => ['type' => 'icon', 'label' => 'Header icon', 'default' => 'database'],
                    'heading' => ['type' => 'text', 'label' => 'Heading',     'default' => 'Electronic ASEANAPOL Database System (e-ADS)'],
                    'body'    => ['type' => 'richtext', 'label' => 'Body',
                        'default' => '<p> The e-ADS is ASEANAPOL\'s primary law enforcement database platform, enabling member countries to share criminal intelligence — including wanted persons, missing persons, and stolen property — across the ASEAN region. </p> <p> The system was first conceived at the 11th ASEANAPOL Conference in 1991 and officially launched in 2006 under the leadership of the Singapore Police Force. It operates on INTERPOL\'s <strong>I-24/7</strong> global communications network, allowing seamless and secure data exchange between ASEANAPOL member countries and INTERPOL\'s global network. </p> <p> Ongoing technical development is coordinated through the <strong>ASEANAPOL Database System Technical Committee (ADSTC)</strong>, which meets biennially to review and enhance the system\'s capabilities, and through bilateral technical meetings with INTERPOL\'s Information Systems Directorate. </p>'],
                ],
            ],
            'ops' => [
                'label'  => 'Joint operations & projects',
                'toggle' => true,
                'fields' => [
                    'heading' => ['type' => 'text', 'label' => 'Heading', 'default' => 'Joint Operations & Projects'],
                    'items' => [
                        'type' => 'repeater', 'label' => 'Operations', 'item_label' => 'name', 'add_label' => 'Add operation',
                        'fields' => [
                            'icon' => ['type' => 'icon',     'label' => 'Icon'],
                            'name' => ['type' => 'text',     'label' => 'Name'],
                            'desc' => ['type' => 'textarea', 'label' => 'Description', 'rows' => 3],
                        ],
                        'default' => [
                            ['name' => 'CT-SEA / Operation Maharlika', 'icon' => 'security', 'desc' => 'INTERPOL\'s Counter-Terrorism project in Southeast Asia. ASEANAPOL coordinates and supports Operation Maharlika deployments targeting terrorism financing networks across the region.'],
                            ['name' => 'Operation Pangea', 'icon' => 'medication', 'desc' => 'A global INTERPOL-led operation targeting the online sale of counterfeit and substandard medicines and medical devices. ASEANAPOL supports Southeast Asian participation.'],
                            ['name' => 'Project Torii', 'icon' => 'manage_search', 'desc' => 'An INTERPOL initiative focused on identifying and disrupting criminal networks engaged in organised crime and serious financial offences in the Asia Pacific region.'],
                            ['name' => 'ASPJOC', 'icon' => 'wifi_protected_setup', 'desc' => 'Asia and South Pacific Joint Operations Against Cybercrime — a joint effort coordinating law enforcement agencies across the region to tackle cyber-enabled crimes.'],
                            ['name' => 'I-GAIN', 'icon' => 'hub', 'desc' => 'INTERPOL Global Analysis and Intelligence Network — ASEANAPOL participates in this intelligence-sharing framework to strengthen global criminal analysis capacity.'],
                            ['name' => 'IASIWG', 'icon' => 'settings_ethernet', 'desc' => 'INTERPOL–ASEANAPOL System Integration Working Group — ensures the e-ADS remains technically aligned with INTERPOL\'s global information systems and I-24/7 network.'],
                        ],
                    ],
                ],
            ],
            'iga' => [
                'label'  => 'General Assembly participation',
                'toggle' => true,
                'fields' => [
                    'heading'      => ['type' => 'text',     'label' => 'Heading', 'default' => 'INTERPOL General Assembly Participation'],
                    'intro'        => ['type' => 'textarea', 'label' => 'Intro text', 'rows' => 3, 'default' => 'ASEANAPOL\'s Executive Director attends the annual INTERPOL General Assembly, which serves as the forum for bilateral meetings with member countries and partner organisations.'],
                    'col_session'  => ['type' => 'text',     'label' => 'Table column: Session',  'default' => 'Session'],
                    'col_year'     => ['type' => 'text',     'label' => 'Table column: Year',     'default' => 'Year'],
                    'col_location' => ['type' => 'text',     'label' => 'Table column: Location', 'default' => 'Location'],
                    'rows' => [
                        'type' => 'repeater', 'label' => 'Sessions', 'item_label' => 'session', 'add_label' => 'Add session',  'columns' => 2,
                        'fields' => [
                            'session' => ['type' => 'text', 'label' => 'Session (e.g. 92nd IGA)'],
                            'year'    => ['type' => 'text', 'label' => 'Year'],
                            'city'    => ['type' => 'text', 'label' => 'City'],
                            'country' => ['type' => 'text', 'label' => 'Country'],
                        ],
                        'default' => [
                            ['session' => '92nd IGA', 'year' => '2024', 'city' => 'Glasgow',   'country' => 'United Kingdom'],
                            ['session' => '90th IGA', 'year' => '2022', 'city' => 'New Delhi', 'country' => 'India'],
                            ['session' => '89th IGA', 'year' => '2021', 'city' => 'Istanbul',  'country' => 'Turkey'],
                            ['session' => '87th IGA', 'year' => '2018', 'city' => 'Dubai',     'country' => 'UAE'],
                            ['session' => '84th IGA', 'year' => '2015', 'city' => 'Kigali',    'country' => 'Rwanda'],
                            ['session' => '83rd IGA', 'year' => '2014', 'city' => 'Monaco',    'country' => 'Monaco'],
                        ],
                    ],
                ],
            ],
        ],
    ],

    // ------------------------------------------------------------------ Europol
    'europol' => [
        'label' => 'Europol',
        'route' => 'international-cooperation.europol',
        'sections' => [
            'hero' => [
                'label'  => 'Page header',
                'fields' => [
                    'title'    => ['type' => 'text',     'label' => 'Title',    'default' => 'ASEANAPOL & Europol'],
                    'subtitle' => ['type' => 'textarea', 'label' => 'Subtitle', 'rows' => 2, 'default' => 'Cross-regional law enforcement cooperation targeting shared threats in financial crime and transnational organised crime.'],
                    'crumb'    => ['type' => 'text',     'label' => 'Breadcrumb label', 'default' => 'Europol'],
                ],
            ],
            'overview' => [
                'label'  => 'Overview paragraph',
                'toggle' => true,
                'fields' => [
                    'text' => ['type' => 'html', 'label' => 'Paragraph', 'rows' => 5, 'help' => 'Use <strong>…</strong> for bold text.',
                        'default' => 'ASEANAPOL maintains a working relationship with <strong>Europol</strong>, the European Union\'s law enforcement agency, focused on areas of mutual interest including financial crime, cybercrime, and the fight against transnational organised crime. Cross-regional cooperation between ASEANAPOL and Europol reflects the shared recognition that major criminal networks operate across both regions.'],
                ],
            ],
            'areas' => [
                'label'  => 'Areas of cooperation',
                'toggle' => true,
                'fields' => [
                    'heading' => ['type' => 'text', 'label' => 'Heading', 'default' => 'Areas of Cooperation'],
                    'items' => [
                        'type' => 'repeater', 'label' => 'Areas', 'item_label' => 'title', 'add_label' => 'Add area',
                        'fields' => [
                            'icon'  => ['type' => 'icon',     'label' => 'Icon'],
                            'title' => ['type' => 'text',     'label' => 'Title'],
                            'desc'  => ['type' => 'textarea', 'label' => 'Description', 'rows' => 3],
                        ],
                        'default' => [
                            ['icon' => 'credit_card',     'title' => 'Payment Card Fraud', 'desc' => 'Joint strategic meetings on payment card fraud, identifying criminal networks operating between Europe and Southeast Asia.'],
                            ['icon' => 'computer',        'title' => 'Cybercrime',         'desc' => 'Collaboration on cybercrime threats affecting both regions, including cooperation through shared intelligence platforms.'],
                            ['icon' => 'account_balance', 'title' => 'Organised Crime',    'desc' => 'Information exchange on transnational organised crime groups with activities spanning both the European Union and ASEAN regions.'],
                        ],
                    ],
                ],
            ],
            'engagements' => [
                'label'  => 'Key engagements',
                'toggle' => true,
                'fields' => [
                    'heading' => ['type' => 'text', 'label' => 'Heading', 'default' => 'Key Engagements'],
                    'items' => [
                        'type' => 'repeater', 'label' => 'Engagements', 'item_label' => 'title', 'add_label' => 'Add engagement',
                        'fields' => $engagementFields,
                        'default' => [
                            ['date' => '2018', 'title' => 'Courtesy Visit by Head of the Europol Office', 'desc' => 'The Head of the Europol Office, Benoit Godart, paid a courtesy call on the ASEANAPOL Secretariat in Kuala Lumpur in April 2018, meeting with the Executive Director and senior directors to explore enhanced cooperation frameworks between both organisations.'],
                            ['date' => '2015', 'title' => 'Strategic Meeting on Payment Card Fraud', 'desc' => 'ASEANAPOL participated in a joint Europol–INTERPOL strategic meeting on payment card fraud held at the INTERPOL Global Complex for Innovation (IGCI) in Singapore, contributing Southeast Asian law enforcement perspectives to the global effort against financial fraud networks.'],
                        ],
                    ],
                ],
            ],
        ],
    ],

    // ------------------------------------------------------------------ UNODC
    'unodc' => [
        'label' => 'UNODC',
        'route' => 'international-cooperation.unodc',
        'sections' => [
            'hero' => [
                'label'  => 'Page header',
                'fields' => [
                    'title'    => ['type' => 'text',     'label' => 'Title',    'default' => 'ASEANAPOL & UNODC'],
                    'subtitle' => ['type' => 'textarea', 'label' => 'Subtitle', 'rows' => 2, 'default' => 'Working together to strengthen justice systems, combat drug trafficking, and reduce transnational organised crime in Southeast Asia.'],
                    'crumb'    => ['type' => 'text',     'label' => 'Breadcrumb label', 'default' => 'UNODC'],
                ],
            ],
            'overview' => [
                'label'  => 'Overview paragraph',
                'toggle' => true,
                'fields' => [
                    'text' => ['type' => 'html', 'label' => 'Paragraph', 'rows' => 5, 'help' => 'Use <strong>…</strong> for bold text.',
                        'default' => 'ASEANAPOL cooperates with the <strong>United Nations Office on Drugs and Crime (UNODC)</strong> on issues affecting the Southeast Asian region, including drug trafficking, wildlife crime, human trafficking, and justice system strengthening. This cooperation draws on UNODC\'s regional presence across ASEAN member countries and its specialised expertise in criminal justice reform.'],
                ],
            ],
            'areas' => [
                'label'  => 'Focus areas',
                'toggle' => true,
                'fields' => [
                    'heading' => ['type' => 'text', 'label' => 'Heading', 'default' => 'Focus Areas'],
                    'items' => [
                        'type' => 'repeater', 'label' => 'Focus areas', 'item_label' => 'title', 'add_label' => 'Add focus area',
                        'fields' => [
                            'icon'  => ['type' => 'icon',     'label' => 'Icon'],
                            'title' => ['type' => 'text',     'label' => 'Title'],
                            'desc'  => ['type' => 'textarea', 'label' => 'Description', 'rows' => 3],
                        ],
                        'default' => [
                            ['icon' => 'medication_liquid', 'title' => 'Drug Trafficking', 'desc' => 'Southeast Asia remains a key region in global illicit drug markets. ASEANAPOL and UNODC share intelligence and coordinate law enforcement responses to combat drug trafficking networks.'],
                            ['icon' => 'forest', 'title' => 'Wildlife & Forest Crime', 'desc' => 'Collaboration through UNODC\'s Global Programme for Combating Wildlife and Forest Crime to strengthen law enforcement capacity against illegal trade in protected species.'],
                            ['icon' => 'gavel', 'title' => 'Justice System Strengthening', 'desc' => 'UNODC supports ASEAN law enforcement through the South East Asia Justice Network (SEAJust), fostering cross-border judicial and prosecutorial cooperation.'],
                            ['icon' => 'hub', 'title' => 'Transnational Organised Crime', 'desc' => 'Coordination with UNODC\'s Regional Advisor on Transnational Organized Crime to align ASEANAPOL\'s operational priorities with the broader UN framework on organised crime.'],
                        ],
                    ],
                ],
            ],
            'engagements' => [
                'label'  => 'Recent engagements',
                'toggle' => true,
                'fields' => [
                    'heading' => ['type' => 'text', 'label' => 'Heading', 'default' => 'Recent Engagements'],
                    'items' => [
                        'type' => 'repeater', 'label' => 'Engagements', 'item_label' => 'title', 'add_label' => 'Add engagement',
                        'fields' => $engagementFields,
                        'default' => [
                            ['date' => 'Aug 2024', 'title' => 'Working Visit to UNODC — Vientiane, Lao PDR', 'desc' => 'Executive Director David Martinez Vinluan and Director for Plans and Programmes Nguyen Huu Ngoc conducted a working visit to the UNODC office in Vientiane to strengthen operational collaboration on transnational crime priorities in the region.'],
                            ['date' => 'Aug 2024', 'title' => 'SEAJust Plenary Meeting', 'desc' => 'ASEANAPOL participated as a panellist in UNODC\'s South East Asia Justice Network (SEAJust) Plenary Meeting in Vientiane, Lao PDR, contributing to discussions on cross-border justice and law enforcement cooperation frameworks in Southeast Asia.'],
                            ['date' => 'May 2024', 'title' => 'Virtual Meeting with UNODC & PCIP', 'desc' => 'ASEANAPOL held a virtual coordination meeting with UNODC and the Police Chiefs International Programme (PCIP) to align priorities and explore joint programming in transnational crime prevention.'],
                            ['date' => 'May 2017', 'title' => 'UNODC & USAID Wildlife Crime Visit', 'desc' => 'UNODC\'s Regional Coordinator for the Global Programme on Wildlife and Forest Crime, together with USAID\'s Wildlife Asia Law Enforcement Specialist, visited the ASEANAPOL Secretariat to discuss joint approaches to combating wildlife trafficking in the region.'],
                        ],
                    ],
                ],
            ],
        ],
    ],

    // ------------------------------------------------------------------ Joint projects
    'joint-projects' => [
        'label' => 'Joint Projects & Operations',
        'route' => 'international-cooperation.joint-projects',
        'sections' => [
            'hero' => [
                'label'  => 'Page header',
                'fields' => [
                    'title'    => ['type' => 'text',     'label' => 'Title',    'default' => 'Joint Projects & Operations'],
                    'subtitle' => ['type' => 'textarea', 'label' => 'Subtitle', 'rows' => 2, 'default' => 'ASEANAPOL\'s active participation in joint law enforcement operations and multilateral programmes targeting transnational crime.'],
                    'crumb'    => ['type' => 'text',     'label' => 'Breadcrumb label', 'default' => 'Joint Projects'],
                ],
            ],
            'intro' => [
                'label'  => 'Introduction paragraph',
                'toggle' => true,
                'fields' => [
                    'text' => ['type' => 'textarea', 'label' => 'Paragraph', 'rows' => 4, 'default' => 'ASEANAPOL actively participates in joint law enforcement operations and multilateral programmes led by international partner organisations. These initiatives range from counter-terrorism and cybercrime operations to wildlife crime and pharmaceutical fraud enforcement.'],
                ],
            ],
            'projects' => [
                'label'  => 'Projects & operations',
                'toggle' => true,
                'help'   => 'Status "Active" and "Ongoing" show a green badge; any other status shows a grey badge.',
                'fields' => [
                    'partner_label' => ['type' => 'text', 'label' => 'Partner line prefix', 'default' => 'Partner:'],
                    'items' => [
                        'type' => 'repeater', 'label' => 'Projects', 'item_label' => 'name', 'add_label' => 'Add project',
                        'fields' => [
                            'name'    => ['type' => 'text',     'label' => 'Name'],
                            'status'  => ['type' => 'select',   'label' => 'Status', 'options' => ['Active' => 'Active', 'Ongoing' => 'Ongoing', 'Past' => 'Past', 'Completed' => 'Completed']],
                            'type'    => ['type' => 'text',     'label' => 'Type badge'],
                            'partner' => ['type' => 'text',     'label' => 'Partner'],
                            'icon'    => ['type' => 'icon',     'label' => 'Icon'],
                            'desc'    => ['type' => 'textarea', 'label' => 'Description', 'rows' => 4],
                        ],
                        'default' => [
                            ['name' => 'CT-SEA / Operation Maharlika', 'partner' => 'INTERPOL', 'icon' => 'security', 'type' => 'Counter-Terrorism', 'status' => 'Active', 'desc' => 'INTERPOL\'s Counter-Terrorism Southeast Asia (CT-SEA) project involves coordinated operations targeting terrorism financing and foreign terrorist fighter networks. ASEANAPOL member countries have participated in multiple Maharlika deployments, including pre-deployment training in Hanoi, Vietnam (2024–2025).'],
                            ['name' => 'Operation Pangea', 'partner' => 'INTERPOL', 'icon' => 'medication', 'type' => 'Health Crime', 'status' => 'Active', 'desc' => 'A global operation targeting online trafficking of counterfeit and substandard medicines. ASEANAPOL participates in annual editions coordinated through INTERPOL\'s Health Crime sub-directorate, supporting Southeast Asian law enforcement agencies in joint enforcement actions.'],
                            ['name' => 'Project Torii', 'partner' => 'INTERPOL', 'icon' => 'manage_search', 'type' => 'Organised Crime', 'status' => 'Active', 'desc' => 'An INTERPOL-coordinated initiative targeting criminal networks involved in fraud and other serious financial crimes in the Asia Pacific region. ASEANAPOL facilitates information-sharing among member country units engaged with this project.'],
                            ['name' => 'ASPJOC', 'partner' => 'INTERPOL', 'icon' => 'wifi_protected_setup', 'type' => 'Cybercrime', 'status' => 'Active', 'desc' => 'Asia and South Pacific Joint Operations Against Cybercrime. ASEANAPOL participated in the 2024 ASPJOC operation in Manila, supporting member country law enforcement agencies in coordinated action against cybercrime networks.'],
                            ['name' => 'I-GAIN', 'partner' => 'INTERPOL', 'icon' => 'hub', 'type' => 'Criminal Intelligence', 'status' => 'Active', 'desc' => 'INTERPOL\'s Global Analysis and Intelligence Network. ASEANAPOL joined the inaugural I-GAIN meeting in 2024, contributing regional criminal intelligence analysis to the global network.'],
                            ['name' => 'SEAJust', 'partner' => 'UNODC', 'icon' => 'gavel', 'type' => 'Justice Cooperation', 'status' => 'Active', 'desc' => 'The South East Asia Justice Network, led by UNODC, strengthens cross-border judicial and prosecutorial cooperation. ASEANAPOL contributes the law enforcement perspective to this justice chain initiative.'],
                            ['name' => 'TRIPOD II', 'partner' => 'Freeland Foundation', 'icon' => 'forest', 'type' => 'Wildlife & Human Trafficking', 'status' => 'Past', 'desc' => 'A joint programme between ASEANAPOL and the Freeland Foundation targeting both wildlife trafficking and human trafficking networks in Southeast Asia, combining intelligence sharing with coordinated enforcement operations.'],
                            ['name' => 'e-ADS Technical Development', 'partner' => 'INTERPOL (IASIWG)', 'icon' => 'database', 'type' => 'Information Systems', 'status' => 'Ongoing', 'desc' => 'The INTERPOL–ASEANAPOL System Integration Working Group (IASIWG) oversees the continuous technical development of the e-ADS on INTERPOL\'s I-24/7 network, ensuring the system meets evolving operational and security requirements.'],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
