<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin ProtJund',
                'password' => bcrypt('4584'),
                'role' => 'admin',
            ]
        );

        $this->call([
            ProtJundSeeder::class,
            ServiceSeeder::class,
        ]);
    }
}
