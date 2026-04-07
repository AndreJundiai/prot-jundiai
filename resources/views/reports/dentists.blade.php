<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Dentistas - ProtJund</title>
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
                <h1 class="text-xl font-black text-slate-900">Relatório de Clientes (Dentistas)</h1>
                <p class="text-sm text-slate-500 font-medium">Contatos e dados cadastrais</p>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('reports.index') }}" class="px-6 py-3 bg-slate-100 text-slate-600 rounded-2xl font-bold flex items-center">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Voltar
                </a>
                <button onclick="generatePDF()" class="px-8 py-3 bg-blue-600 text-white rounded-2xl font-black shadow-lg shadow-blue-900/20 hover:bg-blue-700 transition">
                    <i class="fa-solid fa-file-pdf mr-2"></i> Baixar PDF
                </button>
            </div>
        </div>

        <div id="report-content" class="bg-white p-12 rounded-sm shadow-xl border border-slate-100">
            <div class="flex justify-between items-center border-b-2 border-slate-900 pb-6 mb-8">
                <div>
                    <h2 class="text-2xl font-black text-slate-900">PROTJUND</h2>
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Cadastro de Clientes</p>
                </div>
                <div class="text-right text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                    {{ now()->format('d/m/Y') }}
                </div>
            </div>

            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100 text-slate-600 uppercase text-[9px] font-extrabold tracking-[0.2em]">
                        <th class="px-4 py-4 truncate">Nome do Dentista</th>
                        <th class="px-4 py-4 truncate">E-mail</th>
                        <th class="px-4 py-4 truncate">Telefone</th>
                        <th class="px-4 py-4 truncate">Endereço</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($dentists as $dentist)
                    <tr class="text-sm hover:bg-slate-50 transition">
                        <td class="px-4 py-5 font-black text-slate-900">{{ $dentist->name }}</td>
                        <td class="px-4 py-5 text-slate-500 font-medium">{{ $dentist->email ?: '---' }}</td>
                        <td class="px-4 py-5 font-bold text-slate-700">{{ $dentist->phone ?: '---' }}</td>
                        <td class="px-4 py-5 text-slate-500 text-xs truncate max-w-[200px]">{{ $dentist->address ?: '---' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-12 pt-8 border-t border-slate-100 flex justify-between items-center">
                <div class="text-xs font-bold text-slate-400">Total de Clientes Cadastrados: <span class="text-slate-900 font-black">{{ $dentists->count() }}</span></div>
                <div class="text-[8px] font-bold text-slate-300 uppercase italic">Gerado via ProtJund Dental System</div>
            </div>
        </div>
    </div>

    <script>
        function generatePDF() {
            const element = document.getElementById('report-content');
            const opt = {
                margin:       [0, 0, 0, 0],
                filename:     'Relatorio_Dentistas_{{ date("d_m_Y") }}.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true },
                jsPDF:        { unit: 'in', format: 'a4', orientation: 'portrait' }
            };
            html2pdf().set(opt).from(element).save();
        }
    </script>
</body>
</html>
