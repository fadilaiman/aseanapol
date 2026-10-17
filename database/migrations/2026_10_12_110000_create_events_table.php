<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('events')) {
            Schema::create('events', function (Blueprint $table) {
                $table->id();
                $table->string('kind', 20)->index();           // calendar | conference | training
                $table->string('title');
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->string('city')->nullable();
                $table->string('country')->nullable();
                $table->string('type', 40)->nullable();        // calendar: Conference|Meeting|Training|Activity
                $table->text('description')->nullable();
                $table->string('link', 500)->nullable();
                $table->string('link_label', 120)->nullable();
                $table->string('image')->nullable();
                // conference-specific
                $table->string('conf_number', 20)->nullable(); // e.g. 44th
                $table->unsignedSmallInteger('conf_year')->nullable();
                $table->string('dates_label')->nullable();     // e.g. 21–25 October 2024
                $table->string('flag', 16)->nullable();
                $table->string('theme', 500)->nullable();
                $table->string('status', 20)->nullable();      // upcoming | past | suspended
                // training-specific
                $table->string('abbr', 30)->nullable();
                $table->string('icon', 60)->nullable();
                $table->string('category', 60)->nullable();
                $table->string('frequency', 60)->nullable();
                $table->string('latest')->nullable();
                $table->boolean('is_published')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        // Seed the content that used to be hardcoded in resources/views/events-training/*
        if (DB::table('events')->count() === 0) {
            $now = now();
            $cols = ['start_date', 'end_date', 'city', 'country', 'type', 'description', 'link', 'link_label', 'image', 'conf_number', 'conf_year',
                'dates_label', 'flag', 'theme', 'status', 'abbr', 'icon', 'category', 'frequency', 'latest'];
            $rows = [];
            foreach ($this->rows() as $r) {
                $row = array_fill_keys($cols, null);
                $rows[] = array_merge($row, $r, ['is_published' => true, 'created_at' => $now, 'updated_at' => $now]);
            }
            foreach (array_chunk($rows, 50) as $chunk) {
                DB::table('events')->insert($chunk);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }

    private function rows(): array
    {
        return array (
  0 => 
  array (
    'kind' => 'calendar',
    'title' => '14th ASEANAPOL Training Cooperation Meeting (ATCM)',
    'start_date' => '2026-03-05',
    'end_date' => '2026-03-06',
    'city' => 'Bangkok',
    'country' => 'Thailand',
    'type' => 'Meeting',
    'sort_order' => 1,
  ),
  1 => 
  array (
    'kind' => 'calendar',
    'title' => '8th ASEANAPOL Police Forensic Science Network Meeting (APFSN)',
    'start_date' => '2026-03-09',
    'end_date' => '2026-03-11',
    'city' => 'Bangkok',
    'country' => 'Thailand',
    'type' => 'Meeting',
    'sort_order' => 2,
  ),
  2 => 
  array (
    'kind' => 'calendar',
    'title' => '40th ASEANAPOL Database System Technical Committee (ADSTC)',
    'start_date' => '2026-03-11',
    'end_date' => '2026-03-12',
    'city' => 'Bangkok',
    'country' => 'Thailand',
    'type' => 'Meeting',
    'sort_order' => 3,
  ),
  3 => 
  array (
    'kind' => 'calendar',
    'title' => '33rd Joint ASEANAPOL Senior Police Course (JASPOC)',
    'start_date' => '2026-03-31',
    'end_date' => '2026-04-02',
    'city' => 'Bangkok',
    'country' => 'Thailand',
    'type' => 'Training',
    'sort_order' => 4,
  ),
  4 => 
  array (
    'kind' => 'calendar',
    'title' => '16th ASEANAPOL Contact Person Meeting (ACPM)',
    'start_date' => '2026-04-21',
    'end_date' => '2026-04-24',
    'city' => 'Kuala Lumpur',
    'country' => 'Malaysia',
    'type' => 'Meeting',
    'sort_order' => 5,
  ),
  5 => 
  array (
    'kind' => 'calendar',
    'title' => 'Pre Coordination Meeting for 44th ASEANAPOL Conference 2026',
    'start_date' => '2026-06-02',
    'end_date' => '2026-06-02',
    'city' => 'Manila',
    'country' => 'Philippines',
    'type' => 'Meeting',
    'sort_order' => 6,
  ),
  6 => 
  array (
    'kind' => 'calendar',
    'title' => '44th ASEANAPOL Conference',
    'start_date' => '2026-07-22',
    'end_date' => '2026-07-26',
    'city' => 'Manila',
    'country' => 'Philippines',
    'type' => 'Conference',
    'sort_order' => 7,
  ),
  7 => 
  array (
    'kind' => 'calendar',
    'title' => '43rd ASEANAPOL Conference (Forthcoming)',
    'start_date' => '2025-10-01',
    'end_date' => '2025-10-01',
    'city' => 'Bangkok',
    'country' => 'Thailand',
    'type' => 'Conference',
    'sort_order' => 8,
  ),
  8 => 
  array (
    'kind' => 'calendar',
    'title' => '13th ASEANAPOL Training Cooperation Meeting (ATCM)',
    'start_date' => '2025-07-01',
    'end_date' => '2025-07-01',
    'city' => NULL,
    'country' => NULL,
    'type' => 'Meeting',
    'sort_order' => 9,
  ),
  9 => 
  array (
    'kind' => 'calendar',
    'title' => '39th ASEANAPOL Database System Technical Committee (ADSTC) Meeting',
    'start_date' => '2025-03-01',
    'end_date' => '2025-03-01',
    'city' => NULL,
    'country' => NULL,
    'type' => 'Meeting',
    'sort_order' => 10,
  ),
  10 => 
  array (
    'kind' => 'calendar',
    'title' => '3rd ASEANAPOL Contact Persons Quarterly Discussion (ACPQD) – Virtual',
    'start_date' => '2024-12-18',
    'end_date' => '2024-12-18',
    'city' => NULL,
    'country' => 'Virtual',
    'type' => 'Meeting',
    'sort_order' => 11,
  ),
  11 => 
  array (
    'kind' => 'calendar',
    'title' => '42nd ASEANAPOL Conference',
    'start_date' => '2024-10-21',
    'end_date' => '2024-10-25',
    'city' => 'Nay Pyi Taw',
    'country' => 'Myanmar',
    'type' => 'Conference',
    'sort_order' => 12,
  ),
  12 => 
  array (
    'kind' => 'calendar',
    'title' => '14th ASEANAPOL Contact Persons Meeting (ACPM)',
    'start_date' => '2024-07-08',
    'end_date' => '2024-07-08',
    'city' => 'Kuala Lumpur',
    'country' => 'Malaysia',
    'type' => 'Meeting',
    'sort_order' => 13,
  ),
  13 => 
  array (
    'kind' => 'calendar',
    'title' => '12th ASEANAPOL Training Cooperation Meeting (ATCM)',
    'start_date' => '2024-06-01',
    'end_date' => '2024-06-01',
    'city' => NULL,
    'country' => NULL,
    'type' => 'Meeting',
    'sort_order' => 14,
  ),
  14 => 
  array (
    'kind' => 'calendar',
    'title' => '38th ASEANAPOL Database System Technical Committee (ADSTC) Meeting',
    'start_date' => '2024-02-13',
    'end_date' => '2024-02-14',
    'city' => 'Vientiane',
    'country' => 'Lao PDR',
    'type' => 'Meeting',
    'sort_order' => 15,
  ),
  15 => 
  array (
    'kind' => 'calendar',
    'title' => '41st ASEANAPOL Conference',
    'start_date' => '2023-10-17',
    'end_date' => '2023-10-19',
    'city' => 'Vientiane',
    'country' => 'Laos',
    'type' => 'Conference',
    'sort_order' => 16,
  ),
  16 => 
  array (
    'kind' => 'calendar',
    'title' => '13th ASEANAPOL Contact Persons Meeting (ACPM)',
    'start_date' => '2023-09-01',
    'end_date' => '2023-09-01',
    'city' => 'Kuala Lumpur',
    'country' => 'Malaysia',
    'type' => 'Meeting',
    'sort_order' => 17,
  ),
  17 => 
  array (
    'kind' => 'calendar',
    'title' => 'Regional Anti-Scam Conference on Countering Cyber-Scam',
    'start_date' => '2023-06-13',
    'end_date' => '2023-06-15',
    'city' => 'Singapore',
    'country' => 'Singapore',
    'type' => 'Conference',
    'sort_order' => 18,
  ),
  18 => 
  array (
    'kind' => 'calendar',
    'title' => '37th ASEANAPOL Database System Technical Committee (ADSTC) Meeting',
    'start_date' => '2023-05-01',
    'end_date' => '2023-05-01',
    'city' => NULL,
    'country' => 'Cambodia',
    'type' => 'Meeting',
    'sort_order' => 19,
  ),
  19 => 
  array (
    'kind' => 'calendar',
    'title' => '11th ASEANAPOL Training Cooperation Meeting (ATCM)',
    'start_date' => '2023-03-22',
    'end_date' => '2023-03-23',
    'city' => 'Phnom Penh',
    'country' => 'Cambodia',
    'type' => 'Meeting',
    'sort_order' => 20,
  ),
  20 => 
  array (
    'kind' => 'calendar',
    'title' => '1st ASEANAPOL Contact Persons Quarterly Discussion (ACPQD) 2023 – Virtual',
    'start_date' => '2023-03-15',
    'end_date' => '2023-03-15',
    'city' => NULL,
    'country' => 'Virtual',
    'type' => 'Meeting',
    'sort_order' => 21,
  ),
  21 => 
  array (
    'kind' => 'calendar',
    'title' => '16th ASEAN Ministerial Meeting on Transnational Crime (AMMTC)',
    'start_date' => '2022-09-21',
    'end_date' => '2022-09-21',
    'city' => 'Phnom Penh',
    'country' => 'Cambodia',
    'type' => 'Meeting',
    'sort_order' => 22,
  ),
  22 => 
  array (
    'kind' => 'calendar',
    'title' => 'ASEANAPOL Child Sexual Abuse and Exploitation Conference',
    'start_date' => '2022-09-06',
    'end_date' => '2022-09-08',
    'city' => 'Manila',
    'country' => 'Philippines',
    'type' => 'Conference',
    'sort_order' => 23,
  ),
  23 => 
  array (
    'kind' => 'calendar',
    'title' => '40th ASEANAPOL Conference',
    'start_date' => '2022-03-01',
    'end_date' => '2022-03-05',
    'city' => 'Phnom Penh',
    'country' => 'Cambodia',
    'type' => 'Conference',
    'sort_order' => 24,
  ),
  24 => 
  array (
    'kind' => 'calendar',
    'title' => '39th ASEANAPOL Conference',
    'start_date' => '2019-09-17',
    'end_date' => '2019-09-19',
    'city' => 'Hanoi',
    'country' => 'Viet Nam',
    'type' => 'Conference',
    'sort_order' => 25,
  ),
  25 => 
  array (
    'kind' => 'calendar',
    'title' => '9th ASEANAPOL Training Cooperation Meeting (ATCM)',
    'start_date' => '2019-01-22',
    'end_date' => '2019-01-23',
    'city' => NULL,
    'country' => 'Brunei Darussalam',
    'type' => 'Meeting',
    'sort_order' => 26,
  ),
  26 => 
  array (
    'kind' => 'calendar',
    'title' => '38th ASEANAPOL Conference',
    'start_date' => '2018-09-03',
    'end_date' => '2018-09-05',
    'city' => NULL,
    'country' => 'Brunei Darussalam',
    'type' => 'Conference',
    'sort_order' => 27,
  ),
  27 => 
  array (
    'kind' => 'calendar',
    'title' => '35th ASEANAPOL Database System Technical Committee (ADSTC) Meeting',
    'start_date' => '2018-05-08',
    'end_date' => '2018-05-09',
    'city' => NULL,
    'country' => 'Brunei Darussalam',
    'type' => 'Meeting',
    'sort_order' => 28,
  ),
  28 => 
  array (
    'kind' => 'calendar',
    'title' => '37th ASEANAPOL Conference',
    'start_date' => '2017-09-11',
    'end_date' => '2017-09-15',
    'city' => NULL,
    'country' => 'Singapore',
    'type' => 'Conference',
    'sort_order' => 29,
  ),
  29 => 
  array (
    'kind' => 'calendar',
    'title' => '8th ASEANAPOL Contact Persons Meeting (ACPM)',
    'start_date' => '2017-07-01',
    'end_date' => '2017-07-01',
    'city' => 'Kuala Lumpur',
    'country' => 'Malaysia',
    'type' => 'Meeting',
    'sort_order' => 30,
  ),
  30 => 
  array (
    'kind' => 'calendar',
    'title' => '7th ASEANAPOL Contact Persons Meeting (ACPM)',
    'start_date' => '2016-12-05',
    'end_date' => '2016-12-09',
    'city' => 'Johor Bahru',
    'country' => 'Malaysia',
    'type' => 'Meeting',
    'sort_order' => 31,
  ),
  31 => 
  array (
    'kind' => 'calendar',
    'title' => '36th ASEANAPOL Conference',
    'start_date' => '2016-07-24',
    'end_date' => '2016-07-29',
    'city' => 'Putrajaya',
    'country' => 'Malaysia',
    'type' => 'Conference',
    'sort_order' => 32,
  ),
  32 => 
  array (
    'kind' => 'calendar',
    'title' => '35th ASEANAPOL Conference',
    'start_date' => '2015-08-03',
    'end_date' => '2015-08-07',
    'city' => 'Jakarta',
    'country' => 'Indonesia',
    'type' => 'Conference',
    'sort_order' => 33,
  ),
  33 => 
  array (
    'kind' => 'calendar',
    'title' => '5th ASEANAPOL Contact Persons Meeting (ACPM)',
    'start_date' => '2014-09-29',
    'end_date' => '2014-10-03',
    'city' => 'Kuala Lumpur',
    'country' => 'Malaysia',
    'type' => 'Meeting',
    'sort_order' => 34,
  ),
  34 => 
  array (
    'kind' => 'calendar',
    'title' => '34th ASEANAPOL Conference',
    'start_date' => '2014-05-12',
    'end_date' => '2014-05-16',
    'city' => 'Manila',
    'country' => 'Philippines',
    'type' => 'Conference',
    'sort_order' => 35,
  ),
  35 => 
  array (
    'kind' => 'calendar',
    'title' => '31st ASEANAPOL Database System Technical Committee (ADSTC) Meeting',
    'start_date' => '2014-03-17',
    'end_date' => '2014-03-21',
    'city' => NULL,
    'country' => 'Philippines',
    'type' => 'Meeting',
    'sort_order' => 36,
  ),
  36 => 
  array (
    'kind' => 'calendar',
    'title' => 'ASEANAPOL Secretariat Annual Shooting Exercise',
    'start_date' => '2013-09-18',
    'end_date' => '2013-09-18',
    'city' => NULL,
    'country' => 'Malaysia',
    'type' => 'Activity',
    'sort_order' => 37,
  ),
  37 => 
  array (
    'kind' => 'calendar',
    'title' => '5th INTERPOL-ASEANAPOL System Integration Working Group Meeting',
    'start_date' => '2013-08-01',
    'end_date' => '2013-08-07',
    'city' => 'Pattaya',
    'country' => 'Thailand',
    'type' => 'Meeting',
    'sort_order' => 38,
  ),
  38 => 
  array (
    'kind' => 'calendar',
    'title' => '33rd ASEANAPOL Conference',
    'start_date' => '2013-02-19',
    'end_date' => '2013-02-21',
    'city' => 'Pattaya',
    'country' => 'Thailand',
    'type' => 'Conference',
    'sort_order' => 39,
  ),
  39 => 
  array (
    'kind' => 'calendar',
    'title' => '3rd ASEANAPOL Police Training Cooperation Meeting',
    'start_date' => '2012-12-04',
    'end_date' => '2012-12-08',
    'city' => NULL,
    'country' => 'Cambodia',
    'type' => 'Training',
    'sort_order' => 40,
  ),
  40 => 
  array (
    'kind' => 'calendar',
    'title' => '28th ADSTC Meeting',
    'start_date' => '2012-10-16',
    'end_date' => '2012-10-18',
    'city' => NULL,
    'country' => 'Singapore',
    'type' => 'Meeting',
    'sort_order' => 41,
  ),
  41 => 
  array (
    'kind' => 'calendar',
    'title' => '3rd ASEANAPOL Contact Persons Meeting',
    'start_date' => '2012-10-02',
    'end_date' => '2012-10-04',
    'city' => 'Kuala Lumpur',
    'country' => 'Malaysia',
    'type' => 'Meeting',
    'sort_order' => 42,
  ),
  42 => 
  array (
    'kind' => 'calendar',
    'title' => '32nd ASEANAPOL Conference',
    'start_date' => '2012-05-22',
    'end_date' => '2012-05-24',
    'city' => 'Nay Pyi Taw',
    'country' => 'Myanmar',
    'type' => 'Conference',
    'sort_order' => 43,
  ),
  43 => 
  array (
    'kind' => 'calendar',
    'title' => 'ADSTC Meeting in Nay Pyi Taw',
    'start_date' => '2012-02-20',
    'end_date' => '2012-02-24',
    'city' => 'Nay Pyi Taw',
    'country' => 'Myanmar',
    'type' => 'Meeting',
    'sort_order' => 44,
  ),
  44 => 
  array (
    'kind' => 'calendar',
    'title' => 'JASPOC in Vientiane',
    'start_date' => '2012-02-13',
    'end_date' => '2012-02-22',
    'city' => 'Vientiane',
    'country' => 'Laos',
    'type' => 'Meeting',
    'sort_order' => 45,
  ),
  45 => 
  array (
    'kind' => 'calendar',
    'title' => 'ASEANAPOL Police Training Cooperation Meeting',
    'start_date' => '2011-12-07',
    'end_date' => '2011-12-10',
    'city' => 'Vientiane',
    'country' => 'Laos',
    'type' => 'Training',
    'sort_order' => 46,
  ),
  46 => 
  array (
    'kind' => 'calendar',
    'title' => '2nd ASEANAPOL Contact Person Meeting',
    'start_date' => '2011-11-15',
    'end_date' => '2011-11-18',
    'city' => 'Kuala Lumpur',
    'country' => 'Malaysia',
    'type' => 'Meeting',
    'sort_order' => 47,
  ),
  47 => 
  array (
    'kind' => 'calendar',
    'title' => 'ADSTC Meeting in Manila',
    'start_date' => '2011-10-17',
    'end_date' => '2011-10-21',
    'city' => 'Manila',
    'country' => 'Philippines',
    'type' => 'Meeting',
    'sort_order' => 48,
  ),
  48 => 
  array (
    'kind' => 'calendar',
    'title' => '31st ASEANAPOL Conference',
    'start_date' => '2011-05-31',
    'end_date' => '2011-06-02',
    'city' => 'Vientiane Capital',
    'country' => 'Lao PDR',
    'type' => 'Conference',
    'sort_order' => 49,
  ),
  49 => 
  array (
    'kind' => 'calendar',
    'title' => 'Fraudulent Travel Documents ASEAN Police Forces Course',
    'start_date' => '2011-05-22',
    'end_date' => '2011-05-27',
    'city' => 'Kuala Lumpur',
    'country' => 'Malaysia',
    'type' => 'Training',
    'sort_order' => 50,
  ),
  50 => 
  array (
    'kind' => 'calendar',
    'title' => '1st Joint Narcotics Course by Royal Malaysia Police',
    'start_date' => '2011-03-18',
    'end_date' => '2011-04-05',
    'city' => 'Kuala Lumpur',
    'country' => 'Malaysia',
    'type' => 'Training',
    'sort_order' => 51,
  ),
  51 => 
  array (
    'kind' => 'calendar',
    'title' => 'ADSTC Meeting',
    'start_date' => '2011-03-14',
    'end_date' => '2011-03-19',
    'city' => 'Vientiane',
    'country' => 'Lao PDR',
    'type' => 'Meeting',
    'sort_order' => 52,
  ),
  52 => 
  array (
    'kind' => 'calendar',
    'title' => 'Counter Terrorism Radicalization & Deradicalization Course',
    'start_date' => '2011-03-06',
    'end_date' => '2011-03-11',
    'city' => 'Kuala Lumpur',
    'country' => 'Malaysia',
    'type' => 'Training',
    'sort_order' => 53,
  ),
  53 => 
  array (
    'kind' => 'calendar',
    'title' => 'Anti Trafficking In Person & Human Smuggling Course',
    'start_date' => '2011-03-05',
    'end_date' => '2011-06-05',
    'city' => 'Kuala Lumpur',
    'country' => 'Malaysia',
    'type' => 'Training',
    'sort_order' => 54,
  ),
  54 => 
  array (
    'kind' => 'calendar',
    'title' => 'C3 Workshop and Joint ASEAN Senior Police Officer\'s Course',
    'start_date' => '2011-02-01',
    'end_date' => '2011-02-28',
    'city' => NULL,
    'country' => 'Cambodia',
    'type' => 'Training',
    'sort_order' => 55,
  ),
  55 => 
  array (
    'kind' => 'calendar',
    'title' => '6th INTERPOL-ASEANAPOL System Integration Meeting',
    'start_date' => '2011-01-01',
    'end_date' => '2011-01-31',
    'city' => NULL,
    'country' => 'Thailand',
    'type' => 'Meeting',
    'sort_order' => 56,
  ),
  56 => 
  array (
    'kind' => 'calendar',
    'title' => 'Police Training Cooperation',
    'start_date' => '2010-12-07',
    'end_date' => '2010-12-10',
    'city' => 'Hanoi',
    'country' => 'Vietnam',
    'type' => 'Training',
    'sort_order' => 57,
  ),
  57 => 
  array (
    'kind' => 'calendar',
    'title' => '7th Meeting on Organized Crime in the East Asian Region',
    'start_date' => '2010-11-24',
    'end_date' => '2010-11-26',
    'city' => NULL,
    'country' => 'Japan',
    'type' => 'Meeting',
    'sort_order' => 58,
  ),
  58 => 
  array (
    'kind' => 'calendar',
    'title' => '24th ADSTC Meeting',
    'start_date' => '2010-11-22',
    'end_date' => '2010-11-25',
    'city' => 'Chiangrai',
    'country' => 'Thailand',
    'type' => 'Meeting',
    'sort_order' => 59,
  ),
  59 => 
  array (
    'kind' => 'calendar',
    'title' => '1st ASEANAPOL Contact Person Meeting',
    'start_date' => '2010-10-14',
    'end_date' => '2010-10-16',
    'city' => 'Kuala Lumpur',
    'country' => 'Malaysia',
    'type' => 'Meeting',
    'sort_order' => 60,
  ),
  60 => 
  array (
    'kind' => 'calendar',
    'title' => 'Official Visit by Executive Director to INTERPOL-EUROPOL Headquarters',
    'start_date' => '2010-10-03',
    'end_date' => '2010-10-12',
    'city' => NULL,
    'country' => 'Europe',
    'type' => 'Activity',
    'sort_order' => 61,
  ),
  61 => 
  array (
    'kind' => 'calendar',
    'title' => 'Official Launching of ASEANAPOL Website',
    'start_date' => '2010-05-28',
    'end_date' => '2010-05-28',
    'city' => 'Phnom Penh',
    'country' => 'Cambodia',
    'type' => 'Activity',
    'sort_order' => 62,
  ),
  62 => 
  array (
    'kind' => 'calendar',
    'title' => '30th ASEANAPOL Conference',
    'start_date' => '2010-05-24',
    'end_date' => '2010-05-28',
    'city' => 'Phnom Penh',
    'country' => 'Cambodia',
    'type' => 'Conference',
    'sort_order' => 63,
  ),
  63 => 
  array (
    'kind' => 'conference',
    'title' => '46th ASEANAPOL Conference',
    'conf_number' => '46th',
    'conf_year' => 2026,
    'dates_label' => '22–26 July 2026',
    'city' => 'Manila',
    'country' => 'Philippines',
    'flag' => '🇵🇭',
    'theme' => NULL,
    'status' => 'upcoming',
    'sort_order' => 1,
  ),
  64 => 
  array (
    'kind' => 'conference',
    'title' => '45th ASEANAPOL Conference',
    'conf_number' => '45th',
    'conf_year' => 2025,
    'dates_label' => '3–7 November 2025',
    'city' => 'Bangkok',
    'country' => 'Thailand',
    'flag' => '🇹🇭',
    'theme' => NULL,
    'status' => 'upcoming',
    'sort_order' => 2,
  ),
  65 => 
  array (
    'kind' => 'conference',
    'title' => '44th ASEANAPOL Conference',
    'conf_number' => '44th',
    'conf_year' => 2024,
    'dates_label' => '21–25 October 2024',
    'city' => 'Nay Pyi Taw',
    'country' => 'Myanmar',
    'flag' => '🇲🇲',
    'theme' => 'Strengthening Regional and Global Partnerships to Combat Transnational Crimes',
    'status' => 'past',
    'sort_order' => 3,
  ),
  66 => 
  array (
    'kind' => 'conference',
    'title' => '43rd ASEANAPOL Conference',
    'conf_number' => '43rd',
    'conf_year' => 2023,
    'dates_label' => '17–19 October 2023',
    'city' => 'Vientiane',
    'country' => 'Lao PDR',
    'flag' => '🇱🇦',
    'theme' => NULL,
    'status' => 'past',
    'sort_order' => 4,
  ),
  67 => 
  array (
    'kind' => 'conference',
    'title' => '42nd ASEANAPOL Conference',
    'conf_number' => '42nd',
    'conf_year' => 2022,
    'dates_label' => '1–5 March 2022',
    'city' => 'Phnom Penh',
    'country' => 'Cambodia',
    'flag' => '🇰🇭',
    'theme' => NULL,
    'status' => 'past',
    'sort_order' => 5,
  ),
  68 => 
  array (
    'kind' => 'conference',
    'title' => '41st ASEANAPOL Conference',
    'conf_number' => '41st',
    'conf_year' => 2021,
    'dates_label' => NULL,
    'city' => NULL,
    'country' => NULL,
    'flag' => NULL,
    'theme' => NULL,
    'status' => 'suspended',
    'sort_order' => 6,
  ),
  69 => 
  array (
    'kind' => 'conference',
    'title' => '40th ASEANAPOL Conference',
    'conf_number' => '40th',
    'conf_year' => 2020,
    'dates_label' => NULL,
    'city' => NULL,
    'country' => NULL,
    'flag' => NULL,
    'theme' => NULL,
    'status' => 'suspended',
    'sort_order' => 7,
  ),
  70 => 
  array (
    'kind' => 'conference',
    'title' => '39th ASEANAPOL Conference',
    'conf_number' => '39th',
    'conf_year' => 2019,
    'dates_label' => '16–20 September 2019',
    'city' => 'Hanoi',
    'country' => 'Viet Nam',
    'flag' => '🇻🇳',
    'theme' => 'Honoured By Partnerships, Sustained By Unity',
    'status' => 'past',
    'sort_order' => 8,
  ),
  71 => 
  array (
    'kind' => 'conference',
    'title' => '38th ASEANAPOL Conference',
    'conf_number' => '38th',
    'conf_year' => 2018,
    'dates_label' => '3–5 September 2018',
    'city' => 'Brunei',
    'country' => 'Brunei Darussalam',
    'flag' => '🇧🇳',
    'theme' => NULL,
    'status' => 'past',
    'sort_order' => 9,
  ),
  72 => 
  array (
    'kind' => 'conference',
    'title' => '37th ASEANAPOL Conference',
    'conf_number' => '37th',
    'conf_year' => 2017,
    'dates_label' => '12–14 September 2017',
    'city' => 'Singapore',
    'country' => 'Singapore',
    'flag' => '🇸🇬',
    'theme' => NULL,
    'status' => 'past',
    'sort_order' => 10,
  ),
  73 => 
  array (
    'kind' => 'conference',
    'title' => '36th ASEANAPOL Conference',
    'conf_number' => '36th',
    'conf_year' => 2016,
    'dates_label' => '24–29 July 2016',
    'city' => 'Kuala Lumpur',
    'country' => 'Malaysia',
    'flag' => '🇲🇾',
    'theme' => NULL,
    'status' => 'past',
    'sort_order' => 11,
  ),
  74 => 
  array (
    'kind' => 'conference',
    'title' => '35th ASEANAPOL Conference',
    'conf_number' => '35th',
    'conf_year' => 2015,
    'dates_label' => '3–7 August 2015',
    'city' => 'Jakarta',
    'country' => 'Indonesia',
    'flag' => '🇮🇩',
    'theme' => NULL,
    'status' => 'past',
    'sort_order' => 12,
  ),
  75 => 
  array (
    'kind' => 'conference',
    'title' => '34th ASEANAPOL Conference',
    'conf_number' => '34th',
    'conf_year' => 2014,
    'dates_label' => '12–16 May 2014',
    'city' => 'Manila',
    'country' => 'Philippines',
    'flag' => '🇵🇭',
    'theme' => NULL,
    'status' => 'past',
    'sort_order' => 13,
  ),
  76 => 
  array (
    'kind' => 'conference',
    'title' => '33rd ASEANAPOL Conference',
    'conf_number' => '33rd',
    'conf_year' => 2013,
    'dates_label' => '21–23 February 2013',
    'city' => 'Pattaya',
    'country' => 'Thailand',
    'flag' => '🇹🇭',
    'theme' => NULL,
    'status' => 'past',
    'sort_order' => 14,
  ),
  77 => 
  array (
    'kind' => 'conference',
    'title' => '32nd ASEANAPOL Conference',
    'conf_number' => '32nd',
    'conf_year' => 2012,
    'dates_label' => '22–24 May 2012',
    'city' => 'Nay Pyi Taw',
    'country' => 'Myanmar',
    'flag' => '🇲🇲',
    'theme' => NULL,
    'status' => 'past',
    'sort_order' => 15,
  ),
  78 => 
  array (
    'kind' => 'conference',
    'title' => '31st ASEANAPOL Conference',
    'conf_number' => '31st',
    'conf_year' => 2011,
    'dates_label' => '31 May – 2 June 2011',
    'city' => 'Vientiane',
    'country' => 'Lao PDR',
    'flag' => '🇱🇦',
    'theme' => NULL,
    'status' => 'past',
    'sort_order' => 16,
  ),
  79 => 
  array (
    'kind' => 'conference',
    'title' => '30th ASEANAPOL Conference',
    'conf_number' => '30th',
    'conf_year' => 2010,
    'dates_label' => '24–28 May 2010',
    'city' => 'Phnom Penh',
    'country' => 'Cambodia',
    'flag' => '🇰🇭',
    'theme' => NULL,
    'status' => 'past',
    'sort_order' => 17,
  ),
  80 => 
  array (
    'kind' => 'conference',
    'title' => '29th ASEANAPOL Conference',
    'conf_number' => '29th',
    'conf_year' => 2009,
    'dates_label' => '13–15 May 2009',
    'city' => 'Hanoi',
    'country' => 'Viet Nam',
    'flag' => '🇻🇳',
    'theme' => NULL,
    'status' => 'past',
    'sort_order' => 18,
  ),
  81 => 
  array (
    'kind' => 'conference',
    'title' => '28th ASEANAPOL Conference',
    'conf_number' => '28th',
    'conf_year' => 2008,
    'dates_label' => '25–29 May 2008',
    'city' => 'Brunei',
    'country' => 'Brunei Darussalam',
    'flag' => '🇧🇳',
    'theme' => NULL,
    'status' => 'past',
    'sort_order' => 19,
  ),
  82 => 
  array (
    'kind' => 'conference',
    'title' => '27th ASEANAPOL Conference',
    'conf_number' => '27th',
    'conf_year' => 2007,
    'dates_label' => '2–7 June 2007',
    'city' => 'Singapore',
    'country' => 'Singapore',
    'flag' => '🇸🇬',
    'theme' => NULL,
    'status' => 'past',
    'sort_order' => 20,
  ),
  83 => 
  array (
    'kind' => 'conference',
    'title' => '26th ASEANAPOL Conference',
    'conf_number' => '26th',
    'conf_year' => 2006,
    'dates_label' => '22–26 May 2006',
    'city' => 'Kuala Lumpur',
    'country' => 'Malaysia',
    'flag' => '🇲🇾',
    'theme' => NULL,
    'status' => 'past',
    'sort_order' => 21,
  ),
  84 => 
  array (
    'kind' => 'conference',
    'title' => '25th ASEANAPOL Conference',
    'conf_number' => '25th',
    'conf_year' => 2005,
    'dates_label' => '16–20 May 2005',
    'city' => 'Bali',
    'country' => 'Indonesia',
    'flag' => '🇮🇩',
    'theme' => NULL,
    'status' => 'past',
    'sort_order' => 22,
  ),
  85 => 
  array (
    'kind' => 'conference',
    'title' => '24th ASEANAPOL Conference',
    'conf_number' => '24th',
    'conf_year' => 2004,
    'dates_label' => '16–20 August 2004',
    'city' => 'Chiang Mai',
    'country' => 'Thailand',
    'flag' => '🇹🇭',
    'theme' => NULL,
    'status' => 'past',
    'sort_order' => 23,
  ),
  86 => 
  array (
    'kind' => 'conference',
    'title' => '23rd ASEANAPOL Conference',
    'conf_number' => '23rd',
    'conf_year' => 2003,
    'dates_label' => '8–12 September 2003',
    'city' => 'Manila',
    'country' => 'Philippines',
    'flag' => '🇵🇭',
    'theme' => NULL,
    'status' => 'past',
    'sort_order' => 24,
  ),
  87 => 
  array (
    'kind' => 'conference',
    'title' => '22nd ASEANAPOL Conference',
    'conf_number' => '22nd',
    'conf_year' => 2002,
    'dates_label' => '28–30 May 2002',
    'city' => 'Phnom Penh',
    'country' => 'Cambodia',
    'flag' => '🇰🇭',
    'theme' => NULL,
    'status' => 'past',
    'sort_order' => 25,
  ),
  88 => 
  array (
    'kind' => 'conference',
    'title' => '21st ASEANAPOL Conference',
    'conf_number' => '21st',
    'conf_year' => 2001,
    'dates_label' => '23–25 May 2001',
    'city' => 'Vientiane',
    'country' => 'Lao PDR',
    'flag' => '🇱🇦',
    'theme' => NULL,
    'status' => 'past',
    'sort_order' => 26,
  ),
  89 => 
  array (
    'kind' => 'conference',
    'title' => '20th ASEANAPOL Conference',
    'conf_number' => '20th',
    'conf_year' => 2000,
    'dates_label' => '8–10 May 2000',
    'city' => 'Yangon',
    'country' => 'Myanmar',
    'flag' => '🇲🇲',
    'theme' => NULL,
    'status' => 'past',
    'sort_order' => 27,
  ),
  90 => 
  array (
    'kind' => 'conference',
    'title' => '19th ASEANAPOL Conference',
    'conf_number' => '19th',
    'conf_year' => 1999,
    'dates_label' => '26–28 April 1999',
    'city' => 'Hanoi',
    'country' => 'Viet Nam',
    'flag' => '🇻🇳',
    'theme' => NULL,
    'status' => 'past',
    'sort_order' => 28,
  ),
  91 => 
  array (
    'kind' => 'conference',
    'title' => '18th ASEANAPOL Conference',
    'conf_number' => '18th',
    'conf_year' => 1998,
    'dates_label' => '24–27 May 1998',
    'city' => 'Brunei',
    'country' => 'Brunei Darussalam',
    'flag' => '🇧🇳',
    'theme' => NULL,
    'status' => 'past',
    'sort_order' => 29,
  ),
  92 => 
  array (
    'kind' => 'conference',
    'title' => '17th ASEANAPOL Conference',
    'conf_number' => '17th',
    'conf_year' => 1997,
    'dates_label' => '1–5 June 1997',
    'city' => 'Singapore',
    'country' => 'Singapore',
    'flag' => '🇸🇬',
    'theme' => NULL,
    'status' => 'past',
    'sort_order' => 30,
  ),
  93 => 
  array (
    'kind' => 'conference',
    'title' => '16th ASEANAPOL Conference',
    'conf_number' => '16th',
    'conf_year' => 1996,
    'dates_label' => '26–30 May 1996',
    'city' => 'Kuala Lumpur',
    'country' => 'Malaysia',
    'flag' => '🇲🇾',
    'theme' => NULL,
    'status' => 'past',
    'sort_order' => 31,
  ),
  94 => 
  array (
    'kind' => 'conference',
    'title' => '15th ASEANAPOL Conference',
    'conf_number' => '15th',
    'conf_year' => 1995,
    'dates_label' => '21–25 May 1995',
    'city' => 'Jakarta',
    'country' => 'Indonesia',
    'flag' => '🇮🇩',
    'theme' => NULL,
    'status' => 'past',
    'sort_order' => 32,
  ),
  95 => 
  array (
    'kind' => 'conference',
    'title' => '14th ASEANAPOL Conference',
    'conf_number' => '14th',
    'conf_year' => 1994,
    'dates_label' => '1–5 May 1994',
    'city' => 'Phuket',
    'country' => 'Thailand',
    'flag' => '🇹🇭',
    'theme' => NULL,
    'status' => 'past',
    'sort_order' => 33,
  ),
  96 => 
  array (
    'kind' => 'conference',
    'title' => '13th ASEANAPOL Conference',
    'conf_number' => '13th',
    'conf_year' => 1993,
    'dates_label' => '19–21 April 1993',
    'city' => 'Manila',
    'country' => 'Philippines',
    'flag' => '🇵🇭',
    'theme' => NULL,
    'status' => 'past',
    'sort_order' => 34,
  ),
  97 => 
  array (
    'kind' => 'conference',
    'title' => '12th ASEANAPOL Conference',
    'conf_number' => '12th',
    'conf_year' => 1992,
    'dates_label' => '3–5 August 1992',
    'city' => 'Brunei',
    'country' => 'Brunei Darussalam',
    'flag' => '🇧🇳',
    'theme' => NULL,
    'status' => 'past',
    'sort_order' => 35,
  ),
  98 => 
  array (
    'kind' => 'conference',
    'title' => '11th ASEANAPOL Conference',
    'conf_number' => '11th',
    'conf_year' => 1991,
    'dates_label' => '5–9 May 1991',
    'city' => 'Singapore',
    'country' => 'Singapore',
    'flag' => '🇸🇬',
    'theme' => NULL,
    'status' => 'past',
    'sort_order' => 36,
  ),
  99 => 
  array (
    'kind' => 'conference',
    'title' => '10th ASEANAPOL Conference',
    'conf_number' => '10th',
    'conf_year' => 1990,
    'dates_label' => '25–28 July 1990',
    'city' => 'Kuala Lumpur',
    'country' => 'Malaysia',
    'flag' => '🇲🇾',
    'theme' => NULL,
    'status' => 'past',
    'sort_order' => 37,
  ),
  100 => 
  array (
    'kind' => 'conference',
    'title' => '9th ASEANAPOL Conference',
    'conf_number' => '9th',
    'conf_year' => 1989,
    'dates_label' => '1–6 November 1989',
    'city' => 'Bali',
    'country' => 'Indonesia',
    'flag' => '🇮🇩',
    'theme' => NULL,
    'status' => 'past',
    'sort_order' => 38,
  ),
  101 => 
  array (
    'kind' => 'conference',
    'title' => '8th ASEANAPOL Conference',
    'conf_number' => '8th',
    'conf_year' => 1988,
    'dates_label' => '14–16 November 1988',
    'city' => 'Chiang Mai',
    'country' => 'Thailand',
    'flag' => '🇹🇭',
    'theme' => NULL,
    'status' => 'past',
    'sort_order' => 39,
  ),
  102 => 
  array (
    'kind' => 'conference',
    'title' => '7th ASEANAPOL Conference',
    'conf_number' => '7th',
    'conf_year' => 1987,
    'dates_label' => '16–18 September 1987',
    'city' => 'Manila',
    'country' => 'Philippines',
    'flag' => '🇵🇭',
    'theme' => NULL,
    'status' => 'past',
    'sort_order' => 40,
  ),
  103 => 
  array (
    'kind' => 'conference',
    'title' => '6th ASEANAPOL Conference',
    'conf_number' => '6th',
    'conf_year' => 1986,
    'dates_label' => '11–13 November 1986',
    'city' => 'Brunei',
    'country' => 'Brunei Darussalam',
    'flag' => '🇧🇳',
    'theme' => NULL,
    'status' => 'past',
    'sort_order' => 41,
  ),
  104 => 
  array (
    'kind' => 'conference',
    'title' => '5th ASEANAPOL Conference',
    'conf_number' => '5th',
    'conf_year' => 1985,
    'dates_label' => '7–9 November 1985',
    'city' => 'Singapore',
    'country' => 'Singapore',
    'flag' => '🇸🇬',
    'theme' => NULL,
    'status' => 'past',
    'sort_order' => 42,
  ),
  105 => 
  array (
    'kind' => 'conference',
    'title' => '4th ASEANAPOL Conference',
    'conf_number' => '4th',
    'conf_year' => 1984,
    'dates_label' => '21–24 November 1984',
    'city' => 'Kuala Lumpur',
    'country' => 'Malaysia',
    'flag' => '🇲🇾',
    'theme' => NULL,
    'status' => 'past',
    'sort_order' => 43,
  ),
  106 => 
  array (
    'kind' => 'conference',
    'title' => '3rd ASEANAPOL Conference',
    'conf_number' => '3rd',
    'conf_year' => 1983,
    'dates_label' => '16–19 November 1983',
    'city' => 'Jakarta',
    'country' => 'Indonesia',
    'flag' => '🇮🇩',
    'theme' => NULL,
    'status' => 'past',
    'sort_order' => 44,
  ),
  107 => 
  array (
    'kind' => 'conference',
    'title' => '2nd ASEANAPOL Conference',
    'conf_number' => '2nd',
    'conf_year' => 1982,
    'dates_label' => '18–20 June 1982',
    'city' => 'Pattaya',
    'country' => 'Thailand',
    'flag' => '🇹🇭',
    'theme' => NULL,
    'status' => 'past',
    'sort_order' => 45,
  ),
  108 => 
  array (
    'kind' => 'conference',
    'title' => '1st ASEANAPOL Conference',
    'conf_number' => '1st',
    'conf_year' => 1981,
    'dates_label' => '21–23 October 1981',
    'city' => 'Manila',
    'country' => 'Philippines',
    'flag' => '🇵🇭',
    'theme' => NULL,
    'status' => 'past',
    'sort_order' => 46,
  ),
  109 => 
  array (
    'kind' => 'training',
    'title' => 'ASEANAPOL Training Cooperation Meeting (ATCM)',
    'abbr' => 'ATCM',
    'icon' => 'groups',
    'category' => 'Governance',
    'frequency' => 'Annual',
    'description' => 'The principal forum for ASEANAPOL member police forces to plan, review, and coordinate training cooperation activities. The 13th ATCM was held in Yangon, Myanmar in July 2025, adopting a hybrid format for the first time.',
    'latest' => '13th ATCM — July 2025, Yangon, Myanmar',
    'sort_order' => 1,
  ),
  110 => 
  array (
    'kind' => 'training',
    'title' => 'Cybercrime Investigation Training',
    'abbr' => 'CIT',
    'icon' => 'security',
    'category' => 'Cybercrime',
    'frequency' => 'Bi-annual',
    'description' => 'Specialised training covering digital forensics, ransomware investigation, online fraud detection, and cyber-enabled financial crime. Delivered in partnership with INTERPOL and Europol.',
    'latest' => 'Delivered in 2024–2025 series',
    'sort_order' => 2,
  ),
  111 => 
  array (
    'kind' => 'training',
    'title' => 'Counter-Trafficking Training Programme',
    'abbr' => 'CTTP',
    'icon' => 'person_search',
    'category' => 'TIP',
    'frequency' => 'Annual',
    'description' => 'Training on victim identification, cross-border investigation techniques, and cooperation with UNODC and NGO partners for combating trafficking in persons (TIP) across the ASEAN region.',
    'latest' => 'Held in partnership with UNODC',
    'sort_order' => 3,
  ),
  112 => 
  array (
    'kind' => 'training',
    'title' => 'Drug Enforcement Cooperation Training',
    'abbr' => 'DECT',
    'icon' => 'science',
    'category' => 'Drugs',
    'frequency' => 'Annual',
    'description' => 'Joint training for narcotics enforcement officers covering synthetic drug trends, precursor chemical controls, and cross-border drug trafficking investigation methodologies.',
    'latest' => 'Coordinated with UNODC Southeast Asia',
    'sort_order' => 4,
  ),
  113 => 
  array (
    'kind' => 'training',
    'title' => 'Financial Crime & Money Laundering Training',
    'abbr' => 'FCMLT',
    'icon' => 'account_balance',
    'category' => 'Financial Crime',
    'frequency' => 'Annual',
    'description' => 'Capacity building for financial investigators covering asset tracing, trade-based money laundering, and cooperation with Financial Intelligence Units (FIUs) across member states.',
    'latest' => 'Included in ATCM programme',
    'sort_order' => 5,
  ),
  114 => 
  array (
    'kind' => 'training',
    'title' => 'Wildlife & Environmental Crime Training',
    'abbr' => 'WECT',
    'icon' => 'forest',
    'category' => 'Wildlife',
    'frequency' => 'Bi-annual',
    'description' => 'Training on wildlife trafficking investigation, CITES compliance, and coordinated responses to illegal logging and illegal wildlife trade in the ASEAN region.',
    'latest' => 'In partnership with INTERPOL Environmental Security',
    'sort_order' => 6,
  ),
);
    }
};
