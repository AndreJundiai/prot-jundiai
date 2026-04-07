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
            ['email' => 'adm@protjund.com'],
            [
                'name' => 'Administrador Laboratório',
                'password' => bcrypt('admin123'),
            ]
        );

        $this->call([
            ProtJundSeeder::class,
            ServiceSeeder::class,
        ]);
    }
}
