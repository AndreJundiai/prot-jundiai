@extends('layouts.app')

@section('header', 'Janela de Produção: Pedido #' . str_pad($order->id, 5, '0', STR_PAD_LEFT))

@section('content')
<div class="max-w-5xl mx-auto">
    <form action="{{ route('orders.update', $order) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Coluna 1 & 2: Dados Técnicos e Clínicos -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Identificação Rápida -->
                <div class="bg-slate-900 rounded-3xl shadow-xl p-8 text-white relative overflow-hidden">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-blue-600 rounded-full blur-[80px] opacity-20"></div>
                    <div class="relative z-10 flex justify-between items-start">
                        <div>
                            <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] mb-2">Cliente / Dentista</h3>
                            <p class="text-2xl font-black tracking-tight">{{ $order->dentist->name }}</p>
                            <p class="text-sm font-medium text-slate-400 mt-1 italic">Paciente: {{ $order->patient ? $order->patient->name : 'Não informado' }}</p>
                        </div>
                        <div class="text-right">
                            <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] mb-2">Protocolo</h3>
                            <p class="text-xl font-mono font-bold">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Especificações Técnicas (Technical Record integration) -->
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-8 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center">
                        <i class="fa-solid fa-tooth text-blue-600 mr-3"></i>
                        <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest">Especificações da Prótese</h3>
                    </div>
                    @php $record = $order->technicalRecord ?? new \App\Models\TechnicalRecord(); @endphp
                    <div class="p-8 space-y-8">
                        <!-- Odontograma Visual -->
                        <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100">
                            <div class="flex flex-wrap gap-2 justify-center">
                                @for($i=11; $i<=18; $i++)
                                    <div class="w-8 h-10 border border-slate-200 rounded flex items-center justify-center text-[10px] font-bold {{ in_array($i, [11, 12, 21, 22]) ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-slate-400' }}">
                                        {{ $i }}
                                    </div>
                                @endfor
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-8">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Material</label>
                                <p class="text-sm font-bold text-slate-900 bg-slate-50 px-4 py-3 rounded-xl border border-slate-100">{{ $record->material ?: 'Não definido' }}</p>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Cor (Escala Vita)</label>
                                <p class="text-sm font-black text-blue-600 bg-blue-50 px-4 py-3 rounded-xl border border-blue-100 italic">{{ $record->color ?: 'Selecionar na Ficha' }}</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Observações Técnicas</label>
                            <p class="text-sm text-slate-600 leading-relaxed">{{ $record->notes ?: 'Nenhuma observação adicional.' }}</p>
                        </div>
                        
                        <div class="text-center pt-4">
                            <a href="{{ route('orders.technical_records.edit', $order) }}" class="text-[10px] font-black text-blue-600 uppercase tracking-widest hover:underline">
                                <i class="fa-solid fa-external-link mr-1"></i> Abrir Ficha Técnica Completa
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Coluna 3: Status e Faturamento -->
            <div class="space-y-8">
                
                <!-- Controle de Produção -->
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8 space-y-6">
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest border-b border-slate-50 pb-4">Controle de Produção</h3>
                    
                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-3">Situação Atual</label>
                        <select name="status" id="status" class="w-full px-5 py-4 border border-slate-200 rounded-2xl bg-slate-50 font-black text-slate-900 focus:ring-2 focus:ring-blue-500 transition">
                            <option value="Aberto" {{ $order->status == 'Aberto' ? 'selected' : '' }}>Entrada / Aberto</option>
                            <option value="Em Produção" {{ $order->status == 'Em Produção' ? 'selected' : '' }}>Bancada / Produção</option>
                            <option value="Faltando" {{ $order->status == 'Faltando' ? 'selected' : '' }}>Pendente / Faltando</option>
                            <option value="Finalizado" {{ $order->status == 'Finalizado' ? 'selected' : '' }}>Pronto / Finalizado</option>
                        </select>
                        <p class="mt-2 text-[10px] text-slate-400 italic">Ao mudar para 'Finalizado', o débito será gerado.</p>
                    </div>

                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-3">Data de Entrega</label>
                        <input type="date" name="delivery_date" value="{{ $order->delivery_date ? $order->delivery_date->format('Y-m-d') : '' }}" class="w-full px-5 py-4 border border-slate-200 rounded-2xl bg-slate-50 font-bold text-slate-700 focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                </div>

                <!-- Detalhes de Faturamento -->
                <div class="bg-blue-600 rounded-3xl shadow-xl p-8 text-white space-y-6">
                    <h3 class="text-xs font-black text-blue-200 uppercase tracking-widest border-b border-blue-500/50 pb-4">Fechamento / Valor</h3>
                    
                    <div>
                        <label class="block text-[10px] font-extrabold text-blue-200 uppercase tracking-widest mb-3 text-left">Valor do Serviço (R$)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-blue-300 font-bold text-xl">R$</span>
                            <input type="number" step="0.01" name="price" value="{{ $order->price }}" class="w-full pl-16 pr-5 py-5 border-none rounded-2xl bg-white/10 text-white font-black text-2xl placeholder-blue-300 focus:ring-2 focus:ring-white transition" placeholder="0,00">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-extrabold text-blue-200 uppercase tracking-widest mb-3 text-left">Descrição no Extrato</label>
                        <input type="text" name="service_name" value="{{ $order->service_name }}" placeholder="Ex: Armação Metálica" class="w-full px-5 py-4 border-none rounded-2xl bg-white/10 text-white font-bold text-sm placeholder-blue-300 focus:ring-2 focus:ring-white transition">
                    </div>

                    @if($order->is_invoiced)
                    <div class="flex items-center justify-center p-3 bg-emerald-500/20 rounded-xl border border-emerald-500/30">
                        <i class="fa-solid fa-circle-check mr-2 text-emerald-300"></i>
                        <span class="text-[10px] font-black uppercase tracking-widest text-emerald-100">Já faturado no Contas a Receber</span>
                    </div>
                    @endif
                </div>

                <!-- Ações -->
                <div class="space-y-4 pt-4">
                    <button type="submit" class="w-full py-5 bg-slate-900 text-white rounded-3xl font-black text-sm uppercase tracking-[0.2em] shadow-2xl hover:bg-black transition transform hover:-translate-y-1">
                        Gravar Alterações
                    </button>
                    <a href="{{ route('orders.index') }}" class="w-full inline-flex items-center justify-center py-5 bg-white text-slate-500 border border-slate-200 rounded-3xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-50 transition">
                        Voltar sem Salvar
                    </a>
                </div>

            </div>
        </div>

        <!-- Hidden inputs shared with order table -->
        <input type="hidden" name="dentist_id" value="{{ $order->dentist_id }}">
        <input type="hidden" name="patient_id" value="{{ $order->patient_id }}">

    </form>
</div>
@endsection
