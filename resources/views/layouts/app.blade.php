<!DOCTYPE html>
<html lang="pt-BR" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'HubSolidário' }}</title>

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
<body class="h-full flex flex-col font-sans text-slate-800 antialiased selection:bg-brand-500 selection:text-white pb-24 md:pb-0">

    <!-- Header Mobile / Barra Superior Refatorada -->
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

            <!-- Tag de Status Ativo -->
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-brand-50 text-brand-700 border border-brand-500/25 shrink-0">
                Voluntário Ativo
            </span>
        </div>
    </header>

    <!-- Conteúdo Principal -->
    <main class="flex-1 max-w-md w-full mx-auto p-4">
        @if(session('success'))
            <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-2xl text-xs sm:text-sm font-bold flex items-center gap-2.5 shadow-sm">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                <span>{{ session('success') }}</span>
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

    <!-- Bottom Navigation Bar Refatorada (Mobile-First) -->
    <nav class="fixed bottom-0 inset-x-0 bg-white/95 backdrop-blur border-t border-slate-200 py-2.5 px-4 z-40 md:hidden shadow-lg">
        <div class="max-w-md mx-auto grid grid-cols-3 items-center">
            
            <!-- Aba 1: Doações / Recebidas (Doador) -->
            <a href="{{ route('doador.index') }}" class="flex flex-col items-center justify-center py-1 rounded-xl transition {{ request()->routeIs('doador.*') ? 'text-brand-600 font-black' : 'text-slate-500 hover:text-slate-800 font-semibold' }}">
                <i data-lucide="inbox" class="w-6 h-6 mb-1"></i>
                <span class="text-xs">Recebidas</span>
            </a>

            <!-- Aba 2: Triagem Rápida (Voluntário) -->
            <a href="{{ route('triagem.index') }}" class="flex flex-col items-center justify-center py-1 rounded-xl transition {{ request()->routeIs('triagem.*') ? 'text-brand-600 font-black' : 'text-slate-500 hover:text-slate-800 font-semibold' }}">
                <div class="relative">
                    <i data-lucide="scan-line" class="w-6 h-6 mb-1"></i>
                    @if(request()->routeIs('triagem.*'))
                        <span class="absolute -top-0.5 -right-1 w-2 h-2 bg-brand-500 rounded-full animate-ping"></span>
                    @endif
                </div>
                <span class="text-xs">Triagem</span>
            </a>

            <!-- Aba 3: Painel / Estoque (Instituição) -->
            <a href="{{ route('instituicao.dashboard') }}" class="flex flex-col items-center justify-center py-1 rounded-xl transition {{ request()->routeIs('instituicao.*') ? 'text-brand-600 font-black' : 'text-slate-500 hover:text-slate-800 font-semibold' }}">
                <i data-lucide="boxes" class="w-6 h-6 mb-1"></i>
                <span class="text-xs">Estoque</span>
            </a>

        </div>
    </nav>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>