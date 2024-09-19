<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyTypesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('property_types', function (Blueprint $table) {
            $table->id();
            $table->string('title_en', 255)->nullable()->collate('utf8mb4_unicode_ci');
            $table->string('title_ar', 255)->nullable()->collate('utf8mb4_unicode_ci');
            $table->string('slug_en', 255)->nullable()->collate('utf8mb4_unicode_ci');
            $table->string('slug_ar', 255)->nullable()->collate('utf8mb4_unicode_ci');
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
        Schema::dropIfExists('property_types');
    }
};
