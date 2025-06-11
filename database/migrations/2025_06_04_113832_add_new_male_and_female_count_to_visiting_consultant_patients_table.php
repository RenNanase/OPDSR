<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('visiting_consultants_patients', function (Blueprint $table) {
            $table->integer('new_male_count_vs')->default(0);
            $table->integer('new_female_count_vs')->default(0);

        });
    }

    public function down()
    {
        Schema::table('visiting_consultants_patients', function (Blueprint $table) {
            $table->dropColumn(['new_male_count_vs', 'new_female_count_vs']);
        });
    }
};
