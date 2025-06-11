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
        Schema::create('visiting_consultants_patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visiting_consultants_log_id')->constrained('visiting_consultants_logs')->onDelete('cascade');
            // Race counts
            $table->integer('chinese_count')->default(0);
            $table->integer('malay_count')->default(0);
            $table->integer('india_count')->default(0);
            $table->integer('kdms_count')->default(0);
            $table->integer('others_count')->default(0);
            // Gender counts
            $table->integer('male_count')->default(0);
            $table->integer('female_count')->default(0);
            // New patient counts
            $table->integer('new_male_count_vs')->default(0);
            $table->integer('new_female_count_vs')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visiting_consultants_patients');
    }
};
