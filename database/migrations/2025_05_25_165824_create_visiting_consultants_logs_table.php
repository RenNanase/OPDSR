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
        Schema::create('visiting_consultants_logs', function (Blueprint $table) {
            $table->id();
            $table->string('no_suite'); // Will be populated from ResidentConsultant
            $table->string('consultant_name'); // Will be populated from ResidentConsultant
            $table->string('total_patients_count'); //total pt auto calculate
            $table->time('time_in')->nullable();
            $table->time('time_out')->nullable();
            $table->text('ref_details')->nullable(); // Reference details
            $table->text('remarks')->nullable();
            $table->date('date'); // Date of the log entry
            $table->string('user_name'); // Changed from foreignId to string to match the code
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visiting_consultants_logs');
    }
};
