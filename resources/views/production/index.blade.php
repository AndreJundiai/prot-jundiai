@extends('layouts.app')

@section('header', 'Controle de Produção')

@section('content')
<div class="mb-6">
    <p class="text-gray-500">Acompanhamento dos pedidos em andamento no laboratório.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    
    <!-- Aberto -->
    <div class="flex flex-col">
        <div class="bg-gray-100/50 border-t-4 border-gray-400 rounded-t-lg px-4 py-3 pb-2 flex justify-between items-center">
            <h3 class="font-semibold text-gray-700">Abertos</h3>
            <span class="bg-gray-200 text-gray-700 text-xs font-bold px-2 py-1 rounded-full">{{ $abertos->count() }}</span>
        </div>
        <div class="bg-gray-50/50 border border-t-0 border-gray-200 rounded-b-lg p-3 flex-1 space-y-3">
            @forelse($abertos as $order)
                <div class="bg-white border border-gray-200 shadow-sm rounded-md p-4 transition hover:shadow-md">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-xs font-medium text-gray-400">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                        <a href="{{ route('orders.technical_records.edit', $order) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                            <i class="fa-solid fa-tooth"></i> Ficha
                        </a>
                    </div>
                    <p class="font-medium text-gray-800 text-sm mb-1">{{ $order->dentist->name }}</p>
                    <p class="text-xs text-gray-500 mb-3"><i class="fa-solid fa-user text-gray-400 mr-1"></i> {{ $order->patient ? $order->patient->name : 'N/A' }}</p>
                    <div class="flex items-center text-xs {{ $order->delivery_date && $order->delivery_date->isPast() ? 'text-red-600 font-bold' : 'text-gray-500' }}">
                        <i class="fa-solid fa-calendar mr-1"></i> {{ $order->delivery_date ? $order->delivery_date->format('d/m/Y') : 'Data não definida' }}
                    </div>
                </div>
            @empty
                <div class="text-center py-6 text-gray-400 text-sm border-2 border-dashed border-gray-200 rounded-md">
                    Nenhum pedido aberto.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Em Produção -->
    <div class="flex flex-col">
        <div class="bg-yellow-50/50 border-t-4 border-yellow-400 rounded-t-lg px-4 py-3 pb-2 flex justify-between items-center">
            <h3 class="font-semibold text-yellow-700">Em Produção</h3>
            <span class="bg-yellow-200 text-yellow-800 text-xs font-bold px-2 py-1 rounded-full">{{ $emProducao->count() }}</span>
        </div>
        <div class="bg-gray-50/50 border border-t-0 border-gray-200 rounded-b-lg p-3 flex-1 space-y-3">
            @forelse($emProducao as $order)
                <div class="bg-white border border-yellow-100 shadow-sm rounded-md p-4 transition hover:shadow-md border-l-4 border-l-yellow-400">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-xs font-medium text-gray-400">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                        <a href="{{ route('orders.technical_records.edit', $order) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                            <i class="fa-solid fa-tooth"></i> Ficha
                        </a>
                    </div>
                    <p class="font-medium text-gray-800 text-sm mb-1">{{ $order->dentist->name }}</p>
                    <p class="text-xs text-gray-500 mb-3"><i class="fa-solid fa-user text-gray-400 mr-1"></i> {{ $order->patient ? $order->patient->name : 'N/A' }}</p>
                    <div class="flex items-center text-xs {{ $order->delivery_date && $order->delivery_date->isPast() ? 'text-red-600 font-bold' : 'text-gray-500' }}">
                        <i class="fa-solid fa-calendar mr-1"></i> {{ $order->delivery_date ? $order->delivery_date->format('d/m/Y') : 'Data não definida' }}
                    </div>
                </div>
            @empty
                <div class="text-center py-6 text-gray-400 text-sm border-2 border-dashed border-gray-200 rounded-md">
                    Nenhum pedido em produção.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Faltando / Atrasado -->
    <div class="flex flex-col">
        <div class="bg-red-50/50 border-t-4 border-red-500 rounded-t-lg px-4 py-3 pb-2 flex justify-between items-center">
            <h3 class="font-semibold text-red-700">Faltando / Atraso</h3>
            <span class="bg-red-200 text-red-800 text-xs font-bold px-2 py-1 rounded-full">{{ $faltando->count() }}</span>
        </div>
        <div class="bg-gray-50/50 border border-t-0 border-gray-200 rounded-b-lg p-3 flex-1 space-y-3">
            @forelse($faltando as $order)
                <div class="bg-white border border-red-100 shadow-sm rounded-md p-4 transition hover:shadow-md border-l-4 border-l-red-500">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-xs font-medium text-gray-400">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                        <a href="{{ route('orders.technical_records.edit', $order) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                            <i class="fa-solid fa-tooth"></i> Ficha
                        </a>
                    </div>
                    <p class="font-medium text-gray-800 text-sm mb-1">{{ $order->dentist->name }}</p>
                    <p class="text-xs text-gray-500 mb-3"><i class="fa-solid fa-user text-gray-400 mr-1"></i> {{ $order->patient ? $order->patient->name : 'N/A' }}</p>
                    <div class="flex items-center text-xs text-red-600 font-bold">
                        <i class="fa-solid fa-calendar mr-1"></i> {{ $order->delivery_date ? $order->delivery_date->format('d/m/Y') : 'Data não definida' }}
                    </div>
                </div>
            @empty
                <div class="text-center py-6 text-gray-400 text-sm border-2 border-dashed border-gray-200 rounded-md">
                    Nenhum pedido atrasado.
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
