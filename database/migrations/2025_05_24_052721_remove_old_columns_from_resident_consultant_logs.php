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
            // Drop the race and gender columns if they exist
            if (Schema::hasColumn('resident_consultant_logs', 'race')) {
                $table->dropColumn('race');
            }
            if (Schema::hasColumn('resident_consultant_logs', 'gender')) {
                $table->dropColumn('gender');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resident_consultant_logs', function (Blueprint $table) {
            // Add back the race and gender columns
            $table->string('race')->nullable();
            $table->string('gender')->nullable();
        });
    }
};
