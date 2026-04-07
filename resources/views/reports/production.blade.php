<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Produção - ProtJund</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 p-6">
    <div class="max-w-5xl mx-auto">
        <div class="no-print mb-8 flex justify-between items-center bg-white p-6 rounded-3xl shadow-lg border border-slate-200">
            <div>
                <h1 class="text-xl font-black text-slate-900">Relatório de Produção (Lista de Trabalho)</h1>
                <p class="text-sm text-slate-500 font-medium">Controle interno de prazos e entregas</p>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('reports.index') }}" class="px-6 py-3 bg-slate-100 text-slate-600 rounded-2xl font-bold flex items-center">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Voltar
                </a>
                <button onclick="generatePDF()" class="px-8 py-3 bg-blue-600 text-white rounded-2xl font-black shadow-lg shadow-blue-900/20 hover:bg-blue-700 transition">
                    <i class="fa-solid fa-file-pdf mr-2"></i> Baixar Relatório
                </button>
            </div>
        </div>

        <div id="report-content" class="bg-white p-12 rounded-sm shadow-xl border border-slate-100">
            <div class="flex justify-between items-center border-b-2 border-slate-900 pb-6 mb-8">
                <div>
                    <h2 class="text-2xl font-black text-slate-900">PROTJUND</h2>
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Controle de Produção</p>
                </div>
                <div class="text-right text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                    Gerado em: {{ now()->format('d/m/Y - H:i') }}
                </div>
            </div>

            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900 text-white uppercase text-[10px] font-black tracking-widest">
                        <th class="px-4 py-4 rounded-tl-xl truncate">O.S.</th>
                        <th class="px-4 py-4 truncate">Dentista</th>
                        <th class="px-4 py-4 truncate">Paciente</th>
                        <th class="px-4 py-4 truncate">Serviço</th>
                        <th class="px-4 py-4 truncate">Entrega</th>
                        <th class="px-4 py-4 rounded-tr-xl truncate">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $order)
                    <tr class="text-sm font-medium hover:bg-slate-50 transition">
                        <td class="px-4 py-4 font-black">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-4 py-4 text-slate-600">{{ $order->dentist->name }}</td>
                        <td class="px-4 py-4 text-slate-600">{{ $order->patient ? $order->patient->name : '---' }}</td>
                        <td class="px-4 py-4 text-slate-900 font-bold">{{ $order->service_name }}</td>
                        <td class="px-4 py-4 {{ $order->delivery_date && $order->delivery_date->isPast() ? 'text-rose-600 font-black' : 'text-slate-600' }}">
                            {{ $order->delivery_date ? $order->delivery_date->format('d/m/Y') : 'A definir' }}
                        </td>
                        <td class="px-4 py-4">
                            <span class="text-[10px] uppercase font-black {{ $order->status == 'Atrasado' ? 'text-rose-600' : 'text-blue-600' }}">
                                {{ $order->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-slate-400 font-bold italic uppercase tracking-widest">
                            Nenhum trabalho em produção no momento.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-12 pt-8 border-t border-slate-100 flex justify-between items-center">
                <div class="text-xs font-bold text-slate-400">Total de Trabalhos Ativos: <span class="text-slate-900 font-black">{{ $orders->count() }}</span></div>
                <div class="text-[8px] font-bold text-slate-300 uppercase italic">ProtJund Dental Lab Management System</div>
            </div>
        </div>
    </div>

    <script>
        function generatePDF() {
            const element = document.getElementById('report-content');
            const opt = {
                margin:       [0, 0, 0, 0],
                filename:     'Relatorio_Producao_{{ date("d_m_Y") }}.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true },
                jsPDF:        { unit: 'in', format: 'a4', orientation: 'landscape' }
            };
            html2pdf().set(opt).from(element).save();
        }
    </script>
</body>
</html>
