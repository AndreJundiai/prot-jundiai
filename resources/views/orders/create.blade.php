@extends('layouts.app')

@section('header', 'Novo Pedido de Prótese')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('orders.index') }}" class="text-slate-400 hover:text-slate-600 font-bold text-xs uppercase tracking-widest flex items-center transition">
            <i class="fa-solid fa-arrow-left-long mr-2"></i> Voltar para Listagem
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-10 py-8 border-b border-slate-100 flex items-center bg-slate-50/50">
            <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center text-xl shadow-lg shadow-blue-200 mr-4">
                <i class="fa-solid fa-plus-circle"></i>
            </div>
            <div>
                <h3 class="text-xl font-black text-slate-900 tracking-tight">Abertura de Pedido</h3>
                <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-1">Inicie o fluxo de produção do laboratório</p>
            </div>
        </div>

        <form action="{{ route('orders.store') }}" method="POST" class="p-10 space-y-8">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <!-- Dentista -->
                <div>
                    <label for="dentist_id" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Dentista Responsável <span class="text-red-500">*</span></label>
                    <select name="dentist_id" id="dentist_id" required class="w-full px-5 py-4 border border-slate-200 rounded-2xl bg-slate-50 font-bold text-slate-700 focus:ring-2 focus:ring-blue-500 transition">
                        <option value="">Selecione um Dentista</option>
                        @foreach($dentists as $dentist)
                            <option value="{{ $dentist->id }}">{{ $dentist->name }} @if($dentist->cro) (CRO: {{ $dentist->cro }}) @endif</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Paciente -->
                <div>
                    <label for="patient_id" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Nome do Paciente</label>
                    <select name="patient_id" id="patient_id" class="w-full px-5 py-4 border border-slate-200 rounded-2xl bg-slate-50 font-bold text-slate-700 focus:ring-2 focus:ring-blue-500 transition">
                        <option value="">Selecione um Paciente (Opcional)</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                        @endforeach
                    </select>
                    <p class="mt-2 text-[10px] text-slate-400 italic">Dica: Cadastre o paciente antes se for um novo caso.</p>
                </div>

                <!-- Serviço / Descrição -->
                <div>
                    <label for="service_name" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Serviço a ser realizado</label>
                    <input type="text" name="service_name" id="service_name" list="service_list" placeholder="Ex: Coroa Zircônia Protocolo" class="w-full px-5 py-4 border border-slate-200 rounded-2xl bg-slate-50 font-bold text-slate-900 focus:ring-2 focus:ring-blue-500 transition">
                    <datalist id="service_list">
                        @foreach($services as $service)
                            <option value="{{ $service->name }}">
                        @endforeach
                    </datalist>
                </div>

                <!-- Valor -->
                <div>
                    <label for="price" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Valor do Acordo (R$)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-400 font-bold">R$</span>
                        <input type="number" step="0.01" name="price" id="price" placeholder="0,00" class="w-full pl-12 pr-5 py-4 border border-slate-200 rounded-2xl bg-slate-50 font-black text-slate-900 focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                </div>
                
                <!-- Status Inicial -->
                <div>
                    <label for="status" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Status de Entrada <span class="text-red-500">*</span></label>
                    <select name="status" id="status" required class="w-full px-5 py-4 border border-slate-200 rounded-2xl bg-slate-50 font-black text-slate-900 focus:ring-2 focus:ring-blue-500 transition">
                        <option value="Aberto" selected>Aberto / Triagem</option>
                        <option value="Em Produção">Iniciado / Bancada</option>
                        <option value="Faltando">Pendente de Material</option>
                    </select>
                </div>
                
                <!-- Data Entrega -->
                <div>
                    <label for="delivery_date" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Previsão de Entrega</label>
                    <input type="date" name="delivery_date" id="delivery_date" class="w-full px-5 py-4 border border-slate-200 rounded-2xl bg-slate-50 font-bold text-slate-700 focus:ring-2 focus:ring-blue-500 transition">
                </div>
                
            </div>
            
            <div class="pt-10 border-t border-slate-100 flex flex-row-reverse gap-4">
                <button type="submit" class="flex-1 bg-slate-900 text-white py-5 rounded-2xl font-black text-sm uppercase tracking-[0.2em] shadow-xl hover:bg-black transition transform hover:-translate-y-1">
                    Abrir Ordem de Trabalho
                </button>
                <a href="{{ route('orders.index') }}" class="flex-1 bg-white text-slate-500 border border-slate-200 py-5 rounded-2xl font-black text-[10px] uppercase tracking-widest flex items-center justify-center hover:bg-slate-50 transition">
                    Cancelar
                </a>
            </div>
            
        </form>
    </div>
</div>
@endsection
