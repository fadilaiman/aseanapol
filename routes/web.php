<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PageController;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $preferred = substr(request()->server('HTTP_ACCEPT_LANGUAGE', 'en'), 0, 2);
    $supported = ['en', 'ms', 'id', 'th', 'vi', 'km', 'lo', 'my', 'tl', 'zh'];
    $locale = in_array($preferred, $supported) ? $preferred : 'en';
    return redirect('/' . session('locale', $locale));
});

Route::middleware([SetLocale::class])
    ->prefix('{locale}')
    ->where(['locale' => 'en|ms|id|th|vi|km|lo|my|tl|zh'])
    ->group(function () {

        Route::get('/', [LandingController::class, 'index'])->name('landing');

        // =====================================================
        // ABOUT ASEANAPOL
        // =====================================================
        Route::get('/about-aseanapol', [PageController::class, 'aboutIndex'])->name('about.index');
        Route::get('/about-aseanapol/permanent-secretariat', [PageController::class, 'aboutPermanentSecretariat'])->name('about.permanent-secretariat');
        Route::get('/about-aseanapol/governance', [PageController::class, 'aboutGovernance'])->name('about.governance');
        Route::get('/about-aseanapol/objectives-and-functions', [PageController::class, 'aboutObjectivesAndFunctions'])->name('about.objectives-and-functions');
        Route::get('/about-aseanapol/chronology', [PageController::class, 'aboutChronology'])->name('about.chronology');
        Route::get('/about-aseanapol/aseanapol-logo', [PageController::class, 'aboutLogo'])->name('about.logo');
        Route::get('/about-aseanapol/vision-and-mission', [PageController::class, 'aboutVisionMission'])->name('about.vision-mission');
        Route::get('/about-aseanapol/leadership', [PageController::class, 'aboutLeadership'])->name('about.leadership');
        Route::get('/about-aseanapol/leadership/director-for-police-services', [PageController::class, 'aboutLeadershipDps'])->name('about.leadership.dps');
        Route::get('/about-aseanapol/leadership/director-for-plans-and-programmes', [PageController::class, 'aboutLeadershipDpp'])->name('about.leadership.dpp');
        Route::get('/about-aseanapol/lme', [PageController::class, 'aboutObLme'])->name('about.ob-lme');

        // =====================================================
        // MEMBER COUNTRIES  (canonical under /about-aseanapol)
        // =====================================================
        Route::get('/about-aseanapol/member-countries', [PageController::class, 'informationIndex'])->name('about.member-countries');
        Route::get('/about-aseanapol/member-countries/royal-brunei-police-force', [PageController::class, 'memberBrunei'])->name('about.member.brunei');
        Route::get('/about-aseanapol/member-countries/cambodian-national-police', [PageController::class, 'memberCambodia'])->name('about.member.cambodia');
        Route::get('/about-aseanapol/member-countries/indonesian-national-police', [PageController::class, 'memberIndonesia'])->name('about.member.indonesia');
        Route::get('/about-aseanapol/member-countries/lao-police-force', [PageController::class, 'memberLao'])->name('about.member.lao');
        Route::get('/about-aseanapol/member-countries/royal-malaysia-police', [PageController::class, 'memberMalaysia'])->name('about.member.malaysia');
        Route::get('/about-aseanapol/member-countries/myanmar-police-force', [PageController::class, 'memberMyanmar'])->name('about.member.myanmar');
        Route::get('/about-aseanapol/member-countries/philippine-national-police', [PageController::class, 'memberPhilippines'])->name('about.member.philippines');
        Route::get('/about-aseanapol/member-countries/singapore-police-force', [PageController::class, 'memberSingapore'])->name('about.member.singapore');
        Route::get('/about-aseanapol/member-countries/royal-thai-police', [PageController::class, 'memberThailand'])->name('about.member.thailand');
        Route::get('/about-aseanapol/member-countries/office-of-investigation-police-agency-viet-nam', [PageController::class, 'memberVietnam'])->name('about.member.vietnam');
        Route::get('/about-aseanapol/member-countries/national-police-of-timor-leste', [PageController::class, 'memberTimorLeste'])->name('about.member.timor-leste');

        // Partners
        Route::get('/about-aseanapol/dialogue-partners', [PageController::class, 'dialoguePartners'])->name('about.dialogue-partners');
        Route::get('/about-aseanapol/observers', [PageController::class, 'observers'])->name('about.observers');

        // =====================================================
        // LEGACY /information redirects  (301)
        // =====================================================
        Route::redirect('/information', '/about-aseanapol/member-countries', 301)->name('information.index');
        Route::redirect('/information/royal-brunei-police-force', '/about-aseanapol/member-countries/royal-brunei-police-force', 301)->name('information.brunei');
        Route::redirect('/information/cambodian-national-police', '/about-aseanapol/member-countries/cambodian-national-police', 301)->name('information.cambodia');
        Route::redirect('/information/indonesian-national-police', '/about-aseanapol/member-countries/indonesian-national-police', 301)->name('information.indonesia');
        Route::redirect('/information/lao-police-force', '/about-aseanapol/member-countries/lao-police-force', 301)->name('information.lao');
        Route::redirect('/information/royal-malaysia-police', '/about-aseanapol/member-countries/royal-malaysia-police', 301)->name('information.malaysia');
        Route::redirect('/information/myanmar-police-force', '/about-aseanapol/member-countries/myanmar-police-force', 301)->name('information.myanmar');
        Route::redirect('/information/philippine-national-police', '/about-aseanapol/member-countries/philippine-national-police', 301)->name('information.philippines');
        Route::redirect('/information/singapore-police-force', '/about-aseanapol/member-countries/singapore-police-force', 301)->name('information.singapore');
        Route::redirect('/information/royal-thai-police', '/about-aseanapol/member-countries/royal-thai-police', 301)->name('information.thailand');
        Route::redirect('/information/the-office-of-investigation-police-agency-ministry-of-public-security-socialist-republic-of-viet-nam', '/about-aseanapol/member-countries/office-of-investigation-police-agency-viet-nam', 301)->name('information.vietnam');
        Route::redirect('/information/dialogue-partners', '/about-aseanapol/dialogue-partners', 301)->name('information.dialogue-partners');
        Route::redirect('/information/observers', '/about-aseanapol/observers', 301)->name('information.observers');

        // =====================================================
        // NEWS & MEDIA
        // =====================================================
        Route::get('/news-media', [PageController::class, 'newsMediaIndex'])->name('news-media.index');
        Route::get('/news-media/news', [NewsController::class, 'index'])->name('news.index');
        Route::get('/news-media/news/{slug}', [NewsController::class, 'show'])->name('news.show')->where('slug', '[^/]+');
        Route::get('/news-media/press-releases', [PageController::class, 'pressReleases'])->name('news-media.press-releases');
        Route::get('/news-media/gallery', [PageController::class, 'gallery'])->name('news-media.gallery');
        Route::get('/news-media/video-gallery', [PageController::class, 'videoGallery'])->name('news-media.video-gallery');
        Route::get('/news-media/newsletters', [PageController::class, 'newsletters'])->name('news-media.newsletters');

        // Legacy /news redirect
        Route::redirect('/news', '/news-media/news', 301);

        // =====================================================
        // DATA & RESOURCES
        // =====================================================
        Route::get('/data-resources', [PageController::class, 'dataResourcesIndex'])->name('data-resources.index');
        Route::get('/data-resources/crime-statistics', [PageController::class, 'crimeStatistics'])->name('data-resources.crime-statistics');
        Route::get('/data-resources/publications', [PageController::class, 'publicationIndex'])->name('data-resources.publications');
        Route::get('/data-resources/digital-library', [PageController::class, 'digitalLibrary'])->name('data-resources.digital-library');

        // Legacy /publication redirects
        Route::redirect('/publication', '/data-resources/publications', 301)->name('publication.index');
        Route::redirect('/publication/8th-edition-aseanapol-bulletin', '/data-resources/publications', 301)->name('publication.8th');
        Route::redirect('/publication/9th-edition-aseanapol-bulletin', '/data-resources/publications', 301)->name('publication.9th');
        Route::redirect('/publication/10th-edition-aseanapol-bulletin', '/data-resources/publications', 301)->name('publication.10th');
        Route::redirect('/publication/11th-edition-aseanapol-bulletin', '/data-resources/publications', 301)->name('publication.11th');
        Route::redirect('/publication/12th-edition-aseanapol-bulletin', '/data-resources/publications', 301)->name('publication.12th');
        Route::redirect('/publication/13th-edition-aseanapol-magazine', '/data-resources/publications', 301)->name('publication.13th');

        // =====================================================
        // INTERNATIONAL COOPERATION
        // =====================================================
        Route::get('/international-cooperation', [PageController::class, 'internationalCooperation'])->name('international-cooperation');
        Route::get('/international-cooperation/interpol', [PageController::class, 'cooperationInterpol'])->name('international-cooperation.interpol');
        Route::get('/international-cooperation/europol', [PageController::class, 'cooperationEuropol'])->name('international-cooperation.europol');
        Route::get('/international-cooperation/unodc', [PageController::class, 'cooperationUnodc'])->name('international-cooperation.unodc');
        Route::get('/international-cooperation/joint-projects', [PageController::class, 'cooperationJointProjects'])->name('international-cooperation.joint-projects');

        // Legacy /international-collaboration redirect
        Route::redirect('/international-collaboration', '/international-cooperation', 301)->name('international-collaboration');

        // =====================================================
        // EVENTS & TRAINING
        // =====================================================
        Route::get('/events-training', [PageController::class, 'eventsTrainingIndex'])->name('events-training.index');
        Route::get('/events-training/calendar', [PageController::class, 'eventsCalendar'])->name('events-training.calendar');
        Route::get('/events-training/conferences', [PageController::class, 'conferences'])->name('events-training.conferences');
        Route::get('/events-training/training-programs', [PageController::class, 'trainingPrograms'])->name('events-training.training-programs');

        // =====================================================
        // CAREERS & OPPORTUNITIES
        // =====================================================
        Route::get('/careers', [PageController::class, 'careersIndex'])->name('careers.index');
        Route::get('/careers/vacancies', [PageController::class, 'vacancies'])->name('careers.vacancies');
        Route::get('/careers/internships', [PageController::class, 'internships'])->name('careers.internships');
        Route::get('/careers/exchange-programs', [PageController::class, 'exchangePrograms'])->name('careers.exchange-programs');

        // =====================================================
        // CONTACT US
        // =====================================================
        Route::get('/contact-us', [PageController::class, 'contactUs'])->name('contact-us');
        Route::post('/contact-us', [PageController::class, 'submitContact'])->name('contact-us.submit');

        // =====================================================
        // GUIDELINES  (kept, accessible via footer/search)
        // =====================================================
        Route::get('/guidelines', [PageController::class, 'guidelinesIndex'])->name('guidelines.index');
        Route::get('/guidelines/guidelines-on-the-use-of-the-aseanapol-flag', [PageController::class, 'guidelinesFlag'])->name('guidelines.flag');
        Route::get('/guidelines/guidelines-on-the-roles-and-functions-of-aseanapol-contact-persons', [PageController::class, 'guidelinesContactPersons'])->name('guidelines.contact-persons');
        Route::get('/guidelines/guidelines-for-accepting-donations-and-sponsorships', [PageController::class, 'guidelinesDonations'])->name('guidelines.donations');
        Route::get('/guidelines/policy-guidelines-for-accepting-observers-and-dialogue-partners-with-aseanapol', [PageController::class, 'guidelinesObserversDialogue'])->name('guidelines.observers-dialogue');

    });
