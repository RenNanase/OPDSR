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
            // Drop the old columns
            $table->dropColumn(['race', 'gender', 'foreigner_country', 'foreigner_gender']);

            // Add new column for total patients
            $table->integer('total_patients_count')->nullable()->after('consultant_name'); // Or after any suitable column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resident_consultant_logs', function (Blueprint $table) {
            // Re-add the old columns in case of rollback
            $table->string('race')->nullable();
            $table->string('gender')->nullable();
            $table->string('foreigner_country')->nullable();
            $table->string('foreigner_gender')->nullable();

            // Drop the new column
            $table->dropColumn('total_patients_count');
        });
    }
};