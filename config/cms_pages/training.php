<?php

/**
 * CMS registry — group "training" (Training).
 * Page key = "training.<slug>". DEFAULT values below ARE the original hardcoded content.
 */

/** "Coming soon" placeholder block shared by three of the pages. */
$soon = fn (string $icon, string $heading, string $body) => [
    'label'  => '"Coming soon" notice',
    'toggle' => true,
    'fields' => [
        'icon'    => ['type' => 'icon', 'label' => 'Icon', 'default' => $icon],
        'heading' => ['type' => 'text', 'label' => 'Heading', 'default' => $heading],
        'body'    => ['type' => 'html', 'label' => 'Text', 'rows' => 3, 'help' => 'Use <br> for a line break.', 'default' => $body],
    ],
];

return [
    '_group' => ['label' => 'Training', 'icon' => 'school', 'order' => 60],

    'index' => [
        'label' => 'Training (landing)',
        'route' => 'training.index',
        'sections' => [
            'hero' => [
                'label'  => 'Page header',
                'fields' => [
                    'title'    => ['type' => 'text',     'label' => 'Title',    'default' => 'Training'],
                    'subtitle' => ['type' => 'textarea', 'label' => 'Subtitle', 'rows' => 2, 'default' => 'Capacity-building and professional development programmes for ASEANAPOL member agencies.'],
                ],
            ],
            'soon' => $soon('model_training', 'Coming Soon', "ASEANAPOL Training programmes are currently under development.<br>\n            Please check back soon for updates."),
        ],
    ],

    'online-training' => [
        'label' => 'Online Training',
        'route' => 'training.online-training',
        'sections' => [
            'hero' => [
                'label'  => 'Page header',
                'fields' => [
                    'title'    => ['type' => 'text',     'label' => 'Title',    'default' => 'Online Training'],
                    'subtitle' => ['type' => 'textarea', 'label' => 'Subtitle', 'rows' => 2, 'default' => 'Virtual training programmes and webinars for ASEANAPOL member agencies.'],
                ],
            ],
            'table' => [
                'label'  => 'Programme schedule table',
                'toggle' => true,
                'help'   => 'Row numbers are generated automatically from the row order.',
                'fields' => [
                    'col_no'    => ['type' => 'text', 'label' => 'Column heading: number', 'default' => 'No'],
                    'col_title' => ['type' => 'text', 'label' => 'Column heading: title',  'default' => 'Title'],
                    'col_date'  => ['type' => 'text', 'label' => 'Column heading: date',   'default' => 'Date'],
                    'rows' => [
                        'type' => 'repeater', 'label' => 'Programmes', 'item_label' => 'title', 'add_label' => 'Add programme',
                        'fields' => [
                            'title' => ['type' => 'textarea', 'label' => 'Title', 'rows' => 3],
                            'date'  => ['type' => 'text',     'label' => 'Date'],
                        ],
                        'default' => [
                            ['title' => 'Countering Embezzlement by Means of IT Technologies', 'date' => '30 March – 17 April 2026'],
                            ['title' => 'Countering the Activities of Extremist and Terrorist Structures on the Internet', 'date' => '6 – 10 April 2026'],
                            ['title' => 'Counteraction to Illicit Drug Trafficking', 'date' => '7 – 10 April 2026'],
                            ['title' => 'Cryptocurrencies — Types of Cryptocurrencies, Bitcoins and Electronic Wallets, the Block Chain Method. The Use of Cryptocurrencies in the Financing of Terrorism, in the “Shadow Web”, Transaction Tracking, Methods of Confiscation and Possibilities of Investigation', 'date' => '18 – 22 May 2026'],
                            ['title' => 'Information Protection, Solving of ICT-Crimes, Counteraction to Illicit Transactions with the Use of Digital Assets. Peculiarities of Investigation of Crimes in the Sphere of Information and Telecommunication Technologies', 'date' => '25 – 29 May 2026'],
                            ['title' => 'Techniques for Investigating Cyberspace Crimes', 'date' => '15 – 26 June 2026'],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'in-person-training' => [
        'label' => 'In-Person Training',
        'route' => 'training.in-person-training',
        'sections' => [
            'hero' => [
                'label'  => 'Page header',
                'fields' => [
                    'title'    => ['type' => 'text',     'label' => 'Title',    'default' => 'In-Person Training'],
                    'subtitle' => ['type' => 'textarea', 'label' => 'Subtitle', 'rows' => 2, 'default' => 'Face-to-face training programmes and workshops for ASEANAPOL member agencies.'],
                ],
            ],
            'soon' => $soon('groups', 'Coming Soon', "ASEANAPOL In-Person Training programmes are currently under development.<br>\n            Workshops and face-to-face courses will be available here soon."),
        ],
    ],

    'e-learning' => [
        'label' => 'E-Learning',
        'route' => 'training.e-learning',
        'sections' => [
            'hero' => [
                'label'  => 'Page header',
                'fields' => [
                    'title'    => ['type' => 'text',     'label' => 'Title',    'default' => 'E-Learning Platform'],
                    'subtitle' => ['type' => 'textarea', 'label' => 'Subtitle', 'rows' => 2, 'default' => 'Online training and capacity-building resources for ASEANAPOL member agencies.'],
                    'crumb'    => ['type' => 'text',     'label' => 'Breadcrumb label', 'default' => 'E-Learning'],
                ],
            ],
            'soon' => $soon('school', 'Coming Soon', "The ASEANAPOL E-Learning Platform is currently under development.<br>\n            Training courses and materials will be available here soon."),
        ],
    ],
];
