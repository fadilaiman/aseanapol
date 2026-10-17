<?php

/**
 * CMS registry — group "members" (Member Countries).
 *
 * Page keys:  members.index   -> list page (information/index.blade.php)
 *             members.labels  -> static labels shared by every country page
 *             members.<slug>  -> one page per country (information/member.blade.php), slug = brunei, cambodia, ...
 *
 * The 11 country pages share ONE schema built by $buildCountry(); only the DEFAULT values differ.
 * (The defaults below are the original arrays that used to live in PageController::member*().)
 *
 * Blocks that a country has no default content for (e.g. History for Lao) still get their
 * repeater so an admin can add content; they only get a "show/hide" toggle once they have default rows.
 */

/* ---------------------------------------------------------------- per-country defaults */
$countries = [
    'brunei' => [
        'country' => 'Brunei Darussalam',
        'description' => 'The Royal Brunei Police Force (RBPF) is the national police force of Brunei Darussalam, responsible for maintaining law and order, preventing and detecting crime, and safeguarding the peace and security of the nation.',
        'chief_name' => 'Yang Mulia Dato Paduka Seri Awang Sulaiman Alidin',
        'chief_rank' => 'Commissioner of Police',
        'chief_designation' => 'Commissioner of Police',
        'chief_photo' => 'media/chiefs/brunei.jpg',
        'history' => [
            ['year' => '1906', 'text' => 'The signing of the 1906 treaty between Brunei Darussalam and the United Kingdom laid the foundation of a new government with the formal introduction of the British Resident. The first officers to police Brunei comprised one Pathan and one Sikh, later supplemented by Sikhs seconded from the Straits Settlement government in Labuan, who were eventually replaced by local Malays.'],
            ['year' => '1909', 'text' => 'Sergeant Crummy was appointed as the first Chief Police Officer of Brunei Darussalam.'],
            ['year' => '1917', 'text' => 'The Brunei Police Force was founded under G McAfee, who also continued as Chief Police Officer for Labuan. Chief Inspector McAfee was appointed as the Brunei CPO in 1917.'],
            ['year' => '1921', 'text' => 'The Brunei Police was officially formed on January 1, 1921 immediately after the 1920 Police Enactment was passed. The local force took over all responsibilities from the Police Force of the Straits Settlement.'],
            ['year' => '1950', 'text' => 'The Police headquarters was moved to Kuala Belait and the CPO became answerable to the CP of Sarawak.'],
            ['year' => '1959', 'text' => 'With the introduction of Brunei\'s own Constitution, the sultanate established its own Commissioner of Police, no longer required to report to the CP of Sarawak.'],
        ],
        'contacts' => [
            ['name' => 'ASP Ahmad Fuad bin Haji Puteh', 'designation' => 'Head of International Affairs, INTERPOL & ASEANAPOL Unit', 'mobile' => '+6738767477', 'email' => 'fuad.puteh@police.gov.bn', 'photo' => 'media/contacts/brunei-2026.png'],
        ],
    ],
    'cambodia' => [
        'country' => 'Cambodia',
        'description' => 'The Cambodian National Police (CNP) is the national police organisation of the Kingdom of Cambodia under the Ministry of Interior, responsible for maintaining public order, combating crime, and ensuring national security.',
        'chief_name' => 'Sar Theth',
        'chief_rank' => 'Police General',
        'chief_designation' => 'Commissioner General',
        'chief_photo' => 'media/chiefs/cambodia.jpg',
        'history' => [
            ['year' => '1884', 'text' => 'The Phnom Penh police force was fully equipped with arms.'],
            ['year' => '1885', 'text' => 'Port police was established for ship inspection.'],
            ['year' => '1903', 'text' => 'Administrative and Judicial Police were organised.'],
            ['year' => '1905', 'text' => 'Maritime Police was established.'],
            ['year' => '1925', 'text' => '"Commune Patrols" or Rural Police were established.'],
            ['year' => '1945', 'text' => 'The National Police was officially established by Royal Decree (Royal Krom No. 35), with May 16, 1945 designated as the official founding date.'],
            ['year' => '1953', 'text' => 'France transferred the police institution to the Royal Government following the Franco-Khmer Treaty recognising Cambodia\'s independence.'],
            ['year' => '1979', 'text' => 'The Cambodian People\'s Police Force was re-established following the end of the Khmer Rouge period, with 45 personnel at ministry level.'],
            ['year' => '1992–1993', 'text' => 'During UNTAC operations, the police provided security for 360,000 repatriated refugees and ensured election security.'],
            ['year' => '2007', 'text' => 'Verification process completed; the actual force numbered approximately 52,000 officers nationwide.'],
        ],
        'contacts' => [
            ['name' => 'Pol. Brig. Gen You Boren', 'designation' => 'Head of NCB Phnom Penh and Senior Official in Charge of ASEANAPOL', 'mobile' => '+855 89 66 70 70', 'email' => 'borenyou2959@gmail.com', 'photo' => 'media/contacts/cambodia.png'],
        ],
    ],
    'indonesia' => [
        'country' => 'Indonesia',
        'description' => 'The Indonesian National Police (INP) is the national police force of the Republic of Indonesia, responsible for law enforcement, maintaining public order, and combating crime throughout the archipelago.',
        'chief_name' => 'Suyudi Ario Seto',
        'chief_rank' => 'Police General',
        'chief_designation' => 'Chief of Police',
        'chief_photo' => 'media/chiefs/indonesia-2026.jpg',
        'history' => [
            ['year' => '1946', 'text' => 'The Indonesian police was established following independence. Police units fought in the National Revolution against Dutch colonial forces and helped suppress the communist revolt in Madiun.'],
            ['year' => '1960s', 'text' => 'The police were brought under the control of the Armed Forces Chief during this period.'],
            ['year' => '1999', 'text' => 'Following major political reforms, the Indonesian National Police formally separated from the military in April 1999.'],
            ['year' => '2000', 'text' => 'The separation from the military was formally completed, establishing the INP as a fully independent law enforcement agency headquartered in Kebayoran Baru, South Jakarta.'],
            ['year' => '2001', 'text' => 'The force transitioned to British-style ranks in subsequent organisational reforms.'],
        ],
        'contacts' => [
            ['name' => 'Pol. Snr Supt Dhany Aryanda', 'designation' => 'Head of International Conference Section', 'mobile' => '+6281809210808', 'email' => 'internationalconferencesection@gmail.com', 'photo' => 'media/contacts/indonesia.png'],
        ],
    ],
    'lao' => [
        'country' => 'Lao PDR',
        'description' => 'The Lao Police Force is the national law enforcement agency of the Lao People\'s Democratic Republic, operating under the Ministry of Public Security to maintain peace, order, and national security.',
        'chief_name' => 'Kongsavat Bounlieng',
        'chief_rank' => 'Police Brigadier General',
        'chief_designation' => 'Chief of Police',
        'chief_photo' => 'media/chiefs/lao.jpg',
        'contacts' => [
            ['name' => 'Police Colonel Vanhpheng Phommixay', 'designation' => 'Director of Interpol Department, NCB Vientiane', 'mobile' => '+856 20 57785792', 'email' => 'ncb_vientiane@lpf.gov.la', 'photo' => 'media/contacts/lao.png'],
        ],
    ],
    'malaysia' => [
        'country' => 'Malaysia',
        'description' => 'The Royal Malaysia Police (RMP) or Polis DiRaja Malaysia (PDRM) is the national police force of Malaysia, host of the ASEANAPOL Permanent Secretariat, and one of the founding members of ASEANAPOL.',
        'chief_name' => 'Tan Sri Dato\' Seri Hj. Mohd Khalid Hj. Ismail',
        'chief_rank' => 'Inspector General',
        'chief_designation' => 'Inspector General of Police',
        'chief_photo' => 'media/chiefs/malaysia.jpg',
        'history' => [
            ['year' => '15th Century', 'text' => 'Policing in Malaysia traces its origins to the era of the Malacca Sultanate, where law enforcement was carried out through the institutions of the Temenggung and Hulubalang. The Temenggung functioned similarly to a modern police chief, overseeing public order, arresting criminals, and maintaining internal security.'],
            ['year' => '25 Mar 1807', 'text' => 'Modern policing officially began following the introduction of the Charter of Justice in Penang under British colonial administration. This marked the establishment of the first formal police organisation in Malaya and is recognised today as the foundation date of the Royal Malaysia Police.'],
            ['year' => '1896', 'text' => 'Separate police forces in Selangor, Perak, Negeri Sembilan, and Pahang were consolidated into the Federated Malay States Police Force, with headquarters established at Bukit Aman, Kuala Lumpur.'],
            ['year' => '1940s', 'text' => 'Following the Japanese occupation during World War II, the British reorganised the fragmented police system into the Civil Affairs Police Force to restore public order.'],
            ['year' => '1948–1960', 'text' => 'During the Malayan Emergency, the police played a crucial role in counterinsurgency operations against the Malayan Communist Party, working closely with military and civil authorities under the Briggs Plan. The Emergency period significantly transformed the force into a professional and intelligence-oriented security institution.'],
            ['year' => '24 Jul 1958', 'text' => 'Yang di-Pertuan Agong Tuanku Abdul Rahman officially bestowed the title "Royal" upon the Federation of Malaya Police Force in recognition of its outstanding service during the Emergency. The organisation thereafter became known as the Royal Federation of Malaya Police.'],
            ['year' => '1963', 'text' => 'The formation of Malaysia marked a major milestone when the Royal Federation of Malaya Police merged with the North Borneo Police Force and the Sarawak Constabulary to form the present-day Royal Malaysia Police (RMP).'],
        ],
        'official_positions' => [
            ['i' => 'Inspector-General of Police'],
            ['i' => 'Deputy Inspector-General of Police'],
            ['i' => 'Director of Internal Security and Public Order Department (Commissioner of Police)'],
            ['i' => 'Director of Criminal Investigation Department (Commissioner of Police)'],
            ['i' => 'Director of Commercial Crime Investigation Department (Commissioner of Police)'],
            ['i' => 'Director of Narcotics Investigation Department (Commissioner of Police)'],
            ['i' => 'Director of Traffic Investigation and Enforcement Department (Commissioner of Police)'],
            ['i' => 'Director of Special Branch (Commissioner of Police)'],
            ['i' => 'Director of Management (Commissioner of Police)'],
            ['i' => 'Director of Crime Prevention and Community Safety Department (Commissioner of Police)'],
            ['i' => 'Director of Integrity and Standards Compliance Department (Commissioner of Police)'],
            ['i' => 'Director of Logistics and Technology Department (Commissioner of Police)'],
        ],
        'rank_structure' => [
            ['rank' => 'Inspector-General of Police', 'abbreviation' => 'IGP'],
            ['rank' => 'Deputy Inspector-General of Police', 'abbreviation' => 'DIG'],
            ['rank' => 'Commissioner of Police', 'abbreviation' => 'CP'],
            ['rank' => 'Deputy Commissioner of Police', 'abbreviation' => 'DCP'],
            ['rank' => 'Senior Assistant Commissioner of Police', 'abbreviation' => 'SAC'],
            ['rank' => 'Assistant Commissioner of Police', 'abbreviation' => 'ACP'],
            ['rank' => 'Superintendent of Police', 'abbreviation' => 'SUPT'],
            ['rank' => 'Deputy Superintendent of Police', 'abbreviation' => 'DSP'],
            ['rank' => 'Assistant Superintendent of Police', 'abbreviation' => 'ASP'],
            ['rank' => 'Inspector', 'abbreviation' => 'INSP'],
            ['rank' => 'Probationary Inspector', 'abbreviation' => 'P/INSP'],
            ['rank' => 'Sub-Inspector', 'abbreviation' => 'SI'],
            ['rank' => 'Sergeant Major', 'abbreviation' => 'SM'],
            ['rank' => 'Sergeant', 'abbreviation' => 'SGT'],
            ['rank' => 'Corporal', 'abbreviation' => 'CPL'],
            ['rank' => 'Lance Corporal', 'abbreviation' => 'L/CPL'],
            ['rank' => 'Police Constable', 'abbreviation' => 'CONST'],
        ],
        'contacts' => [
            ['name' => 'Asst. Commissioner of Police Loh Pey Pey', 'designation' => 'ACP International Relations, IGP\'s Secretariat', 'mobile' => '019-3577008', 'email' => 'intercorp@rmp.gov.my', 'photo' => 'media/contacts/malaysia.png'],
        ],
    ],
    'myanmar' => [
        'country' => 'Myanmar',
        'description' => 'The Myanmar Police Force is the national police organisation of the Republic of the Union of Myanmar, responsible for maintaining law and order, preventing crime, and ensuring public safety.',
        'chief_name' => 'Soe Hlaing',
        'chief_rank' => 'Lieutenant General',
        'chief_designation' => 'Chief of Police',
        'chief_photo' => 'media/chiefs/myanmar.jpg',
        'history' => [
            ['year' => '1885', 'text' => 'The British policing system was introduced when Myanmar became a British Colony. The force was known as the Burma Police.'],
            ['year' => '1964', 'text' => 'The Burma Police was reorganised as the People\'s Police Force following independence.'],
            ['year' => '1994', 'text' => 'On January 28, the Committee for Reorganisation of the Police Administration System was formed, presided over by the Secretary of the State Peace and Development Council.'],
            ['year' => '1995', 'text' => 'On October 1, the force was reorganised and established under its current name, the Myanmar Police Force.'],
        ],
        'contacts' => [
            ['name' => 'Police Lieutenant Colonel Myo Thant Tin', 'designation' => 'Head of Interpol and ASEANAPOL Affairs Branch', 'mobile' => '+959420706812', 'email' => 'myothanttin.mpf@gmail.com', 'photo' => 'media/contacts/myanmar.png'],
            ['name' => 'Police Captain Aung Ko', 'designation' => 'Head of Interpol & ASEANAPOL Affairs Section', 'mobile' => '+95943154650', 'email' => 'pslyekoko.007@gmail.com', 'photo' => 'media/contacts/myanmar-aung-ko.jpg'],
        ],
    ],
    'philippines' => [
        'country' => 'Philippines',
        'description' => 'The Philippine National Police (PNP) is the national police force of the Philippines, mandated to enforce the law, prevent and control crimes, maintain peace and order, and ensure public safety.',
        'chief_name' => 'Jose Melencio C. Nartatez Jr.',
        'chief_rank' => 'Police General',
        'chief_designation' => 'Chief of Police',
        'chief_photo' => 'media/chiefs/philippines.jpg',
        'history' => [
            ['year' => '1991', 'text' => 'The Philippine National Police was activated on January 29, 1991 through the merger of the Philippine Constabulary and the Integrated National Police, pursuant to Republic Act 6975. Headquarters are located at Camp Crame, Quezon City.'],
        ],
        'contacts' => [
            ['name' => 'Police Colonel Angelyn C. Salvador', 'designation' => 'Acting Chief, International Affairs and Inter-Agency Division, Directorate for Plans', 'mobile' => '+639190007074', 'email' => 'iaiad.dpl.pnp@gmail.com', 'photo' => 'media/contacts/philippines.png'],
        ],
    ],
    'singapore' => [
        'country' => 'Singapore',
        'description' => 'The Singapore Police Force (SPF) is the national police force of the Republic of Singapore, responsible for maintaining law and order, preventing crime, and ensuring the safety and security of Singapore.',
        'chief_name' => 'How Kwang Hwee',
        'chief_rank' => 'Commissioner of Police',
        'chief_designation' => 'Commissioner of Police',
        'chief_photo' => 'media/chiefs/singapore.jpg',
        'history' => [
            ['year' => '1819', 'text' => 'With the establishment of Singapore as a trading post, the first police team comprised one jemadar (Asian Sergeant), 12 peons (patrolmen) and a jailor.'],
            ['year' => '1826', 'text' => 'Singapore merged with Malacca and Penang to form the Straits Settlements; the SPF became part of the Straits Settlements Police Force.'],
            ['year' => '1856', 'text' => 'The Police Act was passed and Thomas Dunman became the first full-time Commissioner of Police.'],
            ['year' => '1862', 'text' => 'The Detective Branch was established as a precursor to the Criminal Investigation Department, to investigate secret societies and violent crimes.'],
            ['year' => '1866', 'text' => 'The Marine Police was established to address piracy.'],
            ['year' => '1918', 'text' => 'The Criminal Intelligence Department was created to counter seditious activities; the Traffic Police was also started this year.'],
            ['year' => '1949', 'text' => 'The Gurkha Contingent was established; the first batch of 10 female constables was recruited.'],
            ['year' => '1969', 'text' => 'Academy status was granted to the Police Training School.'],
            ['year' => '1975', 'text' => 'Full-time Police National Service was introduced and the Scene of Crime Unit was established.'],
            ['year' => '1983', 'text' => 'The Neighbourhood Police Post system was implemented, bringing policing closer to the community.'],
            ['year' => '1996', 'text' => 'The Neighbourhood Police Centre concept was launched, providing 24-hour, one-stop police service.'],
            ['year' => '2002', 'text' => 'The Singapore Police Force received the Singapore Quality Award (SQA).'],
            ['year' => '2007', 'text' => 'The SPF was awarded the Singapore Quality Award with Special Commendation on October 9.'],
        ],
        'contacts' => [
            ['name' => 'Superintendent Ivan Ler', 'designation' => 'Assistant Director, International Policing (ASEAN & Middle East) & Protocol, International Cooperation Department', 'mobile' => '+65 9299 0175', 'email' => 'ivan_ler@spf.gov.sg', 'photo' => 'media/contacts/singapore-ivan.png'],
            ['name' => 'Deputy Superintendent Julian Teu', 'designation' => 'Desk Officer (ASEAN, ASEANAPOL & Middle East), International Cooperation Department', 'mobile' => '', 'email' => '', 'photo' => 'media/contacts/singapore-julian.png'],
            ['name' => 'Assistant Superintendent Mila Alyssa', 'designation' => 'Desk Officer (ASEAN, ASEANAPOL & Middle East), International Cooperation Department', 'mobile' => '', 'email' => '', 'photo' => 'media/contacts/singapore-mila.png'],
        ],
    ],
    'thailand' => [
        'country' => 'Thailand',
        'description' => 'Royal Thai Police is a centralised national law enforcement agency with approximately 230,000 personnel, responsible for maintaining public order, preventing and suppressing crime, and safeguarding national security throughout the Kingdom of Thailand under the supervision of the National Police Policy Board.',
        'chief_name' => 'Kittharath Punpetch',
        'chief_rank' => 'Police General',
        'chief_designation' => 'Commissioner General',
        'chief_photo' => 'media/chiefs/thailand.jpg',
        'history' => [
            ['year' => '15th Century', 'text' => 'Royal Thai Police traces its origins back to the Ayutthaya Kingdom, gradually evolving from a traditional local policing system into a modern national law enforcement organisation.'],
            ['year' => '1860', 'text' => 'King Rama IV established a modern police force in Bangkok to address increasing criminal activities, with guidance from British advisor Captain Joseph Byrd Ames.'],
            ['year' => '1915', 'text' => 'The provincial and urban police forces were centralised under the Ministry of Interior, strengthening administrative control and coordination.'],
            ['year' => '1932', 'text' => 'Following the Siamese Revolution, the police organisation underwent further modernisation and adopted a semi-military structure, playing important roles in internal security and national administration.'],
            ['year' => '1936', 'text' => 'The police institution further expanded its operational capabilities and organisational influence within the national security framework.'],
            ['year' => '1998', 'text' => 'The Police Department was separated from the Ministry of Interior and reorganised as Royal Thai Police under the direct supervision of the Prime Minister, in accordance with the Royal Thai Police Act. This restructuring aimed to improve operational independence and administrative efficiency.'],
        ],
        'org_chart' => 'media/documents/rtp-org-chart.pdf',
        'official_positions' => [
            ['i' => 'Commissioner General'],
            ['i' => 'Senior Inspector General', 'ii' => 'Deputy Commissioner General'],
            ['i' => 'Assistant Commissioner General'],
            ['i' => 'Commissioner'],
            ['i' => 'Deputy Commissioner'],
            ['i' => 'Commander', 'ii' => 'Inquiry Official, Senior Expert Level'],
            ['i' => 'Deputy Commander', 'ii' => 'Inquiry Official, Expert Level'],
            ['i' => 'Superintendent', 'ii' => 'Inquiry Official, Advisory Level'],
            ['i' => 'Deputy Superintendent', 'ii' => 'Inquiry Official, Senior Professional Level'],
            ['i' => 'Inspector', 'ii' => 'Inquiry Official, Professional Level'],
            ['i' => 'Deputy Inspector', 'ii' => 'Inquiry Official'],
            ['i' => 'Squad Leader'],
            ['i' => 'Police Trainee'],
        ],
        'rank_structure' => [
            ['rank' => 'Police General', 'abbreviation' => 'Pol.Gen.'],
            ['rank' => 'Police Lieutenant General', 'abbreviation' => 'Pol.Lt.Gen.'],
            ['rank' => 'Police Major General', 'abbreviation' => 'Pol.Maj.Gen.'],
            ['rank' => 'Police Colonel', 'abbreviation' => 'Pol.Col.'],
            ['rank' => 'Police Lieutenant Colonel', 'abbreviation' => 'Pol.Lt.Col.'],
            ['rank' => 'Police Major', 'abbreviation' => 'Pol.Maj.'],
            ['rank' => 'Police Captain', 'abbreviation' => 'Pol.Capt.'],
            ['rank' => 'Police Lieutenant', 'abbreviation' => 'Pol.Lt.'],
            ['rank' => 'Police Sub-Lieutenant', 'abbreviation' => 'Pol.Sub-Lt.'],
            ['rank' => 'Police Senior Sergeant Major', 'abbreviation' => 'Pol.Snr.Sgt.Maj.'],
            ['rank' => 'Police Sergeant Major', 'abbreviation' => 'Pol.Sgt.Maj.'],
            ['rank' => 'Police Sergeant', 'abbreviation' => 'Pol.Sgt.'],
            ['rank' => 'Police Corporal', 'abbreviation' => 'Pol.Cpl.'],
            ['rank' => 'Police Lance Corporal', 'abbreviation' => 'Pol.L/Cpl.'],
        ],
        'contacts' => [
            ['name' => 'Pol. Lt. Col. Jarasluk La-Inchua', 'designation' => 'Inspector of Interpol and Foreign Relations Region 1', 'mobile' => '+66 85 171 4949', 'email' => 'gingenejl@gmail.com', 'photo' => 'media/contacts/thailand.png'],
        ],
    ],
    'vietnam' => [
        'country' => 'Viet Nam',
        'description' => 'The Office of Investigation Police Agency, Vietnam Police Force (OIPA, VPF) is the designated ASEANAPOL contact body for the Socialist Republic of Viet Nam, responsible for criminal investigation cooperation and transnational crime enforcement.',
        'chief_name' => 'Tran Minh Tien',
        'chief_rank' => 'Police Major General',
        'chief_designation' => 'Chief of Police',
        'chief_photo' => 'media/chiefs/vietnam.jpg',
        'history' => [
            ['year' => '1930', 'text' => 'The Communist Party of Viet Nam was founded, and \'Red Safeguard\' teams emerged as predecessors to the People\'s Police Force (PPF).'],
            ['year' => '1945', 'text' => 'On August 15, the first organisation of the PPF was formed after the success of the August Revolution. On September 2, the Democratic Republic of Vietnam\'s Independence Day was protected by the new security forces.'],
            ['year' => '1946', 'text' => 'On February 21, President Ho Chi Minh signed Decree 23, merging Police Departments into the Vietnam Public Security Service (VNPSS).'],
            ['year' => '1953', 'text' => 'The Sub-Ministry of Public Security was elevated to a full Ministry of Public Security in August.'],
            ['year' => '1956', 'text' => 'The Central Committee issued Directive 30, formally establishing the People\'s Police Force.'],
            ['year' => '1995', 'text' => 'The Vietnam Interpol Office was created.'],
            ['year' => '1997', 'text' => 'The Department of Drug Crime Investigation was founded.'],
            ['year' => '2003', 'text' => 'The force received the Gold Star Order; the Truong Van Cam organised crime case was successfully investigated.'],
            ['year' => '2007', 'text' => 'The Hero of People\'s Armed Forces honour was awarded; the Department of Environmental Police was created.'],
            ['year' => '2010', 'text' => 'Departments of High-Tech Crime Investigation and Fugitive Pursuit were established.'],
        ],
        'contacts' => [
            ['name' => 'Snr. Lt. Col. Vu Thi Thu Hanh', 'designation' => 'Deputy Head of Division', 'mobile' => '+84 983030328', 'email' => 'hanhdanhen@gmail.com', 'photo' => 'media/contacts/vietnam.png'],
        ],
    ],
    'timor-leste' => [
        'country' => 'Timor-Leste',
        'description' => 'The National Police of Timor-Leste (Polícia Nacional de Timor-Leste / PNTL) is the national police force of the Democratic Republic of Timor-Leste. Timor-Leste has been an ASEANAPOL Observer since the 34th ASEANAPOL Conference (2014) and became ASEAN\'s 11th member state in October 2025.',
        'observer_note' => 'Timor-Leste joined ASEAN as the 11th member state in October 2025. Their ASEANAPOL membership status is currently transitioning from Observer to full member.',
        'chief_name' => 'Afonso Dos Santos',
        'chief_rank' => 'Assistant Commissioner',
        'chief_designation' => 'Chief of Police',
        'chief_photo' => 'media/chiefs/timor-leste-2026.png',
        'contacts' => [
            ['name' => 'Police Supt. Augusto Tilman Da Costa', 'designation' => 'Chief', 'mobile' => '+67078299444', 'email' => 'Augusto.tilman@pntl.tl', 'photo' => 'media/contacts/timor-leste-contact.png'],
        ],
    ],
];

/* ---------------------------------------------------------------- shared country schema */
$buildCountry = function (string $slug, array $d): array {
    $rows = fn (string $k) => $d[$k] ?? [];
    // a show/hide switch only makes sense for blocks that have default content
    $toggle = fn (string $k) => !empty($d[$k]);

    return [
        'label' => $d['country'],
        'route' => 'about.member.' . $slug,
        'sections' => [
            'identity' => [
                'label'   => 'Header badge & status note',
                'help'    => 'Country name, police force, abbreviation, flag code, crest, website, e-mail, phone and fax are edited ONCE in Shared facts > Member countries (admin: "Shared facts (used across pages)") and appear here, on the Member Countries list, the contact directory and the landing page automatically.',
                'columns' => 2,
                'fields'  => [
                    'badge_label'  => ['type' => 'text',  'label' => 'Badge above the force name', 'default' => isset($d['observer_note']) ? 'Newest Member Country' : 'Member Country'],
                    'note'         => ['type' => 'textarea', 'label' => 'Status note (optional)', 'rows' => 3, 'help' => 'Highlighted notice under the force name. Leave empty for no notice.', 'default' => $d['observer_note'] ?? ''],
                ],
            ],
            'overview' => [
                'label'  => 'Overview',
                'fields' => [
                    'description' => ['type' => 'textarea', 'label' => 'Description', 'rows' => 5, 'default' => $d['description']],
                ],
            ],
            'history' => [
                'label'  => 'History timeline',
                'toggle' => $toggle('history'),
                'help'   => 'Leave the list empty to hide the History block.',
                'fields' => [
                    'items' => [
                        'type' => 'repeater', 'label' => 'Timeline entries', 'item_label' => 'year', 'add_label' => 'Add timeline entry',
                        'fields' => [
                            'year' => ['type' => 'text',     'label' => 'Year / date'],
                            'text' => ['type' => 'textarea', 'label' => 'Text', 'rows' => 3],
                        ],
                        'default' => $rows('history'),
                    ],
                ],
            ],
            'chief' => [
                'label'   => 'Chief of Police',
                'help'    => 'Leave the name empty to hide this card.',
                'columns' => 2,
                'fields'  => [
                    'name'        => ['type' => 'text',  'label' => 'Name', 'default' => $d['chief_name']],
                    'rank'        => ['type' => 'text',  'label' => 'Rank', 'default' => $d['chief_rank']],
                    'designation' => ['type' => 'text',  'label' => 'Designation', 'default' => $d['chief_designation']],
                    'photo'       => ['type' => 'image', 'label' => 'Photo', 'default' => $d['chief_photo']],
                ],
            ],
            'org_chart' => [
                'label'  => 'Police Structure: organisational chart',
                'toggle' => $toggle('org_chart'),
                'help'   => 'Link to a PDF organisational chart (leave empty for none).',
                'fields' => [
                    'file' => ['type' => 'file', 'label' => 'Organisational chart (PDF)', 'default' => $d['org_chart'] ?? ''],
                ],
            ],
            'positions' => [
                'label'  => 'Police Structure: official positions',
                'toggle' => $toggle('official_positions'),
                'help'   => 'Numbered list. Use the second line for a "(ii)" alternative title.',
                'fields' => [
                    'items' => [
                        'type' => 'repeater', 'label' => 'Official positions', 'item_label' => 'i', 'add_label' => 'Add position',
                        'fields' => [
                            'i'  => ['type' => 'text', 'label' => 'Position (i)'],
                            'ii' => ['type' => 'text', 'label' => 'Alternative (ii), optional'],
                        ],
                        'default' => $rows('official_positions'),
                    ],
                ],
            ],
            'ranks' => [
                'label'  => 'Rank structure',
                'toggle' => $toggle('rank_structure'),
                'help'   => 'Leave the list empty to show the "pending" placeholder.',
                'fields' => [
                    'items' => [
                        'type' => 'repeater', 'label' => 'Ranks (highest first)', 'item_label' => 'rank', 'add_label' => 'Add rank', 'columns' => 2,
                        'fields' => [
                            'rank'         => ['type' => 'text', 'label' => 'Rank'],
                            'abbreviation' => ['type' => 'text', 'label' => 'Abbreviation'],
                        ],
                        'default' => $rows('rank_structure'),
                    ],
                ],
            ],
            'contacts' => [
                'label'  => 'Contact persons',
                'toggle' => $toggle('contacts'),
                'help'   => 'One card per person. Empty phone / email lines are hidden.',
                'fields' => [
                    'items' => [
                        'type' => 'repeater', 'label' => 'Contact persons', 'item_label' => 'name', 'add_label' => 'Add contact person', 'columns' => 2,
                        'fields' => [
                            'name'        => ['type' => 'text',  'label' => 'Name'],
                            'designation' => ['type' => 'text',  'label' => 'Designation'],
                            'mobile'      => ['type' => 'text',  'label' => 'Mobile'],
                            'email'       => ['type' => 'text',  'label' => 'Email'],
                            'photo'       => ['type' => 'image', 'label' => 'Photo'],
                        ],
                        'default' => $rows('contacts'),
                    ],
                ],
            ],
        ],
    ];
};

$indexSections = [
    'hero' => [
        'label'  => 'Page header',
        'fields' => [
            'title'    => ['type' => 'text',     'label' => 'Title',    'default' => 'Member Countries'],
            'subtitle' => ['type' => 'textarea', 'label' => 'Subtitle', 'rows' => 2, 'default' => 'The {count} national police forces of the ASEAN member states.'],
        ],
    ],
    'intro' => [
        'label'  => 'Member countries intro',
        'help'   => 'The cards below are generated from Shared facts > Member countries (country, force, abbreviation, flag, member-since year, corner badge, order; hiding a member there removes its card). Wording may use {count} (e.g. "eleven"), {Count} ("Eleven") and {count_num} ("11"), taken from Shared facts > Organisation > Counts.',
        'fields' => [
            'badge'   => ['type' => 'text',     'label' => 'Badge text', 'default' => 'Member Countries'],
            'heading' => ['type' => 'text',     'label' => 'Heading',    'default' => '{Count} ASEAN Member States'],
            'text'    => ['type' => 'textarea', 'label' => 'Intro text', 'rows' => 2, 'default' => 'Click on a country to view contact details and more information about each member police force.'],
        ],
    ],
    'card_labels' => [
        'label'   => 'Card labels',
        'columns' => 2,
        'fields'  => [
            'since' => ['type' => 'text', 'label' => '"Since" prefix', 'default' => 'Since'],
            'view'  => ['type' => 'text', 'label' => 'Link text', 'default' => 'View'],
        ],
    ],
];
$indexSections['partners'] = [
    'label'   => 'Dialogue Partners & Observers block',
    'columns' => 2,
    'fields'  => [
        'badge'          => ['type' => 'text',     'label' => 'Badge text', 'default' => 'Partners'],
        'heading'        => ['type' => 'text',     'label' => 'Heading',    'default' => 'Dialogue Partners & Observers'],
        'dialogue_title' => ['type' => 'text',     'label' => 'Dialogue Partners card: title', 'default' => 'Dialogue Partners'],
        'observer_title' => ['type' => 'text',     'label' => 'Observers card: title',         'default' => 'Observers'],
        'dialogue_text'  => ['type' => 'textarea', 'label' => 'Dialogue Partners card: text',  'rows' => 3, 'default' => 'International law enforcement organisations with formal dialogue status with ASEANAPOL.'],
        'observer_text'  => ['type' => 'textarea', 'label' => 'Observers card: text',          'rows' => 3, 'default' => 'Organisations and countries with observer status participating in ASEANAPOL activities.'],
        'view'           => ['type' => 'text',     'label' => 'Card link text', 'default' => 'View'],
    ],
];

/* ---------------------------------------------------------------- shared labels (country pages) */
$labelSections = [
    'general' => [
        'label'   => 'Page labels',
        'columns' => 2,
        'fields'  => [
            'overview_heading' => ['type' => 'text', 'label' => 'Overview heading', 'default' => 'Overview'],
            'history_heading'  => ['type' => 'text', 'label' => 'History heading', 'default' => 'History'],
            'detailed_heading' => ['type' => 'text', 'label' => 'Detailed information heading', 'default' => 'Detailed Information'],
            'back_label'       => ['type' => 'text', 'label' => 'Back link text', 'default' => 'Back to Member Countries'],
            'membership_note'  => ['type' => 'textarea', 'label' => 'Membership note', 'rows' => 3, 'help' => 'Use {country} where the country name should appear and {count} for the number of member states (from Shared facts).', 'default' => '{country} is one of the {count} member states of ASEANAPOL, contributing to the collective mission of enhancing regional police cooperation and combating transnational crime in Southeast Asia.'],
        ],
    ],
    'cards' => [
        'label'   => 'Card captions',
        'columns' => 2,
        'fields'  => [
            'chief_label'   => ['type' => 'text', 'label' => 'Chief of Police caption', 'default' => 'Chief of Police'],
            'contact_label' => ['type' => 'text', 'label' => 'Contact person caption', 'default' => 'Contact Person'],
        ],
    ],
    'structure' => [
        'label'   => 'Police structure & ranks',
        'columns' => 2,
        'fields'  => [
            'structure_heading' => ['type' => 'text', 'label' => 'Police structure heading', 'default' => 'Police Structure'],
            'org_chart_link'    => ['type' => 'text', 'label' => 'Organisational chart link text', 'default' => 'View Organisational Chart (PDF)'],
            'positions_heading' => ['type' => 'text', 'label' => 'Official positions heading', 'default' => 'Official Positions'],
            'ranks_heading'     => ['type' => 'text', 'label' => 'Rank structure heading', 'default' => 'Rank Structure'],
            'col_rank'          => ['type' => 'text', 'label' => 'Rank column header', 'default' => 'Rank'],
            'col_abbreviation'  => ['type' => 'text', 'label' => 'Abbreviation column header', 'default' => 'Abbreviation'],
        ],
    ],
    'sidebar' => [
        'label'  => 'Sidebar',
        'fields' => [
            'contact_heading' => ['type' => 'text', 'label' => 'Contact box heading', 'default' => 'ASEANAPOL Contact'],
        ],
    ],
];

// Placeholders shown when a country has no data for a block. They only render for such countries,
// so this page has no "view live" link (nothing to preview on a single URL); tested in tests/e2e/members.mjs.
$pendingSections = [
    'placeholders' => [
        'label'   => 'Placeholder wording',
        'columns' => 2,
        'fields'  => [
            'pending_badge'     => ['type' => 'text',     'label' => '"Pending" badge', 'default' => 'Pending'],
            'structure_title'   => ['type' => 'text',     'label' => 'Police structure placeholder: title', 'default' => 'Police Structure'],
            'structure_text'    => ['type' => 'textarea', 'label' => 'Police structure placeholder: text', 'rows' => 2, 'default' => 'Organisational chart and key divisions of the national police force.'],
            'ranks_title'       => ['type' => 'text',     'label' => 'Rank placeholder: title', 'default' => 'Rank & Uniforms'],
            'ranks_text'        => ['type' => 'textarea', 'label' => 'Rank placeholder: text', 'rows' => 2, 'default' => 'Police rank hierarchy, insignia and uniform regulations.'],
            'tel_pending'       => ['type' => 'text',     'label' => 'Telephone placeholder', 'default' => 'Telephone: pending'],
            'email_pending'     => ['type' => 'text',     'label' => 'Email placeholder', 'default' => 'Email: pending'],
        ],
    ],
];

/* ---------------------------------------------------------------- assemble */
$pages = [
    '_group' => ['label' => 'Member Countries', 'icon' => 'flag', 'order' => 12],
    'index'  => ['label' => 'Member Countries (list page)', 'route' => 'about.member-countries', 'sections' => $indexSections],
    'labels' => ['label' => 'Shared labels (all country pages)', 'route' => 'about.member.thailand', 'sections' => $labelSections],
    'labels-pending' => ['label' => 'Shared placeholder wording', 'sections' => $pendingSections],
];
foreach ($countries as $slug => $d) {
    $pages[$slug] = $buildCountry($slug, $d);
}

return $pages;
