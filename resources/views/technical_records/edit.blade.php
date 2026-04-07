@extends('layouts.app')

@section('header', 'Ficha Técnica: Pedido #' . str_pad($order->id, 5, '0', STR_PAD_LEFT))

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    
    <!-- Order Context Card -->
    <div class="bg-slate-900 rounded-3xl shadow-2xl p-8 text-white flex flex-col md:flex-row justify-between items-center gap-6 overflow-hidden relative">
        <div class="absolute -top-20 -left-20 w-64 h-64 bg-blue-600 rounded-full blur-[100px] opacity-20"></div>
        <div class="relative z-10">
            <h3 class="text-[10px] font-extrabold text-slate-500 uppercase tracking-[0.3em] mb-2">Informações do Pedido</h3>
            <div class="space-y-1">
                <p class="text-2xl font-black tracking-tight">{{ $order->dentist->name }}</p>
                <div class="flex items-center text-slate-400 font-medium">
                    <i class="fa-solid fa-user mr-2 text-blue-500 text-xs"></i>
                    <span>Paciente: <span class="text-white">{{ $order->patient ? $order->patient->name : 'N/A' }}</span></span>
                    <span class="mx-3 text-slate-700">|</span>
                    <i class="fa-solid fa-calendar mr-2 text-blue-500 text-xs"></i>
                    <span>Entrega: <span class="text-white">{{ $order->delivery_date ? $order->delivery_date->format('d/m/Y') : 'A definir' }}</span></span>
                </div>
            </div>
        </div>
        <div class="relative z-10">
            <span class="px-6 py-2 bg-blue-600 text-white rounded-full font-black text-[10px] uppercase tracking-widest shadow-lg shadow-blue-900/40">
                PRODUÇÃO: {{ $order->status }}
            </span>
        </div>
    </div>

    @if (session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-2xl shadow-sm flex items-center animate-pulse">
        <i class="fa-solid fa-circle-check mr-2 text-lg"></i>
        <span class="font-bold">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Technical Form -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-10 py-8 border-b border-slate-100 flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-slate-900 text-white flex items-center justify-center text-xl shadow-lg shadow-slate-900/20">
                <i class="fa-solid fa-vial-virus"></i>
            </div>
            <div>
                <h3 class="text-xl font-black text-slate-900 tracking-tight">Prescrição Técnica</h3>
                <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-1">Detalhes de Confecção e Acabamento</p>
            </div>
        </div>

        <form action="{{ route('orders.technical_records.update', $order) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="p-10 space-y-8">
                <!-- Grid: Material e Cor -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Material Principal</label>
                        <select name="material" class="w-full px-5 py-4 border border-slate-200 rounded-2xl bg-slate-50 font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 selection:bg-blue-100 transition">
                            <option value="Zircônia" {{ $record->material == 'Zircônia' ? 'selected' : '' }}>Zircônia</option>
                            <option value="Metalo-Cerâmica" {{ $record->material == 'Metalo-Cerâmica' ? 'selected' : '' }}>Metalo-Cerâmica</option>
                            <option value="E-Max" {{ $record->material == 'E-Max' ? 'selected' : '' }}>E-Max / Dissilicato</option>
                            <option value="Resina" {{ $record->material == 'Resina' ? 'selected' : '' }}>Resina Fotopolimerizável</option>
                            <option value="Cromo Cobalto" {{ $record->material == 'Cromo Cobalto' ? 'selected' : '' }}>Cromo Cobalto</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Escala de Cor (VITA)</label>
                        <input type="text" name="color" value="{{ $record->color }}" placeholder="Ex: A1, A2, B3..." class="w-full px-5 py-4 border border-slate-200 rounded-2xl bg-slate-50 font-black text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                </div>

                <!-- Odontograma Placeholder -->
                <div class="bg-slate-50 rounded-3xl p-8 border border-slate-100">
                    <h4 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-6 flex items-center">
                        <i class="fa-solid fa-tooth mr-2 text-blue-500"></i> Localização Dental
                    </h4>
                    <div class="flex flex-wrap gap-4 justify-center">
                        <!-- Simplified tooth selection design -->
                        @for($i=11; $i<=18; $i++)
                            <div class="w-10 h-14 bg-white border border-slate-200 rounded-lg flex flex-col items-center justify-center cursor-pointer hover:border-blue-500 hover:shadow-md transition group">
                                <span class="text-[10px] text-slate-400 group-hover:text-blue-500 font-bold">{{ $i }}</span>
                                <div class="w-4 h-4 rounded-full bg-slate-100 group-hover:bg-blue-100 mt-2"></div>
                            </div>
                        @endfor
                    </div>
                    <p class="text-center text-[10px] text-slate-400 mt-6 italic font-medium uppercase tracking-widest">Clique nos dentes envolvidos no trabalho</p>
                </div>

                <!-- Grid: Oclusão e Acabamento -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Tipo de Oclusão</label>
                        <input type="text" name="occlusion" value="{{ $record->occlusion }}" placeholder="Ex: Normal, Justa..." class="w-full px-5 py-4 border border-slate-200 rounded-2xl bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Tipo de Acabamento</label>
                        <input type="text" name="finish" value="{{ $record->finish }}" placeholder="Ex: Brilhante, Fosco..." class="w-full px-5 py-4 border border-slate-200 rounded-2xl bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Observações Adicionais</label>
                    <textarea name="notes" rows="4" class="w-full px-5 py-4 border border-slate-200 rounded-2xl bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition" placeholder="Instruções específicas para o acabamento, oclusão ou estética...">{{ $record->notes }}</textarea>
                </div>
            </div>

            <div class="px-10 py-8 bg-slate-50 border-t border-slate-100 flex flex-row-reverse gap-4">
                <button type="submit" class="flex-1 bg-slate-900 text-white font-black py-4 rounded-2xl shadow-xl shadow-slate-900/20 hover:bg-black transition transform hover:-translate-y-1">
                    Atualizar Ficha Técnica
                </button>
                <a href="{{ route('orders.index') }}" class="flex-1 bg-white text-slate-500 border border-slate-200 font-bold py-4 rounded-2xl hover:bg-gray-50 flex items-center justify-center transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

</div>
@endsection
