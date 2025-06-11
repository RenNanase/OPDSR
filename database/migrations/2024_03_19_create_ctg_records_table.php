<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ctg_records', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('dr_geetha_count')->default(0);
            $table->integer('dr_joseph_count')->default(0);
            $table->integer('dr_sutha_count')->default(0);
            $table->integer('dr_ramesh_count')->default(0);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ctg_records');
    }
};
