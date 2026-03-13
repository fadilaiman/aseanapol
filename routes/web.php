<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\PageController;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $preferred = substr(request()->server('HTTP_ACCEPT_LANGUAGE', 'en'), 0, 2);
    $supported = ['en', 'ms', 'id', 'th', 'vi', 'km', 'lo', 'my', 'tl'];
    $locale = in_array($preferred, $supported) ? $preferred : 'en';
    return redirect('/' . session('locale', $locale));
});

Route::middleware(SetLocale::class)
    ->prefix('{locale}')
    ->where(['locale' => 'en|ms|id|th|vi|km|lo|my|tl'])
    ->group(function () {

        Route::get('/', [LandingController::class, 'index'])->name('landing');

        // --- About ASEANAPOL ---
        Route::get('/about-aseanapol', [PageController::class, 'aboutIndex'])->name('about.index');
        Route::get('/about-aseanapol/permanent-secretariat', [PageController::class, 'aboutPermanentSecretariat'])->name('about.permanent-secretariat');
        Route::get('/about-aseanapol/governance', [PageController::class, 'aboutGovernance'])->name('about.governance');
        Route::get('/about-aseanapol/objectives-and-functions', [PageController::class, 'aboutObjectivesAndFunctions'])->name('about.objectives-and-functions');
        Route::get('/about-aseanapol/chronology', [PageController::class, 'aboutChronology'])->name('about.chronology');
        Route::get('/about-aseanapol/aseanapol-logo', [PageController::class, 'aboutLogo'])->name('about.logo');
        Route::get('/about-aseanapol/vision-and-mission', [PageController::class, 'aboutVisionMission'])->name('about.vision-mission');

        // --- Information: Member Countries ---
        Route::get('/information', [PageController::class, 'informationIndex'])->name('information.index');
        Route::get('/information/royal-brunei-police-force', [PageController::class, 'memberBrunei'])->name('information.brunei');
        Route::get('/information/cambodian-national-police', [PageController::class, 'memberCambodia'])->name('information.cambodia');
        Route::get('/information/indonesian-national-police', [PageController::class, 'memberIndonesia'])->name('information.indonesia');
        Route::get('/information/lao-police-force', [PageController::class, 'memberLao'])->name('information.lao');
        Route::get('/information/royal-malaysia-police', [PageController::class, 'memberMalaysia'])->name('information.malaysia');
        Route::get('/information/myanmar-police-force', [PageController::class, 'memberMyanmar'])->name('information.myanmar');
        Route::get('/information/philippine-national-police', [PageController::class, 'memberPhilippines'])->name('information.philippines');
        Route::get('/information/singapore-police-force', [PageController::class, 'memberSingapore'])->name('information.singapore');
        Route::get('/information/royal-thai-police', [PageController::class, 'memberThailand'])->name('information.thailand');
        Route::get('/information/the-office-of-investigation-police-agency-ministry-of-public-security-socialist-republic-of-viet-nam', [PageController::class, 'memberVietnam'])->name('information.vietnam');

        // --- Information: Partners ---
        Route::get('/information/dialogue-partners', [PageController::class, 'dialoguePartners'])->name('information.dialogue-partners');
        Route::get('/information/observers', [PageController::class, 'observers'])->name('information.observers');

        // --- International Collaboration ---
        Route::get('/international-collaboration', [PageController::class, 'internationalCollaboration'])->name('international-collaboration');

        // --- Guidelines ---
        Route::get('/guidelines', [PageController::class, 'guidelinesIndex'])->name('guidelines.index');
        Route::get('/guidelines/guidelines-on-the-use-of-the-aseanapol-flag', [PageController::class, 'guidelinesFlag'])->name('guidelines.flag');
        Route::get('/guidelines/guidelines-on-the-roles-and-functions-of-aseanapol-contact-persons', [PageController::class, 'guidelinesContactPersons'])->name('guidelines.contact-persons');
        Route::get('/guidelines/guidelines-for-accepting-donations-and-sponsorships', [PageController::class, 'guidelinesDonations'])->name('guidelines.donations');
        Route::get('/guidelines/policy-guidelines-for-accepting-observers-and-dialogue-partners-with-aseanapol', [PageController::class, 'guidelinesObserversDialogue'])->name('guidelines.observers-dialogue');

        // --- News ---
        Route::get('/news', [PageController::class, 'newsIndex'])->name('news.index');

        // --- Publication ---
        Route::get('/publication', [PageController::class, 'publicationIndex'])->name('publication.index');
        Route::get('/publication/8th-edition-aseanapol-bulletin', [PageController::class, 'publication8th'])->name('publication.8th');
        Route::get('/publication/9th-edition-aseanapol-bulletin', [PageController::class, 'publication9th'])->name('publication.9th');
        Route::get('/publication/10th-edition-aseanapol-bulletin', [PageController::class, 'publication10th'])->name('publication.10th');
        Route::get('/publication/11th-edition-aseanapol-bulletin', [PageController::class, 'publication11th'])->name('publication.11th');
        Route::get('/publication/12th-edition-aseanapol-bulletin', [PageController::class, 'publication12th'])->name('publication.12th');
        Route::get('/publication/13th-edition-aseanapol-magazine', [PageController::class, 'publication13th'])->name('publication.13th');

    });
