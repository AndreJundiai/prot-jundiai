<?php

namespace Database\Seeders;

use App\Models\Dentist;
use App\Models\Patient;
use App\Models\Order;
use App\Models\Service;
use App\Models\TechnicalRecord;
use App\Models\FinancialRecord;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Services
        $services = [
            ['name' => 'Coroa Zircônia', 'base_price' => 550.00],
            ['name' => 'Metalo-Cerâmica', 'base_price' => 380.00],
            ['name' => 'Prótese Total (Dentadura)', 'base_price' => 450.00],
            ['name' => 'Protocolo Superior', 'base_price' => 1200.00],
            ['name' => 'Armação Metálica', 'base_price' => 180.00],
            ['name' => 'Placa de Bruxismo', 'base_price' => 120.00],
            ['name' => 'Pilar Personalizado', 'base_price' => 200.00],
        ];

        foreach ($services as $s) {
            Service::updateOrCreate(['name' => $s['name']], $s);
        }

        // 2. Create Dentists
        $dentists = [
            ['name' => 'Dr. André Jundiaí', 'cro' => '99999-SP', 'phone' => '(11) 99999-0001', 'email' => 'andre@jundiai.com'],
            ['name' => 'Dra. Beatriz Santos', 'cro' => '88888-SP', 'phone' => '(11) 98888-0002', 'email' => 'beatriz@odontosantos.com'],
            ['name' => 'Dr. Carlos Eduardo', 'cro' => '77777-SP', 'phone' => '(11) 97777-0003', 'email' => 'cadu@clinicacentral.com'],
            ['name' => 'Dra. Denise Rosa', 'cro' => '66666-SP', 'phone' => '(11) 96666-0004', 'email' => 'denise@sorrisosaude.com'],
        ];

        $createdDentists = [];
        foreach ($dentists as $d) {
            $createdDentists[] = Dentist::updateOrCreate(['cro' => $d['cro']], $d);
        }

        // 3. Create Patients
        $patientsNames = ['José Oliveira', 'Maria Aparecida', 'Wilson Pereira', 'Dirce Maria', 'Antônio Carlos', 'Cláudia Regiane', 'Marcos Aurélio'];
        $createdPatients = [];
        foreach ($patientsNames as $name) {
            $createdPatients[] = Patient::create(['name' => $name, 'phone' => '(11) 95555-' . rand(1000, 9999)]);
        }

        // 4. Create Historical Financial Records for Dr. André (to test Saldo Anterior)
        // Let's create some debits/credits in March
        $andre = $createdDentists[0];
        FinancialRecord::create([
            'dentist_id' => $andre->id,
            'amount' => 1500.00,
            'type' => 'debit',
            'description' => 'Saldo Acumulado Março',
            'transaction_date' => Carbon::parse('2026-03-01'),
        ]);
        FinancialRecord::create([
            'dentist_id' => $andre->id,
            'amount' => 1000.00,
            'type' => 'credit',
            'description' => 'Pagamento Parcial Março',
            'transaction_date' => Carbon::parse('2026-03-15'),
        ]);

        // 5. Create Current Orders and Records for April
        $aprilServices = [
            ['patient' => $createdPatients[0], 'service' => 'Coroa Zircônia', 'date' => '2026-04-05'],
            ['patient' => $createdPatients[1], 'service' => 'Metalo-Cerâmica', 'date' => '2026-04-10'],
            ['patient' => $createdPatients[2], 'service' => 'Armação Metálica', 'date' => '2026-04-12'],
            ['patient' => $createdPatients[3], 'service' => 'Protocolo Superior', 'date' => '2026-04-15'],
        ];

        foreach ($aprilServices as $data) {
            $order = Order::create([
                'dentist_id' => $andre->id,
                'patient_id' => $data['patient']->id,
                'status' => 'Finalizado',
                'delivery_date' => Carbon::parse($data['date']),
                'service_name' => $data['service'],
            ]);

            $price = Service::where('name', $data['service'])->first()->base_price;

            TechnicalRecord::create([
                'order_id' => $order->id,
                'material_fornecido' => 'Molde, Gesso',
                'material_devolvido' => 'Provisório',
                'notes' => 'Ajuste fino na oclusão.',
            ]);

            FinancialRecord::create([
                'dentist_id' => $andre->id,
                'order_id' => $order->id,
                'amount' => $price,
                'type' => 'debit',
                'description' => $data['service'],
                'patient_name' => $data['patient']->name,
                'transaction_date' => Carbon::parse($data['date']),
            ]);
        }

        // 6. Add a payment in April
        FinancialRecord::create([
            'dentist_id' => $andre->id,
            'amount' => 800.00,
            'type' => 'credit',
            'description' => 'Pagamento via PIX - Ref Abril',
            'transaction_date' => Carbon::parse('2026-04-20'),
        ]);

        // 7. Add data for others
        foreach ($createdDentists as $index => $dentist) {
            if ($index == 0) continue; // Skip André (already populated)
            
            // Random debits for others
            for($i = 0; $i < 3; $i++) {
                FinancialRecord::create([
                    'dentist_id' => $dentist->id,
                    'amount' => rand(100, 1000),
                    'type' => 'debit',
                    'description' => 'Serviço Diverso',
                    'patient_name' => 'Paciente ' . rand(1, 100),
                    'transaction_date' => Carbon::now()->subDays(rand(1, 30)),
                ]);
            }
        }
    }
}
