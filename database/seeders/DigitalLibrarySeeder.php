<?php

namespace Database\Seeders;

use App\Models\DigitalLibraryCollection;
use App\Models\DigitalLibraryItem;
use Illuminate\Database\Seeder;

class DigitalLibrarySeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'name'       => 'ASEANAPOL Bulletins & Magazine',
                'icon'       => 'menu_book',
                'sort_order' => 1,
                'items'      => [
                    [
                        'title'        => 'ASEANAPOL Bulletin 2018 (11th Edition)',
                        'type'         => 'pdf',
                        'external_url' => '/media/bulletin/bulletin/aseanapol-bulletin-2018.pdf',
                        'is_published' => true,
                        'sort_order'   => 1,
                    ],
                ],
            ],
            [
                'name'       => 'Guidelines & Policy Documents',
                'icon'       => 'gavel',
                'sort_order' => 2,
                'items'      => [
                    [
                        'title'        => 'Guidelines on the Use of the ASEANAPOL Flag',
                        'type'         => 'pdf',
                        'external_url' => '/media/default-document-library/default-document-library/guidelines-aseanapol-flag-.pdf',
                        'is_published' => true,
                        'sort_order'   => 1,
                    ],
                    [
                        'title'        => 'Policy Guidelines — Accepting Observers & Dialogue Partners',
                        'type'         => 'link',
                        'external_url' => '/en/about-aseanapol/guidelines/observers-dialogue-partners',
                        'is_published' => true,
                        'sort_order'   => 2,
                    ],
                    [
                        'title'        => 'Guidelines on the Roles and Functions of Contact Persons',
                        'type'         => 'link',
                        'external_url' => '/en/about-aseanapol/guidelines/contact-persons',
                        'is_published' => true,
                        'sort_order'   => 3,
                    ],
                    [
                        'title'        => 'Guidelines for Accepting Donations and Sponsorships',
                        'type'         => 'link',
                        'external_url' => '/en/about-aseanapol/guidelines/donations',
                        'is_published' => true,
                        'sort_order'   => 4,
                    ],
                ],
            ],
            [
                'name'       => 'Publications Index',
                'icon'       => 'library_books',
                'sort_order' => 3,
                'items'      => [
                    ['title' => '14th Edition ASEANAPOL Bulletin (2025)', 'type' => 'link', 'external_url' => '/en/publication/14th-edition-aseanapol-bulletin', 'pdf_url' => '/media/bulletin/bulletin/aseanapol-bulletin-14-2025.pdf', 'is_published' => true, 'sort_order' => 0],
                    ['title' => '8th Edition ASEANAPOL Bulletin (2015)',  'type' => 'link', 'external_url' => '/en/publication/8th-edition-aseanapol-bulletin',  'pdf_url' => '/media/bulletin/bulletin/aseanapol-bulletin-8.pdf',        'is_published' => true, 'sort_order' => 1],
                    ['title' => '9th Edition ASEANAPOL Bulletin (2016)',  'type' => 'link', 'external_url' => '/en/publication/9th-edition-aseanapol-bulletin',  'pdf_url' => '/media/bulletin/bulletin/aseanapol-bulletin-9.pdf',        'is_published' => true, 'sort_order' => 2],
                    ['title' => '10th Edition ASEANAPOL Bulletin (2017)', 'type' => 'link', 'external_url' => '/en/publication/10th-edition-aseanapol-bulletin', 'pdf_url' => '/media/bulletin/bulletin/aseanapol-bulletin-10.pdf',       'is_published' => true, 'sort_order' => 3],
                    ['title' => '11th Edition ASEANAPOL Bulletin (2018)', 'type' => 'link', 'external_url' => '/en/publication/11th-edition-aseanapol-bulletin', 'pdf_url' => '/media/bulletin/bulletin/aseanapol-bulletin-2018.pdf',    'is_published' => true, 'sort_order' => 4],
                    ['title' => '12th Edition ASEANAPOL Bulletin (2019)', 'type' => 'link', 'external_url' => '/en/publication/12th-edition-aseanapol-bulletin', 'pdf_url' => '/media/bulletin/bulletin/aseanapol-bulletin-12.pdf',       'is_published' => true, 'sort_order' => 5],
                    ['title' => '13th Edition ASEANAPOL Magazine (2023)', 'type' => 'link', 'external_url' => '/en/publication/13th-edition-aseanapol-magazine', 'pdf_url' => '/media/bulletin/bulletin/aseanapol-bulletin-13.pdf',       'is_published' => true, 'sort_order' => 6],
                ],
            ],
        ];

        foreach ($data as $collectionData) {
            $items = $collectionData['items'];
            unset($collectionData['items']);

            $collection = DigitalLibraryCollection::firstOrCreate(
                ['name' => $collectionData['name']],
                $collectionData
            );

            foreach ($items as $item) {
                DigitalLibraryItem::firstOrCreate(
                    ['collection_id' => $collection->id, 'title' => $item['title']],
                    array_merge($item, ['collection_id' => $collection->id])
                );
            }
        }
    }
}
