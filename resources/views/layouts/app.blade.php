<!DOCTYPE html>
<html lang="pt-BR" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#16a34a">
    <meta name="mobile-web-app-capable" content="yes">

    <!-- Definições para Apple / iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="HubSolidário">
    <link rel="apple-touch-icon" href="/icons/icon-192.png">

    <!-- Vínculo com o Manifesto Web -->
    <link rel="manifest" href="/manifest.json">

    <!-- <title>{{ $title ?? 'HubSolidário' }}</title> -->

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
    <!-- Lucide Icons para ícones táteis rápidos -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="h-full flex flex-col font-sans text-slate-800 antialiased selection:bg-brand-500 selection:text-white pb-20 md:pb-0">

    <!-- Header Mobile / Top Bar -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur border-b border-slate-200 px-4 py-3 shadow-sm">
        <div class="max-w-md mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <div class="w-9 h-9 rounded-xl bg-brand-600 flex items-center justify-center text-white shadow-md shadow-brand-500/20">
                    <i data-lucide="heart-handshake" class="w-5 h-5"></i>
                </div>
                <div>
                    <h1 class="text-base font-bold leading-tight text-slate-900 tracking-tight">HubSolidário</h1>
                    <p class="text-[11px] font-medium text-slate-500">Instituto Sol do Pantanal</p>
                </div>
            </div>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-brand-50 text-brand-700 border border-brand-500/20">
                Voluntário Ativo
            </span>
        </div>
    </header>

    <!-- Conteúdo Principal -->
    <main class="flex-1 max-w-md w-full mx-auto p-4">
        @if(session('success'))
            <div class="mb-4 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-semibold flex items-center gap-2 animate-fade-in shadow-sm">
                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-3.5 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs font-semibold space-y-1 shadow-sm">
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
    <nav class="fixed bottom-0 inset-x-0 bg-white border-t border-slate-200 py-2 px-6 z-40 md:hidden shadow-lg">
        <div class="max-w-md mx-auto flex justify-around items-center text-slate-400">
            <a href="#" class="flex flex-col items-center gap-1 text-slate-500 hover:text-brand-600 transition">
                <i data-lucide="inbox" class="w-5 h-5"></i>
                <span class="text-[10px] font-medium">Recebidas</span>
            </a>
            <a href="{{ route('triagem.index') }}" class="flex flex-col items-center gap-1 text-brand-600 font-bold">
                <div class="relative">
                    <i data-lucide="scan-line" class="w-5 h-5"></i>
                    <span class="absolute -top-1 -right-1.5 w-2 h-2 bg-brand-500 rounded-full animate-ping"></span>
                </div>
                <span class="text-[10px]">Triagem</span>
            </a>
            <a href="#" class="flex flex-col items-center gap-1 text-slate-500 hover:text-brand-600 transition">
                <i data-lucide="boxes" class="w-5 h-5"></i>
                <span class="text-[10px] font-medium">Estoque</span>
            </a>
        </div>
    </nav>

    <script>
        lucide.createIcons();

        if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js')
                .then((reg) => console.log('PWA Service Worker registrado com sucesso:', reg.scope))
                .catch((err) => console.error('Falha no registro do Service Worker:', err));
        });
    }
    </script>
</body>
</html>