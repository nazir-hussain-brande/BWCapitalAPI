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
        Schema::create('team', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID column
            $table->string('title_en', 255)->nullable()->collate('utf8mb4_unicode_ci');
            $table->string('title_ar', 255)->nullable()->collate('utf8mb4_unicode_ci');
            $table->string('designation_en', 255)->nullable()->collate('utf8mb4_unicode_ci');
            $table->string('designation_ar', 255)->nullable()->collate('utf8mb4_unicode_ci');
            $table->string('linkdin', 255)->nullable()->collate('utf8mb4_unicode_ci');
            $table->integer('agent')->default(0);
            $table->integer('status')->default(0);
            $table->timestamps(0);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team');
    }
};
