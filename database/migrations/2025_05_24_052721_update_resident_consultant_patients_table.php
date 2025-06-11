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
        Schema::table('resident_consultant_patients', function (Blueprint $table) {
            // Drop old columns if they exist
            if (Schema::hasColumn('resident_consultant_patients', 'race')) {
                $table->dropColumn('race');
            }
            if (Schema::hasColumn('resident_consultant_patients', 'gender')) {
                $table->dropColumn('gender');
            }

            // Add new count columns if they don't exist
            if (!Schema::hasColumn('resident_consultant_patients', 'chinese_count')) {
                $table->integer('chinese_count')->default(0);
            }
            if (!Schema::hasColumn('resident_consultant_patients', 'malay_count')) {
                $table->integer('malay_count')->default(0);
            }
            if (!Schema::hasColumn('resident_consultant_patients', 'kdms_count')) {
                $table->integer('kdms_count')->default(0);
            }
            if (!Schema::hasColumn('resident_consultant_patients', 'others_count')) {
                $table->integer('others_count')->default(0);
            }
            if (!Schema::hasColumn('resident_consultant_patients', 'male_count')) {
                $table->integer('male_count')->default(0);
            }
            if (!Schema::hasColumn('resident_consultant_patients', 'female_count')) {
                $table->integer('female_count')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resident_consultant_patients', function (Blueprint $table) {
            // Drop new count columns
            $table->dropColumn([
                'chinese_count',
                'malay_count',
                'kdms_count',
                'india_count',
                'others_count',
                'male_count',
                'female_count'
            ]);

            // Add back old columns
            $table->string('race')->nullable();
            $table->string('gender')->nullable();
        });
    }
}; 