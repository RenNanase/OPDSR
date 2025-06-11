<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create an Admin user
        // User::create([
        //     'name' => 'admin', // Changed to 'admin' (lowercase, simple) for consistency as unique name
        //     'password' => Hash::make('password'),
        //     'role' => User::ROLE_ADMIN,
        // ]);

        // Create a Staff user
        // User::create([
        //     'name' => 'staff', // Changed to 'staff' (lowercase, simple)
        //     'password' => Hash::make('password'),
        //     'role' => User::ROLE_STAFF,
        // ]);

        // Create a Staff user
        User::create([
            'name' => 'catherine', // Changed to 'staff' (lowercase, simple)
            'password' => Hash::make('123'),
            'role' => User::ROLE_STAFF,
        ]);
        // Create a Staff user
        User::create([
            'name' => 'cheryl',
            'password' => Hash::make('123'),
            'role' => User::ROLE_STAFF,
        ]);

        User::create([
            'name' => 'hadriani',
            'password' => Hash::make('123'),
            'role' => User::ROLE_STAFF,
        ]);

        User::create([
            'name' => 'epoi',
            'password' => Hash::make('123'),
            'role' => User::ROLE_STAFF,
        ]);

        User::create([
            'name' => 'amisdorah',
            'password' => Hash::make('123'),
            'role' => User::ROLE_STAFF,
        ]);

        User::create([
            'name' => 'catriana',
            'password' => Hash::make('123'),
            'role' => User::ROLE_STAFF,
        ]);

        User::create([
            'name' => 'lynn',
            'password' => Hash::make('123'),
            'role' => User::ROLE_STAFF,
        ]);

        User::create([
            'name' => 'dereline',
            'password' => Hash::make('123'),
            'role' => User::ROLE_STAFF,
        ]);

        User::create([
            'name' => 'ednisiah',
            'password' => Hash::make('123'),
            'role' => User::ROLE_STAFF,
        ]);

        User::create([
            'name' => 'evelyn',
            'password' => Hash::make('123'),
            'role' => User::ROLE_STAFF,
        ]);

        User::create([
            'name' => 'estee',
            'password' => Hash::make('123'),
            'role' => User::ROLE_STAFF,
        ]);

        User::create([
            'name' => 'esther',
            'password' => Hash::make('123'),
            'role' => User::ROLE_STAFF,
        ]);

        User::create([
            'name' => 'fancy',
            'password' => Hash::make('123'),
            'role' => User::ROLE_STAFF,
        ]);
        User::create([
            'name' => 'hanani',
            'password' => Hash::make('123'),
            'role' => User::ROLE_STAFF,
        ]);

        User::create([
            'name' => 'wana',
            'password' => Hash::make('123'),
            'role' => User::ROLE_STAFF,
        ]);

        User::create([
            'name' => 'ivy',
            'password' => Hash::make('123'),
            'role' => User::ROLE_STAFF,
        ]);

        User::create([
            'name' => 'jainah',
            'password' => Hash::make('123'),
            'role' => User::ROLE_STAFF,
        ]);

        User::create([
            'name' => 'jazebell',
            'password' => Hash::make('123'),
            'role' => User::ROLE_STAFF,
        ]);

        User::create([
            'name' => 'judy',
            'password' => Hash::make('123'),
            'role' => User::ROLE_STAFF,
        ]);

        User::create([
            'name' => 'linah',
            'password' => Hash::make('123'),
            'role' => User::ROLE_STAFF,
        ]);
        User::create([
            'name' => 'lvianinah',
            'password' => Hash::make('123'),
            'role' => User::ROLE_STAFF,
        ]);

        User::create([
            'name' => 'masrsiolena',
            'password' => Hash::make('123'),
            'role' => User::ROLE_STAFF,
        ]);

        User::create([
            'name' => 'mellisa',
            'password' => Hash::make('123'),
            'role' => User::ROLE_STAFF,
        ]);

        User::create([
            'name' => 'merle',
            'password' => Hash::make('123'),
            'role' => User::ROLE_STAFF,
        ]);

        User::create([
            'name' => 'marylene',
            'password' => Hash::make('123'),
            'role' => User::ROLE_STAFF,
        ]);

        User::create([
            'name' => 'merinti',
            'password' => Hash::make('123'),
            'role' => User::ROLE_STAFF,
        ]);
        User::create([
            'name' => 'michelle',
            'password' => Hash::make('123'),
            'role' => User::ROLE_STAFF,
        ]);

        User::create([
            'name' => 'michellyn',
            'password' => Hash::make('123'),
            'role' => User::ROLE_STAFF,
        ]);

        User::create([
            'name' => 'norazila',
            'password' => Hash::make('123'),
            'role' => User::ROLE_STAFF,
        ]);

        User::create([
            'name' => 'prinilla',
            'password' => Hash::make('123'),
            'role' => User::ROLE_STAFF,
        ]);

        User::create([
            'name' => 'richaell',
            'password' => Hash::make('123'),
            'role' => User::ROLE_STAFF,
        ]);
        User::create([
            'name' => 'rozanih',
            'password' => Hash::make('123'),
            'role' => User::ROLE_STAFF,
        ]);

        User::create([
            'name' => 'trees',
            'password' => Hash::make('123'),
            'role' => User::ROLE_STAFF,
        ]);

        User::create([
            'name' => 'amy',
            'password' => Hash::make('123'),
            'role' => User::ROLE_STAFF,
        ]);

        User::create([
            'name' => 'putri',
            'password' => Hash::make('123'),
            'role' => User::ROLE_STAFF,
        ]);
    }
    }
