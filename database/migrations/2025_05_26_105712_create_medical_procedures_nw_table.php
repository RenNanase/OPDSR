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
        Schema::create('medical_procedures_nw', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedBigInteger('user_id');
            $table->integer('injection_vaccine')->default(0);
            $table->integer('iv_medication')->default(0);
            $table->integer('urea_blood_test')->default(0);
            $table->integer('venepuncture')->default(0);
            $table->integer('iv_cannulation')->default(0);
            $table->integer('swab_cs_nose_oral')->default(0);
            $table->integer('dressing')->default(0);
            $table->integer('ecg_12_led')->default(0);
            $table->integer('urinary_catheterization')->default(0);
            $table->integer('ng_tube_insertion')->default(0);
            $table->integer('nebulization')->default(0);
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_procedures_nw');
    }
};
