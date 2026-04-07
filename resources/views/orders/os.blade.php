<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>O.S. #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }} - ProtJund</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .print-only { display: none; }
        @media print {
            .no-print { display: none; }
            .print-only { display: block; }
        }
    </style>
</head>
<body class="bg-slate-50 p-4 md:p-10">

    <div class="max-w-4xl mx-auto">
        <!-- Floating Toolbar -->
        <div class="no-print mb-8 flex justify-between items-center bg-white p-6 rounded-3xl shadow-xl border border-slate-200">
            <div>
                <h1 class="text-xl font-black text-slate-900">Ordem de Serviço (O.S.)</h1>
                <p class="text-sm text-slate-500 font-medium">Visualize e salve a ficha de trabalho</p>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('orders.edit', $order) }}" class="px-6 py-3 bg-slate-100 text-slate-600 rounded-2xl font-bold hover:bg-slate-200 transition flex items-center">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Voltar
                </a>
                <button onclick="generatePDF()" class="px-8 py-3 bg-blue-600 text-white rounded-2xl font-black shadow-lg shadow-blue-900/20 hover:bg-blue-700 transition flex items-center">
                    <i class="fa-solid fa-file-pdf mr-2"></i> Salvar PDF
                </button>
            </div>
        </div>

        <!-- Document Content -->
        <div id="os-document" class="bg-white shadow-2xl p-12 rounded-sm border border-slate-100 relative overflow-hidden">
            <!-- Laboratory Header -->
            <div class="flex justify-between items-start border-b-4 border-slate-900 pb-8 mb-8">
                <div>
                    <h2 class="text-3xl font-black text-slate-900 tracking-tighter">PROTJUND</h2>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-500">Laboratório de Prótese Dentária</p>
                    <div class="mt-4 text-xs font-medium text-slate-400 space-y-1">
                        <p><i class="fa-solid fa-location-dot mr-1"></i> Jundiaí, SP</p>
                        <p><i class="fa-solid fa-phone mr-1"></i> (11) 99999-9999</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="bg-slate-900 text-white px-6 py-3 rounded-lg inline-block">
                        <p class="text-[10px] font-bold uppercase tracking-widest opacity-60">Número da O.S.</p>
                        <p class="text-2xl font-black">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <p class="mt-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Data de Entrada: <span class="text-slate-900">{{ $order->created_at->format('d/m/Y') }}</span></p>
                </div>
            </div>

            <!-- Client & Patient Info -->
            <div class="grid grid-cols-2 gap-12 mb-10">
                <div class="space-y-4">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">Informações do Cliente</h3>
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase">Dentista Requisitante</p>
                        <p class="text-lg font-black text-slate-900">{{ $order->dentist->name }}</p>
                    </div>
                </div>
                <div class="space-y-4 text-right">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2 text-right">Informações do Paciente</h3>
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase">Nome do Paciente</p>
                        <p class="text-lg font-black text-slate-900">{{ $order->patient ? $order->patient->name : 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Technical Details -->
            <div class="bg-slate-50 p-8 rounded-2xl mb-10 border border-slate-100">
                <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-widest mb-6 flex items-center">
                    <i class="fa-solid fa-microscope mr-2 text-blue-600"></i> Especificações Técnicas
                </h3>
                <div class="grid grid-cols-3 gap-8">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Serviço</p>
                        <p class="text-sm font-black text-slate-900">{{ $order->service_name ?: 'A definir' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Material</p>
                        <p class="text-sm font-black text-slate-900">{{ $order->technicalRecord ? $order->technicalRecord->material : 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Cor (VITA)</p>
                        <p class="text-sm font-black text-slate-900">{{ $order->technicalRecord ? $order->technicalRecord->color : 'N/A' }}</p>
                    </div>
                </div>
                
                <div class="mt-8 pt-8 border-t border-slate-200 grid grid-cols-2 gap-8">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Oclusão</p>
                        <p class="text-sm font-bold text-slate-900">{{ $order->technicalRecord ? $order->technicalRecord->occlusion : 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Acabamento</p>
                        <p class="text-sm font-bold text-slate-900">{{ $order->technicalRecord ? $order->technicalRecord->finish : 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Odontograma Visualization -->
            <div class="mb-10">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 border-b border-slate-100 pb-2">Localização Dental (Odontograma)</h3>
                <div class="flex justify-center flex-wrap gap-2">
                    @for($i=11; $i<=18; $i++)
                        <div class="w-8 h-12 border-2 border-slate-200 rounded flex flex-col items-center justify-center">
                            <span class="text-[8px] font-black text-slate-400">{{ $i }}</span>
                            <div class="w-4 h-4 rounded-full bg-slate-100 mt-1"></div>
                        </div>
                    @endfor
                </div>
                <p class="text-center text-[9px] text-slate-400 mt-4 uppercase font-bold tracking-widest italic">* Esta ficha deve acompanhar o modelo de gesso durante todo o processo</p>
            </div>

            <!-- Notes Section -->
            <div class="mb-10">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 border-b border-slate-100 pb-2">Observações Adicionais</h3>
                <div class="min-h-[100px] p-6 border-2 border-dashed border-slate-200 rounded-2xl text-sm italic text-slate-600">
                    {{ $order->technicalRecord ? $order->technicalRecord->notes : 'Nenhuma observação técnica fornecida.' }}
                </div>
            </div>

            <!-- Footer / Deadline -->
            <div class="flex justify-between items-center bg-slate-900 text-white p-8 rounded-2xl">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest opacity-60">Prazo de Entrega Estimado</p>
                    <p class="text-xl font-black">{{ $order->delivery_date ? $order->delivery_date->format('d/m/Y') : 'URGENTE / A DEFINIR' }}</p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-bold uppercase tracking-widest opacity-60">Status do Pedido</p>
                    <p class="text-xl font-black uppercase">{{ $order->status }}</p>
                </div>
            </div>

            <div class="mt-8 flex justify-between">
                <div class="w-48 border-t border-slate-900 pt-2 text-center text-[10px] font-bold uppercase text-slate-400">Assinatura Técnico</div>
                <div class="w-48 border-t border-slate-900 pt-2 text-center text-[10px] font-bold uppercase text-slate-400">Conferência / Q.A.</div>
            </div>

        </div>
    </div>

    <script>
        function generatePDF() {
            const element = document.getElementById('os-document');
            const opt = {
                margin:       [0, 0, 0, 0],
                filename:     'OS_{{ str_pad($order->id, 5, "0", STR_PAD_LEFT) }}_{{ $order->patient ? $order->patient->name : "SemNome" }}.pdf',
                image:        { type: 'jpeg', quality: 1.0 },
                html2canvas:  { scale: 2, useCORS: true, letterRendering: true },
                jsPDF:        { unit: 'in', format: 'a4', orientation: 'portrait' }
            };

            html2pdf().set(opt).from(element).save();
        }
    </script>
</body>
</html>
