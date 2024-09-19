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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('name')->collate('utf8mb4_general_ci');
            $table->string('path')->collate('utf8mb4_general_ci');
            $table->integer('ref_id')->nullable();
            $table->string('ref_point')->nullable()->collate('utf8mb4_general_ci');
            $table->string('alt_text')->nullable()->collate('utf8mb4_general_ci');
            $table->timestamps(0);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
