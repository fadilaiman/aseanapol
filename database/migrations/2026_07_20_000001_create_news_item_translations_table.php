<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news_item_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('news_item_id')->constrained('news_items')->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('title')->nullable();
            $table->text('summary')->nullable();
            $table->longText('content')->nullable();
            // sha256 of the source title|summary|content this translation was made from
            $table->string('source_hash', 64);
            $table->string('status', 20)->default('pending'); // pending | completed | failed
            $table->text('error')->nullable();
            $table->timestamps();

            $table->unique(['news_item_id', 'locale']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_item_translations');
    }
};
