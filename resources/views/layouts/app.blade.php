<!DOCTYPE html>
<html lang="pt_BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProtJund - Gestão de Próteses</title>
    
    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#2563eb">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="ProtJund">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
    <link rel="manifest" href="/manifest.json">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { 
            font-family: 'Inter', sans-serif; 
            -webkit-tap-highlight-color: transparent;
            touch-action: manipulation;
        }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
        
        @media (max-width: 768px) {
            .mobile-hide { display: none !important; }
            body { padding-bottom: 70px; } /* Space for bottom nav */
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased flex h-screen overflow-hidden overscroll-none">

    <!-- Sidebar -->
    <aside class="w-64 bg-slate-900 text-white flex flex-col shadow-2xl z-20 mobile-hide">
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

            @can('manage-admins')
            <a href="{{ route('users.index') }}" class="flex items-center space-x-3 py-3 px-4 rounded-xl transition-all duration-200 group {{ request()->is('users*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-users-gear w-5 text-lg"></i>
                <span class="font-medium">Gerenciar Usuários</span>
            </a>
            @endcan

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
                <div class="overflow-hidden flex-1">
                    <p class="text-xs font-bold text-white truncate">{{ auth()->user()->name }}</p>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-[10px] text-slate-500 hover:text-red-400 transition-colors uppercase font-bold tracking-tighter">
                            Sair do Sistema
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden bg-slate-50">
        <!-- Header / Navigation Bar -->
        <header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-8 z-10 mobile-hide">
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
        <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 md:p-8 pt-6">
            <div class="max-w-7xl mx-auto">
                <div class="flex items-center justify-between mb-6 md:mb-8">
                    <h2 class="text-xl md:text-2xl font-extrabold text-slate-900 tracking-tight">@yield('header', 'Controle Geral')</h2>
                    <div class="text-sm font-medium text-slate-500 mobile-hide">
                        <i class="fa-regular fa-calendar-check mr-2"></i> {{ date('d M, Y') }}
                    </div>
                </div>
                
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Bottom Navigation (Mobile Only) -->
    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 flex items-center justify-around h-20 px-6 z-50 md:hidden pb-safe">
        <a href="/orders" class="flex flex-col items-center space-y-1 {{ request()->is('orders*') ? 'text-blue-600' : 'text-slate-400' }}">
            <i class="fa-solid fa-clipboard-list text-xl"></i>
            <span class="text-[10px] font-bold uppercase tracking-tight">Pedidos</span>
        </a>
        <a href="/dentists" class="flex flex-col items-center space-y-1 {{ request()->is('dentists*') ? 'text-blue-600' : 'text-slate-400' }}">
            <i class="fa-solid fa-user-doctor text-xl"></i>
            <span class="text-[10px] font-bold uppercase tracking-tight">Dentistas</span>
        </a>
        <!-- Action Button -->
        <a href="{{ route('orders.index') }}?action=create" class="flex items-center justify-center -mt-10 bg-blue-600 w-14 h-14 rounded-2xl text-white shadow-xl shadow-blue-500/40 border-4 border-slate-50 transition-transform active:scale-95">
            <i class="fa-solid fa-plus text-xl"></i>
        </a>
        <a href="/financial" class="flex flex-col items-center space-y-1 {{ request()->is('financial*') ? 'text-blue-600' : 'text-slate-400' }}">
            <i class="fa-solid fa-hand-holding-dollar text-xl"></i>
            <span class="text-[10px] font-bold uppercase tracking-tight">Finc.</span>
        </a>
        <a href="/settings" class="flex flex-col items-center space-y-1 {{ request()->is('settings*') ? 'text-blue-600' : 'text-slate-400' }}">
            <i class="fa-solid fa-gear text-xl"></i>
            <span class="text-[10px] font-bold uppercase tracking-tight">Ajustes</span>
        </a>
    </nav>

    <script>
        // Register Service Worker for PWA
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/service-worker.js')
                    .then(reg => console.log('SW Registered!', reg))
                    .catch(err => console.log('SW Error:', err));
            });
        }
    </script>
</body>
</html>
