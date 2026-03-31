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
            'website'     => null,
            'route'       => 'about.member.cambodia',
            'description' => 'The Cambodian National Police (CNP) is the national police organisation of the Kingdom of Cambodia under the Ministry of Interior, responsible for maintaining public order, combating crime, and ensuring national security.',
            'chief_name'        => 'Sar Theth',
            'chief_rank'        => 'Police General',
            'chief_designation' => 'Commissioner General',
            'chief_photo'       => 'media/chiefs/cambodia.jpg',
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
            'chief_name'        => 'Win Zaw Moe',
            'chief_rank'        => 'Police Lieutenant General',
            'chief_designation' => 'Chief of Police',
            'chief_photo'       => 'media/chiefs/myanmar.jpg',
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
            'description' => 'The Royal Thai Police is the national police service of the Kingdom of Thailand, responsible for law enforcement, crime prevention and suppression, immigration control, and maintaining public order.',
            'chief_name'        => 'Kittharath Punpetch',
            'chief_rank'        => 'Police General',
            'chief_designation' => 'Commissioner General',
            'chief_photo'       => 'media/chiefs/thailand.jpg',
        ]);
    }

    public function memberVietnam()
    {
        return $this->memberPage([
            'country'     => 'Viet Nam',
            'force'       => 'Vietnam Police Force',
            'abbreviation'=> 'VPF',
            'iso'         => 'vn',
            'tel'         => ['+8424 3938 7173'],
            'fax'         => '+8424 3938 7176',
            'email'       => 'division6@dfir.gov.vn',
            'website'     => null,
            'route'       => 'about.member.vietnam',
            'description' => 'The Vietnam Police Force (VPF) is the designated ASEANAPOL contact body for the Socialist Republic of Viet Nam, responsible for criminal investigation cooperation and transnational crime enforcement.',
            'chief_name'        => 'Tran Minh Tien',
            'chief_rank'        => 'Police Major General',
            'chief_designation' => 'Chief of Police',
            'chief_photo'       => 'media/chiefs/vietnam.jpg',
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
            'route'       => 'about.member.timor-leste',
            'description' => 'The National Police of Timor-Leste (Polícia Nacional de Timor-Leste / PNTL) is the national police force of the Democratic Republic of Timor-Leste. Timor-Leste has been an ASEANAPOL Observer since the 34th ASEANAPOL Conference (2014) and became ASEAN\'s 11th member state in October 2025.',
            'observer_note' => 'Timor-Leste joined ASEAN as the 11th member state in October 2025. Their ASEANAPOL membership status is currently transitioning from Observer to full member.',
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
        return view('news-media.video-gallery');
    }

    public function newsletters()
    {
        return view('news-media.newsletters');
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
        return view('data-resources.digital-library');
    }

    // Publication editions — kept for direct links
    public function publication8th()  { return $this->publicationPage(['edition' => '8th',  'title' => '8th Edition ASEANAPOL Bulletin',  'type' => 'Bulletin', 'route' => 'publication.8th']); }
    public function publication9th()  { return $this->publicationPage(['edition' => '9th',  'title' => '9th Edition ASEANAPOL Bulletin',  'type' => 'Bulletin', 'route' => 'publication.9th']); }
    public function publication10th() { return $this->publicationPage(['edition' => '10th', 'title' => '10th Edition ASEANAPOL Bulletin', 'type' => 'Bulletin', 'route' => 'publication.10th']); }
    public function publication11th() { return $this->publicationPage(['edition' => '11th', 'title' => '11th Edition ASEANAPOL Bulletin', 'type' => 'Bulletin', 'route' => 'publication.11th']); }
    public function publication12th() { return $this->publicationPage(['edition' => '12th', 'title' => '12th Edition ASEANAPOL Bulletin', 'type' => 'Bulletin', 'route' => 'publication.12th']); }
    public function publication13th() { return $this->publicationPage(['edition' => '13th', 'title' => '13th Edition ASEANAPOL Magazine', 'type' => 'Magazine', 'route' => 'publication.13th']); }

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
