<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('digital_library_collections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon')->default('folder');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('digital_library_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained('digital_library_collections')->cascadeOnDelete();
            $table->string('title');
            $table->string('type')->default('link'); // 'pdf' or 'link'
            $table->string('file_path')->nullable();
            $table->string('external_url', 500)->nullable();
            $table->boolean('is_published')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('digital_library_items');
        Schema::dropIfExists('digital_library_collections');
    }
};
