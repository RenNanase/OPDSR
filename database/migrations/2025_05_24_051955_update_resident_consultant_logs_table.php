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
            // Drop the old columns if they exist
            if (Schema::hasColumn('resident_consultant_logs', 'race')) {
                $table->dropColumn('race');
            }
            if (Schema::hasColumn('resident_consultant_logs', 'gender')) {
                $table->dropColumn('gender');
            }
            if (Schema::hasColumn('resident_consultant_logs', 'foreigner_country')) {
                $table->dropColumn('foreigner_country');
            }
            if (Schema::hasColumn('resident_consultant_logs', 'foreigner_gender')) {
                $table->dropColumn('foreigner_gender');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resident_consultant_logs', function (Blueprint $table) {
            // Add back the columns if needed to rollback
            $table->string('race')->nullable();
            $table->string('gender')->nullable();
            $table->string('foreigner_country')->nullable();
            $table->string('foreigner_gender')->nullable();
        });
    }
};
