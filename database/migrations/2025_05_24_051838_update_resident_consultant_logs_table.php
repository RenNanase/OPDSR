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
            // Make existing fields nullable
            $table->string('race')->nullable()->change();
            $table->string('gender')->nullable()->change();
            
            // Add total_patients_count if it doesn't exist
            if (!Schema::hasColumn('resident_consultant_logs', 'total_patients_count')) {
                $table->integer('total_patients_count')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resident_consultant_logs', function (Blueprint $table) {
            // Revert nullable changes
            $table->string('race')->nullable(false)->change();
            $table->string('gender')->nullable(false)->change();
            
            // Remove total_patients_count if it exists
            if (Schema::hasColumn('resident_consultant_logs', 'total_patients_count')) {
                $table->dropColumn('total_patients_count');
            }
        });
    }
}; 