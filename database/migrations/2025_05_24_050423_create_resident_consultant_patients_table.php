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
        Schema::create('resident_consultant_patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_consultant_log_id')->constrained()->onDelete('cascade');
            // Race counts
            $table->integer('chinese_count')->default(0);
            $table->integer('malay_count')->default(0);
            $table->integer('kdms_count')->default(0);
            $table->integer('others_count')->default(0);
            // Gender counts
            $table->integer('male_count')->default(0);
            $table->integer('female_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resident_consultant_patients');
    }
}; 