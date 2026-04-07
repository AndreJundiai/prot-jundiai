<!DOCTYPE html>
<html lang="pt_BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProtJund - Gestão de Próteses</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-slate-900 text-white flex flex-col shadow-2xl z-20">
        <div class="h-20 flex items-center px-6 border-b border-slate-800/50">
            <div class="bg-blue-600 p-2 rounded-lg mr-3 shadow-lg shadow-blue-900/20">
                <i class="fa-solid fa-tooth text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold tracking-tight text-white">ProtJund</h1>
                <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold">Lab Management</p>
            </div>
        </div>
        
        <div class="flex-1 px-4 py-8 space-y-1 overflow-y-auto custom-scrollbar">
            <p class="px-4 pb-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Operações</p>
            
            <a href="/orders" class="flex items-center space-x-3 py-3 px-4 rounded-xl transition-all duration-200 group {{ request()->is('orders*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-clipboard-list w-5 text-lg"></i>
                <span class="font-medium">Pedidos</span>
            </a>
            
            <a href="/dentists" class="flex items-center space-x-3 py-3 px-4 rounded-xl transition-all duration-200 group {{ request()->is('dentists*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-user-doctor w-5 text-lg"></i>
                <span class="font-medium">Dentistas</span>
            </a>
            
            <a href="/patients" class="flex items-center space-x-3 py-3 px-4 rounded-xl transition-all duration-200 group {{ request()->is('patients*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-users w-5 text-lg"></i>
                <span class="font-medium">Pacientes</span>
            </a>

            <div class="pt-6 pb-2">
                <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Financeiro e Base</p>
            </div>

            <a href="/financial" class="flex items-center space-x-3 py-3 px-4 rounded-xl transition-all duration-200 group {{ request()->is('financial*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-hand-holding-dollar w-5 text-lg"></i>
                <span class="font-medium">Contas a Receber</span>
            </a>

            <a href="/products" class="flex items-center space-x-3 py-3 px-4 rounded-xl transition-all duration-200 group {{ request()->is('products*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-layer-group w-5 text-lg"></i>
                <span class="font-medium">Serviços / Produtos</span>
            </a>

            <div class="pt-6 pb-2">
                <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Sistema</p>
            </div>

            <a href="/settings" class="flex items-center space-x-3 py-3 px-4 rounded-xl transition-all duration-200 group {{ request()->is('settings*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-gear w-5 text-lg"></i>
                <span class="font-medium">Configurações</span>
            </a>
        </div>

        <div class="p-4 bg-slate-900/50 border-t border-slate-800/50">
            <div class="flex items-center p-2 rounded-xl bg-slate-800/50">
                <div class="w-10 h-10 rounded-lg bg-blue-600 flex items-center justify-center text-sm font-bold shadow-lg shadow-blue-900/20 mr-3">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
                <div class="overflow-hidden">
                    <p class="text-xs font-bold text-white truncate">Admin ProtJund</p>
                    <p class="text-[10px] text-slate-500 truncate">Sair do Sistema</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden bg-slate-50">
        <!-- Header / Navigation Bar -->
        <header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-8 z-10">
            <div class="flex items-center flex-1 max-w-xl">
                <div class="relative w-full">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" placeholder="Busca rápida (Dentista, Paciente, Pedido...)" class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-xl leading-5 bg-slate-50 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent sm:text-sm transition-all duration-200">
                </div>
            </div>
            
            <div class="flex items-center space-x-6 ml-6">
                <!-- Novo Pedido Action -->
                <a href="{{ route('orders.index') }}?action=create" class="flex items-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-blue-900/20 transition-all duration-200 transform hover:-translate-y-0.5">
                    <i class="fa-solid fa-plus"></i>
                    <span>Novo Pedido</span>
                </a>
                
                <div class="h-8 w-px bg-slate-200"></div>
                
                <button class="relative text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-regular fa-bell text-xl"></i>
                    <span class="absolute -top-1 -right-1 h-4 w-4 bg-red-500 text-[10px] font-bold text-white flex items-center justify-center rounded-full border-2 border-white">3</span>
                </button>
            </div>
        </header>

        <!-- Main Page Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto p-8">
            <div class="max-w-7xl mx-auto">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">@yield('header', 'Controle Geral')</h2>
                    <div class="text-sm font-medium text-slate-500">
                        <i class="fa-regular fa-calendar-check mr-2"></i> {{ date('d M, Y') }}
                    </div>
                </div>
                
                @yield('content')
            </div>
        </main>
    </div>

</body>
</html>
