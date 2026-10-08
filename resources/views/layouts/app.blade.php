<!DOCTYPE html>
<html lang="pt-BR" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'HubSolidário' }}</title>

    <!-- PWA e Configurações de Ícone para Tela Inicial (iOS/Android/Desktop) -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#16a34a">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="HubSolidário">
    
    <!-- Ícone para dispositivos (Favicon & Apple Touch Icon) -->
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('icons/icon-192.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('icons/icon-192.png') }}">
    <link rel="apple-touch-icon" sizes="512x512" href="{{ asset('icons/icon-512.png') }}">

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0fdf4',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                        },
                        urgent: {
                            500: '#ef4444',
                            600: '#dc2626',
                        }
                    }
                }
            }
        }
    </script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="h-full flex flex-col font-sans text-slate-800 antialiased selection:bg-brand-500 selection:text-white">

    <!-- Header Mobile / Barra Superior -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur border-b border-slate-200 px-4 py-3.5 shadow-sm">
        <div class="max-w-md mx-auto flex items-center justify-between gap-3">
            <div class="flex items-center space-x-3 min-w-0 flex-1">
                <!-- Ícone do Aplicativo -->
                <div class="w-11 h-11 rounded-2xl bg-brand-600 flex items-center justify-center text-white shrink-0 shadow-md shadow-brand-500/20">
                    <i data-lucide="heart-handshake" class="w-6 h-6"></i>
                </div>
                <!-- Identificação Institucional -->
                <div class="min-w-0 flex-1">
                    <h1 class="text-base sm:text-lg font-black leading-tight text-slate-900 tracking-tight truncate">
                        HubSolidário
                    </h1>
                    <p class="text-xs sm:text-sm font-semibold text-slate-500 truncate leading-tight mt-0.5">
                        Instituto Sol do Pantanal
                    </p>
                </div>
            </div>

            <!-- Tag de Status / Botão de Login e Cadastro -->
            @auth
                <div class="flex items-center gap-2 shrink-0">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold 
                        {{ auth()->user()->isGestor() ? 'bg-purple-50 text-purple-700 border border-purple-300' : '' }}
                        {{ auth()->user()->isAgenteTriagem() ? 'bg-emerald-50 text-emerald-700 border border-emerald-300' : '' }}
                        {{ auth()->user()->isDoador() ? 'bg-brand-50 text-brand-700 border border-brand-300' : '' }}">
                        <i data-lucide="{{ auth()->user()->isGestor() ? 'shield-check' : (auth()->user()->isAgenteTriagem() ? 'scan-line' : 'heart') }}" class="w-3.5 h-3.5 mr-1"></i>
                        {{ Str::words(auth()->user()->name, 1, '') }} ({{ auth()->user()->perfil_label }})
                    </span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="p-2 rounded-xl bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-600 transition" title="Sair da Conta">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            @else
                <button type="button" onclick="openAuthModal('register')" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-2xl text-xs font-extrabold bg-brand-600 hover:bg-brand-700 active:scale-95 text-white shadow-md shadow-brand-500/20 shrink-0 transition" title="Clique para entrar ou se cadastrar">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                    <span>Entrar / Cadastrar</span>
                </button>
            @endauth
        </div>
    </header>

    <!-- Conteúdo Principal -->
    <main class="flex-1 max-w-md w-full mx-auto p-4 pb-[calc(5.5rem+env(safe-area-inset-bottom))] md:pb-8">
        @if(session('success'))
            <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-2xl text-xs sm:text-sm font-bold flex items-center gap-2.5 shadow-sm">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-rose-50 border border-rose-200 text-rose-900 rounded-2xl text-xs sm:text-sm font-bold flex items-center gap-2.5 shadow-sm">
                <i data-lucide="alert-circle" class="w-5 h-5 text-rose-600 shrink-0"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-4 bg-rose-50 border border-rose-200 text-rose-900 rounded-2xl text-xs sm:text-sm font-bold space-y-1.5 shadow-sm">
                @foreach($errors->all() as $error)
                    <div class="flex items-center gap-2">
                        <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 shrink-0"></i>
                        <span>{{ $error }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <!-- Bottom Navigation Bar (Mobile-First) -->
    <nav class="fixed bottom-0 inset-x-0 bg-white/95 backdrop-blur border-t border-slate-200 pt-2 pb-[max(0.75rem,env(safe-area-inset-bottom))] px-4 z-40 md:hidden shadow-lg">
        <div class="max-w-md mx-auto grid grid-cols-3 items-center">
            
            <!-- Aba 1: Doações / Recebidas (Doador) -->
            @auth
                @if(auth()->user()->isDoador() || auth()->user()->isGestor())
                    <a href="{{ route('doador.index') }}" class="flex flex-col items-center justify-center py-1 rounded-xl transition {{ request()->routeIs('doador.*') ? 'text-brand-600 font-black' : 'text-slate-500 hover:text-slate-800 font-semibold' }}">
                        <i data-lucide="hand-heart" class="w-6 h-6 mb-1"></i>
                        <span class="text-xs">Doar</span>
                    </a>
                @else
                    <div class="flex flex-col items-center justify-center py-1 rounded-xl text-slate-300 cursor-not-allowed" title="Perfil sem acesso ao módulo de Doação">
                        <i data-lucide="lock" class="w-5 h-5 mb-1 opacity-40"></i>
                        <span class="text-xs text-slate-400">Doar</span>
                    </div>
                @endif
            @else
                <a href="{{ route('doador.index') }}" class="flex flex-col items-center justify-center py-1 rounded-xl transition {{ request()->routeIs('doador.*') ? 'text-brand-600 font-black' : 'text-slate-500 hover:text-slate-800 font-semibold' }}">
                    <i data-lucide="hand-heart" class="w-6 h-6 mb-1"></i>
                    <span class="text-xs">Doar</span>
                </a>
            @endauth

            <!-- Aba 2: Triagem Rápida (Voluntário / Agente) -->
            @auth
                @if(auth()->user()->isAgenteTriagem() || auth()->user()->isGestor())
                    <a href="{{ route('triagem.index') }}" class="flex flex-col items-center justify-center py-1 rounded-xl transition {{ request()->routeIs('triagem.*') ? 'text-brand-600 font-black' : 'text-slate-500 hover:text-slate-800 font-semibold' }}">
                        <div class="relative">
                            <i data-lucide="scan-line" class="w-6 h-6 mb-1"></i>
                            @if(request()->routeIs('triagem.*'))
                                <span class="absolute -top-0.5 -right-1 w-2 h-2 bg-brand-500 rounded-full animate-ping"></span>
                            @endif
                        </div>
                        <span class="text-xs">Triagem</span>
                    </a>
                @else
                    <div class="flex flex-col items-center justify-center py-1 rounded-xl text-slate-300 cursor-not-allowed" title="Perfil sem acesso ao módulo de Triagem">
                        <i data-lucide="lock" class="w-5 h-5 mb-1 opacity-40"></i>
                        <span class="text-xs text-slate-400">Triagem</span>
                    </div>
                @endif
            @else
                <button type="button" onclick="openAuthModal('login')" class="flex flex-col items-center justify-center py-1 rounded-xl text-slate-400 hover:text-brand-600 transition" title="Faça login como Agente de Triagem para acessar">
                    <div class="relative">
                        <i data-lucide="lock" class="w-5 h-5 mb-1 text-slate-400"></i>
                    </div>
                    <span class="text-xs font-semibold">Triagem</span>
                </button>
            @endauth

            <!-- Aba 3: Painel / Estoque (Gestor) -->
            @auth
                @if(auth()->user()->isGestor())
                    <a href="{{ route('instituicao.dashboard') }}" class="flex flex-col items-center justify-center py-1 rounded-xl transition {{ request()->routeIs('instituicao.*') ? 'text-brand-600 font-black' : 'text-slate-500 hover:text-slate-800 font-semibold' }}">
                        <i data-lucide="boxes" class="w-6 h-6 mb-1"></i>
                        <span class="text-xs">Estoque</span>
                    </a>
                @else
                    <div class="flex flex-col items-center justify-center py-1 rounded-xl text-slate-300 cursor-not-allowed" title="Exclusivo para Gestores">
                        <i data-lucide="lock" class="w-5 h-5 mb-1 opacity-40"></i>
                        <span class="text-xs text-slate-400">Estoque</span>
                    </div>
                @endif
            @else
                <button type="button" onclick="openAuthModal('login')" class="flex flex-col items-center justify-center py-1 rounded-xl text-slate-400 hover:text-brand-600 transition" title="Faça login como Gestor para acessar">
                    <i data-lucide="lock" class="w-5 h-5 mb-1 text-slate-400"></i>
                    <span class="text-xs font-semibold">Estoque</span>
                </button>
            @endauth

        </div>
    </nav>

    <!-- Modal de Autenticação (Login / Cadastro) -->
    <div id="modal-auth" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 border border-slate-100 shadow-2xl relative space-y-4 max-h-[90vh] overflow-y-auto">
            <!-- Botão Fechar -->
            <button type="button" onclick="closeAuthModal()" class="absolute top-4 right-4 p-2 text-slate-400 hover:text-slate-700 rounded-full hover:bg-slate-100 transition">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>

            <!-- Cabeçalho do Modal -->
            <div class="text-center space-y-1">
                <div class="w-12 h-12 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center mx-auto shadow-sm">
                    <i data-lucide="lock" class="w-6 h-6"></i>
                </div>
                <h3 class="text-lg font-black text-slate-900" id="modal-auth-title">Acesso ao HubSolidário</h3>
                <p class="text-xs text-slate-500 font-semibold">Faça cadastro ou login para liberar o acesso ao módulo desejado.</p>
            </div>

            <!-- Seletor de Abas -->
            <div class="flex rounded-2xl bg-slate-100 p-1">
                <button type="button" id="tab-btn-register" onclick="switchAuthTab('register')" class="flex-1 py-2 text-xs font-bold rounded-xl transition bg-white text-brand-700 shadow-sm">
                    Cadastrar-se
                </button>
                <button type="button" id="tab-btn-login" onclick="switchAuthTab('login')" class="flex-1 py-2 text-xs font-bold rounded-xl transition text-slate-600">
                    Já sou cadastrado (Entrar)
                </button>
            </div>

            <!-- Formulário 1: Cadastro (Default) -->
            <form id="form-auth-register" action="{{ route('register') }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nome Completo</label>
                    <input type="text" name="name" required placeholder="Ex: Maria Silva Santos" class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3 text-sm font-medium text-slate-900 focus:ring-2 focus:ring-brand-500 focus:outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">E-mail</label>
                    <input type="email" name="email" required placeholder="maria@exemplo.com" class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3 text-sm font-medium text-slate-900 focus:ring-2 focus:ring-brand-500 focus:outline-none transition">
                </div>

                <!-- Campo de Seleção do Perfil do Usuário -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1 flex items-center justify-between">
                        <span>Perfil de Usuário</span>
                        <span class="text-[10px] text-brand-600 font-extrabold uppercase">Permissões</span>
                    </label>
                    <select name="perfil" required class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3 text-xs sm:text-sm font-bold text-slate-800 focus:ring-2 focus:ring-brand-500 focus:outline-none transition">
                        <option value="doador" selected>❤️ Doador (Realizar e agendar doações)</option>
                        <option value="agente_triagem">🔍 Agente de Triagem (Recepção e especificação de lotes)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Senha</label>
                    <input type="password" name="password" required placeholder="Crie uma senha (mínimo 6 caracteres)" class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3 text-sm font-medium text-slate-900 focus:ring-2 focus:ring-brand-500 focus:outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Confirmar Senha</label>
                    <input type="password" name="password_confirmation" required placeholder="Repita a senha" class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3 text-sm font-medium text-slate-900 focus:ring-2 focus:ring-brand-500 focus:outline-none transition">
                </div>
                <button type="submit" class="w-full py-4 px-4 bg-brand-600 hover:bg-brand-700 active:scale-[0.98] text-white font-extrabold text-sm rounded-2xl shadow-lg shadow-brand-500/25 flex items-center justify-center gap-2 transition mt-2">
                    <i data-lucide="user-check" class="w-5 h-5"></i>
                    <span>Cadastrar e Liberar Perfil</span>
                </button>
            </form>

            <!-- Formulário 2: Login -->
            <form id="form-auth-login" action="{{ route('login') }}" method="POST" class="space-y-3 hidden">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">E-mail registrado</label>
                    <input type="email" name="email" required placeholder="maria@exemplo.com" class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3 text-sm font-medium text-slate-900 focus:ring-2 focus:ring-brand-500 focus:outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Sua Senha</label>
                    <input type="password" name="password" required placeholder="••••••••" class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3 text-sm font-medium text-slate-900 focus:ring-2 focus:ring-brand-500 focus:outline-none transition">
                </div>
                <button type="submit" class="w-full py-4 px-4 bg-brand-600 hover:bg-brand-700 active:scale-[0.98] text-white font-extrabold text-sm rounded-2xl shadow-lg shadow-brand-500/25 flex items-center justify-center gap-2 transition mt-2">
                    <i data-lucide="log-in" class="w-5 h-5"></i>
                    <span>Entrar na Conta</span>
                </button>
            </form>
        </div>
    </div>

    <script>
        function openAuthModal(tab = 'register') {
            const modal = document.getElementById('modal-auth');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                switchAuthTab(tab);
            }
        }

        function closeAuthModal() {
            const modal = document.getElementById('modal-auth');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        function switchAuthTab(tab) {
            const formLogin = document.getElementById('form-auth-login');
            const formRegister = document.getElementById('form-auth-register');
            const btnLogin = document.getElementById('tab-btn-login');
            const btnRegister = document.getElementById('tab-btn-register');

            if (tab === 'login') {
                formLogin.classList.remove('hidden');
                formRegister.classList.add('hidden');
                btnLogin.className = 'flex-1 py-2 text-xs font-bold rounded-xl transition bg-white text-brand-700 shadow-sm';
                btnRegister.className = 'flex-1 py-2 text-xs font-bold rounded-xl transition text-slate-600';
            } else {
                formRegister.classList.remove('hidden');
                formLogin.classList.add('hidden');
                btnRegister.className = 'flex-1 py-2 text-xs font-bold rounded-xl transition bg-white text-brand-700 shadow-sm';
                btnLogin.className = 'flex-1 py-2 text-xs font-bold rounded-xl transition text-slate-600';
            }
            if (window.lucide) lucide.createIcons();
        }

        @if(session('error') || $errors->has('email') || $errors->has('password') || $errors->has('name'))
            document.addEventListener('DOMContentLoaded', function() {
                openAuthModal('login');
            });
        @endif

        lucide.createIcons();

        // Registro do Service Worker para PWA (Instalação e Ícone na Tela Inicial)
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(err => {
                    console.log('ServiceWorker registration failed: ', err);
                });
            });
        }
    </script>
</body>
</html>