<?php

/**
 * CMS registry — group "site" (Site-wide & Home).
 * Page key = "site.<slug>". DEFAULT values are the original hardcoded content.
 *
 * Link fields (type text): a route name such as "about.index" (locale is added automatically),
 * a path ("/en/about-aseanapol", "#") or a full URL (https://…, mailto:…, tel:…).
 */

$tw = ['w-48' => 'Narrow (w-48)', 'w-52' => 'Medium (w-52)', 'w-56' => 'Wide (w-56)', 'w-64' => 'Extra wide (w-64)'];
$linkHelp = 'Route name (e.g. about.index), a path (/en/…, #) or a full URL.';

$menu = [
    ['label' => 'About ASEANAPOL', 'mobile_label' => 'About ASEANAPOL', 'link' => 'about.index', 'slug' => 'mob-about', 'width' => 'w-56', 'children' => [
        ['label' => 'History', 'link' => 'about.chronology', 'divider' => false],
        ['label' => 'Permanent Secretariat', 'link' => 'about.permanent-secretariat', 'divider' => false],
        ['label' => 'Governance', 'link' => 'about.governance', 'divider' => false],
        ['label' => 'Vision & Mission', 'link' => 'about.vision-mission', 'divider' => false],
        ['label' => 'Leadership', 'link' => 'about.leadership', 'divider' => false],
        ['label' => 'Member Countries', 'link' => 'about.member-countries', 'divider' => true],
        ['label' => 'Dialogue Partners', 'link' => 'about.dialogue-partners', 'divider' => false],
        ['label' => 'Observers', 'link' => 'about.observers', 'divider' => false],
        ['label' => 'Like-Minded Entities (LME)', 'link' => 'about.ob-lme', 'divider' => false],
    ]],
    ['label' => 'News & Media', 'mobile_label' => 'News & Media', 'link' => 'news-media.index', 'slug' => 'mob-news', 'width' => 'w-52', 'children' => [
        ['label' => 'News', 'link' => 'news.index', 'divider' => false],
        ['label' => 'Press Releases', 'link' => 'news-media.press-releases', 'divider' => false],
        ['label' => 'Photo Gallery', 'link' => 'news-media.gallery', 'divider' => false],
        ['label' => 'Video Gallery', 'link' => 'news-media.video-gallery', 'divider' => false],
        ['label' => 'Newsletters', 'link' => 'news-media.newsletters', 'divider' => false],
    ]],
    ['label' => 'Data & Resources', 'mobile_label' => 'Data & Resources', 'link' => 'data-resources.index', 'slug' => 'mob-data', 'width' => 'w-56', 'children' => [
        ['label' => 'Crime Statistics', 'link' => 'data-resources.crime-statistics', 'divider' => false],
        ['label' => 'Publications', 'link' => 'data-resources.publications', 'divider' => false],
        ['label' => 'Digital Library', 'link' => 'data-resources.digital-library', 'divider' => false],
    ]],
    ['label' => 'Intl. Cooperation', 'mobile_label' => 'International Cooperation', 'link' => 'international-cooperation', 'slug' => 'mob-intl', 'width' => 'w-48', 'children' => [
        ['label' => 'INTERPOL', 'link' => 'international-cooperation.interpol', 'divider' => false],
        ['label' => 'Europol', 'link' => 'international-cooperation.europol', 'divider' => false],
        ['label' => 'UNODC', 'link' => 'international-cooperation.unodc', 'divider' => false],
        ['label' => 'Joint Projects', 'link' => 'international-cooperation.joint-projects', 'divider' => false],
    ]],
    ['label' => 'Events', 'mobile_label' => 'Events & Training', 'link' => 'events-training.index', 'slug' => 'mob-events', 'width' => 'w-48', 'children' => [
        ['label' => 'Event Calendar', 'link' => 'events-training.calendar', 'divider' => false],
        ['label' => 'Conferences', 'link' => 'events-training.conferences', 'divider' => false],
        ['label' => 'Training Programs', 'link' => 'events-training.training-programs', 'divider' => false],
    ]],
    ['label' => 'Training', 'mobile_label' => 'Training', 'link' => 'training.index', 'slug' => 'mob-training', 'width' => 'w-52', 'children' => [
        ['label' => 'Online Training', 'link' => 'training.online-training', 'divider' => false],
        ['label' => 'In-Person Training', 'link' => 'training.in-person-training', 'divider' => false],
        ['label' => 'E-Learning', 'link' => 'training.e-learning', 'divider' => false],
    ]],
    ['label' => 'Contact Us', 'mobile_label' => 'Contact Us', 'link' => 'contact-us', 'slug' => 'mob-contact', 'width' => 'w-48', 'children' => []],
];

$linkRow = fn () => [
    'label' => ['type' => 'text', 'label' => 'Label'],
    'link'  => ['type' => 'text', 'label' => 'Link'],
];

$quick = [];
foreach ([
    ['About ASEANAPOL', 'about.index'], ['Permanent Secretariat', 'about.permanent-secretariat'], ['Governance', 'about.governance'],
    ['History', 'about.chronology'], ['Vision & Mission', 'about.vision-mission'], ['Member Countries', 'about.member-countries'],
    ['Dialogue Partners', 'about.dialogue-partners'], ['International Cooperation', 'international-cooperation'], ['News', 'news.index'],
    ['Publications', 'data-resources.publications'], ['Contact Us', 'contact-us'],
] as [$l, $k]) {
    $quick[] = ['label' => $l, 'link' => $k];
}

// Partner choices for the footer links / landing cards: the rows of shared.partners (names/websites live there).
$sharedReg = (static fn () => require __DIR__ . '/shared.php')();
$partnerOptions = [];
foreach (($sharedReg['partners']['sections']['list']['fields']['rows']['default'] ?? []) as $pr) {
    $partnerOptions[$pr['key']] = $pr['name'];
}

$social = [];
foreach ([
    'facebook'  => ['Facebook', 'https://www.facebook.com/ASEANAPOL', true],
    'x'         => ['X / Twitter', 'https://twitter.com/ASEANAPOL1981', true],
    'linkedin'  => ['LinkedIn', 'https://www.linkedin.com/in/aseanapol-secretariat-344498ab', true],
    'instagram' => ['Instagram', 'https://www.instagram.com/aseanapol', true],
    'youtube'   => ['YouTube', '', true],
] as $k => [$name, $url, $on]) {
    $social["{$k}_url"]     = ['type' => 'url', 'label' => "$name — URL", 'default' => $url, 'help' => $k === 'youtube' ? 'Empty URL = icon not shown (YouTube is not linked by default).' : null];
    $social["{$k}_footer"]  = ['type' => 'boolean', 'label' => "$name — show in footer", 'default' => $on];
    $social["{$k}_sidebar"] = ['type' => 'boolean', 'label' => "$name — show in right floating sidebar", 'default' => $on];
}

return [
    '_group' => ['label' => 'Site-wide & Home', 'icon' => 'home', 'order' => 1],

    // ------------------------------------------------------------------ header + footer (every page)
    'header-footer' => [
        'label' => 'Header, Menu & Footer (all pages)',
        'route' => 'landing',
        'sections' => [
            'meta' => ['label' => 'Browser tab & search description', 'fields' => [
                'title_suffix' => ['type' => 'text', 'label' => 'Title text after the site name', 'default' => 'ASEAN Chiefs of Police', 'help' => 'Shown as "ASEANAPOL - <this text>" in the browser tab.'],
                'description'  => ['type' => 'textarea', 'label' => 'Meta description', 'rows' => 2, 'default' => 'ASEANAPOL - ASEAN Chiefs of Police official portal. Together we keep this region safe.'],
            ]],
            'brand' => ['label' => 'Logo & name', 'columns' => 2, 'help' => 'The logo image and its header alt text are managed in Shared facts → Organisation (identity).', 'fields' => [
                'footer_logo_alt' => ['type' => 'text', 'label' => 'Footer logo alt text', 'default' => 'ASEANAPOL'],
                'name'     => ['type' => 'text', 'label' => 'Name next to logo', 'default' => 'ASEANAPOL'],
                'tagline'  => ['type' => 'text', 'label' => 'Small text under the name', 'default' => 'Secretariat'],
            ]],
            'menu' => ['label' => 'Main menu (desktop + mobile)', 'help' => 'Each top-level item has an optional drop-down. Use the arrows to reorder; Hide removes an item without deleting it.', 'fields' => [
                'home_label'    => ['type' => 'text', 'label' => 'Mobile menu "Home" label', 'default' => 'Home'],
                'theme_tooltip' => ['type' => 'text', 'label' => 'Dark-mode button tooltip', 'default' => 'Dark mode'],
                'items' => [
                    'type' => 'repeater', 'label' => 'Menu items', 'item_label' => 'label', 'add_label' => 'Add menu item',
                    'fields' => [
                        'label'        => ['type' => 'text', 'label' => 'Label (desktop bar)'],
                        'mobile_label' => ['type' => 'text', 'label' => 'Label (mobile menu)'],
                        'link'         => ['type' => 'text', 'label' => 'Link', 'help' => $linkHelp],
                        'slug'         => ['type' => 'text', 'label' => 'Internal id (advanced)', 'help' => 'Technical id of the mobile sub-menu; leave as is (blank = automatic).'],
                        'width'        => ['type' => 'select', 'label' => 'Drop-down width', 'options' => $tw],
                        'children'     => [
                            'type' => 'repeater', 'label' => 'Drop-down links', 'item_label' => 'label', 'add_label' => 'Add drop-down link',
                            'fields' => [
                                'label'   => ['type' => 'text', 'label' => 'Label'],
                                'link'    => ['type' => 'text', 'label' => 'Link', 'help' => $linkHelp],
                                'divider' => ['type' => 'boolean', 'label' => 'Draw a divider line above this link (desktop)'],
                            ],
                        ],
                    ],
                    'default' => $menu,
                ],
            ]],
            'social' => ['label' => 'Social media icons', 'columns' => 3, 'help' => 'One URL per network; choose where it appears. Empty URL = icon not shown.', 'fields' => $social],
            'sidebar' => ['label' => 'Right floating sidebar', 'fields' => [
                'share_title' => ['type' => 'text', 'label' => 'Share button tooltip', 'default' => 'Share this page'],
            ]],
            'footer_about' => ['label' => 'Footer — about column', 'help' => 'Write {count_word} where the number of member states belongs (e.g. "eleven"); it is filled from Shared facts → Member countries.', 'fields' => [
                'description' => ['type' => 'textarea', 'label' => 'Description', 'rows' => 3, 'default' => 'ASEANAPOL is the association of the national police forces of the {count_word} ASEAN member states, working together to enhance regional security and combat transnational crime.'],
            ]],
            'footer_links' => ['label' => 'Footer — link columns', 'fields' => [
                'quick_title' => ['type' => 'text', 'label' => 'Quick links heading', 'default' => 'Quick Links'],
                'quick_links' => ['type' => 'repeater', 'label' => 'Quick links', 'item_label' => 'label', 'add_label' => 'Add link', 'fields' => [
                    'label' => ['type' => 'text', 'label' => 'Label'],
                    'link'  => ['type' => 'text', 'label' => 'Link', 'help' => $linkHelp],
                ], 'default' => $quick],
                'partners_title' => ['type' => 'text', 'label' => 'Partners heading', 'default' => 'Partners'],
                'partner_links' => ['type' => 'repeater', 'label' => 'Partner links (names and websites come from Shared facts → Partner organisations)', 'item_label' => 'partner', 'add_label' => 'Add partner link', 'fields' => [
                    'partner' => ['type' => 'select', 'label' => 'Partner organisation', 'options' => $partnerOptions],
                    'new_tab' => ['type' => 'boolean', 'label' => 'Open in a new tab'],
                ], 'default' => [
                    ['partner' => 'interpol', 'new_tab' => true],
                    ['partner' => 'europol', 'new_tab' => true],
                    ['partner' => 'unodc', 'new_tab' => true],
                    ['partner' => 'accbp', 'new_tab' => false],
                ]],
                'resources_title' => ['type' => 'text', 'label' => 'Resources heading', 'default' => 'Resources'],
                'resource_links' => ['type' => 'repeater', 'label' => 'Resource links', 'item_label' => 'label', 'add_label' => 'Add resource link', 'fields' => [
                    'label'   => ['type' => 'text', 'label' => 'Label'],
                    'link'    => ['type' => 'text', 'label' => 'Link', 'help' => $linkHelp],
                    'new_tab' => ['type' => 'boolean', 'label' => 'Open in a new tab'],
                ], 'default' => [
                    ['label' => 'e-ADS', 'link' => '#', 'new_tab' => false],
                    ['label' => 'ASEANAPOL Database', 'link' => '#', 'new_tab' => false],
                ]],
            ]],
            'footer_contact' => ['label' => 'Footer — Secretariat contact block', 'help' => 'Address, phone, fax and e-mail are managed in Shared facts → Secretariat contact & e-ADS.', 'fields' => [
                'title'   => ['type' => 'text', 'label' => 'Heading', 'default' => 'Contact Us'],
            ]],
            'newsletter' => ['label' => 'Footer — "Stay updated" box', 'toggle' => true, 'columns' => 2, 'fields' => [
                'title'       => ['type' => 'text', 'label' => 'Heading', 'default' => 'Stay Updated'],
                'placeholder' => ['type' => 'text', 'label' => 'Email field placeholder', 'default' => 'Your email'],
            ]],
            'statistics' => ['label' => 'Website statistics banner', 'toggle' => true, 'columns' => 2, 'fields' => [
                'title'       => ['type' => 'text', 'label' => 'Heading', 'default' => 'Website Statistics'],
                'as_of'       => ['type' => 'text', 'label' => '"As of" prefix (date follows)', 'default' => 'As of'],
                'total_label' => ['type' => 'text', 'label' => 'Total visitors label', 'default' => 'Total Visitors'],
                'today_label' => ['type' => 'text', 'label' => "Today's visitors label", 'default' => "Today's Visitors"],
                'chart_title' => ['type' => 'text', 'label' => 'Chart caption', 'default' => 'Daily Visitors — Last 30 Days'],
            ]],
            'bottom' => ['label' => 'Footer — bottom bar', 'fields' => [
                'copyright' => ['type' => 'text', 'label' => 'Copyright line', 'default' => '© 2026 ASEANAPOL Secretariat. All rights reserved.'],
                'links' => ['type' => 'repeater', 'label' => 'Bottom links', 'item_label' => 'label', 'add_label' => 'Add link', 'fields' => [
                    'label' => ['type' => 'text', 'label' => 'Label'],
                    'link'  => ['type' => 'text', 'label' => 'Link', 'help' => $linkHelp],
                ], 'default' => [
                    ['label' => 'Privacy Policy', 'link' => '#'],
                    ['label' => 'Terms of Use', 'link' => '#'],
                    ['label' => 'Sitemap', 'link' => '#'],
                ]],
            ]],
        ],
    ],

    // ------------------------------------------------------------------ landing page
    'landing' => [
        'label' => 'Home page',
        'route' => 'landing',
        'sections' => [
            'banner' => ['label' => 'Welcome banner (optional)', 'help' => 'Shown above the news slider only when a headline is entered. Leave the headline empty for no banner.', 'fields' => [
                'headline'     => ['type' => 'text', 'label' => 'Headline', 'default' => ''],
                'subtitle'     => ['type' => 'textarea', 'label' => 'Subtitle', 'rows' => 2, 'default' => ''],
                'button_label' => ['type' => 'text', 'label' => 'Button label', 'default' => ''],
                'button_link'  => ['type' => 'text', 'label' => 'Button link', 'default' => '', 'help' => $linkHelp],
                'image'        => ['type' => 'image', 'label' => 'Background image', 'default' => ''],
            ]],
            'slider' => ['label' => 'News slider (latest news, automatic)', 'toggle' => true, 'help' => 'The slides come from the News module; here you can only hide the slider or change how many slides appear.', 'fields' => [
                'max_slides' => ['type' => 'number', 'label' => 'Maximum slides', 'default' => 10],
            ]],
            'stats' => ['label' => 'Statistics bar', 'toggle' => true, 'help' => 'The numbers for members, dialogue partners, conferences and founding year are counted/read from Shared facts (Member countries, Dialogue partners, Organisation); only the labels and icons are edited here. Pick "Custom" to type a number yourself.', 'fields' => [
                'items' => ['type' => 'repeater', 'label' => 'Counters', 'item_label' => 'label', 'add_label' => 'Add counter', 'fields' => [
                    'kind'  => ['type' => 'select', 'label' => 'Number comes from', 'options' => ['members' => 'Member countries (count)', 'dialogue' => 'Dialogue partners (count)', 'conferences' => 'Annual conferences (Shared > Organisation)', 'founded' => 'Founding year (Shared > Organisation)', 'custom' => 'Custom (type below)'], 'default' => 'custom'],
                    'value' => ['type' => 'text', 'label' => 'Custom number / value (only for "Custom")', 'audit' => false /* shown only for kind=custom rows (verified in site.mjs); the default rows are all shared-derived */],
                    'label' => ['type' => 'text', 'label' => 'Label'],
                    'icon'  => ['type' => 'icon', 'label' => 'Icon'],
                ], 'default' => [
                    ['kind' => 'members', 'value' => '', 'label' => 'Member States', 'icon' => 'public'],
                    ['kind' => 'dialogue', 'value' => '', 'label' => 'Dialogue Partners', 'icon' => 'handshake'],
                    ['kind' => 'conferences', 'value' => '', 'label' => 'Annual Conferences', 'icon' => 'groups'],
                    ['kind' => 'founded', 'value' => '', 'label' => 'Established', 'icon' => 'calendar_month'],
                ]],
            ]],
            'news' => ['label' => 'Latest news block (cards are automatic)', 'toggle' => true, 'columns' => 2, 'fields' => [
                'badge'        => ['type' => 'text', 'label' => 'Badge', 'default' => 'Stay Informed'],
                'title'        => ['type' => 'text', 'label' => 'Heading', 'default' => 'Latest News & Events'],
                'upcoming_badge' => ['type' => 'text', 'label' => '"Upcoming event" card badge', 'default' => 'Upcoming Event', 'audit' => false /* visible only on a news card flagged is_upcoming_event; none exists in the audited state */],
                'read_more'    => ['type' => 'text', 'label' => '"Read more" label', 'default' => 'Read More'],
                'view_all'     => ['type' => 'text', 'label' => '"View all" button label', 'default' => 'View All News'],
                'count'        => ['type' => 'number', 'label' => 'Number of cards', 'default' => 6],
            ]],
            'events' => ['label' => 'Event highlights', 'toggle' => true, 'fields' => [
                'badge'      => ['type' => 'text', 'label' => 'Badge', 'default' => 'Event Highlights'],
                'title'      => ['type' => 'text', 'label' => 'Heading', 'default' => 'Event Highlights'],
                'range_word' => ['type' => 'text', 'label' => 'Word before end date ("to 06 MAR")', 'default' => 'to'],
                'view_all'   => ['type' => 'text', 'label' => '"View all" button label', 'default' => 'View All Events'],
                'view_all_link' => ['type' => 'text', 'label' => '"View all" link', 'default' => 'events-training.calendar', 'help' => $linkHelp],
                'items' => ['type' => 'repeater', 'label' => 'Events', 'item_label' => 'title', 'add_label' => 'Add event', 'columns' => 2, 'fields' => [
                    'month'     => ['type' => 'text', 'label' => 'Start month (e.g. MAR)'],
                    'day'       => ['type' => 'text', 'label' => 'Start day'],
                    'year'      => ['type' => 'text', 'label' => 'Year (shown when there is no end date)', 'audit' => false /* hidden on row 0 because the audit fills end_day; wired on rows without end_day (verified in qa-a.mjs) */],
                    'end_day'   => ['type' => 'text', 'label' => 'End day (optional)'],
                    'end_month' => ['type' => 'text', 'label' => 'End month (optional)'],
                    'title'     => ['type' => 'text', 'label' => 'Title'],
                    'location'  => ['type' => 'text', 'label' => 'Location'],
                ], 'default' => [
                    ['month' => 'MAR', 'day' => '05', 'year' => '2026', 'end_day' => '06', 'end_month' => 'MAR', 'title' => '14th ASEANAPOL Training Cooperation Meeting (ATCM)', 'location' => 'Bangkok, Thailand'],
                    ['month' => 'MAR', 'day' => '09', 'year' => '2026', 'end_day' => '11', 'end_month' => 'MAR', 'title' => '8th ASEANAPOL Police Forensic Science Network Meeting (APFSN)', 'location' => 'Bangkok, Thailand'],
                    ['month' => 'MAR', 'day' => '11', 'year' => '2026', 'end_day' => '12', 'end_month' => 'MAR', 'title' => '40th ASEANAPOL Database System Technical Committee (ADSTC)', 'location' => 'Bangkok, Thailand'],
                    ['month' => 'MAR', 'day' => '31', 'year' => '2026', 'end_day' => '02', 'end_month' => 'APR', 'title' => '33rd Joint ASEANAPOL Senior Police Course (JASPOC)', 'location' => 'Bangkok, Thailand'],
                    ['month' => 'APR', 'day' => '21', 'year' => '2026', 'end_day' => '24', 'end_month' => 'APR', 'title' => '16th ASEANAPOL Contact Person Meeting (ACPM)', 'location' => 'Kuala Lumpur, Malaysia'],
                    ['month' => 'JUN', 'day' => '02', 'year' => '2026', 'end_day' => '', 'end_month' => '', 'title' => 'Pre Coordination Meeting for 44th ASEANAPOL Conference 2026', 'location' => 'Manila, Philippines'],
                    ['month' => 'JUL', 'day' => '22', 'year' => '2026', 'end_day' => '26', 'end_month' => 'JUL', 'title' => '44th ASEANAPOL Conference', 'location' => 'Manila, Philippines'],
                ]],
            ]],
            'about' => ['label' => 'About — vision & mission', 'toggle' => true, 'help' => 'The quoted vision tagline under the Vision card is managed in Shared facts → Organisation (identity → tagline).', 'fields' => [
                'badge'          => ['type' => 'text', 'label' => 'Badge', 'default' => 'About ASEANAPOL'],
                'title'          => ['type' => 'text', 'label' => 'Heading', 'default' => 'Our Vision & Mission'],
                'vision_heading' => ['type' => 'text', 'label' => 'Vision heading', 'default' => 'Vision'],
                'vision_before'  => ['type' => 'text', 'label' => 'Vision text — before highlighted words', 'default' => 'To be the'],
                'vision_strong'  => ['type' => 'text', 'label' => 'Vision text — highlighted words', 'default' => 'premier regional police organisation'],
                'vision_after'   => ['type' => 'textarea', 'label' => 'Vision text — after highlighted words', 'rows' => 2, 'default' => 'that fosters cooperation in policing among ASEAN Member States and beyond, ensuring a safe and secure region for all.'],
                'mission_heading' => ['type' => 'text', 'label' => 'Mission heading', 'default' => 'Mission'],
                'missions' => ['type' => 'list', 'label' => 'Mission points (one per line)', 'default' => [
                    'Enhance regional police cooperation in combating transnational crime',
                    'Strengthen institutional capacity and professional standards of ASEAN police forces',
                    'Facilitate intelligence sharing and joint operations among member states',
                    'Build strategic partnerships with international law enforcement organisations',
                ]],
            ]],
            'members' => ['label' => 'Member countries strip', 'toggle' => true, 'help' => 'The countries, police acronyms, logos and "member since" years come from Shared facts → Member countries (hide a member there to remove it here). Write {Count_word} in the subtitle for the number of members.', 'fields' => [
                'badge'    => ['type' => 'text', 'label' => 'Badge', 'default' => 'Our Members'],
                'title'    => ['type' => 'text', 'label' => 'Heading', 'default' => 'Member Countries'],
                'subtitle' => ['type' => 'textarea', 'label' => 'Subtitle', 'rows' => 2, 'default' => '{Count_word} ASEAN member states united in promoting police professionalism and regional security cooperation.'],
                'since_word' => ['type' => 'text', 'label' => '"Since" word on the cards', 'default' => 'Since'],
            ]],
            'directory' => ['label' => 'Member directory table', 'toggle' => true, 'help' => 'The rows (country, police force, telephone, fax, e-mail, logo) come from Shared facts → Member countries; only the headings and column titles are edited here.', 'fields' => [
                'badge'    => ['type' => 'text', 'label' => 'Badge', 'default' => 'Directory'],
                'title'    => ['type' => 'text', 'label' => 'Heading', 'default' => 'Member Directory'],
                'subtitle' => ['type' => 'textarea', 'label' => 'Subtitle', 'rows' => 2, 'default' => 'Contact information for all ASEANAPOL member country national police forces.'],
                'col_country' => ['type' => 'text', 'label' => 'Column: country', 'default' => 'Country'],
                'col_org'     => ['type' => 'text', 'label' => 'Column: organisation', 'default' => 'Organisation'],
                'col_tel'     => ['type' => 'text', 'label' => 'Column: telephone', 'default' => 'Telephone'],
                'col_fax'     => ['type' => 'text', 'label' => 'Column: fax', 'default' => 'Fax'],
                'col_email'   => ['type' => 'text', 'label' => 'Column: email', 'default' => 'Email'],
            ]],
            'resources' => ['label' => 'Quick resources', 'toggle' => true, 'fields' => [
                'badge' => ['type' => 'text', 'label' => 'Badge', 'default' => 'Quick Resources'],
                'title' => ['type' => 'text', 'label' => 'Heading', 'default' => 'Quick Resources'],
                'items' => ['type' => 'repeater', 'label' => 'Resource cards', 'item_label' => 'title', 'add_label' => 'Add card', 'fields' => [
                    'title' => ['type' => 'text', 'label' => 'Title'],
                    'icon'  => ['type' => 'icon', 'label' => 'Icon'],
                    'desc'  => ['type' => 'textarea', 'label' => 'Description', 'rows' => 2],
                    'link'  => ['type' => 'text', 'label' => 'Link', 'help' => $linkHelp],
                ], 'default' => [
                    ['title' => 'e-ADS System', 'icon' => 'database', 'desc' => 'ASEAN Electronic Alert & Data System', 'link' => '#'],
                    ['title' => 'Annual Report 2025', 'icon' => 'description', 'desc' => 'Yearly operational and statistical reports', 'link' => '#'],
                    ['title' => 'ASEANAPOL Constitution', 'icon' => 'gavel', 'desc' => 'Founding charter and governing rules', 'link' => '#'],
                    ['title' => 'Media Gallery', 'icon' => 'photo_library', 'desc' => 'Photos, videos, and press materials', 'link' => '#'],
                ]],
            ]],
            'partners' => ['label' => 'Partners', 'toggle' => true, 'fields' => [
                'badge'    => ['type' => 'text', 'label' => 'Badge', 'default' => 'Partners'],
                'title'    => ['type' => 'text', 'label' => 'Heading', 'default' => 'Partners'],
                'subtitle' => ['type' => 'text', 'label' => 'Subtitle', 'default' => 'Dialogue Partners'],
                'items' => ['type' => 'repeater', 'label' => 'Partner cards (name, full name, icon and website come from Shared facts → Partner organisations)', 'item_label' => 'partner', 'add_label' => 'Add partner', 'fields' => [
                    'partner' => ['type' => 'select', 'label' => 'Partner organisation', 'options' => $partnerOptions],
                    'desc' => ['type' => 'textarea', 'label' => 'Description', 'rows' => 3],
                ], 'default' => [
                    ['partner' => 'interpol', 'desc' => 'Global police cooperation organisation enabling law enforcement worldwide to work together to make the world a safer place.'],
                    ['partner' => 'europol', 'desc' => "The EU's law enforcement agency supporting EU member states in their fight against terrorism, cybercrime and other serious crime."],
                    ['partner' => 'unodc', 'desc' => 'The global leader in the fight against illicit drugs, international crime, corruption, and terrorism.'],
                    ['partner' => 'accbp', 'desc' => 'Regional cooperation body focused on customs and border enforcement across ASEAN member states.'],
                ]],
            ]],
        ],
    ],

    // ------------------------------------------------------------------ contact page
    'contact' => [
        'label' => 'Contact Us',
        'route' => 'contact-us',
        'sections' => [
            'hero' => ['label' => 'Page header', 'fields' => [
                'title'    => ['type' => 'text', 'label' => 'Title', 'default' => 'Contact Us'],
                'subtitle' => ['type' => 'textarea', 'label' => 'Subtitle', 'rows' => 2, 'default' => 'Get in touch with the ASEANAPOL Permanent Secretariat.'],
            ]],
            'secretariat' => ['label' => 'Secretariat contact details', 'columns' => 2, 'help' => 'The address, phone, fax and e-mail values are managed in Shared facts → Secretariat contact & e-ADS; only the headings and labels are edited here.', 'fields' => [
                'heading'    => ['type' => 'text', 'label' => 'Heading', 'default' => 'Permanent Secretariat'],
                'subheading' => ['type' => 'text', 'label' => 'Sub-heading', 'default' => 'ASEANAPOL Headquarters, Kuala Lumpur'],
                'address_label' => ['type' => 'text', 'label' => 'Address label', 'default' => 'Address'],
                'phone_label' => ['type' => 'text', 'label' => 'Phone label', 'default' => 'Phone'],
                'fax_label'  => ['type' => 'text', 'label' => 'Fax label', 'default' => 'Fax'],
                'email_label' => ['type' => 'text', 'label' => 'Email label', 'default' => 'Email'],
            ]],
            'hours' => ['label' => 'Office hours note', 'toggle' => true, 'help' => 'The text is managed in Shared facts → Secretariat contact & e-ADS (office hours). Here you can only show or hide the note.', 'fields' => []],
            'map' => ['label' => 'Map', 'toggle' => true, 'help' => 'The map location is managed in Shared facts → Secretariat contact & e-ADS (map embed URL). Here you can only show or hide the map.', 'fields' => []],
            'directory' => ['label' => 'Member country directory', 'toggle' => true, 'help' => 'The rows come from Shared facts → Member countries. Write {count_num} in the sub-heading for the number of members.', 'fields' => [
                'heading'    => ['type' => 'text', 'label' => 'Heading', 'default' => 'Member Country Directory'],
                'subheading' => ['type' => 'text', 'label' => 'Sub-heading', 'default' => 'INTERPOL/ASEANAPOL liaison contact points for all {count_num} member states.'],
                'col_country' => ['type' => 'text', 'label' => 'Column: country', 'default' => 'Country'],
                'col_org'     => ['type' => 'text', 'label' => 'Column: organisation', 'default' => 'Org.'],
                'col_phone'   => ['type' => 'text', 'label' => 'Column: phone', 'default' => 'Phone'],
                'col_fax'     => ['type' => 'text', 'label' => 'Column: fax', 'default' => 'Fax'],
                'col_contact' => ['type' => 'text', 'label' => 'Column: contact', 'default' => 'Contact'],
                'portal_label' => ['type' => 'text', 'label' => 'Link text for rows that have a web link instead of an e-mail', 'default' => 'e-Services portal', 'audit' => false /* only for a member with a website but no e-mail (Shared facts); every default member has an e-mail */],
            ]],
            'form' => ['label' => 'Inquiry form (hidden until a heading is entered)', 'columns' => 2, 'help' => 'The public inquiry form is not currently shown on the page. Enter a heading to display it; clear the heading to hide it. Delivery settings are on the "Contact form delivery" page.', 'fields' => [
                'heading'    => ['type' => 'text', 'label' => 'Heading (blank = form hidden)', 'default' => ''],
                'intro'      => ['type' => 'textarea', 'label' => 'Intro text', 'rows' => 2, 'default' => 'Send us an inquiry and the Secretariat will respond within 2–3 business days.'],
                'name_label' => ['type' => 'text', 'label' => 'Name — label', 'default' => 'Your name'],
                'name_placeholder' => ['type' => 'text', 'label' => 'Name — placeholder', 'default' => 'Full name'],
                'email_label' => ['type' => 'text', 'label' => 'Email — label', 'default' => 'Your email'],
                'email_placeholder' => ['type' => 'text', 'label' => 'Email — placeholder', 'default' => 'name@example.com'],
                'subject_label' => ['type' => 'text', 'label' => 'Subject — label', 'default' => 'Subject'],
                'subject_placeholder' => ['type' => 'text', 'label' => 'Subject — placeholder', 'default' => 'How can we help?'],
                'message_label' => ['type' => 'text', 'label' => 'Message — label', 'default' => 'Message'],
                'message_placeholder' => ['type' => 'text', 'label' => 'Message — placeholder', 'default' => 'Write your message (at least 10 characters)'],
                'button' => ['type' => 'text', 'label' => 'Submit button', 'default' => 'Send Inquiry'],
            ]],
        ],
    ],

    // Not rendered on a page: settings used when the inquiry form is submitted (so no public URL / audit marker).
    'contact-form' => [
        'label' => 'Contact form delivery',
        'sections' => [
            'settings' => ['label' => 'Delivery settings', 'help' => 'Inquiries are sent to the Secretariat e-mail managed in Shared facts → Secretariat contact & e-ADS.', 'fields' => [
                'success_message' => ['type' => 'textarea', 'label' => 'Success message', 'rows' => 2, 'default' => 'Thank you, :name. Your message has been sent. We will respond within 2–3 business days.', 'help' => 'Use :name where the sender\'s name should appear.'],
            ]],
        ],
    ],

    // ------------------------------------------------------------------ coming soon placeholder (no public route currently uses it)
    'coming-soon' => [
        'label' => 'Coming-soon placeholder page',
        'sections' => [
            'content' => ['label' => 'Text', 'fields' => [
                'heading' => ['type' => 'text', 'label' => 'Heading', 'default' => 'Coming Soon'],
                'intro_before' => ['type' => 'text', 'label' => 'Text before the section name', 'default' => 'The'],
                'intro_after'  => ['type' => 'textarea', 'label' => 'Text after the section name', 'rows' => 2, 'default' => 'section is currently being prepared. Please check back soon.'],
                'button' => ['type' => 'text', 'label' => 'Button label', 'default' => 'Back to Home'],
            ]],
        ],
    ],
];
