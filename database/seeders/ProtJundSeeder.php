<?php

namespace Database\Seeders;

use App\Models\Dentist;
use App\Models\Patient;
use App\Models\Order;
use App\Models\TechnicalRecord;
use App\Models\FinancialRecord;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ProtJundSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Dentists
        $dentists = [
            ['name' => 'Dr. Caio Siqueira', 'cro' => '12345-SP', 'phone' => '(11) 98765-4321', 'email' => 'caio@clinicaviva.com'],
            ['name' => 'Dra. Ana Paula', 'cro' => '67890-SP', 'phone' => '(11) 91234-5678', 'email' => 'ana.paula@sorrisoperfeito.com'],
            ['name' => 'Dr. Ricardo M.', 'cro' => '54321-MG', 'phone' => '(11) 99887-7665', 'email' => 'ricardo@dentistas.com'],
        ];

        foreach ($dentists as $d) {
            $createdDentists[] = Dentist::create($d);
        }

        // 2. Create Patients
        $patientsData = [
            ['name' => 'João Silva', 'phone' => '(11) 90000-1111'],
            ['name' => 'Maria Oliveira', 'phone' => '(11) 90000-2222'],
            ['name' => 'Carlos Souza', 'phone' => '(11) 90000-3333'],
            ['name' => 'Fernanda L.', 'phone' => '(11) 90000-4444'],
            ['name' => 'Roberto K.', 'phone' => '(11) 90000-5555'],
        ];

        foreach ($patientsData as $p) {
            $createdPatients[] = Patient::create($p);
        }

        // 3. Create Orders and Technical Records
        $orders = [
            [
                'dentist_id' => $createdDentists[0]->id, // Dr. Caio
                'patient_id' => $createdPatients[0]->id, // João
                'status' => 'Finalizado',
                'delivery_date' => Carbon::now()->subDays(5),
                'description' => 'Armação Metálica Superior',
                'amount' => 300.00,
            ],
            [
                'dentist_id' => $createdDentists[0]->id, // Dr. Caio
                'patient_id' => $createdPatients[1]->id, // Maria
                'status' => 'Em Produção',
                'delivery_date' => Carbon::now()->addDays(3),
                'description' => 'Prótese Total Inferior',
                'amount' => 450.00,
            ],
            [
                'dentist_id' => $createdDentists[1]->id, // Dra. Ana
                'patient_id' => $createdPatients[2]->id, // Carlos
                'status' => 'Finalizado',
                'delivery_date' => Carbon::now()->subDays(2),
                'description' => 'Protocolo Superior',
                'amount' => 1200.00,
            ],
            [
                'dentist_id' => $createdDentists[2]->id, // Dr. Ricardo
                'patient_id' => $createdPatients[3]->id, // Fernanda
                'status' => 'Aberto',
                'delivery_date' => Carbon::now()->addDays(7),
                'description' => 'Coroa Zircônia',
                'amount' => 550.00,
            ],
        ];

        foreach ($orders as $oData) {
            $order = Order::create([
                'dentist_id' => $oData['dentist_id'],
                'patient_id' => $oData['patient_id'],
                'status' => $oData['status'],
                'delivery_date' => $oData['delivery_date'],
            ]);

            TechnicalRecord::create([
                'order_id' => $order->id,
                'material' => 'Metalo-Cerâmica',
                'color' => 'A2',
                'finish' => 'Brilhante',
                'occlusion' => 'Normal',
                'notes' => 'Paciente com sensibilidade.',
            ]);

            // If finished, record in financials
            if ($order->status == 'Finalizado') {
                FinancialRecord::create([
                    'dentist_id' => $order->dentist_id,
                    'amount' => $oData['amount'],
                    'type' => 'debit',
                    'description' => $oData['description'],
                    'patient_name' => Patient::find($order->patient_id)->name,
                    'transaction_date' => $order->delivery_date,
                ]);
            }
        }

        // 4. Create some payments (Credits)
        FinancialRecord::create([
            'dentist_id' => $createdDentists[1]->id, // Dra. Ana paid partially
            'amount' => 500.00,
            'type' => 'credit',
            'description' => 'Pagamento PIX',
            'transaction_date' => Carbon::now(),
        ]);
        
        FinancialRecord::create([
            'dentist_id' => $createdDentists[0]->id, // Dr. Caio paid for first service
            'amount' => 300.00,
            'type' => 'credit',
            'description' => 'Pagamento Transferência',
            'transaction_date' => Carbon::now()->subDays(1),
        ]);
        
        // Add one more pending debit for Dr. Caio to show balance
        FinancialRecord::create([
            'dentist_id' => $createdDentists[0]->id,
            'amount' => 150.00,
            'type' => 'debit',
            'description' => 'Pilar Personalizado',
            'patient_name' => 'Geraldo M.',
            'transaction_date' => Carbon::now()->subDays(10),
        ]);
    }
}
