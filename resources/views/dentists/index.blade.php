@extends('layouts.app')

@section('header', 'Gestão de Dentistas')

@section('content')
<div class="space-y-6">
    <!-- Top Actions -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div class="flex-1 w-full max-w-lg">
            <form action="{{ route('dentists.index') }}" method="GET" class="relative group">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 group-focus-within:text-blue-500 transition-colors">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nome ou CRO..." class="block w-full pl-10 pr-3 py-3 border border-slate-200 rounded-2xl bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
            </form>
        </div>
        <div class="flex gap-3 w-full md:w-auto">
            <a href="{{ route('reports.dentists') }}" class="flex-1 md:flex-none px-6 py-4 bg-white border border-slate-200 text-slate-700 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-50 transition flex items-center justify-center shadow-sm">
                <i class="fa-solid fa-file-export mr-2 text-blue-600"></i> Exportar Lista
            </a>
            <button onclick="document.getElementById('dentistModal').classList.remove('hidden')" class="flex-1 md:flex-none px-8 py-4 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-black transition shadow-xl shadow-slate-900/20 flex items-center justify-center">
                <i class="fa-solid fa-plus mr-2"></i> Novo Dentista
            </button>
        </div>
    </div>

    @if (session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-2xl shadow-sm flex items-center">
        <i class="fa-solid fa-circle-check mr-2 text-lg"></i>
        <span class="font-bold">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Dentists Grid -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 text-[10px] uppercase tracking-widest border-b border-slate-200">
                        <th class="px-8 py-5 font-bold">Dentista / CRO</th>
                        <th class="px-8 py-5 font-bold">Contato</th>
                        <th class="px-8 py-5 font-bold">E-mail Corporativo</th>
                        <th class="px-8 py-5 font-bold text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-100">
                    @forelse($dentists as $dentist)
                    <tr class="hover:bg-slate-50/50 transition group">
                        <td class="px-8 py-6 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center mr-4 font-extrabold text-sm group-hover:bg-blue-100 group-hover:text-blue-600 transition-colors">
                                    {{ substr($dentist->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900">{{ $dentist->name }}</p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">CRO {{ $dentist->cro ?: 'N/A' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap text-slate-600 font-medium">
                            {{ $dentist->phone ?: 'Sem telefone' }}
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap text-slate-500">
                            {{ $dentist->email ?: '-' }}
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap text-right">
                            <div class="flex justify-end space-x-2">
                                <button onclick="openPriceModal({{ $dentist }}, {{ $dentist->prices->toJson() }})" class="px-4 h-9 rounded-xl bg-emerald-50 text-emerald-600 font-black text-[10px] uppercase tracking-widest flex items-center justify-center hover:bg-emerald-600 hover:text-white transition group/btn">
                                    <i class="fa-solid fa-tags mr-2 text-[12px]"></i> Tabela
                                </button>
                                <button onclick="editDentist({{ $dentist }})" class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition">
                                    <i class="fa-solid fa-pen-to-square text-sm"></i>
                                </button>
                                <form action="{{ route('dentists.destroy', $dentist) }}" method="POST" class="inline-block" onsubmit="return confirm('Excluir dentista?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-9 h-9 rounded-xl bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition">
                                        <i class="fa-solid fa-trash text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fa-solid fa-user-doctor text-5xl text-slate-100 mb-4"></i>
                                <h4 class="text-lg font-bold text-slate-900">Nenhum dentista cadastrado</h4>
                                <p class="text-slate-500 text-sm mt-1">Comece adicionando os profissionais que atendem seu laboratório.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Dentist Modal -->
<div id="dentistModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-middle bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
            <form id="dentistForm" action="{{ route('dentists.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <div class="px-10 pt-10 pb-8">
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h3 class="text-2xl font-extrabold text-slate-900 tracking-tight" id="modal-title">Novo Dentista</h3>
                            <p class="text-sm text-slate-500">Cadastre os dados cadastrais do profissional.</p>
                        </div>
                        <button type="button" onclick="closeModal()" class="w-10 h-10 rounded-full bg-slate-50 text-slate-400 hover:text-slate-600 flex items-center justify-center transition">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Nome Completo</label>
                            <input type="text" name="name" id="name" required class="w-full px-5 py-3.5 border border-slate-200 rounded-2xl bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">CRO / Registro</label>
                            <input type="text" name="cro" id="cro" class="w-full px-5 py-3.5 border border-slate-200 rounded-2xl bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Telefone</label>
                            <input type="text" name="phone" id="phone" class="w-full px-5 py-3.5 border border-slate-200 rounded-2xl bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition font-bold">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">E-mail</label>
                            <input type="email" name="email" id="email" class="w-full px-5 py-3.5 border border-slate-200 rounded-2xl bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition font-bold">
                        </div>
                    </div>
                </div>
                <div class="px-10 py-8 bg-slate-50 flex flex-row-reverse gap-4 border-t border-slate-100">
                    <button type="submit" class="flex-1 bg-blue-600 text-white font-black py-4 rounded-2xl shadow-lg shadow-blue-900/20 hover:bg-blue-700 transition">
                        Salvar Cadastro
                    </button>
                    <button type="button" onclick="closeModal()" class="flex-1 bg-white text-slate-600 border border-slate-200 font-bold py-4 rounded-2xl hover:bg-gray-50 transition">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Price Table Modal -->
<div id="priceModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="price-modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closePriceModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-middle bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <form id="priceForm" action="" method="POST">
                @csrf
                <div class="px-10 pt-10 pb-8">
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h3 class="text-2xl font-extrabold text-slate-900 tracking-tight" id="price-modal-title">Tabela de Preços</h3>
                            <p class="text-sm text-slate-500" id="dentist-name-title">Personalize os valores para este cliente.</p>
                        </div>
                        <button type="button" onclick="closePriceModal()" class="w-10 h-10 rounded-full bg-slate-50 text-slate-400 hover:text-slate-600 flex items-center justify-center transition">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>
                    
                    <div class="max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                        <table class="w-full">
                            <thead>
                                <tr class="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                    <th class="py-3 text-left">Serviço</th>
                                    <th class="py-3 text-right">Valor Personalizado (R$)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($services as $service)
                                <tr>
                                    <td class="py-4">
                                        <p class="font-bold text-slate-900 text-sm">{{ $service->name }}</p>
                                        <p class="text-[10px] text-slate-400 font-medium">Preço Base: R$ {{ number_format($service->base_price, 2, ',', '.') }}</p>
                                    </td>
                                    <td class="py-4 text-right">
                                        <input type="text" name="prices[{{ $service->id }}]" id="price_service_{{ $service->id }}" 
                                               placeholder="{{ number_format($service->base_price, 2, ',', '.') }}"
                                               class="w-32 px-4 py-2 border border-slate-200 rounded-xl bg-slate-50 text-right font-black text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="px-10 py-8 bg-slate-50 flex flex-row-reverse gap-4 border-t border-slate-100">
                    <button type="submit" class="flex-1 bg-emerald-600 text-white font-black py-4 rounded-2xl shadow-lg shadow-emerald-900/20 hover:bg-emerald-700 transition">
                        Salvar Tabela
                    </button>
                    <button type="button" onclick="closePriceModal()" class="flex-1 bg-white text-slate-600 border border-slate-200 font-bold py-4 rounded-2xl hover:bg-gray-50 transition">
                        Fechar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function closeModal() {
        document.getElementById('dentistModal').classList.add('hidden');
        document.getElementById('dentistForm').reset();
        document.getElementById('formMethod').value = "POST";
        document.getElementById('dentistForm').action = "{{ route('dentists.store') }}";
        document.getElementById('modal-title').innerText = "Novo Dentista";
    }

    function editDentist(dentist) {
        document.getElementById('modal-title').innerText = "Editar Dentista";
        document.getElementById('name').value = dentist.name;
        document.getElementById('cro').value = dentist.cro || '';
        document.getElementById('phone').value = dentist.phone || '';
        document.getElementById('email').value = dentist.email || '';
        
        let updateUrl = "{{ route('dentists.update', ':id') }}";
        updateUrl = updateUrl.replace(':id', dentist.id);
        
        document.getElementById('dentistForm').action = updateUrl;
        document.getElementById('formMethod').value = "PUT";
        
        document.getElementById('dentistModal').classList.remove('hidden');
    }

    function openPriceModal(dentist, dentistPrices) {
        document.getElementById('dentist-name-title').innerText = "Tabela Personalizada: " + dentist.name;
        
        // Clear all price inputs first
        const priceInputs = document.querySelectorAll('#priceForm input[type="text"]');
        priceInputs.forEach(input => input.value = '');
        
        // Fill existing prices
        dentistPrices.forEach(dp => {
            const input = document.getElementById('price_service_' + dp.service_id);
            if (input) {
                input.value = dp.price.toString().replace('.', ',');
            }
        });
        
        // Update form action
        let actionUrl = "{{ route('dentists.prices.update', ':id') }}";
        actionUrl = actionUrl.replace(':id', dentist.id);
        document.getElementById('priceForm').action = actionUrl;
        
        document.getElementById('priceModal').classList.remove('hidden');
    }

    function closePriceModal() {
        document.getElementById('priceModal').classList.add('hidden');
    }
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
@endsection
