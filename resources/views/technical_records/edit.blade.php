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
                            <option value="Zircônia" {{ $technicalRecord->material == 'Zircônia' ? 'selected' : '' }}>Zircônia</option>
                            <option value="Metalo-Cerâmica" {{ $technicalRecord->material == 'Metalo-Cerâmica' ? 'selected' : '' }}>Metalo-Cerâmica</option>
                            <option value="E-Max" {{ $technicalRecord->material == 'E-Max' ? 'selected' : '' }}>E-Max / Dissilicato</option>
                            <option value="Resina" {{ $technicalRecord->material == 'Resina' ? 'selected' : '' }}>Resina Fotopolimerizável</option>
                            <option value="Cromo Cobalto" {{ $technicalRecord->material == 'Cromo Cobalto' ? 'selected' : '' }}>Cromo Cobalto</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Escala de Cor (VITA)</label>
                        <input type="text" name="color" value="{{ $technicalRecord->color }}" placeholder="Ex: A1, A2, B3..." class="w-full px-5 py-4 border border-slate-200 rounded-2xl bg-slate-50 font-black text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                </div>

                {{-- Interactive Odontogram --}}
                <div class="bg-slate-50 rounded-3xl p-6 border border-slate-100">
                    <h4 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-5 flex items-center">
                        <i class="fa-solid fa-tooth mr-2 text-blue-500"></i> Odontograma — Selecione os Dentes Envolvidos
                    </h4>

                    <input type="hidden" name="teeth" id="teeth-input" value="{{ json_encode($technicalRecord->teeth ?? []) }}">

                    @php
                        $quadrants = [
                            ['label' => 'Superior Direito', 'teeth' => [18,17,16,15,14,13,12,11]],
                            ['label' => 'Superior Esquerdo', 'teeth' => [21,22,23,24,25,26,27,28]],
                            ['label' => 'Inferior Direito', 'teeth' => [41,42,43,44,45,46,47,48]],
                            ['label' => 'Inferior Esquerdo', 'teeth' => [31,32,33,34,35,36,37,38]],
                        ];
                    @endphp

                    <div class="grid grid-cols-2 gap-4">
                        @foreach($quadrants as $quadrant)
                        <div class="bg-white rounded-2xl p-4 border border-slate-100">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 text-center">{{ $quadrant['label'] }}</p>
                            <div class="flex justify-center gap-1.5 flex-wrap">
                                @foreach($quadrant['teeth'] as $tooth)
                                <button type="button" onclick="toggleTooth({{ $tooth }})"
                                    id="tooth-{{ $tooth }}"
                                    class="tooth-btn w-9 h-11 rounded-xl border-2 border-slate-200 bg-slate-50 flex flex-col items-center justify-center cursor-pointer transition-all duration-150 active:scale-90"
                                    title="Dente {{ $tooth }}">
                                    <i class="fa-solid fa-tooth text-slate-300 text-xs"></i>
                                    <span class="text-[9px] font-black text-slate-400 mt-0.5">{{ $tooth }}</span>
                                </button>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <p id="teeth-summary" class="text-center text-[10px] text-slate-400 mt-4 italic font-medium uppercase tracking-widest">
                        Nenhum dente selecionado
                    </p>
                </div>


                <!-- Grid: Escala, Antagonista, Modelo -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Escala</label>
                        <input type="text" name="escala" value="{{ $technicalRecord->escala }}" placeholder="Ex: Vida 3D..." class="w-full px-5 py-4 border border-slate-200 rounded-2xl bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Antagonista</label>
                        <input type="text" name="antagonista" value="{{ $technicalRecord->antagonista }}" placeholder="Ex: Gesso, Silicona..." class="w-full px-5 py-4 border border-slate-200 rounded-2xl bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Modelo</label>
                        <input type="text" name="modelo" value="{{ $technicalRecord->modelo }}" placeholder="Ex: Troquelado..." class="w-full px-5 py-4 border border-slate-200 rounded-2xl bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                </div>

                <!-- Grid: Materiais Fornecidos e Devolvidos -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Detalhamento de Materiais -->
                <div class="col-span-2">
                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Materiais (Checklist para O.S.)</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
                        @php
                            $materialOptions = ['Provisório', 'Molde', 'Articulador', 'Transfer', 'Análogo', 'Gesso', 'Coping', 'Parafuso', 'Chave', 'Escaneamento', 'Pino', 'Coroa'];
                            $currentMaterialsf = strtolower($order->technicalRecord->material_fornecido);
                        @endphp
                        @foreach($materialOptions as $opt)
                            <label class="flex items-center space-x-2 bg-slate-50 p-2 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-100 transition">
                                <input type="checkbox" name="material_list[]" value="{{ $opt }}" 
                                    {{ str_contains($currentMaterialsf, strtolower($opt)) ? 'checked' : '' }}
                                    class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-[10px] font-bold text-slate-700 uppercase">{{ $opt }}</span>
                            </label>
                        @endforeach
                    </div>
                    
                    <div class="mt-4">
                        <label for="material_fornecido" class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-1">Observações de Materiais</label>
                        <input type="text" name="material_fornecido" id="material_fornecido" value="{{ $order->technicalRecord->material_fornecido }}" 
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                               placeholder="Ex: Outros materiais não listados acima...">
                    </div>
                </div>
                </div>

                <!-- Grid: Oclusão e Acabamento -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Tipo de Oclusão</label>
                        <input type="text" name="occlusion" value="{{ $technicalRecord->occlusion }}" placeholder="Ex: Normal, Justa..." class="w-full px-5 py-4 border border-slate-200 rounded-2xl bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Tipo de Acabamento</label>
                        <input type="text" name="finish" value="{{ $technicalRecord->finish }}" placeholder="Ex: Brilhante, Fosco..." class="w-full px-5 py-4 border border-slate-200 rounded-2xl bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Observações Adicionais</label>
                    <textarea name="notes" rows="4" class="w-full px-5 py-4 border border-slate-200 rounded-2xl bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition" placeholder="Instruções específicas para o acabamento, oclusão ou estética...">{{ $technicalRecord->notes }}</textarea>
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

<script>
    let selectedTeeth = JSON.parse(document.getElementById('teeth-input').value || '[]');

    function toggleTooth(num) {
        const btn = document.getElementById('tooth-' + num);
        const idx = selectedTeeth.indexOf(num);
        if (idx > -1) {
            selectedTeeth.splice(idx, 1);
            btn.classList.remove('border-blue-500', 'bg-blue-50', 'shadow-md', 'shadow-blue-200');
            btn.classList.add('border-slate-200', 'bg-slate-50');
            btn.querySelector('i').classList.replace('text-blue-500', 'text-slate-300');
            btn.querySelector('span').classList.replace('text-blue-600', 'text-slate-400');
        } else {
            selectedTeeth.push(num);
            btn.classList.add('border-blue-500', 'bg-blue-50', 'shadow-md', 'shadow-blue-200');
            btn.classList.remove('border-slate-200', 'bg-slate-50');
            btn.querySelector('i').classList.replace('text-slate-300', 'text-blue-500');
            btn.querySelector('span').classList.replace('text-slate-400', 'text-blue-600');
        }
        document.getElementById('teeth-input').value = JSON.stringify(selectedTeeth);
        const summary = document.getElementById('teeth-summary');
        summary.textContent = selectedTeeth.length
            ? 'Dentes selecionados: ' + [...selectedTeeth].sort((a,b)=>a-b).join(', ')
            : 'Nenhum dente selecionado';
    }

    // Init on load: highlight pre-selected teeth
    document.addEventListener('DOMContentLoaded', () => {
        selectedTeeth.forEach(t => {
            const btn = document.getElementById('tooth-' + t);
            if (btn) toggleTooth(t); // this adds once, so we need to pre-add then call
        });
        // Re-sync state since toggleTooth above adds then removes
        selectedTeeth = JSON.parse(document.getElementById('teeth-input').value || '[]');
        selectedTeeth.forEach(t => {
            const btn = document.getElementById('tooth-' + t);
            if (!btn) return;
            btn.classList.add('border-blue-500', 'bg-blue-50', 'shadow-md', 'shadow-blue-200');
            btn.classList.remove('border-slate-200', 'bg-slate-50');
            btn.querySelector('i').classList.remove('text-slate-300');
            btn.querySelector('i').classList.add('text-blue-500');
            btn.querySelector('span').classList.remove('text-slate-400');
            btn.querySelector('span').classList.add('text-blue-600');
        });
        const summary = document.getElementById('teeth-summary');
        summary.textContent = selectedTeeth.length
            ? 'Dentes selecionados: ' + [...selectedTeeth].sort((a,b)=>a-b).join(', ')
            : 'Nenhum dente selecionado';
    });
</script>
@endsection
