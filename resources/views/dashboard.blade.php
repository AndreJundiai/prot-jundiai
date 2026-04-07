@extends('layouts.app')

@section('header', 'Painel de Controle')

@section('content')
@php
    $totalOrders = \App\Models\Order::count();
    $inProduction = \App\Models\Order::where('status', 'Em Produção')->count();
    $finished = \App\Models\Order::where('status', 'Finalizado')->count();
    $totalDentists = \App\Models\Dentist::count();
    $receivables = \App\Models\FinancialRecord::where('type', 'debit')->sum('amount') - \App\Models\FinancialRecord::where('type', 'credit')->sum('amount');
    $recentOrders = \App\Models\Order::with(['dentist', 'patient'])->orderBy('created_at', 'desc')->take(5)->get();
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
    <!-- Production Summary -->
    <div class="bg-white rounded-3xl shadow-sm p-8 border border-slate-200 flex items-center group hover:border-blue-500 transition-all duration-300">
        <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mr-6 group-hover:bg-blue-600 group-hover:text-white transition-all shadow-lg shadow-blue-100">
            <i class="fa-solid fa-hammer text-2xl"></i>
        </div>
        <div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Em Produção</p>
            <p class="text-3xl font-black text-slate-900 leading-none">{{ $inProduction }}</p>
        </div>
    </div>
    
    <!-- Finished Summary -->
    <div class="bg-white rounded-3xl shadow-sm p-8 border border-slate-200 flex items-center group hover:border-emerald-500 transition-all duration-300">
        <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mr-6 group-hover:bg-emerald-600 group-hover:text-white transition-all shadow-lg shadow-emerald-100">
            <i class="fa-solid fa-check-double text-2xl"></i>
        </div>
        <div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Finalizados</p>
            <p class="text-3xl font-black text-slate-900 leading-none">{{ $finished }}</p>
        </div>
    </div>

    <!-- Clients Summary -->
    <div class="bg-white rounded-3xl shadow-sm p-8 border border-slate-200 flex items-center group hover:border-indigo-500 transition-all duration-300">
        <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mr-6 group-hover:bg-indigo-600 group-hover:text-white transition-all shadow-lg shadow-indigo-100">
            <i class="fa-solid fa-user-doctor text-2xl"></i>
        </div>
        <div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Dentistas</p>
            <p class="text-3xl font-black text-slate-900 leading-none">{{ $totalDentists }}</p>
        </div>
    </div>

    <!-- Financial Summary -->
    <div class="bg-slate-900 rounded-3xl shadow-2xl p-8 text-white flex items-center group transition-all duration-300">
        <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center mr-6 shadow-lg shadow-blue-900/40">
            <i class="fa-solid fa-hand-holding-dollar text-2xl"></i>
        </div>
        <div>
            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">A Receber</p>
            <p class="text-2xl font-black text-white leading-none">R$ {{ number_format($receivables, 2, ',', '.') }}</p>
        </div>
    </div>
</div>

<!-- Recent Orders Table -->
<div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
        <h3 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em]">Fluxo de Pedidos Recentes</h3>
        <a href="/orders" class="text-xs font-bold text-blue-600 hover:text-blue-800 uppercase tracking-widest underline">Ver Listagem Completa</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-white text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black border-b border-slate-100">
                    <th class="px-8 py-5">Protocolo</th>
                    <th class="px-8 py-5">Entrega</th>
                    <th class="px-8 py-5">Dentista</th>
                    <th class="px-8 py-5">Paciente</th>
                    <th class="px-8 py-5 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-slate-50">
                @forelse($recentOrders as $order)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-8 py-6 whitespace-nowrap font-mono text-slate-400 text-xs">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-8 py-6 whitespace-nowrap font-bold text-slate-900">{{ $order->delivery_date ? $order->delivery_date->format('d/m/Y') : '-' }}</td>
                    <td class="px-8 py-6 whitespace-nowrap font-extrabold text-slate-800">{{ $order->dentist->name }}</td>
                    <td class="px-8 py-6 whitespace-nowrap text-slate-500 font-medium italic">{{ $order->patient ? $order->patient->name : '-' }}</td>
                    <td class="px-8 py-6 whitespace-nowrap text-center">
                        @php
                            $stClass = match($order->status) {
                                'Aberto' => 'bg-slate-100 text-slate-600',
                                'Em Produção' => 'bg-amber-100 text-amber-700',
                                'Finalizado' => 'bg-emerald-100 text-emerald-700',
                                'Entregue' => 'bg-blue-100 text-blue-700',
                                'Faltando' => 'bg-red-100 text-red-700',
                                default => 'bg-slate-100 text-slate-600'
                            };
                        @endphp
                        <span class="px-4 py-1.5 inline-flex text-[10px] font-black uppercase tracking-widest rounded-full {{ $stClass }}">
                            {{ $order->status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-8 py-12 text-center text-slate-300 italic uppercase tracking-widest font-bold">Nenhum pedido recente</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-8">
    <div class="md:col-span-2 bg-gradient-to-br from-indigo-600 to-blue-700 rounded-3xl shadow-xl p-10 text-white relative overflow-hidden">
        <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-white rounded-full blur-3xl opacity-10"></div>
        <div class="relative z-10">
            <h4 class="text-2xl font-black mb-4 tracking-tight">Bem-vindo ao ProtJund 2.0</h4>
            <p class="text-indigo-100 font-medium leading-relaxed max-w-xl">Centralize todas as operações do seu laboratório em uma única interface inteligente. Use o menu lateral para gerenciar pedidos, financeiro e cadastros com agilidade.</p>
        </div>
    </div>
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8 flex flex-col justify-center items-center text-center">
        <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mb-4">
            <i class="fa-solid fa-rocket text-2xl"></i>
        </div>
        <h5 class="font-bold text-slate-900 uppercase tracking-widest text-xs mb-2">Dica do Dia</h5>
        <p class="text-sm text-slate-500 font-medium">Use os filtros de status na tela de Pedidos para organizar sua bancada de produção.</p>
    </div>
</div>
@endsection
