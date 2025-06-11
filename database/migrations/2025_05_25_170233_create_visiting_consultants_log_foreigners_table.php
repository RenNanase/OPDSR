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
        Schema::create('visiting_consultants_log_foreigners', function (Blueprint $table) {
            $table->id();
            // THIS IS THE CRUCIAL LINE YOU WERE MISSING
            $table->foreignId('visiting_consultants_log_id')
      ->constrained()
      ->onDelete('cascade')
      ->name('vclog_foreigners_fk'); // <--- ADD THIS LINE with a short, unique name
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
        Schema::dropIfExists('visiting_consultants_log_foreigners');
    }
};
