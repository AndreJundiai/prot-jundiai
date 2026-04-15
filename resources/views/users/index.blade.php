@extends('layouts.app')

@section('header', 'Gerenciar Usuários e Permissões')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Formulário de Cadastro -->
    <div class="lg:col-span-1 space-y-8">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-8 py-5 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest">Novo Usuário</h3>
            </div>
            <div class="p-8">
                @if(session('status'))
                    <div class="mb-4 bg-emerald-50 text-emerald-600 px-4 py-3 rounded-xl text-sm font-bold border border-emerald-100">
                        {{ session('status') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 bg-red-50 text-red-600 px-4 py-3 rounded-xl text-sm font-bold border border-red-100">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('users.store') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Nome Completo</label>
                        <input type="text" name="name" required class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">E-mail de Acesso</label>
                        <input type="email" name="email" required class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Senha</label>
                            <input type="password" name="password" required class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Permissão</label>
                            <select name="role" required class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                                <option value="admin">Administrador</option>
                                <option value="worker">Técnico / Atendente</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-4 bg-blue-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-blue-900/20 hover:bg-blue-700 transition transform hover:-translate-y-0.5 mt-4">
                        Criar Usuário
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabela de Listagem -->
    <div class="lg:col-span-2 space-y-8">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <div>
                    <h3 class="text-xl font-black text-slate-900 tracking-tight">Equipe do Sistema</h3>
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-1">Usuários com acesso ao sistema</p>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-white text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black border-b border-slate-100">
                            <th class="px-8 py-5">Nome / Email</th>
                            <th class="px-8 py-5">Perfil de Acesso</th>
                            <th class="px-8 py-5 text-right">Ação</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-slate-50">
                        @foreach ($users as $user)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center mr-4 font-black">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-extrabold text-slate-900">{{ $user->name }}</p>
                                        <p class="text-xs font-medium text-slate-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap">
                                @if($user->role === 'admin')
                                    <span class="px-3 py-1 bg-red-50 text-red-600 rounded-lg text-[10px] font-bold uppercase tracking-wider">Administrador</span>
                                @else
                                    <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-[10px] font-bold uppercase tracking-wider">Técnico/Atendente</span>
                                @endif
                                
                                @if($user->email === 'admin@admin.com')
                                    <div class="mt-1"><span class="px-2 py-0.5 bg-slate-800 text-slate-200 rounded text-[9px] font-black uppercase">Root (Dono)</span></div>
                                @endif
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-right">
                                @if($user->id !== auth()->id() && $user->email !== 'admin@admin.com')
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja remover este usuário? Essa ação revogará o acesso dele imediatamente.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-white border border-slate-200 hover:border-red-500 hover:bg-red-50 hover:text-red-600 text-slate-400 w-10 h-10 rounded-xl flex items-center justify-center transition shadow-sm">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                                @else
                                    <span class="text-[10px] text-slate-300 font-bold uppercase tracking-wider">Protegido</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
