@extends('layouts.app')

@section('header', 'Contas a Receber / Gestão Financeira')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    
    <!-- Sidebar: Filtros e Lançamento -->
    <div class="lg:col-span-1 space-y-8">
        
        <!-- Seleção de Dentista -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8">
            <h3 class="text-xs font-extrabold text-slate-500 uppercase tracking-widest mb-4">Filtrar por Cliente</h3>
            <form action="{{ route('financial.index') }}" method="GET">
                <select name="dentist_id" onchange="this.form.submit()" class="w-full px-5 py-3.5 border border-slate-200 rounded-2xl bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 font-bold text-slate-700 transition">
                    <option value="">Selecione um Dentista...</option>
                    @foreach($dentists as $dentist)
                        <option value="{{ $dentist->id }}" {{ request('dentist_id') == $dentist->id ? 'selected' : '' }}>
                            {{ $dentist->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        @if($selectedDentist)
        <!-- Resumo do Saldo Selecionado -->
        <div class="bg-slate-900 rounded-3xl shadow-2xl p-8 text-white relative overflow-hidden group">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-600 rounded-full blur-3xl opacity-20 group-hover:opacity-40 transition-opacity"></div>
            <h3 class="text-[10px] font-extrabold text-slate-500 uppercase tracking-[0.2em] mb-3 relative z-10">Saldo Pendente</h3>
            <div class="flex items-baseline space-x-1 relative z-10">
                <span class="text-lg font-bold text-slate-400">R$</span>
                <span class="text-4xl font-black tracking-tight">{{ number_format($balance, 2, ',', '.') }}</span>
            </div>
            
            <!-- Filtro de Período -->
            <form action="{{ route('financial.extrato', $selectedDentist->id) }}" method="GET" target="_blank" class="mt-8 pt-8 border-t border-slate-800 relative z-10 space-y-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Filtrar Período</label>
                    <div class="grid grid-cols-2 gap-3 text-slate-900">
                        <input type="date" name="from" value="{{ date('Y-m-01') }}" class="w-full px-3 py-2 border border-slate-700 rounded-xl bg-slate-800 text-white text-[10px] focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <input type="date" name="to" value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 border border-slate-700 rounded-xl bg-slate-800 text-white text-[10px] focus:outline-none focus:ring-1 focus:ring-blue-500">
                    </div>
                </div>

                <button type="submit" class="w-full inline-flex items-center justify-center py-3.5 px-6 rounded-2xl bg-white text-slate-900 font-black text-xs uppercase tracking-widest hover:bg-blue-50 transition shadow-lg shadow-blue-900/40">
                    <i class="fa-solid fa-file-invoice mr-2"></i> Gerar Extrato
                </button>
            </form>
        </div>
        @endif
        
        <!-- Formulário de Lançamento -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-8 py-5 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest">Novo Lançamento</h3>
            </div>
            <div class="p-8">
                <form action="{{ route('financial.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <input type="hidden" name="dentist_id" value="{{ request('dentist_id') }}">
                    
                    @if(!request('dentist_id'))
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Dentista Responsável</label>
                        <select name="dentist_id" required class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                            <option value="">Selecionar...</option>
                            @foreach($dentists as $d)
                                <option value="{{ $d->id }}">{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Tipo</label>
                            <select name="type" required class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                                <option value="debit">Débito</option>
                                <option value="credit">Crédito</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Valor (R$)</label>
                            <input type="number" step="0.01" name="amount" required class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-100 font-bold focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Nome do Paciente</label>
                        <input type="text" name="patient_name" placeholder="Ex: Maria Silva" class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Descrição do Serviço</label>
                        <input type="text" name="description" required placeholder="Ex: Coroa Zircônia" class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Data da Operação</label>
                        <input type="date" name="transaction_date" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>

                    <button type="submit" class="w-full py-4 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl hover:bg-slate-800 transition transform hover:-translate-y-0.5">
                        Registrar Movimentação
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="lg:col-span-3 space-y-8">
        
        @if($selectedDentist)
        <!-- Listagem de Transações de um Dentista -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <div>
                    <h3 class="text-xl font-black text-slate-900 tracking-tight">{{ $selectedDentist->name }}</h3>
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-1">Extrato Analítico de Lançamentos</p>
                </div>
                <div class="text-right">
                    <span class="px-4 py-1.5 bg-white border border-slate-200 rounded-full text-xs font-bold text-slate-400">Total: {{ $transactions->count() }} itens</span>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-white text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black border-b border-slate-100">
                            <th class="px-8 py-5">Data</th>
                            <th class="px-8 py-5">Paciente</th>
                            <th class="px-8 py-5">Discriminação</th>
                            <th class="px-8 py-5 text-right">Valor Líquido</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-slate-50">
                        @forelse ($transactions as $transaction)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-8 py-6 whitespace-nowrap font-mono text-slate-500 text-xs">{{ $transaction->transaction_date->format('d/m/Y') }}</td>
                            <td class="px-8 py-6 whitespace-nowrap font-extrabold text-slate-900">{{ $transaction->patient_name ?: '-' }}</td>
                            <td class="px-8 py-6 text-slate-600 font-medium">{{ $transaction->description }}</td>
                            <td class="px-8 py-6 whitespace-nowrap text-right font-black {{ $transaction->type == 'credit' ? 'text-emerald-600' : 'text-red-500' }}">
                                {{ $transaction->type == 'credit' ? '-' : '' }} R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center opacity-20">
                                    <i class="fa-solid fa-receipt text-6xl mb-4"></i>
                                    <p class="font-bold uppercase tracking-widest">Nenhum histórico encontrado</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($transactions->isNotEmpty())
                    <tfoot class="bg-slate-900 text-white">
                        <tr>
                            <td colspan="3" class="px-8 py-6 text-right text-[10px] font-black uppercase tracking-[0.3em]">Saldo Atual Acumulado</td>
                            <td class="px-8 py-6 text-right text-xl font-black">R$ {{ number_format($balance, 2, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
        @else
        <!-- Dashboard Geral de Contas -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8 flex items-center group hover:border-blue-500 transition-colors">
                <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mr-6 group-hover:bg-blue-600 group-hover:text-white transition-all shadow-lg shadow-blue-100">
                    <i class="fa-solid fa-user-doctor text-2xl"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-1">Carteira de Clientes</h4>
                    <p class="text-3xl font-black text-slate-900 leading-none">{{ $dentists->count() }}</p>
                </div>
            </div>
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8 flex items-center group hover:border-red-500 transition-colors">
                <div class="w-16 h-16 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center mr-6 group-hover:bg-red-600 group-hover:text-white transition-all shadow-lg shadow-red-100">
                    <i class="fa-solid fa-hand-holding-dollar text-2xl"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-1">Total Geral a Receber</h4>
                    <p class="text-3xl font-black text-red-600 leading-none">R$ {{ number_format($dentistsWithBalance->sum('balance'), 2, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-[0.2em]">Painel de Controle Financeiro</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-white text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black border-b border-slate-100">
                            <th class="px-8 py-5">Consultório / Dentista</th>
                            <th class="px-8 py-5 text-right">Saldo Devedor</th>
                            <th class="px-8 py-5 text-right">Ação</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-slate-100 text-slate-900">
                        @forelse($dentistsWithBalance as $dentist)
                        <tr class="hover:bg-slate-50 transition group">
                            <td class="px-8 py-6 whitespace-nowrap font-black">{{ $dentist->name }}</td>
                            <td class="px-8 py-6 whitespace-nowrap text-right font-black {{ $dentist->balance > 0 ? 'text-red-500' : 'text-emerald-600' }}">
                                R$ {{ number_format($dentist->balance, 2, ',', '.') }}
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-right">
                                <a href="{{ route('financial.index', ['dentist_id' => $dentist->id]) }}" class="inline-flex items-center px-4 py-2 bg-slate-50 text-blue-600 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-blue-600 hover:text-white transition shadow-sm">
                                    Ver Detalhes
                                </a>
                            </td>
                        </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-3xl shadow-xl p-10 text-white overflow-hidden relative">
            <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-white rounded-full blur-3xl opacity-10"></div>
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="max-w-md">
                    <h4 class="text-2xl font-black mb-4 leading-tight tracking-tight">Gestão de Cobranças Dinâmica</h4>
                    <p class="text-blue-100 text-sm font-medium leading-relaxed">Utilize o extrato individual para gerar documentos profissionais de cobrança e manter seu fluxo de caixa sempre em dia.</p>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                    <i class="fa-solid fa-lightbulb text-amber-400 text-4xl"></i>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
