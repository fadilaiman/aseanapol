<?php

/**
 * CMS registry — group "newsmedia" (News & Media).
 * Page key = "newsmedia.<slug>". DEFAULT values below ARE the original hardcoded content.
 * DB-driven parts (articles, newsletter issues, videos, gallery images) stay in their own modules;
 * only the static headings / labels around them are editable here.
 */
return [
    '_group' => ['label' => 'News & Media', 'icon' => 'newspaper', 'order' => 20],

    // ------------------------------------------------------------------ hub
    'index' => [
        'label' => 'News & Media hub',
        'route' => 'news-media.index',
        'sections' => [
            'hero' => [
                'label' => 'Page header',
                'fields' => [
                    'title'    => ['type' => 'text',     'label' => 'Title',    'default' => 'News & Media'],
                    'subtitle' => ['type' => 'textarea', 'label' => 'Subtitle', 'rows' => 2, 'default' => 'The latest news, press releases, and multimedia resources from ASEANAPOL.'],
                ],
            ],
            'cards' => [
                'label'  => 'Section cards',
                'toggle' => true,
                'help'   => 'One card per News & Media section. Hide a card to remove it from the hub.',
                'fields' => [
                    'items' => [
                        'type' => 'repeater', 'label' => 'Cards', 'item_label' => 'title', 'add_label' => 'Add card',
                        'fields' => [
                            'icon'  => ['type' => 'icon', 'label' => 'Icon'],
                            'title' => ['type' => 'text', 'label' => 'Title'],
                            'desc'  => ['type' => 'textarea', 'label' => 'Description', 'rows' => 2],
                            'route' => ['type' => 'select', 'label' => 'Links to', 'options' => [
                                'news.index'                => 'News',
                                'news-media.press-releases' => 'Press Releases',
                                'news-media.gallery'        => 'Photo Gallery',
                                'news-media.video-gallery'  => 'Video Gallery',
                                'news-media.newsletters'    => 'Newsletters',
                            ]],
                            'label' => ['type' => 'text', 'label' => 'Link label'],
                        ],
                        'default' => [
                            ['icon' => 'newspaper',     'title' => 'News',           'desc' => 'Latest news articles, announcements, and updates from ASEANAPOL and its member police forces.', 'route' => 'news.index',                'label' => 'Browse Articles'],
                            ['icon' => 'campaign',      'title' => 'Press Releases', 'desc' => 'Official press statements and media releases issued by the ASEANAPOL Permanent Secretariat.',     'route' => 'news-media.press-releases', 'label' => 'Read Releases'],
                            ['icon' => 'photo_library', 'title' => 'Photo Gallery',  'desc' => 'A visual archive of ASEANAPOL events, activities, governance, and partner engagements.',          'route' => 'news-media.gallery',        'label' => 'Browse Gallery'],
                            ['icon' => 'video_library', 'title' => 'Video Gallery',  'desc' => 'Video recordings of ASEANAPOL events, ceremonies, and training activities.',                      'route' => 'news-media.video-gallery',  'label' => 'Watch Videos'],
                            ['icon' => 'mail',          'title' => 'Newsletters',    'desc' => 'Periodic newsletters distributed to ASEANAPOL member police forces and stakeholders.',            'route' => 'news-media.newsletters',    'label' => 'Browse Newsletters'],
                        ],
                    ],
                ],
            ],
        ],
    ],

    // ------------------------------------------------------------------ news list
    'news-list' => [
        'label' => 'News list (headings & labels)',
        'route' => 'news.index',
        'sections' => [
            'hero' => [
                'label' => 'Page header',
                'fields' => [
                    'title'    => ['type' => 'text',     'label' => 'Title',    'default' => 'News'],
                    'subtitle' => ['type' => 'textarea', 'label' => 'Subtitle', 'rows' => 2, 'default' => 'Latest news, activities and announcements from ASEANAPOL.'],
                ],
            ],
            'labels' => [
                'label'  => 'Result count & card labels',
                'help'   => 'Reads like: "Showing 1–12 of 40 articles". The article list itself is managed in Admin > News.',
                'fields' => [
                    'showing'   => ['type' => 'text', 'label' => 'Word before the range', 'default' => 'Showing'],
                    'of'        => ['type' => 'text', 'label' => 'Word between range and total', 'default' => 'of'],
                    'articles'  => ['type' => 'text', 'label' => 'Word after the total', 'default' => 'articles'],
                    'read_more' => ['type' => 'text', 'label' => 'Card link label', 'default' => 'Read More'],
                ],
            ],
        ],
    ],

    'news-search' => [
        'label' => 'News list — search & empty state',
        'route' => 'news.index',
        'route_params' => ['q' => 'zzqxnomatch'],
        'sections' => [
            'filter' => [
                'label'  => 'Active search banner',
                'toggle' => true,
                'help'   => 'Shown above the list when a search term is used (e.g. from a homepage link).',
                'fields' => [
                    'text'  => ['type' => 'text', 'label' => 'Banner text', 'default' => 'Showing activities related to'],
                    'clear' => ['type' => 'text', 'label' => 'Clear link label', 'default' => 'Clear filter'],
                ],
            ],
            'empty' => [
                'label'  => 'No-results messages',
                'fields' => [
                    'summary'    => ['type' => 'text', 'label' => 'Result line', 'default' => 'No articles found'],
                    'summary_for' => ['type' => 'text', 'label' => 'Word before the search term', 'default' => 'for'],
                    'message'    => ['type' => 'text', 'label' => 'Empty-state message', 'default' => 'No articles found.'],
                    'link'       => ['type' => 'text', 'label' => 'Empty-state link label', 'default' => 'View all news'],
                ],
            ],
        ],
    ],

    // ------------------------------------------------------------------ single article
    'news-article' => [
        'label' => 'News article (labels)',
        // any published article works; the editor links to this one for preview only
        'route' => 'news.show',
        'route_params' => ['slug' => 'aseanapol-6p-ecosystem-approach-counter-scams-2026-10-06'],
        'sections' => [
            'labels' => [
                'label'  => 'Breadcrumbs & labels',
                'help'   => 'The article title, date and body come from Admin > News.',
                'fields' => [
                    'breadcrumb_news'    => ['type' => 'text', 'label' => 'Breadcrumb: News', 'default' => 'News'],
                    'breadcrumb_article' => ['type' => 'text', 'label' => 'Breadcrumb: current article', 'default' => 'Article'],
                    'no_content'         => ['type' => 'text', 'label' => 'Message when an article has no body', 'default' => 'Content not available.', 'audit' => false /* shown only for an article with an empty body; the audited article has content */],
                    'back'               => ['type' => 'text', 'label' => 'Back link label', 'default' => 'Back to News'],
                ],
            ],
        ],
    ],

    // ------------------------------------------------------------------ press releases
    'press-releases' => [
        'label' => 'Press Releases',
        'route' => 'news-media.press-releases',
        'sections' => [
            'hero' => [
                'label' => 'Page header',
                'fields' => [
                    'title'    => ['type' => 'text',     'label' => 'Title',    'default' => 'Press Releases'],
                    'subtitle' => ['type' => 'textarea', 'label' => 'Subtitle', 'rows' => 2, 'default' => 'Official statements and media releases from the ASEANAPOL Permanent Secretariat.'],
                ],
            ],
            'banner' => [
                'label'  => 'Media enquiries banner',
                'toggle' => true,
                'fields' => [
                    'text'  => ['type' => 'textarea', 'label' => 'Text before the email', 'rows' => 2, 'default' => 'For media enquiries or to request a full press kit, contact the ASEANAPOL Permanent Secretariat at'],
                    'email' => ['type' => 'text',     'label' => 'Email address', 'default' => 'info@aseanapol.org'],
                    'after' => ['type' => 'text',     'label' => 'Text after the email', 'default' => '.'],
                ],
            ],
            'releases' => [
                'label'  => 'Release list',
                'toggle' => true,
                'help'   => 'Each release links to a news article by its slug (the last part of the article URL).',
                'fields' => [
                    'read_more' => ['type' => 'text', 'label' => 'Link label', 'default' => 'Read Full Release'],
                    'items' => [
                        'type' => 'repeater', 'label' => 'Releases', 'item_label' => 'title', 'add_label' => 'Add release',
                        'fields' => [
                            'date'     => ['type' => 'text',     'label' => 'Date (as displayed)'],
                            'category' => ['type' => 'text',     'label' => 'Category'],
                            'title'    => ['type' => 'text',     'label' => 'Title'],
                            'summary'  => ['type' => 'textarea', 'label' => 'Summary', 'rows' => 3],
                            'slug'     => ['type' => 'text',     'label' => 'Linked news article slug'],
                        ],
                        'default' => [
                            ['date' => '25 Oct 2024', 'category' => 'Conference', 'title' => '42nd ASEANAPOL Conference — Joint Communiqué',
                             'summary' => 'Heads of ASEANAPOL member police forces convened in Nay Pyi Taw, Myanmar, reaffirming regional commitment to combating transnational crime and adopted a new strategic framework for 2025–2030.',
                             'slug' => '42nd-aseanapol-conference'],
                            ['date' => '19 Sep 2024', 'category' => 'Cooperation', 'title' => 'ASEANAPOL Reaffirms Commitment to ASEAN Vision 2045 at the 9th Forum of Entities',
                             'summary' => "The ASEANAPOL Secretariat participated in the 9th ASEAN Entities Forum in Bangkok, underscoring the organisation's alignment with ASEAN's long-term regional integration goals.",
                             'slug' => 'aseanapol-reaffirms-commitment-to-asean-vision-2045-at-the-9-forum-of-entities'],
                            ['date' => '04 Jul 2025', 'category' => 'Training', 'title' => '13th ASEANAPOL Training Cooperation Meeting (ATCM)',
                             'summary' => 'The 13th ATCM was successfully convened in Yangon, Myanmar, adopting a hybrid format for the first time to maximise engagement from all AMCs, Dialogue Partners, and Observer Organisations.',
                             'slug' => '13th-aseanapol-training-cooperation-meeting-(atcm)'],
                            ['date' => '26 Jun 2025', 'category' => 'Advisory', 'title' => 'J.O.B. — Just Observe Before You Apply: ASEANAPOL Warns of Job Scams',
                             'summary' => 'ASEANAPOL issues a public advisory urging job seekers to verify employment offers carefully. Stay alert for fraudulent job advertisements using the ASEANAPOL name.',
                             'slug' => 'j.o.b-'],
                            ['date' => '15 May 2025', 'category' => 'Cooperation', 'title' => "ASEANAPOL's Strategic Role in Regional and Global Collaboration to Address Transnational Crime",
                             'summary' => 'At the World Police Summit 2025 in Dubai, ASEANAPOL reaffirmed its commitment to empowering a future-ready police force through training, information sharing, and multilateral cooperation.',
                             'slug' => 'empowering-a-future-ready-police-force-aseanapols-strategic-role-in-regional-and-global-collaboration-to-address-transnational-crime'],
                            ['date' => '19 May 2025', 'category' => 'Cybercrime', 'title' => 'ASEANAPOL Advocates Regional Unity Against Ransomware at Southeast Asia Cybercrime Conference',
                             'summary' => 'The Executive Director of ASEANAPOL called for unified regional response to ransomware threats at the Southeast Asia Cybercrime Conference held in Kuala Lumpur.',
                             'slug' => 'aseanapol-secretariat-executive-director-advocates-regional-unity-against-ransomware-at-southeast-asia-cybercrime-conference'],
                        ],
                    ],
                ],
            ],
            'more' => [
                'label'  => 'Browse-all-news call to action',
                'toggle' => true,
                'fields' => [
                    'text'   => ['type' => 'textarea', 'label' => 'Text', 'rows' => 2, 'default' => 'Additional announcements and statements are published in the News section.'],
                    'button' => ['type' => 'text',     'label' => 'Button label', 'default' => 'Browse All News'],
                ],
            ],
        ],
    ],

    // ------------------------------------------------------------------ photo gallery
    'gallery' => [
        'label' => 'Photo Gallery',
        'route' => 'news-media.gallery',
        'sections' => [
            'hero' => [
                'label' => 'Page header',
                'fields' => [
                    'title'    => ['type' => 'text',     'label' => 'Title',    'default' => 'Photo Gallery'],
                    'subtitle' => ['type' => 'textarea', 'label' => 'Subtitle', 'rows' => 2, 'default' => 'Images from ASEANAPOL conferences, activities, and member organisations.'],
                    'crumb'    => ['type' => 'text',     'label' => 'Breadcrumb label', 'default' => 'Gallery'],
                ],
            ],
            'labels' => [
                'label'  => 'Tabs & buttons',
                'help'   => 'Photos themselves are indexed automatically from news articles and media folders.',
                'fields' => [
                    'all'        => ['type' => 'text', 'label' => '"All" tab', 'default' => 'All'],
                    'load_more'  => ['type' => 'text', 'label' => '"Load more" button', 'default' => 'Load more'],
                    'loading'    => ['type' => 'text', 'label' => 'Button text while loading', 'default' => 'Loading…'],
                    'empty'      => ['type' => 'text', 'label' => 'Message when there are no photos', 'default' => 'No gallery images available.', 'audit' => false /* shown only when the gallery has zero photos */],
                ],
            ],
            'albums' => [
                'label'  => 'Album names',
                'fields' => [
                    'activities' => ['type' => 'text', 'label' => 'Activities album', 'default' => 'ASEANAPOL Activities'],
                    'news'       => ['type' => 'text', 'label' => 'News photos album', 'default' => 'News Photos'],
                    'events'     => ['type' => 'text', 'label' => 'Events album', 'default' => 'Events'],
                    'governance' => ['type' => 'text', 'label' => 'Governance album', 'default' => 'Governance'],
                    'partners'   => ['type' => 'text', 'label' => 'Dialogue Partners album', 'default' => 'Dialogue Partners'],
                    'observers'  => ['type' => 'text', 'label' => 'Observers album', 'default' => 'Observers'],
                ],
            ],
        ],
    ],

    // ------------------------------------------------------------------ video gallery
    'video-gallery' => [
        'label' => 'Video Gallery',
        'route' => 'news-media.video-gallery',
        'sections' => [
            'hero' => [
                'label' => 'Page header',
                'fields' => [
                    'title'    => ['type' => 'text',     'label' => 'Title',    'default' => 'Video Gallery'],
                    'subtitle' => ['type' => 'textarea', 'label' => 'Subtitle', 'rows' => 2, 'default' => 'Video recordings of ASEANAPOL events, ceremonies, and training activities.'],
                ],
            ],
            'banner' => [
                'label'  => 'Banner shown while there are no videos',
                'toggle' => true,
                'help'   => 'Only appears when no video is published (Admin > Video Gallery).',
                'fields' => [
                    'text'  => ['type' => 'textarea', 'label' => 'Text before the email', 'rows' => 3, 'default' => 'Video recordings of ASEANAPOL events and activities are being compiled and will be published here shortly. For media enquiries, contact the ASEANAPOL Permanent Secretariat at', 'audit' => false],
                    'email' => ['type' => 'text', 'label' => 'Email address', 'default' => 'info@aseanapol.org', 'audit' => false],
                    'after' => ['type' => 'text', 'label' => 'Text after the email', 'default' => '.', 'audit' => false],
                ],
            ],
            'cta' => [
                'label'  => 'Photo gallery call to action',
                'toggle' => true,
                'fields' => [
                    'text'   => ['type' => 'textarea', 'label' => 'Text', 'rows' => 2, 'default' => 'Browse photos from ASEANAPOL events in the Photo Gallery.'],
                    'button' => ['type' => 'text',     'label' => 'Button label', 'default' => 'Browse Photo Gallery'],
                ],
            ],
        ],
    ],

    // ------------------------------------------------------------------ newsletters
    'newsletters' => [
        'label' => 'Newsletters',
        'route' => 'news-media.newsletters',
        'sections' => [
            'hero' => [
                'label' => 'Page header',
                'fields' => [
                    'title'    => ['type' => 'text',     'label' => 'Title',    'default' => 'Newsletters'],
                    'subtitle' => ['type' => 'textarea', 'label' => 'Subtitle', 'rows' => 2, 'default' => 'Periodic newsletters and circulars distributed to ASEANAPOL member police forces and stakeholders.'],
                ],
            ],
            'banner' => [
                'label'  => 'Information banner',
                'toggle' => true,
                'fields' => [
                    'text'  => ['type' => 'textarea', 'label' => 'Text before the email', 'rows' => 3, 'default' => 'Digital copies of ASEANAPOL newsletters will be made available for download as they are cleared for public release. Enquiries can be directed to'],
                    'email' => ['type' => 'text', 'label' => 'Email address', 'default' => 'info@aseanapol.org'],
                    'after' => ['type' => 'text', 'label' => 'Text after the email', 'default' => '.'],
                ],
            ],
            'labels' => [
                'label'  => 'Issue card labels',
                'help'   => 'Issues themselves are managed in Admin > Newsletters.',
                'fields' => [
                    'download' => ['type' => 'text', 'label' => 'Download link label', 'default' => 'Download PDF', 'audit' => false /* shown only on an issue card with a file; no newsletter is published in the audited state */],
                    'pending'  => ['type' => 'text', 'label' => 'Label when no file is attached', 'default' => 'Download pending clearance', 'audit' => false /* shown only on an issue card without a file; no newsletter is published in the audited state */],
                ],
            ],
            'cta' => [
                'label'  => 'Publications call to action',
                'toggle' => true,
                'fields' => [
                    'text'   => ['type' => 'textarea', 'label' => 'Text', 'rows' => 2, 'default' => 'The ASEANAPOL Bulletin and Magazine series are available in the Publications section.'],
                    'button' => ['type' => 'text',     'label' => 'Button label', 'default' => 'Browse Publications'],
                ],
            ],
        ],
    ],
];
