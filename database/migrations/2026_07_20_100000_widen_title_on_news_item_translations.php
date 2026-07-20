<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Some source titles run close to the 255-char VARCHAR limit already, and
 * scripts like Khmer/Lao/Thai/Burmese can expand the same content to more
 * characters than the English original — observed in practice truncating
 * a real translated title mid-save. Widen to TEXT to remove the ceiling
 * entirely. Raw SQL (not ->change()) to avoid a doctrine/dbal dependency.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE news_item_translations MODIFY title TEXT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE news_item_translations MODIFY title VARCHAR(255) NULL');
    }
};
