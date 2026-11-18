<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * The seeded Digital Library guideline links pointed to URLs that never existed (404). Correct exactly those three
 * URLs wherever they are stored; rows an admin has edited to anything else are not touched. Idempotent.
 */
return new class extends Migration
{
    private const MAP = [
        '/en/about-aseanapol/guidelines/observers-dialogue-partners' => '/en/guidelines/policy-guidelines-for-accepting-observers-and-dialogue-partners-with-aseanapol',
        '/en/about-aseanapol/guidelines/contact-persons'             => '/en/guidelines/guidelines-on-the-roles-and-functions-of-aseanapol-contact-persons',
        '/en/about-aseanapol/guidelines/donations'                   => '/en/guidelines/guidelines-for-accepting-donations-and-sponsorships',
    ];

    public function up(): void
    {
        if (! Schema::hasTable('digital_library_items')) {
            return;
        }
        foreach (self::MAP as $old => $new) {
            DB::table('digital_library_items')->where('external_url', $old)->update(['external_url' => $new]);
        }
    }

    public function down(): void
    {
    }
};
