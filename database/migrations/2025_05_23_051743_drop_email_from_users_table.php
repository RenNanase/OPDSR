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
        Schema::table('users', function (Blueprint $table) {
            // First, ensure 'name' is unique as it will now be the primary identifier for login
            $table->string('name')->unique()->change(); // Make 'name' column unique

            // Drop the 'email' and 'email_verified_at' columns
            $table->dropColumn(['email', 'email_verified_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Re-add the columns if rolling back (adjust types if they were different originally)
            $table->string('email')->unique()->nullable(); // Make it nullable as it might have been
            $table->timestamp('email_verified_at')->nullable();

            // Make 'name' no longer unique if it was before (optional, depends on original design)
            // If you had a unique constraint on name previously, you might need to adjust or remove it.
            // For simplicity, we'll assume it wasn't unique before.
        });
    }
};