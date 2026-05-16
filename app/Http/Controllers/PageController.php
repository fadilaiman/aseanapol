<?php

namespace App\Http\Controllers;

use App\Mail\ContactInquiryMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    // =========================================================
    // ABOUT ASEANAPOL
    // =========================================================

    public function aboutIndex()       { return view('about.index'); }
    public function aboutPermanentSecretariat() { return view('about.permanent-secretariat'); }
    public function aboutGovernance()  { return view('about.governance'); }
    public function aboutObjectivesAndFunctions() { return view('about.objectives-and-functions'); }
    public function aboutChronology()  { return view('about.chronology'); }
    public function aboutLogo()        { return view('about.aseanapol-logo'); }
    public function aboutVisionMission() { return view('about.vision-and-mission'); }

    public function aboutLeadership()
    {
        return view('about.leadership');
    }

    public function aboutLeadershipDps()
    {
        return view('about.leadership-dps');
    }

    public function aboutLeadershipDpp()
    {
        return view('about.leadership-dpp');
    }

    public function aboutObLme()
    {
        return view('about.ob-lme');
    }

    // =========================================================
    // MEMBER COUNTRIES
    // =========================================================

    public function informationIndex()
    {
        return view('information.index');
    }

    public function memberBrunei()
    {
        return $this->memberPage([
            'country'     => 'Brunei Darussalam',
            'force'       => 'Royal Brunei Police Force',
            'abbreviation'=> 'RBPF',
            'iso'         => 'bn',
            'tel'         => ['+673-2459 500', '+673-2423 901 ext: 784/785'],
            'fax'         => null,
            'email'       => 'rbpf.interpol@police.gov.bn',
            'website'     => 'https://www.police.gov.bn',
            'route'       => 'about.member.brunei',
            'description' => 'The Royal Brunei Police Force (RBPF) is the national police force of Brunei Darussalam, responsible for maintaining law and order, preventing and detecting crime, and safeguarding the peace and security of the nation.',
            'chief_name'        => 'Dato Seri Pahlawan Sulaiman bin Alidin',
            'chief_rank'        => 'Commissioner of Police',
            'chief_designation' => 'Commissioner of Police',
            'chief_photo'       => 'media/chiefs/brunei.jpg',
            'logo'              => 'media/police-logo/police-logo/brunei.png',
            'contact_person'    => [
                'name'        => 'ASP Ahmad Fuad bin Haji Puteh',
                'designation' => 'Head of International Affairs, INTERPOL & ASEANAPOL Unit',
                'mobile'      => '+6738767477',
                'email'       => 'fuad.puteh@police.gov.bn',
                'photo'       => 'media/contacts/brunei.png',
            ],
            'history'           => [
                ['year' => '1906', 'text' => 'The signing of the 1906 treaty between Brunei Darussalam and the United Kingdom laid the foundation of a new government with the formal introduction of the British Resident. The first officers to police Brunei comprised one Pathan and one Sikh, later supplemented by Sikhs seconded from the Straits Settlement government in Labuan, who were eventually replaced by local Malays.'],
                ['year' => '1909', 'text' => 'Sergeant Crummy was appointed as the first Chief Police Officer of Brunei Darussalam.'],
                ['year' => '1917', 'text' => 'The Brunei Police Force was founded under G McAfee, who also continued as Chief Police Officer for Labuan. Chief Inspector McAfee was appointed as the Brunei CPO in 1917.'],
                ['year' => '1921', 'text' => 'The Brunei Police was officially formed on January 1, 1921 immediately after the 1920 Police Enactment was passed. The local force took over all responsibilities from the Police Force of the Straits Settlement.'],
                ['year' => '1950', 'text' => 'The Police headquarters was moved to Kuala Belait and the CPO became answerable to the CP of Sarawak.'],
                ['year' => '1959', 'text' => "With the introduction of Brunei's own Constitution, the sultanate established its own Commissioner of Police, no longer required to report to the CP of Sarawak."],
            ],
        ]);
    }

    public function memberCambodia()
    {
        return $this->memberPage([
            'country'     => 'Cambodia',
            'force'       => 'Cambodian National Police',
            'abbreviation'=> 'CNP',
            'iso'         => 'kh',
            'tel'         => null,
            'fax'         => null,
            'email'       => 'camcontactperson@gmail.com',
            'website'     => 'https://www.police.gov.kh',
            'route'       => 'about.member.cambodia',
            'description' => 'The Cambodian National Police (CNP) is the national police organisation of the Kingdom of Cambodia under the Ministry of Interior, responsible for maintaining public order, combating crime, and ensuring national security.',
            'chief_name'        => 'Sar Theth',
            'chief_rank'        => 'Police General',
            'chief_designation' => 'Commissioner General',
            'chief_photo'       => 'media/chiefs/cambodia.jpg',
            'logo'              => 'media/default-album/default-album/cambodian.png',
            'contact_person'    => [
                'name'        => 'Pol. Brig. Gen You Boren',
                'designation' => 'Head of NCB Phnom Penh and Senior Official in Charge of ASEANAPOL',
                'mobile'      => '+855 89 66 70 70',
                'email'       => 'borenyou2959@gmail.com',
                'photo'       => 'media/contacts/cambodia.png',
            ],
            'history'           => [
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
        ]);
    }

    public function memberIndonesia()
    {
        return $this->memberPage([
            'country'     => 'Indonesia',
            'force'       => 'Indonesian National Police',
            'abbreviation'=> 'INP',
            'iso'         => 'id',
            'tel'         => ['+021 739 3650', '+021 721 8467'],
            'fax'         => '+021 720 1402',
            'email'       => 'ncb-jakarta@interpol.go.id',
            'website'     => 'https://www.polri.go.id',
            'route'       => 'about.member.indonesia',
            'description' => 'The Indonesian National Police (INP) is the national police force of the Republic of Indonesia, responsible for law enforcement, maintaining public order, and combating crime throughout the archipelago.',
            'chief_name'        => 'Drs. Listyo Sigit Prabowo, M.Si',
            'chief_rank'        => 'Police General',
            'chief_designation' => 'Chief of Police',
            'chief_photo'       => 'media/chiefs/indonesia.jpg',
            'logo'              => 'media/police-logo/indonesia-polri.png',
            'contact_person'    => [
                'name'        => 'Pol. Snr Supt Dhany Aryanda',
                'designation' => 'Head of International Conference Section',
                'mobile'      => '+6281809210808',
                'email'       => 'internationalconferencesection@gmail.com',
                'photo'       => 'media/contacts/indonesia.png',
            ],
            'history'           => [
                ['year' => '1946', 'text' => 'The Indonesian police was established following independence. Police units fought in the National Revolution against Dutch colonial forces and helped suppress the communist revolt in Madiun.'],
                ['year' => '1960s', 'text' => 'The police were brought under the control of the Armed Forces Chief during this period.'],
                ['year' => '1999', 'text' => 'Following major political reforms, the Indonesian National Police formally separated from the military in April 1999.'],
                ['year' => '2000', 'text' => 'The separation from the military was formally completed, establishing the INP as a fully independent law enforcement agency headquartered in Kebayoran Baru, South Jakarta.'],
                ['year' => '2001', 'text' => 'The force transitioned to British-style ranks in subsequent organisational reforms.'],
            ],
        ]);
    }

    public function memberLao()
    {
        return $this->memberPage([
            'country'     => 'Lao PDR',
            'force'       => 'Lao Police Force',
            'abbreviation'=> 'LPF',
            'iso'         => 'la',
            'tel'         => ['+856 213 16323'],
            'fax'         => '+856 2131 6323',
            'email'       => 'ncbvientiane@gmail.com',
            'website'     => null,
            'route'       => 'about.member.lao',
            'description' => 'The Lao Police Force is the national law enforcement agency of the Lao People\'s Democratic Republic, operating under the Ministry of Public Security to maintain peace, order, and national security.',
            'chief_name'        => 'Kongsavat Bounlieng',
            'chief_rank'        => 'Police Brigadier General',
            'chief_designation' => 'Chief of Police',
            'chief_photo'       => 'media/chiefs/lao.jpg',
            'logo'              => 'media/police-logo/police-logo/lpf.jpg',
            'contact_person'    => [
                'name'        => 'Police Colonel Vanhpheng Phommixay',
                'designation' => 'Director of Interpol Department, NCB Vientiane',
                'mobile'      => '+856 20 57785792',
                'email'       => 'ncb_vientiane@lpf.gov.la',
                'photo'       => 'media/contacts/lao.png',
            ],
        ]);
    }

    public function memberMalaysia()
    {
        return $this->memberPage([
            'country'     => 'Malaysia',
            'force'       => 'Royal Malaysia Police',
            'abbreviation'=> 'RMP',
            'iso'         => 'my',
            'tel'         => ['+603 2266 2222'],
            'fax'         => '+603 2070 7500',
            'email'       => 'rmp@rmp.gov.my',
            'website'     => 'https://www.rmp.gov.my',
            'route'       => 'about.member.malaysia',
            'description' => 'The Royal Malaysia Police (RMP) or Polis DiRaja Malaysia (PDRM) is the national police force of Malaysia, host of the ASEANAPOL Permanent Secretariat, and one of the founding members of ASEANAPOL.',
            'chief_name'        => "Dato' Sri Hj. Mohd Khalid Hj. Ismail",
            'chief_rank'        => 'Inspector General',
            'chief_designation' => 'Inspector General of Police',
            'chief_photo'       => 'media/chiefs/malaysia.jpg',
            'logo'              => 'media/police-logo/police-logo/malaysia.png',
            'contact_person'    => [
                'name'        => 'Asst. Commissioner of Police Loh Pey Pey',
                'designation' => 'ACP International Relations, IGP\'s Secretariat',
                'mobile'      => '019-3577008',
                'email'       => 'intercorp@rmp.gov.my',
                'photo'       => 'media/contacts/malaysia.png',
            ],
            'history'           => [
                ['year' => '1807', 'text' => 'The modern police organisation in Malaysia began after the Charter of Justice in Penang was granted.'],
                ['year' => '1940s', 'text' => 'Following World War II, a single centralised police organisation was established, known as the Civil Affairs Police Force, led by H. B. Longworthy.'],
                ['year' => '1958', 'text' => 'On July 24, the Malaysian King granted the Royal title to the Malayan Federation\'s Police Forces.'],
                ['year' => '1963', 'text' => 'The Royal Federation of Malayan Police (RFMP), the North Borneo Armed Constabulary and Sarawak Constabulary were merged to form the Royal Malaysian Police.'],
                ['year' => '1963–1965', 'text' => 'Police and military forces were engaged during the Malaysia–Indonesia Confrontation, defending against infiltrations in Johor and Sabah.'],
                ['year' => '2005', 'text' => 'The Rakan Cop community outreach programme was launched on August 9.'],
            ],
        ]);
    }

    public function memberMyanmar()
    {
        return $this->memberPage([
            'country'     => 'Myanmar',
            'force'       => 'Myanmar Police Force',
            'abbreviation'=> 'MPF',
            'iso'         => 'mm',
            'tel'         => ['+95 6741 2066'],
            'fax'         => '+95 6741 2188',
            'email'       => 'naypyitaw.ncb@gmail.com',
            'website'     => null,
            'route'       => 'about.member.myanmar',
            'description' => 'The Myanmar Police Force is the national police organisation of the Republic of the Union of Myanmar, responsible for maintaining law and order, preventing crime, and ensuring public safety.',
            'chief_name'        => 'Soe Hlaing',
            'chief_rank'        => 'Major General',
            'chief_designation' => 'Chief of Police',
            'chief_photo'       => null,
            'logo'              => 'media/police-logo/police-logo/myanmar-new-crest-1.png',
            'contact_person'    => [
                'name'        => 'Police Lieutenant Colonel Myo Thant Tin',
                'designation' => 'Head of Interpol and ASEANAPOL Affairs Branch',
                'mobile'      => '+959420706812',
                'email'       => 'myothanttin.mpf@gmail.com',
                'photo'       => 'media/contacts/myanmar.png',
            ],
            'history'           => [
                ['year' => '1885', 'text' => 'The British policing system was introduced when Myanmar became a British Colony. The force was known as the Burma Police.'],
                ['year' => '1964', 'text' => 'The Burma Police was reorganised as the People\'s Police Force following independence.'],
                ['year' => '1994', 'text' => 'On January 28, the Committee for Reorganisation of the Police Administration System was formed, presided over by the Secretary of the State Peace and Development Council.'],
                ['year' => '1995', 'text' => 'On October 1, the force was reorganised and established under its current name, the Myanmar Police Force.'],
            ],
        ]);
    }

    public function memberPhilippines()
    {
        return $this->memberPage([
            'country'     => 'Philippines',
            'force'       => 'Philippine National Police',
            'abbreviation'=> 'PNP',
            'iso'         => 'ph',
            'tel'         => ['+632 8723 0401'],
            'fax'         => null,
            'email'       => 'laiad.dpl@pnp.gov.ph',
            'website'     => 'https://www.pnp.gov.ph',
            'route'       => 'about.member.philippines',
            'description' => 'The Philippine National Police (PNP) is the national police force of the Philippines, mandated to enforce the law, prevent and control crimes, maintain peace and order, and ensure public safety.',
            'chief_name'        => 'Jose Melencio C. Nartatez Jr.',
            'chief_rank'        => 'Police General',
            'chief_designation' => 'Chief of Police',
            'chief_photo'       => 'media/chiefs/philippines.jpg',
            'logo'              => 'media/default-album/default-album/philippines.gif',
            'contact_person'    => [
                'name'        => 'Police Colonel Angelyn C. Salvador',
                'designation' => 'Acting Chief, International Affairs and Inter-Agency Division, Directorate for Plans',
                'mobile'      => '+639190007074',
                'email'       => 'iaiad.dpl.pnp@gmail.com',
                'photo'       => 'media/contacts/philippines.png',
            ],
            'history'           => [
                ['year' => '1991', 'text' => 'The Philippine National Police was activated on January 29, 1991 through the merger of the Philippine Constabulary and the Integrated National Police, pursuant to Republic Act 6975. Headquarters are located at Camp Crame, Quezon City.'],
            ],
        ]);
    }

    public function memberSingapore()
    {
        return $this->memberPage([
            'country'     => 'Singapore',
            'force'       => 'Singapore Police Force',
            'abbreviation'=> 'SPF',
            'iso'         => 'sg',
            'tel'         => ['1800 358 000'],
            'fax'         => '+65 6256 1296',
            'email'       => null,
            'website'     => 'https://www.police.gov.sg',
            'route'       => 'about.member.singapore',
            'description' => 'The Singapore Police Force (SPF) is the national police force of the Republic of Singapore, responsible for maintaining law and order, preventing crime, and ensuring the safety and security of Singapore.',
            'chief_name'        => 'How Kwang Hwee',
            'chief_rank'        => 'Commissioner of Police',
            'chief_designation' => 'Commissioner of Police',
            'chief_photo'       => 'media/chiefs/singapore.jpg',
            'logo'              => 'media/default-album/default-album/singapore.png',
            'contact_person'    => [
                'name'        => 'Superintendent Ivan Ler',
                'designation' => 'Assistant Director International Policing (ASEAN & Middle East) & Protocol, International Cooperation Department',
                'mobile'      => '+65 9299 0175',
                'email'       => 'ivan_ler@spf.gov.sg',
                'photo'       => 'media/contacts/singapore.png',
            ],
            'history'           => [
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
        ]);
    }

    public function memberThailand()
    {
        return $this->memberPage([
            'country'     => 'Thailand',
            'force'       => 'Royal Thai Police',
            'abbreviation'=> 'RTP',
            'iso'         => 'th',
            'tel'         => ['+6622053001'],
            'fax'         => '+6622533856',
            'email'       => 'aseanapol.th@gmail.com',
            'website'     => 'https://www.royalthaipolice.go.th',
            'route'       => 'about.member.thailand',
            'description' => 'Royal Thai Police is a centralised national law enforcement agency with approximately 230,000 personnel, responsible for maintaining public order, preventing and suppressing crime, and safeguarding national security throughout the Kingdom of Thailand under the supervision of the National Police Policy Board.',
            'chief_name'        => 'Kittharath Punpetch',
            'chief_rank'        => 'Police General',
            'chief_designation' => 'Commissioner General',
            'chief_photo'       => 'media/chiefs/thailand.jpg',
            'logo'              => 'media/default-album/default-album/emblem_of_royal_thai_police_new.jpg',
            'contact_person'    => [
                'name'        => 'Pol. Lt. Col. Jarasluk La-Inchua',
                'designation' => 'Inspector of Interpol and Foreign Relations Region 1',
                'mobile'      => '+66 85 171 4949',
                'email'       => 'gingenejl@gmail.com',
                'photo'       => 'media/contacts/thailand.png',
            ],
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
                ['i' => 'Senior Inspector General',   'ii' => 'Deputy Commissioner General'],
                ['i' => 'Assistant Commissioner General'],
                ['i' => 'Commissioner'],
                ['i' => 'Deputy Commissioner'],
                ['i' => 'Commander',                  'ii' => 'Inquiry Official, Senior Expert Level'],
                ['i' => 'Deputy Commander',            'ii' => 'Inquiry Official, Expert Level'],
                ['i' => 'Superintendent',              'ii' => 'Inquiry Official, Advisory Level'],
                ['i' => 'Deputy Superintendent',       'ii' => 'Inquiry Official, Senior Professional Level'],
                ['i' => 'Inspector',                   'ii' => 'Inquiry Official, Professional Level'],
                ['i' => 'Deputy Inspector',            'ii' => 'Inquiry Official'],
                ['i' => 'Squad Leader'],
                ['i' => 'Police Trainee'],
            ],
            'rank_structure' => [
                ['rank' => 'Police General',               'abbreviation' => 'Pol.Gen.'],
                ['rank' => 'Police Lieutenant General',    'abbreviation' => 'Pol.Lt.Gen.'],
                ['rank' => 'Police Major General',         'abbreviation' => 'Pol.Maj.Gen.'],
                ['rank' => 'Police Colonel',               'abbreviation' => 'Pol.Col.'],
                ['rank' => 'Police Lieutenant Colonel',    'abbreviation' => 'Pol.Lt.Col.'],
                ['rank' => 'Police Major',                 'abbreviation' => 'Pol.Maj.'],
                ['rank' => 'Police Captain',               'abbreviation' => 'Pol.Capt.'],
                ['rank' => 'Police Lieutenant',            'abbreviation' => 'Pol.Lt.'],
                ['rank' => 'Police Sub-Lieutenant',        'abbreviation' => 'Pol.Sub-Lt.'],
                ['rank' => 'Police Senior Sergeant Major', 'abbreviation' => 'Pol.Snr.Sgt.Maj.'],
                ['rank' => 'Police Sergeant Major',        'abbreviation' => 'Pol.Sgt.Maj.'],
                ['rank' => 'Police Sergeant',              'abbreviation' => 'Pol.Sgt.'],
                ['rank' => 'Police Corporal',              'abbreviation' => 'Pol.Cpl.'],
                ['rank' => 'Police Lance Corporal',        'abbreviation' => 'Pol.L/Cpl.'],
            ],
        ]);
    }

    public function memberVietnam()
    {
        return $this->memberPage([
            'country'     => 'Viet Nam',
            'force'       => 'Office of Investigation Police Agency, Vietnam Police Force',
            'abbreviation'=> 'OIPA, VPF',
            'iso'         => 'vn',
            'tel'         => ['+8424 3938 7173'],
            'fax'         => '+8424 3938 7176',
            'email'       => 'division6@dfir.gov.vn',
            'website'     => null,
            'route'       => 'about.member.vietnam',
            'description' => 'The Office of Investigation Police Agency, Vietnam Police Force (OIPA, VPF) is the designated ASEANAPOL contact body for the Socialist Republic of Viet Nam, responsible for criminal investigation cooperation and transnational crime enforcement.',
            'chief_name'        => 'Tran Minh Tien',
            'chief_rank'        => 'Police Major General',
            'chief_designation' => 'Chief of Police',
            'chief_photo'       => 'media/chiefs/vietnam.jpg',
            'logo'              => 'media/police-logo/police-logo/vietnam.png',
            'contact_person'    => [
                'name'        => 'Snr. Lt. Col. Vu Thi Thu Hanh',
                'designation' => 'Deputy Head of Division',
                'mobile'      => '+84 983030328',
                'email'       => 'hanhdanhen@gmail.com',
                'photo'       => 'media/contacts/vietnam.png',
            ],
            'history'           => [
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
        ]);
    }

    public function memberTimorLeste()
    {
        return $this->memberPage([
            'country'     => 'Timor-Leste',
            'force'       => 'National Police of Timor-Leste',
            'abbreviation'=> 'PNTL',
            'iso'         => 'tl',
            'tel'         => null,   // KIV — placeholder on pntl.tl
            'fax'         => null,   // KIV
            'email'       => null,   // KIV
            'website'     => 'https://www.pntl.tl',
            'logo'              => 'media/police-logo/timorleste-pntl.png', // TODO: replace with final PNTL crest once provided
            'route'       => 'about.member.timor-leste',
            'description' => 'The National Police of Timor-Leste (Polícia Nacional de Timor-Leste / PNTL) is the national police force of the Democratic Republic of Timor-Leste. Timor-Leste has been an ASEANAPOL Observer since the 34th ASEANAPOL Conference (2014) and became ASEAN\'s 11th member state in October 2025.',
            'observer_note'  => 'Timor-Leste joined ASEAN as the 11th member state in October 2025. Their ASEANAPOL membership status is currently transitioning from Observer to full member.',
            'chief_name'        => 'Henrique Da Costa',
            'chief_rank'        => 'Police Commissioner General',
            'chief_designation' => 'Chief of Police',
            'chief_photo'       => 'media/chiefs/timor-leste.png',
            'contact_person' => [
                'name'        => 'Police Supt. Augusto Tilman Da Costa',
                'designation' => 'Chief',
                'mobile'      => '+67078299444',
                'email'       => 'Augusto.tilman@pntl.tl',
                'photo'       => 'media/contacts/timor-leste-contact.png',
            ],
        ]);
    }

    private function memberPage(array $data)
    {
        return view('information.member', $data);
    }

    // =========================================================
    // PARTNERS
    // =========================================================

    public function dialoguePartners() { return view('information.dialogue-partners'); }
    public function observers()        { return view('information.observers'); }

    // =========================================================
    // NEWS & MEDIA
    // =========================================================

    public function newsMediaIndex()
    {
        return view('news-media.index');
    }

    public function newsIndex()
    {
        return view('news.index');
    }

    public function pressReleases()
    {
        return view('news-media.press-releases');
    }

    public function gallery()
    {
        $mediaBase = public_path('media');
        $imageExts = '{jpg,jpeg,png,gif,webp,JPG,JPEG,PNG}';

        $libraries = [
            ['key' => 'activities', 'label' => 'ASEANAPOL Activities', 'dirs' => ['rotating-image/rotating-image', 'default-album/default-album'], 'limit' => 48],
            ['key' => 'news',       'label' => 'News Photos',           'dirs' => ['news/news'],                                                    'limit' => 48],
            ['key' => 'events',     'label' => 'Events',                'dirs' => ['events/events'],                                                'limit' => 48],
            ['key' => 'governance', 'label' => 'Governance',            'dirs' => ['governance/governance'],                                        'limit' => 48],
            ['key' => 'partners',   'label' => 'Dialogue Partners',     'dirs' => ['dialogue-partner/dialogue-partner'],                            'limit' => 48],
            ['key' => 'observers',  'label' => 'Observers',             'dirs' => ['observer/observer'],                                            'limit' => 48],
        ];

        $albums = [];
        foreach ($libraries as $lib) {
            $images = [];
            foreach ($lib['dirs'] as $relDir) {
                $pattern = "{$mediaBase}/{$relDir}/*.{$imageExts}";
                $found = glob($pattern, GLOB_BRACE) ?: [];
                foreach ($found as $f) {
                    $images[] = 'media/' . $relDir . '/' . basename($f);
                }
            }
            $images = array_slice($images, 0, $lib['limit']);
            $albums[] = [
                'key'    => $lib['key'],
                'label'  => $lib['label'],
                'images' => $images,
                'count'  => count($images),
            ];
        }

        return view('news.gallery', compact('albums'));
    }

    public function videoGallery()
    {
        $videos = \App\Models\VideoGallery::published()
            ->orderBy('sort_order')
            ->orderBy('event_date', 'desc')
            ->get();

        return view('news-media.video-gallery', compact('videos'));
    }

    public function newsletters()
    {
        $newsletters = \App\Models\Newsletter::published()
            ->orderBy('sort_order')
            ->orderBy('issue_date', 'desc')
            ->get();

        return view('news-media.newsletters', compact('newsletters'));
    }

    // =========================================================
    // DATA & RESOURCES
    // =========================================================

    public function dataResourcesIndex()
    {
        return view('data-resources.hub');
    }

    public function crimeStatistics()
    {
        return view('data-resources.crime-statistics');
    }

    public function publicationIndex()
    {
        return view('publication.index');
    }

    public function digitalLibrary()
    {
        $collections = \App\Models\DigitalLibraryCollection::with(['publishedItems'])
            ->orderBy('sort_order')
            ->get()
            ->filter(fn($c) => $c->publishedItems->isNotEmpty())
            ->values();

        return view('data-resources.digital-library', compact('collections'));
    }

    public function eLearning()
    {
        return view('training.e-learning');
    }

    public function trainingIndex()
    {
        return view('training.index');
    }

    public function onlineTraining()
    {
        return view('training.online-training');
    }

    public function inPersonTraining()
    {
        return view('training.in-person-training');
    }

    // Publication editions — kept for direct links
    public function publication8th()  { return $this->publicationPage(['edition' => '8th',  'title' => '8th Edition ASEANAPOL Bulletin',  'type' => 'Bulletin', 'route' => 'publication.8th']); }
    public function publication9th()  { return $this->publicationPage(['edition' => '9th',  'title' => '9th Edition ASEANAPOL Bulletin',  'type' => 'Bulletin', 'route' => 'publication.9th']); }
    public function publication10th() { return $this->publicationPage(['edition' => '10th', 'title' => '10th Edition ASEANAPOL Bulletin', 'type' => 'Bulletin', 'route' => 'publication.10th']); }
    public function publication11th() { return $this->publicationPage(['edition' => '11th', 'title' => '11th Edition ASEANAPOL Bulletin', 'type' => 'Bulletin', 'route' => 'publication.11th']); }
    public function publication12th() { return $this->publicationPage(['edition' => '12th', 'title' => '12th Edition ASEANAPOL Bulletin', 'type' => 'Bulletin', 'route' => 'publication.12th']); }
    public function publication13th() { return $this->publicationPage(['edition' => '13th', 'title' => '13th Edition ASEANAPOL Magazine',  'type' => 'Magazine', 'route' => 'publication.13th']); }
    public function publication14th() { return $this->publicationPage(['edition' => '14th', 'title' => '14th Edition ASEANAPOL Bulletin', 'type' => 'Bulletin', 'route' => 'publication.14th']); }

    private function publicationPage(array $data)
    {
        return view('publication.edition', $data);
    }

    // =========================================================
    // INTERNATIONAL COOPERATION
    // =========================================================

    public function internationalCooperation()
    {
        return view('international-collaboration.index');
    }

    public function cooperationInterpol()
    {
        return view('international-collaboration.interpol');
    }

    public function cooperationEuropol()
    {
        return view('international-collaboration.europol');
    }

    public function cooperationUnodc()
    {
        return view('international-collaboration.unodc');
    }

    public function cooperationJointProjects()
    {
        return view('international-collaboration.joint-projects');
    }

    // =========================================================
    // EVENTS & TRAINING
    // =========================================================

    public function eventsTrainingIndex()
    {
        return view('events-training.index');
    }

    public function eventsCalendar()
    {
        return view('events-training.calendar');
    }

    public function conferences()
    {
        return view('events-training.conferences');
    }

    public function trainingPrograms()
    {
        return view('events-training.training-programs');
    }

    // =========================================================
    // CAREERS & OPPORTUNITIES
    // =========================================================

    public function careersIndex()
    {
        return view('careers.index');
    }

    public function vacancies()
    {
        return view('careers.vacancies');
    }

    public function internships()
    {
        return view('careers.internships');
    }

    public function exchangePrograms()
    {
        return view('careers.exchange-programs');
    }

    // =========================================================
    // CONTACT US
    // =========================================================

    public function contactUs()
    {
        return view('contact.index');
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:120'],
            'email'   => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:200'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ]);

        Mail::to('aseanapolsec@aseanapol.org')
            ->send(new ContactInquiryMail(
                senderName:     $validated['name'],
                senderEmail:    $validated['email'],
                inquirySubject: $validated['subject'],
                messageBody:    $validated['message'],
            ));

        return redirect()
            ->route('contact-us', ['locale' => app()->getLocale()])
            ->with('contact_success', 'Thank you, ' . $validated['name'] . '. Your message has been sent. We will respond within 2–3 business days.');
    }

    // =========================================================
    // GUIDELINES
    // =========================================================

    public function guidelinesIndex()           { return view('guidelines.index'); }
    public function guidelinesFlag()            { return view('guidelines.flag'); }
    public function guidelinesContactPersons()  { return view('guidelines.contact-persons'); }
    public function guidelinesDonations()       { return view('guidelines.donations'); }
    public function guidelinesObserversDialogue(){ return view('guidelines.observers-dialogue'); }

    // =========================================================
    // HELPERS
    // =========================================================

    private function comingSoon(string $title, string $section)
    {
        return view('coming-soon', compact('title', 'section'));
    }
}
