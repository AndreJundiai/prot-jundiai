<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Extrato Individual #{{ $order->id }} - Laboratório J.C</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .print-only { display: none; }
        @media print {
            @page { margin: 0; size: auto; }
            .no-print { display: none !important; visibility: hidden !important; height: 0 !important; margin: 0 !important; padding: 0 !important; }
            .print-only { display: block !important; }
            body { background: white !important; padding: 0 !important; margin: 0 !important; height: auto !important; }
            #os-document { 
                box-shadow: none !important; 
                border: 0 !important;
                padding: 10mm !important;
                margin: 0 auto !important;
                width: 210mm !important;
                height: 295mm !important; /* Shorter to be absolutely safe */
                overflow: hidden !important;
                page-break-after: avoid !important;
                page-break-before: avoid !important;
            }
        }
        .official-table th, .official-table td {
            border: 1px solid #000;
            padding: 4px 8px;
        }
    </style>
</head>
<body class="bg-gray-100 p-4 min-h-screen">

    <div class="max-w-[210mm] mx-auto">
        <!-- Toolbar -->
        <div class="no-print mb-8 flex justify-between items-center bg-white p-6 rounded-2xl shadow-xl border border-slate-200">
            <a href="{{ route('orders.index') }}" class="px-6 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-200 transition flex items-center text-sm">
                <i class="fa-solid fa-arrow-left mr-2"></i> Voltar
            </a>
            <div class="flex gap-4">
                <button onclick="window.print()" class="px-8 py-3 bg-white border-2 border-slate-900 text-slate-900 rounded-xl font-black hover:bg-slate-900 hover:text-white transition flex items-center text-xs uppercase tracking-widest">
                    <i class="fa-solid fa-print mr-2"></i> Abrir Impressão
                </button>
                <button id="downloadBtn" onclick="generatePDF()" class="px-10 py-3 bg-blue-600 text-white rounded-xl font-black shadow-lg shadow-blue-900/20 hover:bg-blue-700 transition flex items-center text-xs uppercase tracking-widest">
                    <i class="fa-solid fa-file-pdf mr-2 text-sm"></i> Salvar PDF
                </button>
            </div>
        </div>
        <!-- Document Content -->
        <div id="os-document" class="bg-white p-8 md:p-12 relative shadow-2xl border border-black overflow-hidden">
            
            <!-- Header -->
            <div class="flex justify-between items-start mb-4 border-b-2 border-black pb-4">
                <div class="text-left">
                    <h1 class="text-3xl font-black tracking-tighter leading-none">LABORATÓRIO J. C</h1>
                    <p class="text-xs font-black uppercase tracking-[0.2em] mt-1">LABORATÓRIO DE PRÓTESE DENTARIA</p>
                </div>
                <div class="text-right">
                    <h2 class="text-3xl font-black text-slate-900 uppercase tracking-tighter mb-1 italic">Extrato Individual</h2>
                    <div class="flex flex-col items-end">
                        <p class="text-3xl font-black text-black tracking-tight">{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}-1</p>
                        <div class="mt-1 flex flex-col items-center">
                            <div class="h-8 w-48 flex items-center justify-center bg-white p-1">
                                <div class="flex gap-[0.5px] items-end h-full w-full">
                                    @foreach(str_split(str_pad($order->id, 12, '0', STR_PAD_LEFT)) as $digit)
                                        <div class="flex-1 bg-black h-{{ ($digit % 3 == 0) ? 'full' : (($digit % 2 == 0) ? '[80%]' : '[60%]') }}"></div>
                                        <div class="flex-1 bg-white h-full"></div>
                                    @endforeach
                                </div>
                            </div>
                            <span class="text-[8px] font-black mt-0.5 tracking-[0.3em]">{{ str_pad($order->id, 12, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Bar -->
            <div class="grid grid-cols-12 gap-4 text-[10px] items-start">
                <div class="col-span-8 space-y-2 uppercase font-bold text-slate-800">
                    <div class="flex items-center">
                        <span class="w-16 font-black">Cliente:</span>
                        <div class="flex-1 border-b border-dotted border-black pb-0.5">
                            {{ $order->dentist->name }} <span class="bg-black text-white px-2 ml-4">0</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <span class="w-16 font-black">Paciente:</span>
                        <div class="flex-1 border-b border-dotted border-black pb-0.5 text-xl font-black italic">
                            {{ $order->patient ? $order->patient->name : 'N/A' }} <span class="text-sm not-italic ml-2">C/A</span>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <span class="w-16 font-black">Endereço:</span>
                        <div class="flex-1 border-b border-dotted border-black pb-0.5">
                            {{ $order->dentist->address ?: 'RUA EXEMPLO, 123 - CENTRO' }}
                        </div>
                    </div>
                </div>

                <div class="col-span-4">
                    <table class="w-full official-table font-black text-[9px] uppercase">
                        <tr>
                            <th class="bg-gray-100 text-left py-1">Entrada:</th>
                            <td class="text-right py-1">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th class="bg-gray-100 text-left py-1">Prevista:</th>
                            <td class="text-right py-1">{{ $order->delivery_date ? $order->delivery_date->format('d/m/Y') : '---' }} 00:00</td>
                        </tr>
                        <tr>
                            <th class="bg-gray-100 text-left py-1">Finalizado:</th>
                            <td class="text-right py-1 font-black italic">{{ $order->status == 'Finalizado' ? $order->updated_at->format('d/m/Y') : '---' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Services Table -->
            <div class="mt-4">
                <table class="w-full official-table text-[10px] font-black uppercase">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="text-left py-1 px-2 border border-black">Descrição [SERVIÇOS]</th>
                            <th class="w-24 text-right py-1 px-2 border border-black">Unitário</th>
                            <th class="w-12 text-center py-1 px-2 border border-black">%</th>
                            <th class="w-24 text-right py-1 px-2 border border-black">Valor Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="h-6">
                            <td class="border border-black px-2">{{ $order->service_name ?: '---' }}</td>
                            <td class="border border-black px-2 text-right">{{ number_format($order->price, 2, ',', '.') }}</td>
                            <td class="border border-black px-2 text-center">0</td>
                            <td class="border border-black px-2 text-right">{{ number_format($order->price, 2, ',', '.') }}</td>
                        </tr>
                        @for($i=0; $i<4; $i++)
                        <tr class="h-6"><td class="border border-black"></td><td class="border border-black"></td><td class="border border-black"></td><td class="border border-black"></td></tr>
                        @endfor
                    </tbody>
                </table>
            </div>

            <!-- Financial Summary Row - EXACTLY AS IMAGE -->
            <div class="mt-2 flex justify-end gap-8 text-[11px] font-black uppercase">
                <div class="flex items-center gap-1">
                    <span>Saldo Anterior:</span>
                    <span class="border-b border-black min-w-[70px] text-right">{{ number_format($previousBalance, 2, ',', '.') }}</span>
                </div>
                <div class="flex items-center gap-1">
                    <span>PRODUÇÃO:</span>
                    <span class="border-b border-black min-w-[70px] text-right">{{ number_format($order->price, 2, ',', '.') }}</span>
                </div>
                <div class="flex items-center gap-1">
                    <span>ULT PGTO:</span>
                    <span class="border-b border-black min-w-[70px] text-right">{{ $lastPayment ? number_format($lastPayment->amount, 2, ',', '.') : '0,00' }}</span>
                </div>
                <div class="flex items-center gap-1">
                    <span>Saldo ATUAL:</span>
                    <span class="border-b border-black min-w-[70px] text-right">{{ number_format($currentBalance, 2, ',', '.') }}</span>
                </div>
            </div>

            <!-- Material Checklist Grid -->
            <div class="mt-4 grid grid-cols-5 gap-y-1 text-[8px] font-black uppercase">
                @php
                    $materialList = ['Provisório', 'Molde', 'Articulador', 'Transfer', 'Análogo', 'Gesso', 'Coping', 'Parafuso', 'Chave', 'Escaneamento', 'Pino', 'Coroa', 'Outros'];
                    $fornecido = strtolower($order->technicalRecord->material_fornecido);
                    $devolvido = strtolower($order->technicalRecord->material_devolvido);
                @endphp
                @foreach($materialList as $material)
                    <div class="flex items-center gap-1">
                        <div class="w-3 h-3 border border-black flex items-center justify-center text-[7px]">
                            {{ str_contains($fornecido, strtolower($material)) ? 'X' : '' }}
                        </div>
                        <span>{{ $material }}</span>
                    </div>
                @endforeach
            </div>

            <!-- Bottom Section: Technical & Odontogram -->
            <div class="mt-4 grid grid-cols-12 gap-8 border-t-2 border-black pt-4">
                
                <!-- Left: Technical Specs -->
                <div class="col-span-4 space-y-1 text-[10px] font-black uppercase">
                    <div class="flex justify-between border-b border-black pb-0.5">
                        <span>ESCALA:</span>
                        <span>{{ $order->technicalRecord->escala ?: '---' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-black pb-0.5">
                        <span>COR:</span>
                        <span>{{ $order->technicalRecord->color ?: '---' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-black pb-0.5">
                        <span>ANTAGONISTA:</span>
                        <span>{{ $order->technicalRecord->antagonista ?: '---' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-black pb-0.5">
                        <span>MODELO:</span>
                        <span>{{ $order->technicalRecord->modelo ?: '---' }}</span>
                    </div>
                    
                    <div class="mt-6 flex flex-col gap-1">
                        <span class="bg-black text-white px-2 py-0.5 text-center text-[8px]">ÚLTIMO PAGAMENTO</span>
                        <div class="border border-black p-2 text-center text-sm">
                            {{ $lastPayment ? $lastPayment->transaction_date->format('d/m/Y') : '---' }}
                            <br>
                            R$ {{ $lastPayment ? number_format($lastPayment->amount, 2, ',', '.') : '0,00' }}
                        </div>
                    </div>
                </div>

                <!-- Right: Odontogram Grid -->
                <div class="col-span-8 flex flex-col items-center">
                    <div class="border border-black p-2 w-full max-w-sm">
                        {{-- Legend --}}
                        <div class="flex justify-between text-[7px] font-black uppercase mb-1 opacity-60">
                            <span>C - Coroa | I - Implante | L - Laminado</span>
                            <span>O - Onlay-Inlay | P - Pontico | A - Ausente</span>
                        </div>
                        
                        <div class="grid grid-cols-2 text-center font-mono text-[11px] font-black">
                            <div class="border-r border-black pr-2 flex justify-between">
                                <span>18</span><span>17</span><span>16</span><span>15</span><span>14</span><span>13</span><span>12</span><span>11</span>
                            </div>
                            <div class="pl-2 flex justify-between">
                                <span>21</span><span>22</span><span>23</span><span>24</span><span>25</span><span>26</span><span>27</span><span>28</span>
                            </div>
                        </div>
                        <div class="border-t border-black my-1"></div>
                        <div class="grid grid-cols-2 text-center font-mono text-[11px] font-black">
                            <div class="border-r border-black pr-2 flex justify-between">
                                <span>48</span><span>47</span><span>46</span><span>45</span><span>44</span><span>43</span><span>42</span><span>41</span>
                            </div>
                            <div class="pl-2 flex justify-between">
                                <span>31</span><span>32</span><span>33</span><span>34</span><span>35</span><span>36</span><span>37</span><span>38</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Details -->
            <div class="mt-4 flex justify-between text-[10px] font-black border-y border-black py-1.5 uppercase">
                <p>Código: <span class="px-2">{{ str_pad($order->dentist_id, 4, '0', STR_PAD_LEFT) }}</span></p>
                <p>Nº Ordem: <span class="px-2">{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span></p>
                <p>Caixa: <span class="px-2">---</span></p>
                <div class="flex items-center gap-1">
                    <span>X</span>
                    <div class="w-48 border-b border-black"></div>
                </div>
            </div>

            <!-- Signature Area -->
            <div class="mt-4 flex justify-end">
                <p class="text-[9px] font-black uppercase text-center mr-12">Recebi (emos) o (s) trabalho (s) acima</p>
            </div>

            <!-- Slogan -->
            <div class="mt-4 text-center">
                <p class="text-sm font-black italic tracking-tighter uppercase whitespace-pre-line underline decoration-slate-200 decoration-4 underline-offset-4">"TRABALHAMOS HOJE PARA O SEU PACIENTE SORRIR AMANHÃ"</p>
                
                <div class="mt-4 flex justify-between text-[7px] font-black text-slate-300 uppercase tracking-widest">
                    <span>PROTJUND SYSTEM V4.7.7</span>
                    <span>PROTOCOLO DE ENTREGA INDIVIDUAL</span>
                </div>
            </div>

        </div>
    </div>

    <script>
        function generatePDF() {
            const btn = document.getElementById('downloadBtn');
            const toast = document.getElementById('toast');
            
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Gerando...';
            btn.classList.add('opacity-50', 'pointer-events-none');

            const element = document.getElementById('os-document');
            const opt = {
                margin:       [10, 10, 10, 10],
                filename:     'Extrato_Individual_{{ $order->id }}_{{ str_replace(' ', '_', $order->patient->name ?? 'Sem_Paciente') }}.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };

            html2pdf().set(opt).from(element).save().then(() => {
                btn.innerHTML = '<i class="fa-solid fa-file-pdf mr-2 text-sm"></i> Salvar PDF';
                btn.classList.remove('opacity-50', 'pointer-events-none');
                
                // Show toast
                toast.classList.remove('opacity-0');
                setTimeout(() => { toast.classList.add('opacity-0'); }, 3000);
            });
        }
    </script>

    <div id="toast" class="no-print fixed bottom-10 left-1/2 -translate-x-1/2 bg-slate-900 text-white px-8 py-4 rounded-3xl font-bold shadow-2xl opacity-0 transition-opacity pointer-events-none z-50">
        <i class="fa-solid fa-circle-check text-emerald-400 mr-2"></i> PDF gerado com sucesso!
    </div>

</body>
</html>
