<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['category' => 'Prótese Total', 'name' => 'Prótese Total (Dentadura) Superior', 'base_price' => 450.00],
            ['category' => 'Prótese Total', 'name' => 'Prótese Total (Dentadura) Inferior', 'base_price' => 450.00],
            ['category' => 'Prótese Fixa', 'name' => 'Coroa em Zircônia', 'base_price' => 550.00],
            ['category' => 'Prótese Fixa', 'name' => 'Coroa Metalo-Cerâmica', 'base_price' => 380.00],
            ['category' => 'Prótese Flexível', 'name' => 'Partial Flex (Roach)', 'base_price' => 320.00],
            ['category' => 'Diversos', 'name' => 'Armação Metálica', 'base_price' => 200.00],
            ['category' => 'Diversos', 'name' => 'Pilar Personalizado', 'base_price' => 150.00],
            ['category' => 'Protocolo', 'name' => 'Protocolo Superior em Resina', 'base_price' => 1200.00],
        ];

        foreach ($services as $s) {
            Service::create($s);
        }
    }
}
