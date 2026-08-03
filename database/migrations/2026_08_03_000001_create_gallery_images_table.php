<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gallery_images', function (Blueprint $table) {
            $table->id();
            $table->string('album_key', 30); // news, events, governance, partners, observers, activities
            $table->string('path', 500)->unique(); // relative to public/, e.g. media/news/news/foo-1.jpeg
            $table->date('event_date')->index();
            $table->timestamps();

            $table->index(['album_key', 'event_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gallery_images');
    }
};
