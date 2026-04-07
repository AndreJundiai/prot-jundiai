@extends('layouts.app')

@section('header', 'Relatórios & Estatísticas')

@section('content')
<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-3xl shadow-sm p-8 border border-slate-100 relative overflow-hidden group">
        <div class="absolute -right-4 -bottom-4 text-slate-50 text-7xl group-hover:scale-110 transition-transform">
            <i class="fa-solid fa-box-archive"></i>
        </div>
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 relative z-10">Total de Pedidos</p>
        <p class="text-4xl font-black text-slate-900 relative z-10">{{ $stats['total_orders'] }}</p>
    </div>
    
    <div class="bg-white rounded-3xl shadow-sm p-8 border border-slate-100 relative overflow-hidden group">
        <div class="absolute -right-4 -bottom-4 text-emerald-50 text-7xl group-hover:scale-110 transition-transform">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 relative z-10">Pedidos Finalizados</p>
        <p class="text-4xl font-black text-emerald-600 relative z-10">{{ $stats['finished_orders'] }}</p>
    </div>

    <div class="bg-white rounded-3xl shadow-sm p-8 border border-slate-100 relative overflow-hidden group">
        <div class="absolute -right-4 -bottom-4 text-slate-50 text-7xl group-hover:scale-110 transition-transform">
            <i class="fa-solid fa-user-doctor"></i>
        </div>
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 relative z-10">Clientes Ativos</p>
        <p class="text-4xl font-black text-slate-900 relative z-10">{{ $stats['active_dentists'] }}</p>
    </div>

    <div class="bg-white rounded-3xl shadow-sm p-8 border border-slate-100 relative overflow-hidden group">
        <div class="absolute -right-4 -bottom-4 text-blue-50 text-7xl group-hover:scale-110 transition-transform">
            <i class="fa-solid fa-money-bill-trend-up"></i>
        </div>
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 relative z-10">Volume Bruto</p>
        <p class="text-4xl font-black text-blue-600 relative z-10">R$ {{ number_format($stats['total_revenue'], 2, ',', '.') }}</p>
    </div>
</div>

<!-- Main Reports Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    
    <!-- Production List Report Card -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-xl transition-shadow flex flex-col">
        <div class="p-8 pb-0">
            <div class="w-14 h-14 bg-indigo-600 text-white rounded-2xl flex items-center justify-center text-2xl shadow-lg shadow-indigo-200 mb-6 font-black">
                <i class="fa-solid fa-list-check"></i>
            </div>
            <h3 class="text-2xl font-black text-slate-900 tracking-tight mb-2">Lista de Produção</h3>
            <p class="text-slate-500 font-medium text-sm mb-8 leading-relaxed">
                Gere uma relação detalhada de todos os serviços que estão atualmente em bancada ou com entrega agendada. Ideal para reuniões de equipe e controle de prazos.
            </p>
        </div>
        <div class="mt-auto bg-slate-50 p-8 border-t border-slate-100">
            <a href="{{ route('reports.production') }}" class="w-full bg-indigo-600 text-white font-black py-4 rounded-2xl flex items-center justify-center hover:bg-indigo-700 transition shadow-lg shadow-indigo-900/10">
                <i class="fa-solid fa-file-invoice mr-2"></i> Abrir Relatório de Produção
            </a>
        </div>
    </div>

    <!-- Dentist List Report Card -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-xl transition-shadow flex flex-col">
        <div class="p-8 pb-0">
            <div class="w-14 h-14 bg-slate-900 text-white rounded-2xl flex items-center justify-center text-2xl shadow-lg shadow-slate-200 mb-6 font-black">
                <i class="fa-solid fa-address-book"></i>
            </div>
            <h3 class="text-2xl font-black text-slate-900 tracking-tight mb-2">Lista de Contatos</h3>
            <p class="text-slate-500 font-medium text-sm mb-8 leading-relaxed">
                Exporte todos os dados cadastrais dos dentistas e clínicas parceiras. Útil para malas-diretas, contatos rápidos e atualização de tabela de preços.
            </p>
        </div>
        <div class="mt-auto bg-slate-900 p-8 border-t border-slate-800">
            <a href="{{ route('reports.dentists') }}" class="w-full bg-white text-slate-900 font-black py-4 rounded-2xl flex items-center justify-center hover:bg-slate-100 transition">
                <i class="fa-solid fa-file-export mr-2"></i> Gerar Lista de Clientes
            </a>
        </div>
    </div>

</div>

<!-- Interactive Statement Generation -->
<div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-200 overflow-hidden mb-8">
    <div class="px-10 py-8 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
        <div>
            <h3 class="text-xl font-black text-slate-900 tracking-tight">Extratos de Cobrança</h3>
            <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-1">Geração de Demonstrativos Mensais</p>
        </div>
        <i class="fa-solid fa-file-invoice-dollar text-slate-200 text-4xl"></i>
    </div>
    <div class="p-10">
        <form action="#" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
            <div class="col-span-1 md:col-span-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Selecione o Dentista</label>
                <select id="dentist_select" class="w-full px-5 py-4 border border-slate-200 rounded-2xl bg-slate-50 font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    <option value="">--- Todos os Clientes ---</option>
                    @foreach($dentists as $dentist)
                        <option value="{{ $dentist->id }}">{{ $dentist->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Período de Referência</label>
                <input type="month" name="month" id="month" value="{{ date('Y-m') }}" class="w-full px-5 py-4 border border-slate-200 rounded-2xl bg-slate-50 font-black text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
            </div>
            <div>
                <button type="button" onclick="goToExtrato()" class="w-full bg-slate-900 text-white font-black py-4 rounded-2xl flex items-center justify-center hover:bg-black transition shadow-xl shadow-slate-900/20">
                    <i class="fa-solid fa-magnifying-glass mr-2"></i> Consultar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function goToExtrato() {
        const dentistId = document.getElementById('dentist_select').value;
        if (!dentistId) {
            alert('Por favor, selecione um dentista para gerar o extrato.');
            return;
        }
        window.location.href = `/financial/extrato/${dentistId}`;
    }
</script>
@endsection
