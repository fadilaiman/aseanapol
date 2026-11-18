<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Admin decisions that survive `gallery:reindex` (which truncates gallery_images every 15 minutes):
        // hidden photos and manually uploaded photos.
        if (! Schema::hasTable('gallery_overrides')) {
            Schema::create('gallery_overrides', function (Blueprint $table) {
                $table->id();
                $table->string('path', 500)->unique();   // relative to public/, e.g. media/gallery/foo-123.jpg
                $table->string('album_key', 30);
                $table->date('event_date')->nullable();
                $table->string('caption')->nullable();
                $table->boolean('is_manual')->default(false); // uploaded through the admin (file lives in media/gallery/)
                $table->boolean('is_hidden')->default(false);
                $table->timestamps();
            });
        }
        if (Schema::hasTable('gallery_images') && ! Schema::hasColumn('gallery_images', 'caption')) {
            Schema::table('gallery_images', function (Blueprint $table) {
                $table->string('caption')->nullable()->after('event_date');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('gallery_overrides');
        if (Schema::hasColumn('gallery_images', 'caption')) {
            Schema::table('gallery_images', fn (Blueprint $t) => $t->dropColumn('caption'));
        }
    }
};
