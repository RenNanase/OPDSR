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
        Schema::create('resident_consultants', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Consultant's Name
            $table->string('suite_number')->unique(); // Associated Suite Number
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resident_consultants');
    }
};