<?php

namespace App\Http\Controllers;

use App\Models\NewsItem;
use App\Models\Publication;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    private const PAGES = [
        '', '/about-aseanapol', '/about-aseanapol/aseanapol-logo', '/about-aseanapol/chronology',
        '/about-aseanapol/dialogue-partners', '/about-aseanapol/governance', '/about-aseanapol/leadership',
        '/about-aseanapol/leadership/director-for-plans-and-programmes',
        '/about-aseanapol/leadership/director-for-police-services', '/about-aseanapol/lme',
        '/about-aseanapol/member-countries', '/about-aseanapol/objectives-and-functions',
        '/about-aseanapol/observers', '/about-aseanapol/permanent-secretariat', '/about-aseanapol/vision-and-mission',
        '/careers', '/careers/exchange-programs', '/careers/internships', '/careers/vacancies', '/contact-us',
        '/data-resources', '/data-resources/crime-statistics', '/data-resources/digital-library',
        '/data-resources/publications',
        '/events-training', '/events-training/calendar', '/events-training/conferences', '/events-training/training-programs',
        '/guidelines',
        '/international-cooperation', '/international-cooperation/europol', '/international-cooperation/interpol',
        '/international-cooperation/joint-projects', '/international-cooperation/unodc',
        '/news-media', '/news-media/gallery', '/news-media/news', '/news-media/newsletters',
        '/news-media/press-releases', '/news-media/video-gallery',
        '/training', '/training/e-learning', '/training/in-person-training', '/training/online-training',
    ];

    private const MEMBERS = [
        'cambodian-national-police', 'indonesian-national-police', 'lao-police-force', 'myanmar-police-force',
        'national-police-of-timor-leste', 'office-of-investigation-police-agency-viet-nam', 'philippine-national-police',
        'royal-brunei-police-force', 'royal-malaysia-police', 'royal-thai-police', 'singapore-police-force',
    ];

    private const EDITIONS = [
        '8th-edition-aseanapol-bulletin', '9th-edition-aseanapol-bulletin', '10th-edition-aseanapol-bulletin',
        '11th-edition-aseanapol-bulletin', '12th-edition-aseanapol-bulletin', '13th-edition-aseanapol-magazine',
        '14th-edition-aseanapol-bulletin', '15th-edition-aseanapol-magazine',
    ];

    public function __invoke()
    {
        $base = request()->getSchemeAndHttpHost();
        $xml = Cache::remember('sitemap.xml.v1.'.md5($base), 3600, fn () => $this->build($base));

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    private function build(string $base): string
    {
        $urls = [];
        foreach (self::PAGES as $p) {
            $urls[$base.'/en'.$p] = null;
        }
        foreach (self::MEMBERS as $s) {
            $urls[$base.'/en/about-aseanapol/member-countries/'.$s] = null;
        }
        foreach (self::EDITIONS as $s) {
            $urls[$base.'/en/publication/'.$s] = null;
        }
        try {
            foreach (Publication::published()->get(['slug', 'updated_at']) as $p) {
                $urls[$base.'/en/publication/'.$p->slug] = $p->updated_at;
            }
            foreach (NewsItem::query()->whereNotNull('slug')->where('published_at', '<=', now())->orderByDesc('published_at')->get(['slug', 'published_at', 'updated_at']) as $n) {
                $urls[$base.'/en/news-media/news/'.$n->slug] = $n->updated_at ?? $n->published_at;
            }
        } catch (\Throwable $e) {
            // DB unavailable: static URLs only
        }

        $out = '<?xml version="1.0" encoding="UTF-8"?>'."\n".'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
        foreach ($urls as $loc => $mod) {
            $out .= '  <url><loc>'.htmlspecialchars($loc, ENT_XML1 | ENT_QUOTES, 'UTF-8').'</loc>'
                .($mod ? '<lastmod>'.$mod->toAtomString().'</lastmod>' : '').'</url>'."\n";
        }

        return $out.'</urlset>'."\n";
    }
}
