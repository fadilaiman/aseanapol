<?php

/**
 * CMS registry — group "guidelines".
 * Page key = "guidelines.<slug>". DEFAULT values below ARE the original hardcoded content.
 *
 * Each guideline page: hero, head (icon/kicker/heading), body (intro + sections repeater:
 * heading + optional paragraph + optional bullet list), optional attached document (file),
 * footer line (label + email + back link).
 */

$sectionsRepeater = fn (array $default) => [
    'type' => 'repeater', 'label' => 'Sections', 'item_label' => 'heading', 'add_label' => 'Add section',
    'fields' => [
        'heading' => ['type' => 'text',     'label' => 'Section heading (e.g. "1. Designation")'],
        'body'    => ['type' => 'textarea', 'label' => 'Paragraph (optional)', 'rows' => 4],
        'items'   => ['type' => 'list',     'label' => 'Bullet points (optional, one per line)'],
    ],
    'default' => $default,
];

$documentSection = [
    'label'  => 'Attached document (PDF)',
    'help'   => 'Optional. Upload the official guideline document; a download button appears above the footer line once a file is set.',
    'fields' => [
        'label' => ['type' => 'text', 'label' => 'Button label', 'default' => 'Download the full document (PDF)'],
        'file'  => ['type' => 'file', 'label' => 'Document file', 'default' => ''],
    ],
];

$hero = fn (string $title, string $sub, string $crumb) => ['label' => 'Page header', 'fields' => [
    'title'    => ['type' => 'text',     'label' => 'Title', 'default' => $title],
    'subtitle' => ['type' => 'textarea', 'label' => 'Subtitle', 'rows' => 2, 'default' => $sub],
    'crumb'    => ['type' => 'text',     'label' => 'Breadcrumb label', 'default' => $crumb],
]];

$head = fn (string $icon, string $heading) => ['label' => 'Title block', 'toggle' => true, 'fields' => [
    'icon'    => ['type' => 'icon', 'label' => 'Icon', 'default' => $icon],
    'kicker'  => ['type' => 'text', 'label' => 'Small label above heading', 'default' => 'ASEANAPOL Official Guidelines'],
    'heading' => ['type' => 'text', 'label' => 'Heading', 'default' => $heading],
]];

$foot = fn (string $label) => ['label' => 'Footer line', 'help' => 'Contact e-mail is managed in Shared facts → Secretariat.', 'fields' => [
    'label'      => ['type' => 'text', 'label' => 'Enquiries label', 'default' => $label],
    'back_label' => ['type' => 'text', 'label' => 'Back link label', 'default' => 'Back to Guidelines'],
]];

return [
    '_group' => ['label' => 'Guidelines', 'icon' => 'gavel', 'order' => 80],

    'index' => [
        'label' => 'Guidelines (index)',
        'route' => 'guidelines.index',
        'sections' => [
            'hero' => ['label' => 'Page header', 'fields' => [
                'title'    => ['type' => 'text',     'label' => 'Title', 'default' => 'Guidelines'],
                'subtitle' => ['type' => 'textarea', 'label' => 'Subtitle', 'rows' => 2, 'default' => 'Official ASEANAPOL guidelines governing operations, conduct, and partnerships.'],
            ]],
            'intro' => ['label' => 'Introduction', 'toggle' => true, 'fields' => [
                'text' => ['type' => 'textarea', 'label' => 'Text', 'rows' => 4, 'default' => 'ASEANAPOL has established a set of guidelines to ensure consistent and principled operations across its member states, partner organisations, and stakeholders. These documents provide clear frameworks for key operational and governance matters.'],
            ]],
            'cards' => ['label' => 'Guideline cards', 'fields' => [
                'read_icon'  => ['type' => 'icon', 'label' => 'Link icon', 'default' => 'description'],
                'read_label' => ['type' => 'text', 'label' => 'Link label', 'default' => 'Read Guidelines'],
                'items' => [
                    'type' => 'repeater', 'label' => 'Cards', 'item_label' => 'title', 'add_label' => 'Add card',
                    'fields' => [
                        'icon'       => ['type' => 'icon',     'label' => 'Icon'],
                        'title'      => ['type' => 'text',     'label' => 'Title'],
                        'desc'       => ['type' => 'textarea', 'label' => 'Description', 'rows' => 3],
                        'target'     => ['type' => 'select',   'label' => 'Links to', 'options' => [
                            ''                              => '(custom URL below)',
                            'guidelines.flag'               => 'Flag guidelines page',
                            'guidelines.contact-persons'    => 'Contact Persons page',
                            'guidelines.donations'          => 'Donations page',
                            'guidelines.observers-dialogue' => 'Observers & Dialogue Partners page',
                        ]],
                        'custom_url' => ['type' => 'url',      'label' => 'Custom URL (used when "Links to" is custom)'],
                    ],
                    'default' => [
                        ['icon' => 'flag', 'title' => 'Guidelines on the Use of the ASEANAPOL Flag', 'desc' => 'Provides rules and protocols for the proper display, use, and handling of the ASEANAPOL flag at official events, premises, and publications.', 'target' => 'guidelines.flag', 'custom_url' => ''],
                        ['icon' => 'contact_phone', 'title' => 'Guidelines on the Roles and Functions of ASEANAPOL Contact Persons', 'desc' => 'Defines the responsibilities, duties, and operational procedures for designated ASEANAPOL Contact Persons within each member police force.', 'target' => 'guidelines.contact-persons', 'custom_url' => ''],
                        ['icon' => 'volunteer_activism', 'title' => 'Guidelines for Accepting Donations and Sponsorships', 'desc' => 'Establishes the framework and criteria for ASEANAPOL to accept financial contributions, donations, and sponsorships from external parties.', 'target' => 'guidelines.donations', 'custom_url' => ''],
                        ['icon' => 'policy', 'title' => 'Policy Guidelines for Accepting Observers and Dialogue Partners', 'desc' => 'Sets out the eligibility requirements, application process, and terms for organisations seeking observer or dialogue partner status with ASEANAPOL.', 'target' => 'guidelines.observers-dialogue', 'custom_url' => ''],
                    ],
                ],
            ]],
        ],
    ],

    'flag' => [
        'label' => 'Use of the ASEANAPOL Flag',
        'route' => 'guidelines.flag',
        'sections' => [
            'hero' => $hero('Guidelines on the Use of the ASEANAPOL Flag', 'Rules and protocols for the proper use and display of the ASEANAPOL flag.', 'Use of the ASEANAPOL Flag'),
            'head' => $head('flag', 'Guidelines on the Use of the ASEANAPOL Flag'),
            'body' => ['label' => 'Guideline text', 'fields' => [
                'intro'    => ['type' => 'textarea', 'label' => 'Introduction paragraph', 'rows' => 4, 'default' => 'The ASEANAPOL Flag is the official emblem of the Association of Southeast Asian Nations Chiefs of Police. Its use is governed by these guidelines to ensure consistency, dignity, and respect in all official contexts.'],
                'sections' => $sectionsRepeater([
                    ['heading' => '1. Authorised Use', 'body' => '', 'items' => [
                        'The ASEANAPOL Flag shall be displayed at all official ASEANAPOL events, including the annual Conference, Executive Committee meetings, and working committee meetings.',
                        'Member police forces may display the flag at events conducted in cooperation with or on behalf of ASEANAPOL.',
                        'The flag may be used in official publications, websites, and digital communications of ASEANAPOL.',
                    ]],
                    ['heading' => '2. Display Protocol', 'body' => '', 'items' => [
                        'When displayed alongside national flags of member states, the ASEANAPOL Flag shall be given a position of equal prominence.',
                        'The flag shall not be displayed in a worn, torn, or damaged state.',
                        'The flag shall not be used for commercial or advertising purposes without prior written approval from the Permanent Secretariat.',
                    ]],
                    ['heading' => '3. Reproduction', 'body' => '', 'items' => [
                        'Any reproduction of the ASEANAPOL Flag must maintain the original proportions and colours.',
                        'Alterations, modifications, or distortions of the flag design are prohibited.',
                        'Requests for official flag files or physical flags should be directed to the Permanent Secretariat.',
                    ]],
                    ['heading' => '4. Prohibited Uses', 'body' => '', 'items' => [
                        'The flag shall not be used in a manner that is disrespectful, derogatory, or contrary to ASEANAPOL\'s values and objectives.',
                        'Unauthorised commercial use of the flag is strictly prohibited.',
                        'The flag shall not be used to imply endorsement by ASEANAPOL without explicit written approval.',
                    ]],
                ]),
            ]],
            'document' => $documentSection,
            'foot' => $foot('For enquiries regarding flag use, contact:'),
        ],
    ],

    'contact-persons' => [
        'label' => 'Roles of ASEANAPOL Contact Persons',
        'route' => 'guidelines.contact-persons',
        'sections' => [
            'hero' => $hero('Guidelines on the Roles and Functions of ASEANAPOL Contact Persons', 'Defining responsibilities of designated ASEANAPOL Contact Persons in each member police force.', 'Contact Persons'),
            'head' => $head('contact_phone', 'Guidelines on the Roles and Functions of ASEANAPOL Contact Persons'),
            'body' => ['label' => 'Guideline text', 'fields' => [
                'intro'    => ['type' => 'textarea', 'label' => 'Introduction paragraph', 'rows' => 4, 'default' => 'Each ASEAN member police force designates an ASEANAPOL Contact Person who serves as the primary liaison between their organisation and the ASEANAPOL Permanent Secretariat. These guidelines define the roles, responsibilities, and operational requirements of this position.'],
                'sections' => $sectionsRepeater([
                    ['heading' => '1. Designation', 'body' => '', 'items' => [
                        'Each member police force shall designate one primary ASEANAPOL Contact Person and one alternate.',
                        'Contact Persons shall be officers of sufficient seniority to facilitate prompt decision-making and communication.',
                        'Changes in Contact Person designation must be communicated to the Permanent Secretariat within 14 days.',
                    ]],
                    ['heading' => '2. Primary Roles', 'body' => '', 'items' => [
                        'Serve as the official liaison between the member police force and the ASEANAPOL Permanent Secretariat.',
                        'Coordinate the dissemination and response to ASEANAPOL communications within their organisation.',
                        'Facilitate participation in ASEANAPOL conferences, working committees, and training events.',
                        'Maintain and update member country information in ASEANAPOL databases.',
                    ]],
                    ['heading' => '3. Operational Functions', 'body' => '', 'items' => [
                        'Process and respond to ASEANAPOL requests for information, intelligence, and assistance.',
                        'Coordinate joint operations and investigative support with other member forces via ASEANAPOL channels.',
                        'Submit periodic activity reports to the Permanent Secretariat as required.',
                        'Ensure compliance with ASEANAPOL resolutions and programme commitments.',
                    ]],
                    ['heading' => '4. Confidentiality', 'body' => 'Contact Persons are bound by confidentiality obligations regarding all sensitive information exchanged through ASEANAPOL channels. Information classified at any level must be handled in accordance with the relevant member state\'s security protocols and ASEANAPOL information security guidelines.', 'items' => []],
                ]),
            ]],
            'document' => $documentSection,
            'foot' => ['label' => 'Footer line', 'fields' => [
                'back_label' => ['type' => 'text', 'label' => 'Back link label', 'default' => 'Back to Guidelines'],
            ]],
        ],
    ],

    'donations' => [
        'label' => 'Donations and Sponsorships',
        'route' => 'guidelines.donations',
        'sections' => [
            'hero' => $hero('Guidelines for Accepting Donations and Sponsorships', 'Framework and criteria for receiving financial contributions and sponsorships.', 'Donations and Sponsorships'),
            'head' => $head('volunteer_activism', 'Guidelines for Accepting Donations and Sponsorships'),
            'body' => ['label' => 'Guideline text', 'fields' => [
                'intro'    => ['type' => 'textarea', 'label' => 'Introduction paragraph', 'rows' => 4, 'default' => 'These guidelines establish the framework for ASEANAPOL to accept donations and sponsorships from external parties, ensuring transparency, accountability, and alignment with the organisation\'s mission and values.'],
                'sections' => $sectionsRepeater([
                    ['heading' => '1. Eligibility of Donors and Sponsors', 'body' => '', 'items' => [
                        'Donations and sponsorships may be accepted from governments, international organisations, non-governmental organisations, and private sector entities.',
                        'Donors and sponsors must not have interests conflicting with ASEANAPOL\'s mandate or the laws of any member state.',
                        'Anonymous donations above a specified threshold may not be accepted without due diligence.',
                    ]],
                    ['heading' => '2. Acceptance Criteria', 'body' => '', 'items' => [
                        'Donations and sponsorships must support ASEANAPOL\'s stated objectives and programmes.',
                        'No conditions may be attached that would compromise ASEANAPOL\'s independence, integrity, or impartiality.',
                        'The Executive Committee must approve donations exceeding defined financial thresholds.',
                    ]],
                    ['heading' => '3. Transparency and Accountability', 'body' => '', 'items' => [
                        'All accepted donations and sponsorships shall be recorded in ASEANAPOL\'s financial records.',
                        'An annual report of donations and sponsorships received shall be presented to the Conference.',
                        'Donors/sponsors shall receive acknowledgment of their contribution in accordance with agreed terms.',
                    ]],
                    ['heading' => '4. Application Process', 'body' => 'Organisations wishing to make a donation or sponsor ASEANAPOL programmes should submit a formal proposal to the Permanent Secretariat. The Secretariat will assess the proposal against these guidelines and present recommendations to the Executive Committee.', 'items' => []],
                ]),
            ]],
            'document' => $documentSection,
            'foot' => $foot('Enquiries:'),
        ],
    ],

    'observers-dialogue' => [
        'label' => 'Observers and Dialogue Partners',
        'route' => 'guidelines.observers-dialogue',
        'sections' => [
            'hero' => $hero('Policy Guidelines for Accepting Observers and Dialogue Partners', 'Eligibility requirements and process for organisations seeking partnership status with ASEANAPOL.', 'Observers and Dialogue Partners'),
            'head' => $head('policy', 'Policy Guidelines for Accepting Observers and Dialogue Partners with ASEANAPOL'),
            'intro' => ['label' => 'Introduction', 'fields' => [
                'text' => ['type' => 'textarea', 'label' => 'Introduction paragraph', 'rows' => 4, 'default' => 'These policy guidelines govern the process by which external organisations and countries may be admitted as Observers or Dialogue Partners of ASEANAPOL. Both statuses offer distinct levels of engagement with the organisation.'],
            ]],
            'status' => ['label' => 'Observer / Dialogue Partner boxes', 'toggle' => true, 'fields' => [
                'observer_title'  => ['type' => 'text',     'label' => 'Observer: title', 'default' => 'Observer Status'],
                'observer_text'   => ['type' => 'textarea', 'label' => 'Observer: text', 'rows' => 4, 'default' => 'Observers may attend ASEANAPOL Conference sessions as specified, participate in working committees as invited, and receive ASEANAPOL publications and communications. Observers do not have voting rights.'],
                'dialogue_title'  => ['type' => 'text',     'label' => 'Dialogue Partner: title', 'default' => 'Dialogue Partner Status'],
                'dialogue_text'   => ['type' => 'textarea', 'label' => 'Dialogue Partner: text', 'rows' => 4, 'default' => 'Dialogue Partners have a higher level of engagement, including formal dialogue sessions at the Conference, joint programme participation, and closer operational cooperation with ASEANAPOL.'],
            ]],
            'eligibility' => ['label' => '1. Eligibility criteria', 'toggle' => true, 'fields' => [
                'heading' => ['type' => 'text', 'label' => 'Heading', 'default' => '1. Eligibility Criteria'],
                'items' => [
                    'type' => 'repeater', 'label' => 'Criteria', 'item_label' => 'text', 'add_label' => 'Add criterion',
                    'fields' => ['text' => ['type' => 'textarea', 'label' => 'Criterion', 'rows' => 3]],
                    'default' => [
                        ['text' => 'The applicant must be a recognised international or regional organisation with a mandate relevant to law enforcement, crime prevention, or public safety.'],
                        ['text' => 'The applicant must demonstrate a clear basis for cooperation with ASEANAPOL that benefits the ASEAN region.'],
                        ['text' => 'The applicant must not have objectives or activities in conflict with ASEANAPOL\'s mission or any ASEAN member state\'s laws.'],
                    ],
                ],
            ]],
            'process' => ['label' => '2. Application process (numbered steps)', 'toggle' => true, 'fields' => [
                'heading' => ['type' => 'text', 'label' => 'Heading', 'default' => '2. Application Process'],
                'steps' => [
                    'type' => 'repeater', 'label' => 'Steps (numbered automatically)', 'item_label' => 'text', 'add_label' => 'Add step',
                    'fields' => ['text' => ['type' => 'textarea', 'label' => 'Step', 'rows' => 3]],
                    'default' => [
                        ['text' => 'Submit a formal letter of application to the ASEANAPOL Permanent Secretariat, stating the basis for the request and proposed areas of cooperation.'],
                        ['text' => 'The Permanent Secretariat reviews the application against eligibility criteria and prepares a recommendation.'],
                        ['text' => 'The recommendation is presented to the Executive Committee for initial endorsement.'],
                        ['text' => 'The Executive Committee\'s endorsement is submitted to the full ASEANAPOL Conference for approval.'],
                        ['text' => 'Upon Conference approval, the applicant is formally notified and an appropriate arrangement is concluded.'],
                    ],
                ],
            ]],
            'review' => ['label' => '3. Review and termination', 'toggle' => true, 'fields' => [
                'heading' => ['type' => 'text',     'label' => 'Heading', 'default' => '3. Review and Termination'],
                'text'    => ['type' => 'textarea', 'label' => 'Text', 'rows' => 4, 'default' => 'Observer and Dialogue Partner status is subject to periodic review. Status may be suspended or terminated by decision of the ASEANAPOL Conference if the organisation no longer meets eligibility criteria or acts contrary to ASEANAPOL\'s values and objectives.'],
            ]],
            'extra' => ['label' => 'Additional sections', 'help' => 'Optional extra sections appended after section 3.', 'fields' => [
                'sections' => $sectionsRepeater([]),
            ]],
            'document' => $documentSection,
            'foot' => $foot('Applications:'),
        ],
    ],
];
