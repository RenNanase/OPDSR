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
        Schema::create('resident_consultant_log_foreigners', function (Blueprint $table) {
            $table->id();
            // THIS IS THE CRUCIAL LINE YOU WERE MISSING
            $table->foreignId('resident_consultant_log_id')
      ->constrained()
      ->onDelete('cascade')
      ->name('rclog_foreigners_fk'); // <--- ADD THIS LINE with a short, unique name
            $table->string('country'); // Missing: Country of the foreigner patient
            $table->string('gender');  // Missing: Gender of the foreigner patient
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resident_consultant_log_foreigners');
    }
};