<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\CtgRecord;
use App\Models\User;

return new class extends Migration
{
    public function up(): void
    {
        // First, add the new user_name column
        Schema::table('ctg_records', function (Blueprint $table) {
            $table->string('user_name')->after('user_id')->nullable();
        });

        // Migrate the data from user_id to user_name
        CtgRecord::chunk(100, function ($records) {
            foreach ($records as $record) {
                $user = User::find($record->user_id);
                if ($user) {
                    $record->user_name = $user->name;
                    $record->save();
                }
            }
        });

        // Make user_name required and remove user_id
        Schema::table('ctg_records', function (Blueprint $table) {
            $table->string('user_name')->nullable(false)->change();
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }

    public function down(): void
    {
        // Add back user_id column
        Schema::table('ctg_records', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->nullable();
        });

        // Migrate data back from user_name to user_id
        CtgRecord::chunk(100, function ($records) {
            foreach ($records as $record) {
                $user = User::where('name', $record->user_name)->first();
                if ($user) {
                    $record->user_id = $user->id;
                    $record->save();
                }
            }
        });

        // Make user_id required and remove user_name
        Schema::table('ctg_records', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->dropColumn('user_name');
        });
    }
};