@extends('layouts.app')

@section('header', 'Controle de Pacientes')

@section('content')
<div class="space-y-6">
    <!-- Top Actions -->
    <div class="flex justify-between items-center">
        <div class="flex-1 max-w-lg">
            <form action="{{ route('patients.index') }}" method="GET" class="relative group">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 group-focus-within:text-blue-500 transition-colors">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nome do paciente..." class="block w-full pl-10 pr-3 py-2.5 border border-slate-200 rounded-xl bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
            </form>
        </div>
        <button onclick="document.getElementById('patientModal').classList.remove('hidden')" class="ml-4 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-blue-900/20 transition-all flex items-center">
            <i class="fa-solid fa-plus mr-2"></i> Novo Paciente
        </button>
    </div>

    @if (session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-2xl shadow-sm flex items-center">
        <i class="fa-solid fa-circle-check mr-2 text-lg"></i>
        <span class="font-bold">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Patients List -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 text-[10px] uppercase tracking-widest border-b border-slate-200">
                        <th class="px-8 py-5 font-bold">Identificação do Paciente</th>
                        <th class="px-8 py-5 font-bold">Telefone / Contato</th>
                        <th class="px-8 py-5 font-bold text-center">Total de Pedidos</th>
                        <th class="px-8 py-5 font-bold text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-100">
                    @forelse($patients as $patient)
                    <tr class="hover:bg-slate-50/50 transition group">
                        <td class="px-8 py-6 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center mr-4 font-extrabold text-xs">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900">{{ $patient->name }}</p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Registrado em {{ $patient->created_at->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap text-slate-600 font-medium">
                            {{ $patient->phone ?: 'Não informado' }}
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap text-center">
                            <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-lg font-bold text-xs uppercase">{{ $patient->orders_count ?? '0' }} Serviços</span>
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap text-right">
                            <div class="flex justify-end space-x-2">
                                <button onclick="editPatient({{ $patient }})" class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition">
                                    <i class="fa-solid fa-pen-to-square text-sm"></i>
                                </button>
                                <form action="{{ route('patients.destroy', $patient) }}" method="POST" class="inline-block" onsubmit="return confirm('Excluir paciente?')">
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
                                <i class="fa-solid fa-users text-5xl text-slate-100 mb-4"></i>
                                <h4 class="text-lg font-bold text-slate-900">Nenhum paciente cadastrado</h4>
                                <p class="text-slate-500 text-sm mt-1">Os pacientes são vinculados aos pedidos do laboratório.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="patientModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-middle bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
            <form id="patientForm" action="{{ route('patients.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <div class="px-10 pt-10 pb-8 text-center sm:text-left">
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h3 class="text-2xl font-extrabold text-slate-900 tracking-tight" id="modal-title">Novo Paciente</h3>
                        </div>
                        <button type="button" onclick="closeModal()" class="w-10 h-10 rounded-full bg-slate-50 text-slate-400 hover:text-slate-600 flex items-center justify-center transition">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 text-left">Nome do Paciente</label>
                            <input type="text" name="name" id="name" required class="w-full px-5 py-3.5 border border-slate-200 rounded-2xl bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 text-left">Telefone / WhatsApp</label>
                            <input type="text" name="phone" id="phone" class="w-full px-5 py-3.5 border border-slate-200 rounded-2xl bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        </div>
                    </div>
                </div>
                <div class="px-10 py-8 bg-slate-50 flex flex-row-reverse gap-4">
                    <button type="submit" class="flex-1 bg-blue-600 text-white font-bold py-4 rounded-2xl shadow-lg shadow-blue-900/20 hover:bg-blue-700 transition">
                        Salvar Paciente
                    </button>
                    <button type="button" onclick="closeModal()" class="flex-1 bg-white text-slate-600 border border-slate-200 font-bold py-4 rounded-2xl hover:bg-gray-50 transition">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function closeModal() {
        document.getElementById('patientModal').classList.add('hidden');
        document.getElementById('patientForm').reset();
        document.getElementById('formMethod').value = "POST";
        document.getElementById('patientForm').action = "{{ route('patients.store') }}";
        document.getElementById('modal-title').innerText = "Novo Paciente";
    }

    function editPatient(patient) {
        document.getElementById('modal-title').innerText = "Editar Paciente";
        document.getElementById('name').value = patient.name;
        document.getElementById('phone').value = patient.phone || '';
        
        let updateUrl = "{{ route('patients.update', ':id') }}";
        updateUrl = updateUrl.replace(':id', patient.id);
        
        document.getElementById('patientForm').action = updateUrl;
        document.getElementById('formMethod').value = "PUT";
        
        document.getElementById('patientModal').classList.remove('hidden');
    }
</script>
@endsection
