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
        return view('information.index', ['members' => \App\Support\MemberPages::cards()]);
    }

    public function memberBrunei() { return $this->memberPage('brunei'); }
    public function memberCambodia() { return $this->memberPage('cambodia'); }
    public function memberIndonesia() { return $this->memberPage('indonesia'); }
    public function memberLao() { return $this->memberPage('lao'); }
    public function memberMalaysia() { return $this->memberPage('malaysia'); }
    public function memberMyanmar() { return $this->memberPage('myanmar'); }
    public function memberPhilippines() { return $this->memberPage('philippines'); }
    public function memberSingapore() { return $this->memberPage('singapore'); }
    public function memberThailand() { return $this->memberPage('thailand'); }
    public function memberVietnam() { return $this->memberPage('vietnam'); }
    public function memberTimorLeste() { return $this->memberPage('timor-leste'); }

    // Member country data lives in the CMS registry (config/cms_pages/members.php).
    private function memberPage(string $slug)
    {
        return view('information.member', \App\Support\MemberPages::data($slug));
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

    private const GALLERY_ALBUM_LABELS = [
        'activities' => 'ASEANAPOL Activities',
        'news'       => 'News Photos',
        'events'     => 'Events',
        'governance' => 'Governance',
        'partners'   => 'Dialogue Partners',
        'observers'  => 'Observers',
    ];

    private const GALLERY_PAGE_SIZE = 60;

    /**
     * Images come from the gallery_images index (rebuilt by `gallery:reindex`
     * from news_items + public/media — see routes/console.php), sorted by
     * each image's real event date rather than filesystem/upload order.
     * Each album loads its first page here; the rest is fetched via
     * galleryMore() as the user clicks "Load more" so a 6000+ image album
     * still renders fast.
     */
    public function gallery()
    {
        $albums = [];
        foreach (self::GALLERY_ALBUM_LABELS as $key => $label) {
            $query = \App\Models\GalleryImage::where('album_key', $key)->orderByDesc('event_date')->orderByDesc('id');
            $count = (clone $query)->count();
            $images = $query->limit(self::GALLERY_PAGE_SIZE)->pluck('path')->all();

            $albums[] = [
                'key'      => $key,
                'label'    => cms('newsmedia.gallery', 'albums.' . $key, $label),
                'images'   => $images,
                'count'    => $count,
                'hasMore'  => $count > count($images),
            ];
        }

        return view('news.gallery', compact('albums'));
    }

    public function galleryMore(Request $request)
    {
        $key = $request->query('album');
        $offset = max(0, (int) $request->query('offset'));

        if (! is_string($key) || ! isset(self::GALLERY_ALBUM_LABELS[$key])) {
            return response()->json(['images' => [], 'hasMore' => false], 404);
        }

        $query = \App\Models\GalleryImage::where('album_key', $key)->orderByDesc('event_date')->orderByDesc('id');
        $total = (clone $query)->count();
        $images = $query->skip($offset)->limit(self::GALLERY_PAGE_SIZE)->pluck('path')
            ->map(fn ($p) => asset($p))
            ->all();

        return response()->json([
            'images'  => $images,
            'hasMore' => ($offset + count($images)) < $total,
        ]);
    }

    public function videoGallery()
    {
        $videos = \App\Models\VideoGallery::published()
            ->orderBy('created_at', 'desc')
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
        $publications = \App\Models\Publication::published()->ordered()->get();

        return view('publication.index', compact('publications'));
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
    public function publication15th() { return $this->publicationPage(['edition' => '15th', 'title' => '15th Edition ASEANAPOL Magazine',  'type' => 'Magazine', 'route' => 'publication.15th']); }

    private function publicationPage(array $data)
    {
        // Edition data is DB-driven (admin Publications module); the arguments only select the edition.
        $pub = \App\Models\Publication::published()->where('edition', $data['edition'])->where('kind', strtolower($data['type']))->firstOrFail();

        return view('publication.edition', compact('pub'));
    }

    // Generic detail page for any edition added in the admin
    public function publicationShow($locale, $slug)
    {
        $pub = \App\Models\Publication::published()->where('slug', $slug)->firstOrFail();

        return view('publication.edition', compact('pub'));
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
        // Calendar entries grouped by year (latest year first); order inside a year = sort_order, then date.
        $events = \App\Models\Event::published()->kind('calendar')
            ->orderByRaw('YEAR(start_date) DESC')->orderBy('sort_order')->orderByDesc('start_date')->orderBy('id')
            ->get();
        $byYear = $events->groupBy(fn ($e) => $e->start_date->format('Y'));

        return view('events-training.calendar', ['byYear' => $byYear]);
    }

    public function conferences()
    {
        $conferences = \App\Models\Event::published()->kind('conference')
            ->orderByDesc('conf_year')->orderBy('sort_order')->orderBy('id')
            ->get();

        return view('events-training.conferences', ['conferences' => $conferences]);
    }

    public function trainingPrograms()
    {
        $programs = \App\Models\Event::published()->kind('training')
            ->orderBy('sort_order')->orderBy('id')
            ->get();

        return view('events-training.training-programs', ['programs' => $programs]);
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

        Mail::to(\App\Support\Shared::secretariat('contact.email_secretariat') ?: 'aseanapolsec@aseanapol.org')
            ->send(new ContactInquiryMail(
                senderName:     $validated['name'],
                senderEmail:    $validated['email'],
                inquirySubject: $validated['subject'],
                messageBody:    $validated['message'],
            ));

        return redirect()
            ->route('contact-us', ['locale' => app()->getLocale()])
            ->with('contact_success', str_replace(':name', $validated['name'], (string) cms('site.contact-form', 'settings.success_message')));
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
