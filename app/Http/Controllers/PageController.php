<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    // =========================================================
    // ABOUT ASEANAPOL
    // =========================================================

    public function aboutIndex()
    {
        return view('about.index');
    }

    public function aboutPermanentSecretariat()
    {
        return view('about.permanent-secretariat');
    }

    public function aboutGovernance()
    {
        return view('about.governance');
    }

    public function aboutObjectivesAndFunctions()
    {
        return view('about.objectives-and-functions');
    }

    public function aboutChronology()
    {
        return view('about.chronology');
    }

    public function aboutLogo()
    {
        return view('about.aseanapol-logo');
    }

    public function aboutVisionMission()
    {
        return view('about.vision-and-mission');
    }

    // =========================================================
    // INFORMATION — MAIN
    // =========================================================

    public function informationIndex()
    {
        return view('information.index');
    }

    // =========================================================
    // INFORMATION — MEMBER COUNTRIES
    // =========================================================

    public function memberBrunei()
    {
        return $this->memberPage([
            'country'   => 'Brunei Darussalam',
            'force'     => 'Royal Brunei Police Force',
            'iso'       => 'bn',
            'tel'       => ['+673-2459 500', '+673-2423 901 ext: 784/785'],
            'fax'       => null,
            'email'     => 'rbpf.interpol@police.gov.bn',
            'website'   => 'https://www.police.gov.bn',
            'route'     => 'information.brunei',
            'description' => 'The Royal Brunei Police Force (RBPF) is the national police force of Brunei Darussalam, responsible for maintaining law and order, preventing and detecting crime, and safeguarding the peace and security of the nation.',
        ]);
    }

    public function memberCambodia()
    {
        return $this->memberPage([
            'country'   => 'Cambodia',
            'force'     => 'Cambodian National Police',
            'iso'       => 'kh',
            'tel'       => null,
            'fax'       => null,
            'email'     => 'camcontactperson@gmail.com',
            'website'   => null,
            'route'     => 'information.cambodia',
            'description' => 'The Cambodian National Police (CNP) is the national police organisation of the Kingdom of Cambodia under the Ministry of Interior, responsible for maintaining public order, combating crime, and ensuring national security.',
        ]);
    }

    public function memberIndonesia()
    {
        return $this->memberPage([
            'country'   => 'Indonesia',
            'force'     => 'Indonesian National Police',
            'iso'       => 'id',
            'tel'       => ['+021 739 3650', '+021 721 8467'],
            'fax'       => '+021 720 1402',
            'email'     => 'ncb-jakarta@interpol.go.id',
            'website'   => 'https://www.polri.go.id',
            'route'     => 'information.indonesia',
            'description' => 'The Indonesian National Police (POLRI) is the national police force of the Republic of Indonesia, responsible for law enforcement, maintaining public order, and combating crime throughout the archipelago.',
        ]);
    }

    public function memberLao()
    {
        return $this->memberPage([
            'country'   => 'Lao PDR',
            'force'     => 'Lao Police Force',
            'iso'       => 'la',
            'tel'       => ['+856 213 16323'],
            'fax'       => '+856 2131 6323',
            'email'     => 'ncbvientiane@gmail.com',
            'website'   => null,
            'route'     => 'information.lao',
            'description' => 'The Lao Police Force is the national law enforcement agency of the Lao People\'s Democratic Republic, operating under the Ministry of Public Security to maintain peace, order, and national security.',
        ]);
    }

    public function memberMalaysia()
    {
        return $this->memberPage([
            'country'   => 'Malaysia',
            'force'     => 'Royal Malaysia Police',
            'iso'       => 'my',
            'tel'       => ['+603 2266 2222'],
            'fax'       => '+603 2070 7500',
            'email'     => 'rmp@rmp.gov.my',
            'website'   => 'https://www.rmp.gov.my',
            'route'     => 'information.malaysia',
            'description' => 'The Royal Malaysia Police (RMP) or Polis DiRaja Malaysia (PDRM) is the national police force of Malaysia, host of the ASEANAPOL Permanent Secretariat, and one of the founding members of ASEANAPOL.',
        ]);
    }

    public function memberMyanmar()
    {
        return $this->memberPage([
            'country'   => 'Myanmar',
            'force'     => 'Myanmar Police Force',
            'iso'       => 'mm',
            'tel'       => ['+95 6741 2066'],
            'fax'       => '+95 6741 2188',
            'email'     => 'naypyitaw.ncb@gmail.com',
            'website'   => null,
            'route'     => 'information.myanmar',
            'description' => 'The Myanmar Police Force is the national police organisation of the Republic of the Union of Myanmar, responsible for maintaining law and order, preventing crime, and ensuring public safety.',
        ]);
    }

    public function memberPhilippines()
    {
        return $this->memberPage([
            'country'   => 'Philippines',
            'force'     => 'Philippine National Police',
            'iso'       => 'ph',
            'tel'       => ['+632 8723 0401'],
            'fax'       => null,
            'email'     => 'laiad.dpl@pnp.gov.ph',
            'website'   => 'https://www.pnp.gov.ph',
            'route'     => 'information.philippines',
            'description' => 'The Philippine National Police (PNP) is the national police force of the Philippines, mandated to enforce the law, prevent and control crimes, maintain peace and order, and ensure public safety.',
        ]);
    }

    public function memberSingapore()
    {
        return $this->memberPage([
            'country'   => 'Singapore',
            'force'     => 'Singapore Police Force',
            'iso'       => 'sg',
            'tel'       => ['1800 358 000'],
            'fax'       => '+65 6256 1296',
            'email'     => null,
            'website'   => 'https://www.police.gov.sg',
            'route'     => 'information.singapore',
            'description' => 'The Singapore Police Force (SPF) is the national police force of the Republic of Singapore, responsible for maintaining law and order, preventing crime, and ensuring the safety and security of Singapore.',
        ]);
    }

    public function memberThailand()
    {
        return $this->memberPage([
            'country'   => 'Thailand',
            'force'     => 'Royal Thai Police',
            'iso'       => 'th',
            'tel'       => ['+6622053001'],
            'fax'       => '+6622533856',
            'email'     => 'aseanapol.th@gmail.com',
            'website'   => 'https://www.royalthaipolice.go.th',
            'route'     => 'information.thailand',
            'description' => 'The Royal Thai Police is the national police service of the Kingdom of Thailand, responsible for law enforcement, crime prevention and suppression, immigration control, and maintaining public order.',
        ]);
    }

    public function memberVietnam()
    {
        return $this->memberPage([
            'country'   => 'Viet Nam',
            'force'     => 'Office of Investigation Police Agency, Ministry of Public Security',
            'iso'       => 'vn',
            'tel'       => ['+8424 3938 7173'],
            'fax'       => '+8424 3938 7176',
            'email'     => 'division6@dfir.gov.vn',
            'website'   => null,
            'route'     => 'information.vietnam',
            'description' => 'The Office of Investigation Police Agency under Vietnam\'s Ministry of Public Security is the designated ASEANAPOL contact body for the Socialist Republic of Viet Nam, responsible for criminal investigation cooperation and transnational crime enforcement.',
        ]);
    }

    private function memberPage(array $data)
    {
        return view('information.member', $data);
    }

    // =========================================================
    // INFORMATION — PARTNERS
    // =========================================================

    public function dialoguePartners()
    {
        return view('information.dialogue-partners');
    }

    public function observers()
    {
        return view('information.observers');
    }

    // =========================================================
    // INTERNATIONAL COLLABORATION
    // =========================================================

    public function internationalCollaboration()
    {
        return view('international-collaboration.index');
    }

    // =========================================================
    // GUIDELINES
    // =========================================================

    public function guidelinesIndex()
    {
        return view('guidelines.index');
    }

    public function guidelinesFlag()
    {
        return view('guidelines.flag');
    }

    public function guidelinesContactPersons()
    {
        return view('guidelines.contact-persons');
    }

    public function guidelinesDonations()
    {
        return view('guidelines.donations');
    }

    public function guidelinesObserversDialogue()
    {
        return view('guidelines.observers-dialogue');
    }

    // =========================================================
    // NEWS
    // =========================================================

    public function newsIndex()
    {
        return view('news.index');
    }

    // =========================================================
    // PUBLICATION
    // =========================================================

    public function publicationIndex()
    {
        return view('publication.index');
    }

    public function publication8th()
    {
        return $this->publicationPage([
            'edition'   => '8th',
            'title'     => '8th Edition ASEANAPOL Bulletin',
            'type'      => 'Bulletin',
            'route'     => 'publication.8th',
        ]);
    }

    public function publication9th()
    {
        return $this->publicationPage([
            'edition'   => '9th',
            'title'     => '9th Edition ASEANAPOL Bulletin',
            'type'      => 'Bulletin',
            'route'     => 'publication.9th',
        ]);
    }

    public function publication10th()
    {
        return $this->publicationPage([
            'edition'   => '10th',
            'title'     => '10th Edition ASEANAPOL Bulletin',
            'type'      => 'Bulletin',
            'route'     => 'publication.10th',
        ]);
    }

    public function publication11th()
    {
        return $this->publicationPage([
            'edition'   => '11th',
            'title'     => '11th Edition ASEANAPOL Bulletin',
            'type'      => 'Bulletin',
            'route'     => 'publication.11th',
        ]);
    }

    public function publication12th()
    {
        return $this->publicationPage([
            'edition'   => '12th',
            'title'     => '12th Edition ASEANAPOL Bulletin',
            'type'      => 'Bulletin',
            'route'     => 'publication.12th',
        ]);
    }

    public function publication13th()
    {
        return $this->publicationPage([
            'edition'   => '13th',
            'title'     => '13th Edition ASEANAPOL Magazine',
            'type'      => 'Magazine',
            'route'     => 'publication.13th',
        ]);
    }

    private function publicationPage(array $data)
    {
        return view('publication.edition', $data);
    }
}
