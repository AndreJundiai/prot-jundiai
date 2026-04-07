@extends('layouts.app')

@section('header', 'Catálogo de Serviços / Produtos')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-lg font-bold text-slate-800">Tabela de Preços Base</h3>
            <p class="text-sm text-slate-500">Cadastre aqui os serviços que o laboratório executa e seus valores padrão.</p>
        </div>
        <button onclick="document.getElementById('serviceModal').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-bold shadow-lg shadow-blue-900/20 transition-all flex items-center">
            <i class="fa-solid fa-plus mr-2"></i> Adicionar Serviço
        </div>
    </div>

    @if (session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl relative mb-6">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Services Grid/Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-[10px] uppercase tracking-widest border-b border-slate-200">
                        <th class="px-6 py-4 font-bold">Categoria</th>
                        <th class="px-6 py-4 font-bold">Nome do Serviço</th>
                        <th class="px-6 py-4 font-bold text-right">Preço Base (R$)</th>
                        <th class="px-6 py-4 font-bold text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-100">
                    @php $currentCategory = ''; @endphp
                    @forelse($services as $service)
                    <tr class="hover:bg-slate-50 transition group">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 bg-slate-100 text-slate-600 rounded text-[10px] font-bold uppercase">{{ $service->category ?: 'Diversos' }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-slate-900 font-semibold">{{ $service->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-slate-900">
                            R$ {{ number_format($service->base_price, 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex justify-end space-x-2">
                                <button class="text-slate-400 hover:text-blue-600 transition" title="Editar"><i class="fa-solid fa-pen-to-square"></i></button>
                                <form action="{{ route('products.destroy', $service->id) }}" method="POST" onsubmit="return confirm('Excluir este serviço?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-red-500 transition" title="Remover"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                            <i class="fa-solid fa-box-open text-4xl mb-3 block opacity-20"></i>
                            Nenhum serviço cadastrado ainda.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="serviceModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="document.getElementById('serviceModal').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="{{ route('products.store') }}" method="POST">
                @csrf
                <div class="bg-white px-8 pt-8 pb-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-slate-900" id="modal-title">Novo Serviço / Produto</h3>
                        <button type="button" onclick="document.getElementById('serviceModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Categoria (ex: Prótese Fixa, Removível)</label>
                            <input type="text" name="category" placeholder="Ex: Prótese Total" class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Nome do Serviço</label>
                            <input type="text" name="name" required placeholder="Ex: Coroa em Zircônia" class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Preço Base (R$)</label>
                            <input type="number" step="0.01" name="base_price" required placeholder="0.00" class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-8 py-6 flex flex-row-reverse gap-3">
                    <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-lg shadow-blue-900/20 px-6 py-3 bg-blue-600 text-base font-bold text-white hover:bg-blue-700 focus:outline-none transition sm:text-sm">
                        Cadastrar Serviço
                    </button>
                    <button type="button" onclick="document.getElementById('serviceModal').classList.add('hidden')" class="w-full inline-flex justify-center rounded-xl border border-slate-300 px-6 py-3 bg-white text-base font-bold text-slate-600 hover:bg-slate-100 focus:outline-none transition sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
