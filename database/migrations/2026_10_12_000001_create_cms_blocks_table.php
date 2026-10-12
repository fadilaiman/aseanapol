<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('cms_blocks')) {
            return;
        }

        Schema::create('cms_blocks', function (Blueprint $table) {
            $table->id();
            $table->string('page_key', 120);   // "group.page", e.g. "about.vision-mission"
            $table->string('block_key', 160);  // "section.field" or "section.__visible"
            $table->longText('value')->nullable(); // JSON-encoded override
            $table->timestamps();
            $table->unique(['page_key', 'block_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_blocks');
    }
};
