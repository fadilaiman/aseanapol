<?php

namespace Database\Seeders;

use App\Models\VideoGallery;
use Illuminate\Database\Seeder;

class VideoGallerySeeder extends Seeder
{
    public function run(): void
    {
        $videos = [
            [
                'title'          => '14th ASEANAPOL Training Corporation Meeting (ACPM) on 5-6 March 2026 in Bangkok, Thailand',
                'file_url'       => 'media/videos/14th-acpm-bangkok-march-2026.mp4',
                'category'       => 'Conference',
                'event_location' => 'Bangkok, Thailand',
                'event_date'     => '2026-03-05',
                'is_published'   => true,
                'sort_order'     => 1,
            ],
            [
                'title'          => '8th ASEANAPOL Police Forensic Science Network Meeting (APFSN) on 9-11 March 2026 in Bangkok, Thailand',
                'file_url'       => 'media/videos/8th-apfsn-bangkok-march-2026.mp4',
                'category'       => 'Conference',
                'event_location' => 'Bangkok, Thailand',
                'event_date'     => '2026-03-09',
                'is_published'   => true,
                'sort_order'     => 2,
            ],
            [
                'title'          => '40th ASEANAPOL Database System Technical Committee (ADSTC) on 11-12 March 2026 in Bangkok, Thailand',
                'file_url'       => 'media/videos/40th-adstc-bangkok-march-2026.mp4',
                'category'       => 'Conference',
                'event_location' => 'Bangkok, Thailand',
                'event_date'     => '2026-03-11',
                'is_published'   => true,
                'sort_order'     => 3,
            ],
            [
                'title'          => '33rd Joint ASEANAPOL Senior Police Course (JASPOC) on 31 March – 2 April 2026 in Bangkok, Thailand',
                'file_url'       => 'media/videos/33rd-jaspoc-bangkok-march-2026.mp4',
                'category'       => 'Training',
                'event_location' => 'Bangkok, Thailand',
                'event_date'     => '2026-03-31',
                'is_published'   => true,
                'sort_order'     => 4,
            ],
            [
                'title'          => 'Official Visit Inspector General of Police Royal Malaysia Police to ASEANAPOL Secretariat Office on 06 April 2026',
                'file_url'       => 'media/videos/official-visit-igp-rmp-april-2026.mp4',
                'category'       => 'General',
                'event_location' => 'Kuala Lumpur, Malaysia',
                'event_date'     => '2026-04-06',
                'is_published'   => true,
                'sort_order'     => 5,
            ],
            [
                'title'          => '16th ASEANAPOL Contact Person Meeting (ACPM) from 21-24 April 2026 in Kuala Lumpur',
                'file_url'       => 'media/videos/16th-acpm-kuala-lumpur-april-2026.mp4',
                'category'       => 'Conference',
                'event_location' => 'Kuala Lumpur, Malaysia',
                'event_date'     => '2026-04-21',
                'is_published'   => true,
                'sort_order'     => 6,
            ],
        ];

        foreach ($videos as $video) {
            VideoGallery::create($video);
        }
    }
}
