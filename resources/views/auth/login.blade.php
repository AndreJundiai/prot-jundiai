<!DOCTYPE html>
<html lang="pt_BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ProtJund Control Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass-morphism {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }
        .bg-gradient {
            background: radial-gradient(circle at top right, #1e293b, #0f172a, #000000);
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
    </style>
</head>
<body class="bg-gradient min-h-screen flex items-center justify-center p-6 relative overflow-hidden">
    
    <!-- Animated Background Orbs -->
    <div class="absolute top-0 -left-4 w-72 h-72 bg-blue-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
    <div class="absolute top-0 -right-4 w-72 h-72 bg-purple-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
    <div class="absolute -bottom-8 left-20 w-72 h-72 bg-indigo-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>

    <div class="w-full max-w-md z-10">
        <!-- Logo Area -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-600 rounded-3xl shadow-2xl shadow-blue-500/20 mb-4 transform hover:rotate-12 transition-transform duration-300">
                <i class="fa-solid fa-tooth text-white text-4xl"></i>
            </div>
            <h1 class="text-4xl font-bold text-white tracking-tight">ProtJund</h1>
            <p class="text-blue-400 font-medium tracking-widest uppercase text-xs mt-2">Laboratory Command Center</p>
        </div>

        <!-- Login Card -->
        <div class="glass-morphism rounded-3xl p-8 md:p-10 transform transition-all hover:scale-[1.01]">
            <h2 class="text-2xl font-bold text-white mb-8">Acesso Restrito</h2>

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                
                <!-- Errors -->
                @if ($errors->any())
                    <div class="bg-red-500/10 border border-red-500/50 text-red-400 p-4 rounded-xl text-sm mb-6">
                        @foreach ($errors->all() as $error)
                            <p><i class="fa-solid fa-circle-exclamation mr-2"></i>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <div>
                    <label class="block text-slate-400 text-sm font-semibold mb-2 ml-1">E-mail</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-500">
                            <i class="fa-regular fa-envelope"></i>
                        </span>
                        <input type="email" name="email" required autofocus placeholder="admin@admin.com"
                            class="w-full bg-slate-900/50 border border-slate-700 rounded-2xl py-4 pl-12 pr-4 text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-slate-400 text-sm font-semibold mb-2 ml-1">Senha</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-500">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input type="password" name="password" required placeholder="••••••••"
                            class="w-full bg-slate-900/50 border border-slate-700 rounded-2xl py-4 pl-12 pr-4 text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all">
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <label class="flex items-center text-slate-400 text-sm cursor-pointer group">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-700 bg-slate-900/50 text-blue-600 focus:ring-blue-600 transition-all">
                        <span class="ml-2 group-hover:text-slate-300">Lembrar-me</span>
                    </label>
                    <a href="#" class="text-sm text-blue-400 hover:text-blue-300 font-medium transition-colors">Esqueceu a senha?</a>
                </div>

                <button type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-4 rounded-2xl shadow-xl shadow-blue-600/20 transform transition-all active:scale-[0.98] mt-4 flex items-center justify-center space-x-2">
                    <span>Entrar no Sistema</span>
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                </button>
            </form>
        </div>

        <!-- Footer -->
        <p class="text-center text-slate-500 text-sm mt-10">
            &copy; {{ date('Y') }} ProtJund. Todos os direitos reservados.
        </p>
    </div>

</body>
</html>
