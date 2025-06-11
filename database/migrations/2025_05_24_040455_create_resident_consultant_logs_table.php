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
        Schema::create('resident_consultant_logs', function (Blueprint $table) {
            $table->id();
            $table->string('no_suite'); // Will be populated from ResidentConsultant
            $table->string('consultant_name'); // Will be populated from ResidentConsultant
            $table->string('race')->nullable(); // e.g., 'Malay', 'Chinese', 'Indian', 'Other'
            $table->string('gender')->nullable(); // e.g., 'Male', 'Female'
            $table->string('foreigner_country')->nullable(); // Country of origin if foreigner
            $table->string('foreigner_gender')->nullable(); // Gender if foreigner
            $table->time('time_in')->nullable();
            $table->time('time_out')->nullable();
            $table->text('ref_details')->nullable(); // Reference details
            $table->text('remarks')->nullable();
            $table->date('date'); // Date of the log entry
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key to users table
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resident_consultant_logs');
    }
};
