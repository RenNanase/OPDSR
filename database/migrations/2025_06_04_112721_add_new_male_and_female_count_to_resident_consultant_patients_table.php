<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('resident_consultant_patients', function (Blueprint $table) {
            $table->integer('new_male_count')->default(0)->after('female_count');
            $table->integer('new_female_count')->default(0)->after('new_male_count');
        });
    }

    public function down()
    {
        Schema::table('resident_consultant_patients', function (Blueprint $table) {
            $table->dropColumn(['new_male_count', 'new_female_count']);
        });
    }
};
