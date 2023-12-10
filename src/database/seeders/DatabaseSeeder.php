<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'alice',
            'email' => 'alice@mail.com',
            'password' => Hash::make(123456),
        ]);

        \App\Models\User::factory()->create([
            'name' => 'bob',
            'email' => 'bob@mail.com',
            'password' => Hash::make(123456),
        ]);
    }
}
