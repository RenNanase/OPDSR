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
        Schema::table('visiting_consultants_logs', function (Blueprint $table) {
            // First, check if the foreign key exists before trying to drop it
            if (Schema::hasColumn('visiting_consultants_logs', 'user_id')) {
                // Drop the foreign key if it exists
                $foreignKeys = Schema::getConnection()
                    ->getDoctrineSchemaManager()
                    ->listTableForeignKeys('visiting_consultants_logs');

                foreach ($foreignKeys as $foreignKey) {
                    if ($foreignKey->getName() === 'visiting_consultants_logs_user_id_foreign') {
                        $table->dropForeign('visiting_consultants_logs_user_id_foreign');
                        break;
                    }
                }

                // Drop the user_id column
                $table->dropColumn('user_id');
            }

            // Add the user_name column if it doesn't exist
            if (!Schema::hasColumn('visiting_consultants_logs', 'user_name')) {
                $table->string('user_name')->after('date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visiting_consultants_logs', function (Blueprint $table) {
            // Drop user_name column
            if (Schema::hasColumn('visiting_consultants_logs', 'user_name')) {
                $table->dropColumn('user_name');
            }

            // Add back user_id column
            if (!Schema::hasColumn('visiting_consultants_logs', 'user_id')) {
                $table->foreignId('user_id')->after('date')->constrained()->onDelete('cascade');
            }
        });
    }
};
