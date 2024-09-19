<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('property', function (Blueprint $table) {
            $table->id();
            $table->string('title_en', 255)->nullable()->collate('utf8mb4_unicode_ci');
            $table->string('title_ar', 255)->nullable()->collate('utf8mb4_unicode_ci');
            $table->string('slug_en', 255)->nullable()->collate('utf8mb4_unicode_ci');
            $table->string('slug_ar', 255)->nullable()->collate('utf8mb4_unicode_ci');
            $table->double('price');
            $table->double('bed');
            $table->double('bath');
            $table->double('size');
            $table->longText('description_en')->nullable()->collate('utf8mb4_unicode_ci');
            $table->longText('description_ar')->nullable()->collate('utf8mb4_unicode_ci');
            $table->longText('highlights_en')->nullable()->collate('utf8mb4_unicode_ci');
            $table->longText('highlights_ar')->nullable()->collate('utf8mb4_unicode_ci');
            $table->unsignedBigInteger('agent_id');
            $table->unsignedBigInteger('property_type');
            $table->unsignedBigInteger('property_for');
            $table->string('sixty_tour', 255)->nullable()->collate('utf8mb4_unicode_ci');
            $table->string('features_line_en', 255)->nullable()->collate('utf8mb4_unicode_ci');
            $table->string('features_line_ar', 255)->nullable()->collate('utf8mb4_unicode_ci');
            $table->string('location', 255)->nullable()->collate('utf8mb4_unicode_ci');
            $table->string('map_link', 255)->nullable()->collate('utf8mb4_unicode_ci');
            $table->string('dld_permit_number', 255)->nullable()->collate('utf8mb4_unicode_ci');
            $table->integer('status');
            $table->timestamps(0);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property');
    }
};
