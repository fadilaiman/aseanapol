<?php

/**
 * CMS registry — group "events" (Events & Training).
 * Page key = "events.<slug>". DEFAULT values below ARE the original hardcoded content.
 * The event / conference / training-programme RECORDS live in the dedicated "Events" module
 * (table `events`, /admin/events); only the static page text is edited here.
 */
return [
    '_group' => ['label' => 'Events & Training', 'icon' => 'event', 'order' => 40],

    'index' => [
        'label' => 'Events & Training (landing)',
        'route' => 'events-training.index',
        'sections' => [
            'hero' => [
                'label'  => 'Page header',
                'fields' => [
                    'title'    => ['type' => 'text',     'label' => 'Title',    'default' => 'Events & Training'],
                    'subtitle' => ['type' => 'textarea', 'label' => 'Subtitle', 'rows' => 2, 'default' => 'ASEANAPOL conferences, training programmes, and capacity-building activities.'],
                ],
            ],
            'cards' => [
                'label'  => 'Section cards',
                'help'   => 'The three cards linking to the sub-pages.',
                'fields' => [
                    'items' => [
                        'type' => 'repeater', 'label' => 'Cards', 'item_label' => 'title', 'add_label' => 'Add card',
                        'fields' => [
                            'icon'   => ['type' => 'icon',     'label' => 'Icon'],
                            'title'  => ['type' => 'text',     'label' => 'Title'],
                            'desc'   => ['type' => 'textarea', 'label' => 'Description', 'rows' => 3],
                            'target' => ['type' => 'select',   'label' => 'Links to', 'options' => [
                                'events-training.calendar'          => 'Event Calendar',
                                'events-training.conferences'       => 'Conferences',
                                'events-training.training-programs' => 'Training Programs',
                            ]],
                            'label'  => ['type' => 'text',     'label' => 'Link label'],
                        ],
                        'default' => [
                            ['icon' => 'calendar_month', 'title' => 'Event Calendar', 'desc' => 'A chronological record of all ASEANAPOL conferences, meetings, training sessions, and activities from 2010 to the present.', 'target' => 'events-training.calendar', 'label' => 'View Calendar'],
                            ['icon' => 'groups', 'title' => 'Conferences', 'desc' => 'Annual ASEANAPOL Conferences — the principal decision-making body of ASEANAPOL, held annually in a member country.', 'target' => 'events-training.conferences', 'label' => 'View Conferences'],
                            ['icon' => 'school', 'title' => 'Training Programs', 'desc' => 'Capacity-building courses, joint exercises, and specialised training programmes offered to member police forces.', 'target' => 'events-training.training-programs', 'label' => 'View Programs'],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'calendar' => [
        'label' => 'Event Calendar',
        'route' => 'events-training.calendar',
        'sections' => [
            'hero' => [
                'label'  => 'Page header',
                'fields' => [
                    'title'    => ['type' => 'text',     'label' => 'Title',    'default' => 'Event Calendar'],
                    'subtitle' => ['type' => 'textarea', 'label' => 'Subtitle', 'rows' => 2, 'default' => 'ASEANAPOL conferences, meetings, training programmes, and activities.'],
                ],
            ],
            'legend' => [
                'label'  => 'Event type legend',
                'toggle' => true,
                'help'   => 'The coloured badges (Conference, Meeting, Training, Activity) above the timeline.',
                'fields' => [],
            ],
            'labels' => [
                'label'  => 'Timeline labels',
                'help'   => 'Events themselves are managed under Events in the admin menu.',
                'fields' => [
                    'event_one'  => ['type' => 'text', 'label' => 'Count label (singular)', 'default' => 'event'],
                    'event_many' => ['type' => 'text', 'label' => 'Count label (plural)',   'default' => 'events'],
                    'to_word'    => ['type' => 'text', 'label' => 'Word between start and end date', 'default' => 'to'],
                    'empty'      => ['type' => 'text', 'label' => 'Message when no events exist', 'default' => 'No events have been published yet.', 'audit' => false /* shown only when no event of this kind is published; 115 events exist in the audited state */],
                ],
            ],
        ],
    ],

    'conferences' => [
        'label' => 'Conferences',
        'route' => 'events-training.conferences',
        'sections' => [
            'hero' => [
                'label'  => 'Page header',
                'fields' => [
                    'title'    => ['type' => 'text',     'label' => 'Title',    'default' => 'ASEANAPOL Conferences'],
                    'breadcrumb' => ['type' => 'text',   'label' => 'Breadcrumb label', 'default' => 'Conferences'],
                    'subtitle' => ['type' => 'textarea', 'label' => 'Subtitle', 'rows' => 2, 'default' => 'The annual ASEANAPOL Conference is the principal decision-making body of ASEANAPOL, held each year in a member country.'],
                ],
            ],
            'notice' => [
                'label'  => 'COVID-19 notice',
                'toggle' => true,
                'fields' => [
                    'icon' => ['type' => 'icon', 'label' => 'Icon', 'default' => 'info'],
                    'text' => ['type' => 'html', 'label' => 'Notice text', 'rows' => 3, 'default' => 'The <strong>40th and 41st ASEANAPOL Conferences</strong> (2020–2021) were suspended due to the COVID-19 pandemic. The <strong>42nd Conference</strong> in Phnom Penh, Cambodia was the first to resume.'],
                ],
            ],
            'labels' => [
                'label'  => 'Conference card labels',
                'help'   => 'Conferences themselves are managed under Events in the admin menu.',
                'fields' => [
                    'conference_word' => ['type' => 'text', 'label' => 'Word after the number (e.g. "44th Conference")', 'default' => 'Conference'],
                    'forthcoming'     => ['type' => 'text', 'label' => 'Badge: forthcoming', 'default' => 'Forthcoming'],
                    'suspended'       => ['type' => 'text', 'label' => 'Badge: suspended',   'default' => 'Suspended'],
                    'suspended_text'  => ['type' => 'text', 'label' => 'Text for a suspended conference', 'default' => 'Not held — COVID-19 Pandemic'],
                    'empty'           => ['type' => 'text', 'label' => 'Message when no conferences exist', 'default' => 'No conferences have been published yet.', 'audit' => false /* shown only when no event of this kind is published; 115 events exist in the audited state */],
                ],
            ],
            'back' => [
                'label'  => 'Link to the calendar',
                'toggle' => true,
                'fields' => [
                    'label' => ['type' => 'text', 'label' => 'Link text', 'default' => 'View full Event Calendar'],
                ],
            ],
        ],
    ],

    'training-programs' => [
        'label' => 'Training Programs',
        'route' => 'events-training.training-programs',
        'sections' => [
            'hero' => [
                'label'  => 'Page header',
                'fields' => [
                    'title'    => ['type' => 'text',     'label' => 'Title',    'default' => 'Training Programs'],
                    'subtitle' => ['type' => 'textarea', 'label' => 'Subtitle', 'rows' => 2, 'default' => 'Capacity-building courses, joint exercises, and specialised training offered to ASEANAPOL member police forces.'],
                ],
            ],
            'intro' => [
                'label'  => 'Introduction note',
                'toggle' => true,
                'help'   => 'The paragraph is built from text + two links (to the Event Calendar and to an e-mail address).',
                'fields' => [
                    'icon'        => ['type' => 'icon', 'label' => 'Icon', 'default' => 'school'],
                    'before'      => ['type' => 'textarea', 'label' => 'Text before the calendar link', 'rows' => 3, 'default' => 'ASEANAPOL training programs are open to police officers from all ten member countries. Training schedules are confirmed at the annual ATCM and published in the'],
                    'calendar'    => ['type' => 'text', 'label' => 'Calendar link text', 'default' => 'Event Calendar'],
                    'after_cal'   => ['type' => 'text', 'label' => 'Punctuation after the calendar link', 'default' => '.'],
                    'middle'      => ['type' => 'textarea', 'label' => 'Text before the e-mail link', 'rows' => 2, 'default' => 'For nominations and enquiries, contact your national ASEANAPOL focal point or the Secretariat at'],
                    'email'       => ['type' => 'text', 'label' => 'E-mail address', 'default' => 'info@aseanapol.org'],
                    'after_email' => ['type' => 'text', 'label' => 'Punctuation after the e-mail', 'default' => '.'],
                ],
            ],
            'cta' => [
                'label'  => 'Calendar call-to-action',
                'toggle' => true,
                'fields' => [
                    'text'   => ['type' => 'textarea', 'label' => 'Text', 'rows' => 2, 'default' => 'See the full schedule of upcoming training sessions and events.'],
                    'button' => ['type' => 'text',     'label' => 'Button label', 'default' => 'View Event Calendar'],
                ],
            ],
            'labels' => [
                'label'  => 'Empty-state message',
                'help'   => 'Training programmes themselves are managed under Events in the admin menu.',
                'fields' => [
                    'empty' => ['type' => 'text', 'label' => 'Message when no training programmes exist', 'default' => 'No training programmes have been published yet.', 'audit' => false /* shown only when no event of this kind is published; 115 events exist in the audited state */],
                ],
            ],
        ],
    ],
];
