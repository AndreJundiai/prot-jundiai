<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['name' => 'Armação Metálica', 'category' => 'Prótese Parcial', 'base_price' => 300.00],
            ['name' => 'Prótese Total (Dentadura)', 'category' => 'Prótese Total', 'base_price' => 450.00],
            ['name' => 'RMF (Ponte Móvel)', 'category' => 'Prótese Parcial', 'base_price' => 350.00],
            ['name' => 'Coroa Zircônia', 'category' => 'Prótese Fixa', 'base_price' => 600.00],
            ['name' => 'Coroa Metalocid', 'category' => 'Prótese Fixa', 'base_price' => 250.00],
            ['name' => 'Placa de Bruxismo', 'category' => 'Diversos', 'base_price' => 120.00],
            ['name' => 'Conserto Prótese', 'category' => 'Manutenção', 'base_price' => 80.00],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(['name' => $service['name']], $service);
        }
    }
}
