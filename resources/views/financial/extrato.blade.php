<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Extrato Serviços - {{ $dentist->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .print-only { display: none; }
        @media print {
            @page { margin: 0; }
            .no-print { display: none !important; }
            .print-only { display: block !important; }
            body { background: white !important; padding: 0 !important; margin: 0 !important; }
            #extrato-document { 
                box-shadow: none !important; 
                border: 0 !important;
                padding: 15mm !important;
                margin: 0 !important;
                width: 210mm !important;
                height: 297mm !important;
                overflow: hidden !important;
            }
        }
        .official-table th, .official-table td {
            border: 1px solid #000;
            padding: 4px 8px;
        }
    </style>
</head>
<body class="bg-gray-100 p-4 min-h-screen">

    <div class="max-w-5xl mx-auto">
        <!-- Toolbar -->
        <div class="no-print mb-8 flex justify-between items-center bg-white p-6 rounded-2xl shadow-xl border border-slate-200">
            <button onclick="window.close()" class="px-6 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-200 transition flex items-center text-sm">
                <i class="fa-solid fa-arrow-left mr-2"></i> Voltar
            </button>
            <div class="flex gap-4">
                <button onclick="window.print()" class="px-8 py-3 bg-white border-2 border-slate-900 text-slate-900 rounded-xl font-black hover:bg-slate-900 hover:text-white transition flex items-center text-xs uppercase tracking-widest">
                    <i class="fa-solid fa-print mr-2"></i> Abrir Impressão
                </button>
                <button id="downloadBtn" onclick="downloadPDF()" class="px-10 py-3 bg-blue-600 text-white rounded-xl font-black shadow-lg shadow-blue-900/20 hover:bg-blue-700 transition flex items-center text-xs uppercase tracking-widest">
                    <i class="fa-solid fa-file-pdf mr-2 text-sm"></i> Salvar PDF
                </button>
            </div>
        </div>

        <!-- Document Content -->
        <div id="extrato-document" class="bg-white p-12 relative min-h-[297mm] shadow-2xl border border-black overflow-hidden">
            
            <!-- Header Group -->
            <div class="flex justify-between items-start mb-8 border-b-2 border-black pb-6">
                <div class="flex-1 text-center pl-20">
                    <h1 class="text-3xl font-black tracking-tighter leading-none">LABORATÓRIO J. C</h1>
                    <p class="text-sm font-black uppercase tracking-[0.2em] mt-1">LABORATÓRIO DE PRÓTESE DENTARIA</p>
                </div>
                <div class="text-right w-64">
                    <h2 class="text-4xl font-black text-black uppercase tracking-tighter mb-1 italic">Extrato</h2>
                    <h2 class="text-3xl font-black text-black uppercase tracking-tighter mb-2">Serviços</h2>
                    <p class="text-[10px] font-black uppercase">
                        {{ $from ? date('d/m/Y', strtotime($from)) : '---' }} a {{ $to ? date('d/m/Y', strtotime($to)) : date('d/m/Y') }}
                    </p>
                </div>
            </div>

            <!-- Client Info Grid -->
            <div class="grid grid-cols-12 gap-y-2 text-[11px] font-black uppercase mb-6">
                <div class="col-span-8 flex border-b border-black pb-1">
                    <span class="w-20">Cliente:</span>
                    <span class="flex-1">{{ $dentist->name }} </span>
                    <span class="bg-black text-white px-2"> [{{ str_pad($dentist->id, 4, '0', STR_PAD_LEFT) }}] </span>
                </div>
                <div class="col-span-4 flex border-b border-black pb-1 ml-4">
                    <span class="w-24">CPF/CNPJ:</span>
                    <span class="flex-1 text-right">---</span>
                </div>
                <div class="col-span-12 flex border-b border-black pb-1">
                    <span class="w-20">Endereço:</span>
                    <span class="flex-1">{{ $dentist->address ?: '---' }}</span>
                </div>
            </div>

            <!-- Statement Table -->
            <table class="w-full official-table text-[10px] font-bold uppercase mb-2">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="w-24 text-center py-1">Data</th>
                        <th class="w-20 text-center py-1">Ficha</th>
                        <th class="text-left py-1">Histórico</th>
                        <th class="w-24 text-right py-1">Crédito</th>
                        <th class="w-24 text-right py-1">Débito</th>
                        <th class="w-28 text-right py-1">Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Saldo Anterior Row -->
                    <tr>
                        <td class="border border-black"></td>
                        <td class="border border-black"></td>
                        <td class="border border-black font-black bg-gray-50 uppercase">Saldo Anterior</td>
                        <td class="border border-black"></td>
                        <td class="border border-black"></td>
                        <td class="border border-black text-right font-black">{{ number_format($previousBalance, 2, ',', '.') }}</td>
                    </tr>

                    @php $currentRunning = $previousBalance; @endphp
                    @foreach($transactions as $transaction)
                    @php
                        if($transaction->type == 'debit') $currentRunning += $transaction->amount;
                        else $currentRunning -= $transaction->amount;
                    @endphp
                    <tr>
                        <td class="border border-black text-center">{{ $transaction->transaction_date->format('d/m/Y') }}</td>
                        <td class="border border-black text-center">{{ $transaction->order_id ?: '---' }}</td>
                        <td class="border border-black text-[9px]">
                            Paciente: {{ $transaction->patient_name ?: '---' }} | {{ $transaction->description }}
                        </td>
                        <td class="border border-black text-right text-emerald-700">
                            {{ $transaction->type == 'credit' ? number_format($transaction->amount, 2, ',', '.') : '' }}
                        </td>
                        <td class="border border-black text-right">
                            {{ $transaction->type == 'debit' ? number_format($transaction->amount, 2, ',', '.') : '' }}
                        </td>
                        <td class="border border-black text-right font-black">
                            {{ number_format($currentRunning, 2, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach

                    {{-- Fill empty rows to maintain layout if few transactions --}}
                    @for($i = count($transactions); $i < 10; $i++)
                    <tr class="h-6">
                        <td class="border border-black"></td><td class="border border-black"></td>
                        <td class="border border-black"></td><td class="border border-black"></td>
                        <td class="border border-black"></td><td class="border border-black"></td>
                    </tr>
                    @endfor
                </tbody>
            </table>

            <!-- Summary Footer Box -->
            <div class="mt-4 flex justify-end">
                <div class="w-64 border-2 border-black p-2 space-y-1 font-black uppercase text-[11px]">
                    <div class="flex justify-between">
                        <span>Saldo Anterior:</span>
                        <span>{{ number_format($previousBalance, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Total de Pagamentos (C):</span>
                        <span>{{ number_format($totalCredits, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Total de Pedidos (D):</span>
                        <span>{{ number_format($totalDebits, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between border-t border-black pt-1 text-sm bg-gray-100 px-1">
                        <span>Saldo ATUAL:</span>
                        <span>{{ number_format($balance, 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Footer Text -->
            <div class="absolute bottom-8 left-12 right-12 flex justify-between text-[7px] font-black text-slate-300 uppercase tracking-widest">
                <span>PROTJUND SYSTEM V4.7.7</span>
                <span>FECHAMENTO PERIÓDICO DE CONTAS</span>
                <span>PÁGINA 1 / 1</span>
            </div>

        </div>
    </div>

    <script>
        function downloadPDF() {
            const btn = document.getElementById('downloadBtn');
            const toast = document.getElementById('toast');
            
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Gerando...';
            btn.classList.add('opacity-50', 'pointer-events-none');

            const element = document.getElementById('extrato-document');
            const opt = {
                margin:       [10, 10, 10, 10],
                filename:     'Extrato_Servicos_{{ str_replace(' ', '_', $dentist->name) }}.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };

            html2pdf().set(opt).from(element).save().then(() => {
                btn.innerHTML = '<i class="fa-solid fa-file-pdf mr-2"></i> Salvar PDF';
                btn.classList.remove('opacity-50', 'pointer-events-none');
                
                // Show toast
                toast.classList.remove('opacity-0');
                setTimeout(() => { toast.classList.add('opacity-0'); }, 3000);
            });
        }
    </script>

    <div id="toast" class="fixed bottom-10 left-1/2 -translate-x-1/2 bg-slate-900 text-white px-8 py-4 rounded-3xl font-bold shadow-2xl opacity-0 transition-opacity pointer-events-none z-50">
        <i class="fa-solid fa-circle-check text-emerald-400 mr-2"></i> PDF gerado com sucesso!
    </div>

</body>
</html>
