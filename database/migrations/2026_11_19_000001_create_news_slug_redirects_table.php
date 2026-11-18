<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Previous slugs of renamed articles: old public URLs keep working (301 to the current slug, every locale).
        Schema::create('news_slug_redirects', function (Blueprint $table) {
            $table->id();
            $table->string('old_slug')->unique();
            $table->uuid('news_item_id')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_slug_redirects');
    }
};
