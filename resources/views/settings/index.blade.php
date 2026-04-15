@extends('layouts.app')

@section('header', 'Configurações do Sistema')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    
    <!-- Lab Identity -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold text-slate-900">Identidade do Laboratório</h3>
                <p class="text-sm text-slate-500">Dados que aparecem em relatórios e extratos.</p>
            </div>
            <button class="bg-blue-600 text-white px-5 py-2 rounded-xl text-sm font-bold shadow-lg shadow-blue-900/10 transition">Salvar Alterações</button>
        </div>
        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Nome Fantasia</label>
                    <input type="text" value="ProtJund Laboratório Dental" class="w-full px-5 py-3 border border-slate-200 rounded-2xl bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Razão Social</label>
                    <input type="text" value="Laboratório JC de Prótese Ltda" class="w-full px-5 py-3 border border-slate-200 rounded-2xl bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">CNPJ</label>
                    <input type="text" value="00.000.000/0001-00" class="w-full px-5 py-3 border border-slate-200 rounded-2xl bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Responsável Técnico</label>
                    <input type="text" value="Joaquim da Costa - TPD 1234" class="w-full px-5 py-3 border border-slate-200 rounded-2xl bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>
            </div>
        </div>
    </div>

    <!-- Interface & Customization -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8 space-y-6">
            <h3 class="text-lg font-bold text-slate-900 flex items-center">
                <i class="fa-solid fa-palette text-blue-500 mr-3"></i> Personalização
            </h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between group">
                    <span class="text-sm font-medium text-slate-700">Logo do Laboratório</span>
                    <button class="text-blue-600 text-xs font-bold uppercase tracking-wider hover:underline">Alterar Logo</button>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-slate-700">Cores da Marca</span>
                    <div class="flex space-x-2">
                        <div class="w-6 h-6 rounded-full bg-blue-600 border-2 border-white shadow-sm cursor-pointer"></div>
                        <div class="w-6 h-6 rounded-full bg-slate-900 border-2 border-white shadow-sm cursor-pointer"></div>
                        <div class="w-6 h-6 rounded-full bg-emerald-500 border-2 border-white shadow-sm cursor-pointer"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8 space-y-6 flex flex-col">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-bold text-slate-900 flex items-center">
                    <i class="fa-solid fa-shield-halved text-blue-500 mr-3"></i> Proteção e Backup
                </h3>
                <form action="{{ route('backup.run') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-blue-600 text-white px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest shadow-md shadow-blue-900/20 hover:bg-blue-700 transition">
                        Gerar Agora
                    </button>
                </form>
            </div>
            
            @if(session('status'))
                <div class="bg-emerald-50 text-emerald-600 px-4 py-3 rounded-xl text-sm font-bold border border-emerald-100">
                    {{ session('status') }}
                </div>
            @endif
            @if($errors->any())
                <div class="bg-red-50 text-red-600 px-4 py-3 rounded-xl text-sm font-bold border border-red-100">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-slate-700">Rotina Automática</span>
                    <span class="px-2 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-bold rounded uppercase tracking-wider">Ativa (Diária)</span>
                </div>
                
                @if(isset($backups) && count($backups) > 0)
                    <div class="pt-4 border-t border-slate-100">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 block">Arquivos Prontos para Download</span>
                        <ul class="space-y-2 max-h-40 overflow-y-auto custom-scrollbar pr-2">
                            @foreach($backups as $backup)
                            <li class="flex items-center justify-between bg-slate-50 p-3 rounded-xl border border-slate-100">
                                <div>
                                    <p class="text-xs font-bold text-slate-700">{{ date('d/m/Y H:i', $backup['last_modified']) }}</p>
                                    <p class="text-[10px] text-slate-400">{{ round($backup['file_size'] / 1024, 2) }} KB</p>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('backup.download', $backup['file_name']) }}" class="w-8 h-8 flex items-center justify-center bg-white border border-slate-200 text-blue-600 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition" title="Baixar Backup">
                                        <i class="fa-solid fa-download"></i>
                                    </a>
                                    <form action="{{ route('backup.destroy', $backup['file_name']) }}" method="POST" onsubmit="return confirm('Deseja realmente apagar este backup?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center bg-white border border-slate-200 text-red-500 rounded-lg hover:border-red-500 hover:bg-red-50 transition" title="Excluir Backup">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <div class="pt-4 border-t border-slate-100">
                        <p class="text-xs text-slate-400 font-bold italic text-center py-2">Nenhum backup gerado ainda. Clique em "Gerar Agora".</p>
                    </div>
                @endif
                
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-sm font-medium text-slate-700">Último Acesso</span>
                    <span class="text-xs text-slate-400">{{ date('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
