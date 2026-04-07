<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Extrato de Serviços - {{ $dentist->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- PDF Generation Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        @media print {
            .no-print { display: none; }
            body { background: white; padding: 0; }
        }
        .invoice-container {
            width: 210mm;
            min-height: 297mm;
            padding: 20mm;
            margin: auto;
            background: white;
        }
    </style>
</head>
<body class="bg-slate-100 py-12">
    
    <!-- Top Action Bar -->
    <div class="max-w-[210mm] mx-auto mb-6 flex justify-between items-center no-print px-4">
        <a href="{{ route('financial.index', ['dentist_id' => $dentist->id]) }}" class="text-slate-500 hover:text-slate-700 font-bold text-xs uppercase tracking-widest flex items-center transition">
            <i class="fa-solid fa-arrow-left mr-2"></i> Voltar ao Painel
        </a>
        <div class="flex gap-3">
            <button onclick="window.print()" class="px-6 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-slate-50 transition flex items-center shadow-sm">
                <i class="fa-solid fa-print mr-2 text-slate-400"></i> Imprimir
            </button>
            <button onclick="downloadPDF()" id="downloadBtn" class="px-6 py-3 bg-red-600 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-red-700 transition flex items-center shadow-lg shadow-red-900/20">
                <i class="fa-solid fa-file-pdf mr-2"></i> Salvar PDF no Computador
            </button>
        </div>
    </div>

    <!-- The actual PDF Content -->
    <div id="invoice" class="invoice-container shadow-2xl rounded-sm">
        
        <!-- Document Header -->
        <div class="flex justify-between items-start mb-12">
            <div>
                <h1 class="text-4xl font-black text-slate-900 tracking-tighter uppercase leading-none">ProtJund</h1>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] mt-2 mb-8">Dental Laboratory Solutions</p>
                
                <div class="space-y-1">
                    <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Destinatário:</h3>
                    <p class="text-xl font-black text-slate-900">{{ $dentist->name }}</p>
                    <p class="text-sm font-medium text-slate-500 italic">CRO: {{ $dentist->cro ?: '---' }}</p>
                    <p class="text-sm font-medium text-slate-500 italic">{{ $dentist->phone ?: '---' }}</p>
                </div>
            </div>
            <div class="text-right">
                <div class="inline-block bg-slate-900 text-white px-6 py-2 rounded-lg font-black text-xs uppercase tracking-[0.2em] mb-4">
                    Extrato de Fechamento
                </div>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Emissão: {{ date('d/m/Y') }}</p>
                <div class="mt-12">
                    <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Responsável:</h3>
                    <p class="text-sm font-bold text-slate-900">Lab J.C de Prótese</p>
                    <p class="text-sm text-slate-500">Jundiaí - SP</p>
                </div>
            </div>
        </div>

        <div class="h-px bg-slate-100 w-full mb-12"></div>

        <!-- Table Content -->
        <table class="w-full text-left mb-12">
            <thead>
                <tr class="text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black border-b-2 border-slate-100">
                    <th class="py-4 font-black">Data do Trabalho</th>
                    <th class="py-4 font-black">Paciente</th>
                    <th class="py-4 font-black">Discriminação Técnica</th>
                    <th class="py-4 font-black text-right">Valor Líquido</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($transactions as $transaction)
                <tr class="text-sm">
                    <td class="py-6 font-mono text-xs text-slate-500">{{ $transaction->transaction_date->format('d/m/Y') }}</td>
                    <td class="py-6 font-black text-slate-900">{{ $transaction->patient_name ?: '---' }}</td>
                    <td class="py-6 text-slate-600 font-medium italic">{{ $transaction->description }}</td>
                    <td class="py-6 text-right font-black {{ $transaction->type == 'credit' ? 'text-emerald-600' : 'text-slate-900' }}">
                        {{ $transaction->type == 'credit' ? '-' : '' }} R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals Selection -->
        <div class="border-t-2 border-slate-900 pt-8 flex justify-end">
            <div class="w-72 space-y-4">
                <div class="flex justify-between text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    <span>Subtotal de Serviços</span>
                    <span>R$ {{ number_format($totalDebits, 2, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-[10px] font-black text-emerald-500 uppercase tracking-widest">
                    <span>Pagamentos Antecipados</span>
                    <span>- R$ {{ number_format($totalCredits, 2, ',', '.') }}</span>
                </div>
                <div class="pt-4 border-t border-slate-100 flex justify-between items-baseline">
                    <span class="text-xs font-black text-slate-900 uppercase tracking-[0.2em]">Saldo a Pagar</span>
                    <span class="text-3xl font-black text-red-600 tracking-tighter">R$ {{ number_format($balance, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>
        
        <!-- Footer / Signature -->
        <div class="mt-20 pt-12 border-t border-slate-50 text-center">
            <div class="inline-block border-t border-slate-200 px-12 pt-2">
                <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.3em]">Conferido e Assinado</p>
            </div>
            <p class="mt-12 text-[10px] text-slate-300 font-bold uppercase tracking-widest">Este documento é um extrato consolidado de serviços prestados pelo Laboratório ProtJund.</p>
        </div>

    </div>

    <!-- Success Message (Toast) -->
    <div id="toast" class="fixed bottom-10 left-1/2 -translate-x-1/2 bg-slate-900 text-white px-8 py-4 rounded-3xl font-bold shadow-2xl opacity-0 transition-opacity pointer-events-none z-50">
        <i class="fa-solid fa-circle-check text-emerald-400 mr-2"></i> PDF gerado com sucesso!
    </div>

    <script>
        function downloadPDF() {
            const btn = document.getElementById('downloadBtn');
            const toast = document.getElementById('toast');
            
            // UI Feedback
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Gerando Arquivo...';
            btn.classList.add('opacity-50', 'pointer-events-none');

            const element = document.getElementById('invoice');
            const opt = {
                margin:       0,
                filename:     'Extrato_ProtJund_{{ str_replace(' ', '_', $dentist->name) }}_{{ date('d_m_Y') }}.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };

            // New Promise-based usage:
            html2pdf().set(opt).from(element).save().then(() => {
                // Restore button
                btn.innerHTML = '<i class="fa-solid fa-file-pdf mr-2"></i> Salvar PDF no Computador';
                btn.classList.remove('opacity-50', 'pointer-events-none');
                
                // Show toast
                toast.classList.remove('opacity-0');
                setTimeout(() => { toast.classList.add('opacity-0'); }, 3000);
            });
        }
    </script>

</body>
</html>
