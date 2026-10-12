<?php

/**
 * CMS registry — group "data" (Data & Resources).
 * Page key = "data.<slug>". DEFAULT values below ARE the original hardcoded content.
 * Editions themselves (title, theme, PDF ...) live in the Publications module (/admin/publications).
 */
return [
    '_group' => ['label' => 'Data & Resources', 'icon' => 'database', 'order' => 30],

    // ------------------------------------------------------------------ hub
    'hub' => [
        'label' => 'Data & Resources hub',
        'route' => 'data-resources.index',
        'sections' => [
            'hero' => [
                'label' => 'Page header',
                'fields' => [
                    'title'    => ['type' => 'text',     'label' => 'Title',    'default' => 'Data & Resources'],
                    'subtitle' => ['type' => 'textarea', 'label' => 'Subtitle', 'rows' => 2, 'default' => 'Publications, statistics, and official documents from ASEANAPOL.'],
                ],
            ],
            'cards' => [
                'label'  => 'Resource cards',
                'fields' => [
                    'soon_label' => ['type' => 'text', 'label' => 'Badge text for "Coming soon" cards', 'default' => 'Coming Soon', 'audit' => false /* badge shown only on a card with status=soon; no default card is 'soon' */],
                    'items' => [
                        'type' => 'repeater', 'label' => 'Cards', 'item_label' => 'title', 'add_label' => 'Add card', 'columns' => 2,
                        'fields' => [
                            'icon'   => ['type' => 'icon',     'label' => 'Icon'],
                            'title'  => ['type' => 'text',     'label' => 'Title'],
                            'desc'   => ['type' => 'textarea', 'label' => 'Description', 'rows' => 3],
                            'route'  => ['type' => 'select',   'label' => 'Links to', 'options' => [
                                'data-resources.crime-statistics' => 'Crime Statistics page',
                                'data-resources.publications'     => 'Publications page',
                                'data-resources.digital-library'  => 'Digital Library page',
                                'data-resources.index'            => 'Data & Resources hub',
                            ]],
                            'label'  => ['type' => 'text',     'label' => 'Link label'],
                            'status' => ['type' => 'select',   'label' => 'Status', 'options' => ['active' => 'Active (clickable)', 'soon' => 'Coming soon (disabled)']],
                        ],
                        'default' => [
                            ['icon' => 'bar_chart',     'title' => 'Crime Statistics', 'desc' => 'Public crime data dashboard covering transnational crime trends across ASEAN member states.', 'route' => 'data-resources.crime-statistics', 'label' => 'View Statistics',     'status' => 'active'],
                            ['icon' => 'menu_book',     'title' => 'Publications',     'desc' => 'The official ASEANAPOL Bulletin and Magazine series — editions from the 8th (2015) through the 13th (2023).', 'route' => 'data-resources.publications', 'label' => 'Browse Publications', 'status' => 'active'],
                            ['icon' => 'library_books', 'title' => 'Digital Library',  'desc' => 'Downloadable guidelines, policy documents, and reference materials from ASEANAPOL.', 'route' => 'data-resources.digital-library', 'label' => 'Open Library', 'status' => 'active'],
                        ],
                    ],
                ],
            ],
        ],
    ],

    // ------------------------------------------------------- crime statistics
    'crime-statistics' => [
        'label' => 'Crime Statistics',
        'route' => 'data-resources.crime-statistics',
        'sections' => [
            'hero' => [
                'label' => 'Page header',
                'fields' => [
                    'title'    => ['type' => 'text',     'label' => 'Title',    'default' => 'Crime Statistics'],
                    'subtitle' => ['type' => 'textarea', 'label' => 'Subtitle', 'rows' => 2, 'default' => 'Public crime data covering transnational crime trends across ASEAN member states.'],
                ],
            ],
            'notice' => [
                'label'  => 'Status notice',
                'toggle' => true,
                'help'   => 'Contact e-mail is managed in Shared facts → Secretariat.',
                'fields' => [
                    'icon'    => ['type' => 'icon',     'label' => 'Icon', 'default' => 'construction'],
                    'heading' => ['type' => 'text',     'label' => 'Heading', 'default' => 'Coming Soon'],
                    'body'    => ['type' => 'textarea', 'label' => 'Text (before the e-mail link)', 'rows' => 4, 'default' => 'The public crime statistics dashboard is currently being developed. Aggregated crime data across ASEAN member states will be published here once validated. For data enquiries, contact'],
                ],
            ],
            'library' => [
                'label'  => 'Digital Library pointer',
                'toggle' => true,
                'fields' => [
                    'text'   => ['type' => 'textarea', 'label' => 'Text', 'rows' => 2, 'default' => 'Published reports and analysis documents are available in the Digital Library.'],
                    'button' => ['type' => 'text',     'label' => 'Button label', 'default' => 'Open Digital Library'],
                ],
            ],
        ],
    ],

    // ------------------------------------------------------------ digital library
    'digital-library' => [
        'label' => 'Digital Library (static text)',
        'route' => 'data-resources.digital-library',
        'sections' => [
            'hero' => [
                'label' => 'Page header',
                'help'  => 'Collections and documents are managed in Digital Library (admin sidebar).',
                'fields' => [
                    'title'    => ['type' => 'text',     'label' => 'Title',    'default' => 'Digital Library'],
                    'subtitle' => ['type' => 'textarea', 'label' => 'Subtitle', 'rows' => 2, 'default' => 'Official documents, guidelines, and publications from ASEANAPOL.'],
                ],
            ],
            'table' => [
                'label' => 'Table labels',
                'fields' => [
                    'col_document' => ['type' => 'text', 'label' => 'Column: Document', 'default' => 'Document'],
                    'col_format'   => ['type' => 'text', 'label' => 'Column: Format',   'default' => 'Format'],
                    'col_action'   => ['type' => 'text', 'label' => 'Column: Action',   'default' => 'Action'],
                    'badge_pdf'    => ['type' => 'text', 'label' => 'Format badge: PDF', 'default' => 'PDF'],
                    'badge_web'    => ['type' => 'text', 'label' => 'Format badge: web link', 'default' => 'Web'],
                    'btn_download' => ['type' => 'text', 'label' => 'Download button', 'default' => 'Download PDF'],
                    'btn_view'     => ['type' => 'text', 'label' => 'View link', 'default' => 'View'],
                ],
            ],
            'empty' => [
                'label'  => 'Empty state',
                'fields' => [
                    'text' => ['type' => 'text', 'label' => 'Message when no documents are published', 'default' => 'Documents will be published here shortly.', 'audit' => false /* shown only when no document is published; the audited page has documents */],
                ],
            ],
        ],
    ],

    // ------------------------------------------------------ publications index
    'publications-index' => [
        'label' => 'Publications (index page text)',
        'route' => 'data-resources.publications',
        'sections' => [
            'hero' => [
                'label' => 'Page header',
                'help'  => 'The editions themselves are managed in Publications (admin sidebar).',
                'fields' => [
                    'title'    => ['type' => 'text',     'label' => 'Title',    'default' => 'Publications'],
                    'subtitle' => ['type' => 'textarea', 'label' => 'Subtitle', 'rows' => 2, 'default' => 'The official ASEANAPOL Bulletin and Magazine editions.'],
                ],
            ],
            'intro' => [
                'label'  => 'Introduction',
                'toggle' => true,
                'fields' => [
                    'text' => ['type' => 'textarea', 'label' => 'Paragraph', 'rows' => 4, 'default' => 'The ASEANAPOL Bulletin and Magazine are the official publications of the organisation, documenting key activities, cooperative programmes, news from member police forces, and developments in regional law enforcement cooperation.'],
                ],
            ],
            'cards' => [
                'label' => 'Edition card labels',
                'fields' => [
                    'brand'        => ['type' => 'text', 'label' => 'Cover brand line', 'default' => 'ASEANAPOL'],
                    'caption'      => ['type' => 'text', 'label' => 'Caption under title', 'default' => 'Official ASEANAPOL Publication'],
                    'btn_download' => ['type' => 'text', 'label' => 'Download button', 'default' => 'Download PDF'],
                    'size_format'  => ['type' => 'text', 'label' => 'File size line ({size} = size)', 'default' => 'PDF, {size}'],
                    'unavailable'  => ['type' => 'text', 'label' => 'No-PDF message', 'default' => 'PDF not available', 'audit' => false /* shown only on a card whose edition has no PDF; every audited edition has one */],
                ],
            ],
        ],
    ],

    // ------------------------------------------------- publication edition (shared)
    'publication-edition' => [
        'label' => 'Publication edition (shared labels)',
        'route' => 'publication.15th',
        'sections' => [
            'hero' => [
                'label' => 'Page header',
                'help'  => 'Shared by every edition. Placeholders: {type} = Bulletin/Magazine, {title} = edition title, {size} = file size.',
                'fields' => [
                    'subtitle' => ['type' => 'text', 'label' => 'Subtitle', 'default' => 'Official ASEANAPOL {type}'],
                ],
            ],
            'cover' => [
                'label' => 'Cover graphic',
                'fields' => [
                    'brand'         => ['type' => 'text', 'label' => 'Brand line', 'default' => 'ASEANAPOL'],
                    'edition_label' => ['type' => 'text', 'label' => 'Edition label', 'default' => 'Edition {type}'],
                ],
            ],
            'intro' => [
                'label' => 'Introduction',
                'fields' => [
                    'text' => ['type' => 'textarea', 'label' => 'Paragraph (used when an edition has no own description)', 'rows' => 5, 'default' => 'The {title} is an official publication of the ASEANAPOL Permanent Secretariat. It documents the activities, programmes, and achievements of ASEANAPOL and its member police forces, serving as a key resource for police professionals, researchers, and stakeholders across the ASEAN region.'],
                ],
            ],
            'contents' => [
                'label'  => 'Typical contents',
                'toggle' => true,
                'fields' => [
                    'heading' => ['type' => 'text', 'label' => 'Heading', 'default' => 'Typical Contents'],
                    'items'   => [
                        'type' => 'repeater', 'label' => 'Items', 'item_label' => 'text', 'add_label' => 'Add item',
                        'fields' => [
                            'icon' => ['type' => 'icon', 'label' => 'Icon'],
                            'text' => ['type' => 'text', 'label' => 'Text'],
                        ],
                        'default' => [
                            ['icon' => 'article',   'text' => 'Message from the ASEANAPOL Executive Director'],
                            ['icon' => 'groups',    'text' => 'Annual Conference highlights and resolutions'],
                            ['icon' => 'task_alt',  'text' => 'Working committee reports and achievements'],
                            ['icon' => 'handshake', 'text' => 'Partnership and collaboration updates'],
                            ['icon' => 'school',    'text' => 'Training and capacity building activities'],
                            ['icon' => 'newspaper', 'text' => 'Member police force news and updates'],
                        ],
                    ],
                ],
            ],
            'download' => [
                'label' => 'Download box',
                'help'  => 'The request e-mail address is managed in Shared facts → Secretariat.',
                'fields' => [
                    'heading'       => ['type' => 'text', 'label' => 'Heading', 'default' => 'Download PDF'],
                    'available'     => ['type' => 'text', 'label' => 'Text when a PDF exists', 'default' => 'This edition is available for download.'],
                    'size_format'   => ['type' => 'text', 'label' => 'File size text ({size})', 'default' => '(PDF, {size})'],
                    'button'        => ['type' => 'text', 'label' => 'Download button', 'default' => 'Download PDF'],
                    'unavailable'   => ['type' => 'textarea', 'label' => 'Text when no PDF exists', 'rows' => 2, 'default' => 'To request a copy of this publication, please contact the ASEANAPOL Permanent Secretariat.', 'audit' => false /* shown only on an edition page without a PDF; the audited edition has one */],
                    'request_subject' => ['type' => 'text', 'label' => 'Request e-mail subject ({title})', 'default' => 'Request: {title}', 'audit' => false /* shown only on an edition page without a PDF; the audited edition has one */],
                    'request_button'  => ['type' => 'text', 'label' => 'Request button', 'default' => 'Request This Publication', 'audit' => false /* shown only on an edition page without a PDF; the audited edition has one */],
                ],
            ],
            'back' => [
                'label' => 'Back link',
                'fields' => [
                    'label' => ['type' => 'text', 'label' => 'Label', 'default' => 'Back to Publications'],
                ],
            ],
        ],
    ],
];
