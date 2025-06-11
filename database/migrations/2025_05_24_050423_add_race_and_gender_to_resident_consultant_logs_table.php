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
        Schema::table('resident_consultant_logs', function (Blueprint $table) {
            $table->string('race')->after('total_patients_count');
            $table->string('gender')->after('race');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resident_consultant_logs', function (Blueprint $table) {
            $table->dropColumn(['race', 'gender']);
        });
    }
};
