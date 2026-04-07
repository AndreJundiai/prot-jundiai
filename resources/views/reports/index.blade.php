@extends('layouts.app')

@section('header', 'Relatórios & Estatísticas')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <p class="text-sm font-medium text-gray-500 mb-1">Total de Pedidos</p>
        <p class="text-3xl font-bold text-gray-800">{{ $stats['total_orders'] }}</p>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <p class="text-sm font-medium text-gray-500 mb-1">Pedidos Finalizados</p>
        <p class="text-3xl font-bold text-green-600">{{ $stats['finished_orders'] }}</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <p class="text-sm font-medium text-gray-500 mb-1">Dentistas Cadastrados</p>
        <p class="text-3xl font-bold text-gray-800">{{ $stats['active_dentists'] }}</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <p class="text-sm font-medium text-gray-500 mb-1">Volume Processado (Bruto)</p>
        <p class="text-3xl font-bold text-blue-600">R$ {{ number_format($stats['total_revenue'], 2, ',', '.') }}</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Extratos de Cobrança por Dentista</h3>
    </div>
    <div class="p-6">
        <form action="#" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div class="col-span-1 md:col-span-2">
                <label for="dentist_id" class="block text-sm font-medium text-gray-700 mb-1">Dentista</label>
                <select name="dentist_id" id="dentist_id" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">Selecione um Dentista</option>
                    @foreach($dentists as $dentist)
                        <option value="{{ $dentist->id }}">{{ $dentist->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="month" class="block text-sm font-medium text-gray-700 mb-1">Mês/Ano</label>
                <input type="month" name="month" id="month" value="{{ date('Y-m') }}" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div>
                <button type="button" onclick="alert('Funcionalidade de geração de PDF simulada para o Prototipo.')" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-slate-800 hover:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                    <i class="fa-solid fa-file-pdf mr-2"></i> Gerar Extrato
                </button>
            </div>
        </form>
    </div>
</div>

<div class="bg-blue-50 rounded-xl border border-blue-100 p-6 text-center">
    <i class="fa-solid fa-chart-line text-blue-300 text-5xl mb-4"></i>
    <h3 class="text-lg font-medium text-blue-900 mb-2">Relatórios Avançados</h3>
    <p class="text-blue-700 text-sm max-w-2xl mx-auto">Esta seção será expandida com gráficos analíticos de produção, materiais mais utilizados e histórico financeiro na próxima phase de implementação.</p>
</div>
@endsection
