<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\ResidentConsultantLog;
use App\Models\VisitingConsultantLog;
use App\Models\User;

return new class extends Migration
{
    public function up(): void
    {
        // First, add the new user_name column to both tables
        Schema::table('resident_consultant_logs', function (Blueprint $table) {
            $table->string('user_name')->after('user_id')->nullable();
        });

        Schema::table('visiting_consultants_logs', function (Blueprint $table) {
            $table->string('user_name')->after('user_id')->nullable();
        });

        // Migrate the data from user_id to user_name for Resident Consultant Logs
        ResidentConsultantLog::chunk(100, function ($records) {
            foreach ($records as $record) {
                $user = User::find($record->user_id);
                if ($user) {
                    $record->user_name = $user->name;
                    $record->save();
                }
            }
        });

        // Migrate the data from user_id to user_name for Visiting Consultant Logs
        VisitingConsultantLog::chunk(100, function ($records) {
            foreach ($records as $record) {
                $user = User::find($record->user_id);
                if ($user) {
                    $record->user_name = $user->name;
                    $record->save();
                }
            }
        });

        // Make user_name required and remove user_id from both tables
        Schema::table('resident_consultant_logs', function (Blueprint $table) {
            $table->string('user_name')->nullable(false)->change();
            $table->dropForeign('resident_consultant_logs_user_id_foreign');
            $table->dropColumn('user_id');
        });

        Schema::table('visiting_consultants_logs', function (Blueprint $table) {
            $table->string('user_name')->nullable(false)->change();
            $table->dropForeign('visiting_consultants_logs_user_id_foreign');
            $table->dropColumn('user_id');
        });
    }

    public function down(): void
    {
        // Add back user_id column to both tables
        Schema::table('resident_consultant_logs', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->nullable();
        });

        Schema::table('visiting_consultants_logs', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->nullable();
        });

        // Migrate data back from user_name to user_id for Resident Consultant Logs
        ResidentConsultantLog::chunk(100, function ($records) {
            foreach ($records as $record) {
                $user = User::where('name', $record->user_name)->first();
                if ($user) {
                    $record->user_id = $user->id;
                    $record->save();
                }
            }
        });

        // Migrate data back from user_name to user_id for Visiting Consultant Logs
        VisitingConsultantLog::chunk(100, function ($records) {
            foreach ($records as $record) {
                $user = User::where('name', $record->user_name)->first();
                if ($user) {
                    $record->user_id = $user->id;
                    $record->save();
                }
            }
        });

        // Make user_id required and remove user_name from both tables
        Schema::table('resident_consultant_logs', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->dropColumn('user_name');
        });

        Schema::table('visiting_consultants_logs', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->dropColumn('user_name');
        });
    }
};
