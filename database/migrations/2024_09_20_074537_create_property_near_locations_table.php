<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('property_near_locations', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->string('location_en', 255)->nullable()->collate('utf8mb4_unicode_ci');
            $table->string('location_ar', 255)->nullable()->collate('utf8mb4_unicode_ci');
            $table->string('distance', 255)->collate('utf8mb4_unicode_ci');
            $table->unsignedBigInteger('property_id');

            $table->timestamps(0);
            $table->softDeletes();


            $table->foreign('property_id')->references('id')->on('property')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_near_locations');
    }
};
