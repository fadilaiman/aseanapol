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
                        'external_url' => '/en/guidelines/policy-guidelines-for-accepting-observers-and-dialogue-partners-with-aseanapol',
                        'is_published' => true,
                        'sort_order'   => 2,
                    ],
                    [
                        'title'        => 'Guidelines on the Roles and Functions of Contact Persons',
                        'type'         => 'link',
                        'external_url' => '/en/guidelines/guidelines-on-the-roles-and-functions-of-aseanapol-contact-persons',
                        'is_published' => true,
                        'sort_order'   => 3,
                    ],
                    [
                        'title'        => 'Guidelines for Accepting Donations and Sponsorships',
                        'type'         => 'link',
                        'external_url' => '/en/guidelines/guidelines-for-accepting-donations-and-sponsorships',
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
                    // Single link into the canonical Publications page (resources/views/publication/index.blade.php)
                    // instead of duplicating every edition here — that page's $publications array is the one place
                    // to update when a new edition is added.
                    ['title' => 'ASEANAPOL Bulletins & Magazine — All Editions', 'type' => 'link', 'external_url' => '/en/data-resources/publications', 'is_published' => true, 'sort_order' => 0],
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
