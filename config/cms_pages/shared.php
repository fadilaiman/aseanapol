<?php

/**
 * CMS registry — group "shared" (Shared facts used across pages).
 * Page key = "shared.<slug>". These pages are the SINGLE source for real-world facts that public pages
 * used to restate separately (organisation identity, Secretariat contact, member forces, office holders, partners).
 * Read them in PHP/Blade with Shared::* / shared() (app/Support/Shared.php), never copy the values into other registries.
 * DEFAULT values are the original hardcoded content (member pages are canonical where copies disagreed).
 */

$mk = ['brunei' => 'Brunei Darussalam', 'cambodia' => 'Cambodia', 'indonesia' => 'Indonesia', 'lao' => 'Lao PDR', 'malaysia' => 'Malaysia',
       'myanmar' => 'Myanmar', 'philippines' => 'Philippines', 'singapore' => 'Singapore', 'thailand' => 'Thailand', 'vietnam' => 'Viet Nam', 'timor-leste' => 'Timor-Leste'];

/* ------------------------------------------------------------------ members */
// [key, country, iso, force, abbreviation, joined_year, logo (member page crest), landing_logo (file in media/police-logo/), website, ncb_email, tel, tel2, fax, badge]
$m = fn (...$a) => array_combine(
    ['key', 'country', 'iso', 'force', 'abbreviation', 'joined_year', 'logo', 'landing_logo', 'website', 'ncb_email', 'tel', 'tel2', 'fax', 'badge'], $a);
$members = [
    $m('brunei', 'Brunei Darussalam', 'bn', 'Royal Brunei Police Force', 'RBPF', '1984', 'media/police-logo/police-logo/brunei.png', 'brunei-rbpf.png', 'https://www.police.gov.bn', 'rbpf@aseanapol.org', '+673-2459 500', '+673-2423 901 ext: 784/785', '+673 245 9527', ''),
    $m('cambodia', 'Cambodia', 'kh', 'Cambodian National Police', 'CNP', '2000', 'media/default-album/default-album/cambodian.png', 'cambodia-cnp.png', 'https://www.police.gov.kh', 'cnp@aseanapol.org', '+855 23216585', '', '+855 2321 6585', ''),
    $m('indonesia', 'Indonesia', 'id', 'Indonesian National Police', 'INP', '1981', 'media/police-logo/indonesia-polri.png', 'indonesia-polri.png', 'https://www.polri.go.id', 'inp@aseanapol.org', '+021 739 3650', '+021 721 8467', '+021 720 1402', ''),
    $m('lao', 'Lao PDR', 'la', 'Lao Police Force', 'LPF', '1998', 'media/police-logo/police-logo/lpf.jpg', 'laos-lpp.png', '', 'lpf@aseanapol.org', '+856 213 16323', '', '+856 2131 6323', ''),
    $m('malaysia', 'Malaysia', 'my', 'Royal Malaysia Police', 'RMP', '1981', 'media/police-logo/police-logo/malaysia.png', 'malaysia-pdrm.png', 'https://www.rmp.gov.my', 'rmp@aseanapol.org', '+603 2266 2222', '', '+603 2070 7500', ''),
    $m('myanmar', 'Myanmar', 'mm', 'Myanmar Police Force', 'MPF', '1998', 'media/police-logo/police-logo/myanmar-new-crest-1.png', 'myanmar-mpf.png', '', 'mpf@aseanapol.org', '+95 6741 2066', '', '+95 6741 2188', ''),
    $m('philippines', 'Philippines', 'ph', 'Philippine National Police', 'PNP', '1981', 'media/default-album/default-album/philippines.gif', 'philippines-pnp.png', 'https://www.pnp.gov.ph', 'pnp@aseanapol.org', '+632 8723 0401', '', '+632 7218549', ''),
    $m('singapore', 'Singapore', 'sg', 'Singapore Police Force', 'SPF', '1981', 'media/default-album/default-album/singapore.png', 'singapore-spf.png', 'https://www.police.gov.sg', 'spf@aseanapol.org', '1800 358 000', '', '+65 6256 1296', ''),
    $m('thailand', 'Thailand', 'th', 'Royal Thai Police', 'RTP', '1981', 'media/default-album/default-album/emblem_of_royal_thai_police_new.jpg', 'thailand-rtp.png', 'https://www.royalthaipolice.go.th', 'rtp@aseanapol.org', '+6622053001', '', '+6622533856', ''),
    $m('vietnam', 'Viet Nam', 'vn', 'Office of Investigation Police Agency, Vietnam Police Force', 'OIPA, VPF', '1996', 'media/police-logo/police-logo/vietnam.png', 'vietnam-mps.png', '', 'vpf@aseanapol.org', '+8424 3938 7173', '', '+8424 3938 7176', ''),
    $m('timor-leste', 'Timor-Leste', 'tl', 'National Police of Timor-Leste', 'PNTL', '2026', 'media/police-logo/timorleste-pntl.png', 'timorleste-pntl.png', 'https://www.pntl.tl', 'pntl@aseanapol.org', '', '', '', 'New'),
];

/* ------------------------------------------------------------------ roster */
// Photos: media/ed/N.(jpg|png), media/dps/noN.png, media/dpp/noN.png (as used by the leadership pages).
$p = fn (...$a) => array_combine(
    ['post', 'ordinal', 'rank', 'name', 'member_key', 'force_label', 'term_start', 'term_end', 'photo', 'is_current', 'bio'], $a);
$roster = [
    // Executive Director
    $p('ed', '1st', 'ACP', 'Mohd Nadzri bin Zainal Abidin', 'malaysia', '', '2010', '2011', 'media/ed/1.jpg', false, ''),
    $p('ed', '2nd', 'Lt. General', 'Sar Moline', 'cambodia', '', '2012', '2013', 'media/ed/2.jpg', false, ''),
    $p('ed', '3rd', 'SAC', "Pengiran Dato' Paduka Hj. Abdul Wahab bin Pengiran Hj. Omar", 'brunei', '', '2014', '2015', 'media/ed/3.png', false, ''),
    $p('ed', '4th', 'Police Inspector General', 'Yohanes Agus Mulyono', 'indonesia', '', '2016', '2017', 'media/ed/4.jpg', false, ''),
    $p('ed', '5th', 'Pol. Colonel', 'Kenechanh Phommachack', 'lao', '', '2018', '2019', 'media/ed/5.jpg', false, ''),
    $p('ed', '6th', 'DAC', 'Jim Wee', 'singapore', '', '2020', '2021', 'media/ed/6.jpg', false, ''),
    $p('ed', '7th', 'Police Brigadier General', 'Zaw Lin Tun', 'myanmar', '', '2022', '2023', 'media/ed/7.jpg', false, ''),
    $p('ed', '8th', 'Pol. Colonel', 'David Martinez Vinluan', 'philippines', '', '2024', '2025', 'media/ed/8.jpg', false, ''),
    $p('ed', '9th', 'Police Senior Colonel', 'Kongkrissada Kittithiraphong', 'thailand', '', '2026', '2027', 'media/ed/9.jpg', true,
        "Police Senior Colonel Kongkrissada Kittithiraphong serves as the 9th Executive Director of the ASEANAPOL Secretariat, nominated by the Royal Thai Police for the term 2026–2027. He oversees the operations of the Secretariat and leads ASEANAPOL's efforts in strengthening regional cooperation to combat transnational crime."),
    // Director for Police Services
    $p('dps', '1st', 'DAC', 'Lim Kim Tak', 'singapore', '', '2010', '2012', 'media/dps/no1.png', false, ''),
    $p('dps', '2nd', 'ACP', 'Mohamad Anil Shah bin Abdullah', 'malaysia', '', '2013', '2015', 'media/dps/no2.png', false, ''),
    $p('dps', '3rd', 'PSSUPT.', 'Ferdinand R.P. Bartolome', 'philippines', '', '2016', '2017', 'media/dps/no3.png', false, ''),
    $p('dps', '4th', 'DAC', 'Jim Wee', 'singapore', '', '2018', '2019', 'media/dps/no4.png', false, ''),
    $p('dps', '5th', 'Pol. Snr. Supt.', 'Joni Getamala', 'indonesia', '', '2020', '2022', 'media/dps/no5.png', false, ''),
    $p('dps', '6th', 'ACP', 'Dr. Bakri bin Zainal Abidin', 'malaysia', '', '2023', '2024', 'media/dps/no6.png', false, ''),
    $p('dps', '7th', 'Police Senior Superintendent', 'Huntal Tambunan', 'indonesia', '', '2025', '', 'media/dps/no7.png', true,
        'Police Senior Superintendent Huntal Tambunan serves as the Director for Police Services of the ASEANAPOL Secretariat, nominated by the Indonesian National Police. He is responsible for overseeing police cooperation and operational programmes under the Secretariat.'),
    // Director for Plans and Programmes
    $p('dpp', '1st', 'Pol. Supt.', 'Desy Adriani', 'indonesia', '', '2010', '2012', 'media/dpp/no1.png', false, ''),
    $p('dpp', '2nd', 'Pol. Supt.', 'Reinhard Hutagaol', 'indonesia', '', '2013', '2014', 'media/dpp/no2.png', false, ''),
    $p('dpp', '3rd', 'Pol. Supt.', 'Yuli Cahyanti', 'indonesia', '', '2015', '2016', 'media/dpp/no3.png', false, ''),
    $p('dpp', '4th', 'ACP', 'Aidah binti Othman', 'malaysia', '', '2017', '2019', 'media/dpp/no4.png', false, ''),
    $p('dpp', '5th', 'Supt.', 'Tang Yik Tung', 'brunei', '', '2020', '2022', 'media/dpp/no5.png', false, ''),
    $p('dpp', '6th', 'Pol. Snr. Lt. Col.', 'Nguyen Huu Ngoc', 'vietnam', '', '2023', '2025', 'media/dpp/no6.png', false, ''),
    $p('dpp', '7th', 'Police Colonel', 'Jean Mary A. Mangahis', 'philippines', '', '2026', '', 'media/dpp/no7.png', true,
        'Police Colonel Jean Mary A. Mangahis serves as the Director for Plans and Programmes of the ASEANAPOL Secretariat, nominated by the Philippine National Police. She is responsible for strategic planning and overseeing programme implementation within the Secretariat.'),
];

/* ------------------------------------------------------------------ partners (international organisations) */
// [key, name, short_name, full_name, website, logo, icon, is_lme, is_cooperation_partner]
$o = fn (...$a) => array_combine(['key', 'name', 'short_name', 'full_name', 'website', 'logo', 'icon', 'is_lme', 'is_cooperation_partner'], $a);
$partners = [
    $o('interpol', 'INTERPOL', 'INTERPOL', 'International Criminal Police Organization', 'https://www.interpol.int/', '', 'policy', false, false),
    $o('europol', 'Europol', 'Europol', 'European Union Agency for Law Enforcement Cooperation', 'https://www.europol.europa.eu/', '', 'shield', false, false),
    $o('accbp', 'ACCBP', 'ACCBP', 'ASEAN Chiefs of Customs & Border Police', '', '', 'security', false, false),
    $o('iafi', 'IAFI', 'IAFI', 'International Association for Forensic Institutes', '', 'media/lme/iafi.png', 'science', true, false),
    $o('interpa', 'INTERPA', 'INTERPA', 'International Association of Police Academies', '', 'media/lme/interpa.png', 'school', true, true),
    $o('apmla', 'APMLA', 'APMLA', 'The Asia Pacific Medico Legal Agencies', 'https://www.theapmla.net/', 'media/lme/apmla.jpg', 'medical_services', true, true),
    $o('borderpol', 'BORDERPOL', 'BORDERPOL', 'The World Border Organization, Canada', '', 'media/lme/borderpol.png', 'border_style', true, true),
    $o('caricc', 'CARICC', 'CARICC', 'Central Asian Regional Information and Coordination Centre', 'https://www.caricc.org/en/', 'media/lme/caricc.png', 'hub', true, true),
    $o('cites', 'CITES', 'CITES', 'Secretariat of the Convention on International Trade in Endangered Species of Fauna and Flora', 'https://cites.org/eng', 'media/lme/cites.png', 'nature', true, true),
    $o('un-cted', 'UN CTED', 'UN CTED', 'United Nations Counter-Terrorism Committee Executive Directorate', 'https://www.un.org/securitycouncil/ctc/', 'media/lme/un-cted.png', 'security', true, true),
    $o('selec', 'SELEC', 'SELEC', 'Southeast European Law Enforcement Center', 'https://www.selec.org/', 'media/lme/selec.jpg', 'gavel', true, true),
    $o('picp', 'PICP', 'PICP', 'Pacific Islands Chiefs of Police Secretariat', 'https://www.picp.co.nz/', 'media/lme/picp.png', 'local_police', true, true),
    $o('east', 'EAST', 'EAST', 'European Association for Secure Transactions', 'https://www.association-secure-transactions.eu/', 'media/lme/east.png', 'credit_card', true, true),
    $o('unodc', 'UNODC', 'UNODC', 'United Nations Office on Drugs and Crime', 'https://www.unodc.org/', 'media/lme/unodc.png', 'balance', true, true),
    $o('rats-sco', 'RATS-SCO', 'RATS-SCO', 'Regional Anti-Terrorism Structure of the Shanghai Cooperation Organisation', 'https://ecrats.org/ru/', 'media/lme/rats-sco.png', 'policy', true, true),
    $o('apta', 'APTA', 'APTA', 'Association of Police Training Institutions in Asia', 'https://aptahome.org/', 'media/lme/apta.jpeg', 'school', true, true),
    $o('usaid-wildlife-asia', 'USAID Wildlife Asia', 'USAID Wildlife Asia', 'U.S. Agency for International Development Wildlife Asia', 'https://www.fhi360.org/projects/wildlife-asia/', 'media/lme/usaid.png', 'pets', true, true),
    $o('rru', 'RRU', 'RRU', 'Rashtriya Raksha University', 'https://rru.ac.in/', 'media/lme/rru.png', 'emergency', true, true),
    $o('mastercard', 'Mastercard', 'Mastercard', 'Mastercard Asia Pacific', 'https://www.mastercard.com/news/ap/en/', 'media/lme/mastercard.png', 'payments', true, true),
    $o('freeland', 'Freeland', 'Freeland', 'Freeland', 'https://www.freeland.org/', 'media/lme/freeland.jpeg', 'forest', true, true),
    $o('latf', 'LATF', 'LATF', 'Lusaka Agreement Task Force', 'https://lusakaagreement.org/', 'media/lme/latf.png', 'task', true, true),
];

/* ------------------------------------------------------------------ dialogue partners + observers */
// [key, name, full_name, is_dialogue, is_observer, dialogue_since, observer_since, observer_conference, badge, website, q, desc]
// (INTERPOL/Europol use the same key as on the Partner organisations page: their name, full name and website are left empty here
// and filled from that page by Shared::dialogue(), so the facts live in one place.)
$d = fn (...$a) => array_combine(
    ['key', 'name', 'full_name', 'is_dialogue', 'is_observer', 'dialogue_since', 'observer_since', 'observer_conference', 'badge', 'website', 'q', 'desc'], $a);
$dialogue = [
    $d('interpol', '', '', true, false, '2012', '', '', 'travel_explore', '', 'INTERPOL',
        "The world's largest international police organisation. ASEANAPOL's e-ADS operates on INTERPOL's I-24/7 secure communications network, enabling seamless criminal intelligence exchange across the region."),
    $d('afp-australia', 'Australian Federal Police (AFP)', 'Australian Federal Police', true, false, '2008', '', '', '🇦🇺', 'https://www.afp.gov.au/', 'Australian Federal Police',
        'A founding Dialogue Partner since 2008, AFP provides capacity-building training for ASEANAPOL member countries and participates actively in annual conferences and contact person meetings.'),
    $d('mps-china', 'Ministry of Public Security (MPS)', 'Ministry of Public Security, China', true, false, '2008', '', '', '🇨🇳', 'https://www.mps.gov.cn/', 'China',
        "China's national law enforcement ministry contributes expertise in combating transnational crime, sharing intelligence and supporting ASEANAPOL's regional security agenda."),
    $d('npa-japan', 'National Police Agency (NPA)', 'National Police Agency, Japan', true, false, '2008', '', '', '🇯🇵', 'https://www.npa.go.jp/english/', 'Japan',
        "Japan's NPA engages as a key Dialogue Partner, contributing to cybercrime, drug trafficking, and financial crime cooperation efforts across the ASEAN region."),
    $d('knpa-korea', 'Korean National Police Agency (KNPA)', 'Korean National Police Agency, Republic of Korea', true, false, '2008', '', '', '🇰🇷', 'https://www.police.go.kr/eng/main.do', 'Korean National Police',
        'KNPA supports ASEANAPOL through shared expertise in cybercrime investigation, digital forensics, and capacity-building programmes for member country police forces.'),
    $d('nzp', 'New Zealand Police (NZP)', 'New Zealand Police', true, false, '2008', '', '', '🇳🇿', 'https://www.police.govt.nz/', 'New Zealand',
        "New Zealand Police actively participates in ASEANAPOL conferences and training cooperation meetings, supporting the region's capacity in serious crime investigation."),
    $d('mia-russia', 'Ministry of Internal Affairs (MIA)', 'Ministry of Internal Affairs, Russian Federation', true, false, '2014', '', '', '🇷🇺', 'https://mvd.ru/', 'Russia',
        "Elevated to Dialogue Partner status at the 34th ASEANAPOL Conference in 2014. Russia's MIA provides annual training programmes on counter-narcotics and organised crime for AMC officers."),
    $d('tnp-turkey', 'Turkish National Police (TNP)', 'Turkish National Police', true, false, '2015', '', '', '🇹🇷', 'https://www.egm.gov.tr/', 'Turkish',
        "Turkey's national police service engages with ASEANAPOL through knowledge exchange and cooperation on counter-terrorism, drug trafficking, and financial crime."),
    $d('nca-uk', 'UK National Crime Agency (NCA)', 'National Crime Agency, United Kingdom', true, false, '2019', '', '', '🇬🇧', 'https://www.nationalcrimeagency.gov.uk/', 'United Kingdom',
        "The UK's NCA collaborates with ASEANAPOL on combating serious and organised crime, including human trafficking, cybercrime, and drug networks affecting the ASEAN region."),
    $d('rcmp', 'Royal Canadian Mounted Police (RCMP)', 'Royal Canadian Mounted Police', true, true, '2019', '2019', '39th AC, Viet Nam', '🇨🇦', 'https://rcmp.ca/en', 'Canada',
        'The RCMP supports ASEANAPOL capacity-building through training and knowledge transfer programmes, particularly through initiatives hosted at the Jakarta Centre for Law Enforcement Cooperation (JCLEC).'),
    $d('asean-secretariat', 'ASEAN Secretariat', 'Secretariat of the Association of Southeast Asian Nations', true, false, '2012', '', '', 'hub', 'https://asean.org/', 'ASEAN Secretariat',
        "The ASEAN Secretariat participates as a Dialogue Partner, supporting alignment between ASEANAPOL's work and broader ASEAN frameworks on transnational crime and regional security."),
    $d('europol', '', '', true, false, '2018', '', '', 'public', '', 'Europol',
        'Europol engages with ASEANAPOL on cross-regional threats including financial fraud, cybercrime, and organised crime affecting both Southeast Asia and Europe.'),
    // Observers (rows below are observers only; RCMP above is both)
    $d('afp-argentina', 'Argentine Federal Police (AFP)', '', false, true, '', '2022', '40th AC, Cambodia', '🇦🇷', 'https://www.argentina.gob.ar/seguridad/pfa', 'Argentina', ''),
    $d('bangladesh-police', 'Bangladesh Police', '', false, true, '', '2022', '40th AC, Cambodia', '🇧🇩', 'https://www.police.gov.bd/', 'Bangladesh', ''),
    $d('fiji', 'Fiji', '', false, true, '', '2016', '36th AC, Malaysia', '🇫🇯', '', 'Fiji', ''),
    $d('fnp-france', 'French National Police (FNP)', '', false, true, '', '2019', '39th AC, Viet Nam', '🇫🇷', 'https://www.police-nationale.interieur.gouv.fr/', 'French', ''),
    $d('psd-iim-italy', 'Security Department of Italian Interior (PSD IIM)', '', false, true, '', '2022', '40th AC, Cambodia', '🇮🇹', '', 'Italian', ''),
    $d('timor-leste', 'Timor-Leste', '', false, true, '', '2014', '34th AC, Philippines', '🇹🇱', '', 'Timor', ''),
    $d('uae', 'United Arab Emirates (UAE)', '', false, true, '', '2022', '40th AC, Cambodia', '🇦🇪', 'https://moi.gov.ae/ar/default.aspx', 'UAE', ''),
    $d('iacp', 'International Association of Chiefs of Police (IACP)', '', false, true, '', '2016', '36th AC, Malaysia', '', 'https://www.theiacp.org/', 'IACP', ''),
    $d('icrc', 'International Committee of the Red Cross (ICRC)', '', false, true, '', '2015', '35th AC, Indonesia', '', 'https://www.icrc.org/en', 'ICRC', ''),
    $d('gccpol', 'Gulf Cooperation Council Police (GCCPOL)', '', false, true, '', '2019', '39th AC, Viet Nam', '', 'https://www.gcc-sg.org/ar/JointGulf/Cooperation/GCCPOL/Pages/default.aspx', 'GCCPOL', ''),
];

/* ------------------------------------------------------------------ definition */
return [
    '_group' => ['label' => 'Shared facts (used across pages)', 'icon' => 'hub', 'order' => 2],

    // ------------------------------------------------------------------ organisation
    'organisation' => [
        'label' => 'Organisation (name, dates, counts)',
        'sections' => [
            'identity' => ['label' => 'Identity', 'help' => 'Used wherever the association is named across the site.', 'columns' => 2, 'fields' => [
                'name_long'  => ['type' => 'text', 'label' => 'Full name', 'default' => 'Association of Southeast Asian Nations Chiefs of Police'],
                'name_short' => ['type' => 'text', 'label' => 'Short name', 'default' => 'ASEANAPOL'],
                'tagline'    => ['type' => 'text', 'label' => 'Tagline / vision statement (without quotes)', 'default' => 'Toward a peaceful and crime-free ASEAN region'],
                'logo'       => ['type' => 'image', 'label' => 'Logo', 'default' => 'images/aseanapol-logo.png'],
                'logo_alt'   => ['type' => 'text', 'label' => 'Logo alt text', 'default' => 'ASEANAPOL Logo'],
            ]],
            'key_dates' => ['label' => 'Key dates', 'columns' => 2, 'fields' => [
                'founded_year'       => ['type' => 'text', 'label' => 'Year founded', 'default' => '1981'],
                'founded_date_label' => ['type' => 'text', 'label' => 'Founding date (as written)', 'default' => '21 October 1981'],
                'first_conference_label' => ['type' => 'text', 'label' => 'First conference (dates and place)', 'default' => '21 to 23 October 1981, Manila, Philippines'],
                'secretariat_established_label' => ['type' => 'text', 'label' => 'Permanent Secretariat fully operational from', 'default' => '1 January 2010'],
                'conference_count_label' => ['type' => 'text', 'label' => 'Number of annual conferences (as shown, e.g. 44+)', 'default' => '44+'],
            ]],
            'counts' => ['label' => 'Counts', 'columns' => 2, 'fields' => [
                'member_count_word' => ['type' => 'text', 'label' => 'Number of member states (in words, lower case)', 'default' => 'eleven'],
                'member_count_num'  => ['type' => 'number', 'label' => 'Number of member states (digits)', 'default' => 11],
            ]],
        ],
    ],

    // ------------------------------------------------------------------ secretariat
    'secretariat' => [
        'label' => 'Secretariat contact & e-ADS',
        'sections' => [
            'contact' => ['label' => 'Permanent Secretariat contact', 'columns' => 2, 'fields' => [
                'address'         => ['type' => 'textarea', 'label' => 'Address (one line per row)', 'rows' => 3, 'default' => "Level 13, Tower 2, Bank Rakyat Twin Tower\nNo. 33, Jalan Rakyat\n50470 Kuala Lumpur, Malaysia"],
                'address_oneline' => ['type' => 'text', 'label' => 'Address (single line, e.g. footer)', 'default' => 'Level 13, Tower 2, Bank Rakyat Twin Tower, No 33, Jalan Rakyat, 50470 Kuala Lumpur, Malaysia'],
                'phone'           => ['type' => 'text', 'label' => 'Phone (displayed)', 'default' => '+603-22602222'],
                'phone_link'      => ['type' => 'text', 'label' => 'Phone link (tel:)', 'default' => 'tel:+60322602222'],
                'fax'             => ['type' => 'text', 'label' => 'Fax', 'default' => '+603-22602205'],
                'email_secretariat' => ['type' => 'text', 'label' => 'Secretariat e-mail', 'help' => 'The Secretariat mailbox (contact page, footer, inquiries).', 'default' => 'aseanapolsec@aseanapol.org'],
                'email_general'   => ['type' => 'text', 'label' => 'General information e-mail', 'help' => 'A different mailbox from the Secretariat one.', 'default' => 'info@aseanapol.org'],
                'office_hours'    => ['type' => 'text', 'label' => 'Office hours', 'default' => 'Office hours: Monday–Friday, 8:30 AM – 5:30 PM (MYT, UTC+8)'],
                'map_embed_src'   => ['type' => 'url', 'label' => 'Map embed URL (Google Maps "output=embed" link)', 'default' => 'https://maps.google.com/maps?q=Menara+Kembar+Bank+Rakyat,+33+Jalan+Rakyat,+Kuala+Lumpur&output=embed&z=17'],
            ]],
            'eads' => ['label' => 'e-ADS database system', 'columns' => 2, 'fields' => [
                'name' => ['type' => 'text', 'label' => 'Name', 'default' => 'Electronic ASEANAPOL Database System (e-ADS)'],
                'url'  => ['type' => 'text', 'label' => 'Link', 'help' => 'Empty = not linked (the site currently uses "#").', 'default' => ''],
            ]],
        ],
    ],

    // ------------------------------------------------------------------ members
    'members' => [
        'label' => 'Member countries',
        'sections' => [
            'list' => ['label' => 'Member police forces (display order)', 'help' => 'One row per member state. The key is a technical id used by the pages - do not change it. Display name = "force (abbreviation)". Hide removes a member from every page that reads this list.', 'fields' => [
                'rows' => [
                    'type' => 'repeater', 'label' => 'Members', 'item_label' => 'country', 'add_label' => 'Add member', 'columns' => 2,
                    'fields' => [
                        'key'          => ['type' => 'text', 'label' => 'Key (technical id, e.g. brunei)'],
                        'country'      => ['type' => 'text', 'label' => 'Country name'],
                        'iso'          => ['type' => 'text', 'label' => 'Flag code (2 letters)'],
                        'force'        => ['type' => 'text', 'label' => 'Police force (official English name)'],
                        'abbreviation' => ['type' => 'text', 'label' => 'Abbreviation'],
                        'joined_year'  => ['type' => 'text', 'label' => 'Member since (year)'],
                        'logo'         => ['type' => 'image', 'label' => 'Crest (member page)'],
                        'landing_logo' => ['type' => 'text', 'label' => 'Logo file in /media/police-logo/ (landing & contact directory) or URL'],
                        'website'      => ['type' => 'url', 'label' => 'Website'],
                        'ncb_email'    => ['type' => 'text', 'label' => 'ASEANAPOL e-mail'],
                        'tel'          => ['type' => 'text', 'label' => 'Telephone (primary)'],
                        'tel2'         => ['type' => 'text', 'label' => 'Telephone (second)'],
                        'fax'          => ['type' => 'text', 'label' => 'Fax'],
                        'badge'        => ['type' => 'text', 'label' => 'Corner badge (e.g. New)'],
                    ],
                    'default' => $members,
                ],
            ]],
        ],
    ],

    // ------------------------------------------------------------------ roster (Executive Director, DPS, DPP)
    'roster' => [
        'label' => 'Secretariat office holders (roster)',
        'sections' => [
            'people' => ['label' => 'Executive Directors and Directors (all terms)', 'help' => 'One row per person per post. Set exactly one row per post as "current". Term end empty = still serving. Name is without rank; rank is shown before the name.', 'fields' => [
                'rows' => [
                    'type' => 'repeater', 'label' => 'People', 'item_label' => 'name', 'add_label' => 'Add person', 'columns' => 2,
                    'fields' => [
                        'post'        => ['type' => 'select', 'label' => 'Post', 'options' => ['ed' => 'Executive Director', 'dps' => 'Director for Police Services', 'dpp' => 'Director for Plans and Programmes'], 'default' => 'ed'],
                        'ordinal'     => ['type' => 'text', 'label' => 'Number (e.g. 9th)'],
                        'rank'        => ['type' => 'text', 'label' => 'Rank'],
                        'name'        => ['type' => 'text', 'label' => 'Name'],
                        'member_key'  => ['type' => 'select', 'label' => 'Nominating member country', 'options' => ['' => '— none —'] + $mk, 'default' => ''],
                        'force_label' => ['type' => 'text', 'label' => 'Police force text (leave empty to use the member force)'],
                        'term_start'  => ['type' => 'text', 'label' => 'Term start (year)'],
                        'term_end'    => ['type' => 'text', 'label' => 'Term end (year; empty = present)'],
                        'photo'       => ['type' => 'image', 'label' => 'Photo'],
                        'is_current'  => ['type' => 'boolean', 'label' => 'Current post holder'],
                        'bio'         => ['type' => 'textarea', 'label' => 'Biography (current holders)', 'rows' => 4],
                    ],
                    'default' => $roster,
                ],
            ]],
        ],
    ],

    // ------------------------------------------------------------------ partners (international organisations / LME)
    'partners' => [
        'label' => 'Partner organisations (LME & cooperation)',
        'sections' => [
            'list' => ['label' => 'International organisations', 'help' => 'Names and websites used for Like-Minded Entities, International Cooperation and the footer partner links. Card icon/category/description stay on each page.', 'fields' => [
                'rows' => [
                    'type' => 'repeater', 'label' => 'Organisations', 'item_label' => 'name', 'add_label' => 'Add organisation', 'columns' => 2,
                    'fields' => [
                        'key'       => ['type' => 'text', 'label' => 'Key (technical id)'],
                        'name'      => ['type' => 'text', 'label' => 'Name (as listed)'],
                        'short_name' => ['type' => 'text', 'label' => 'Short name / acronym'],
                        'full_name' => ['type' => 'text', 'label' => 'Full name'],
                        'website'   => ['type' => 'url', 'label' => 'Website (empty = none)'],
                        'logo'      => ['type' => 'image', 'label' => 'Logo'],
                        'icon'      => ['type' => 'icon', 'label' => 'Icon'],
                        'is_lme'    => ['type' => 'boolean', 'label' => 'Like-Minded Entity'],
                        'is_cooperation_partner' => ['type' => 'boolean', 'label' => 'Listed as a cooperation partner'],
                    ],
                    'default' => $partners,
                ],
            ]],
        ],
    ],

    // ------------------------------------------------------------------ dialogue partners + observers
    'dialogue' => [
        'label' => 'Dialogue partners & observers',
        'sections' => [
            'list' => ['label' => 'Dialogue partners and observers', 'help' => 'One row per organisation. Tick "Dialogue partner" and/or "Observer" (RCMP is both). INTERPOL and Europol leave name, full name and website empty on purpose: they come from Partner organisations (type a value here only to override it).', 'fields' => [
                'rows' => [
                    'type' => 'repeater', 'label' => 'Organisations', 'item_label' => 'name', 'add_label' => 'Add organisation', 'columns' => 2,
                    'fields' => [
                        'key'                 => ['type' => 'text', 'label' => 'Key (technical id)'],
                        'name'                => ['type' => 'text', 'label' => 'Name'],
                        'full_name'           => ['type' => 'text', 'label' => 'Full name'],
                        'is_dialogue'         => ['type' => 'boolean', 'label' => 'Dialogue partner'],
                        'is_observer'         => ['type' => 'boolean', 'label' => 'Observer'],
                        'dialogue_since'      => ['type' => 'text', 'label' => 'Dialogue partner since (year)'],
                        'observer_since'      => ['type' => 'text', 'label' => 'Observer since (year)'],
                        'observer_conference' => ['type' => 'text', 'label' => 'Observer admitted at conference'],
                        'badge'               => ['type' => 'text', 'label' => 'Badge: flag emoji, or a Material Symbols icon name for organisations'],
                        'website'             => ['type' => 'url', 'label' => 'Website (empty = none)'],
                        'q'                   => ['type' => 'text', 'label' => 'News search keyword for "See Activities"'],
                        'desc'                => ['type' => 'textarea', 'label' => 'Description (dialogue partners)', 'rows' => 3],
                    ],
                    'default' => $dialogue,
                ],
            ]],
        ],
    ],
];
