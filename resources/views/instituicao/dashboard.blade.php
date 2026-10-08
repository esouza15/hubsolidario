@extends('layouts.app')

@section('content')
<div class="space-y-4">

    <!-- Card Banner de Alerta se Estiver Congelado (Visitante / Guest) -->
    @guest
        <div onclick="openAuthModal('register')" class="cursor-pointer bg-gradient-to-r from-amber-500 to-orange-500 rounded-3xl p-4 text-white shadow-md flex items-center justify-between gap-3 hover:brightness-105 active:scale-[0.99] transition group">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center shrink-0">
                    <i data-lucide="lock" class="w-5 h-5 text-white"></i>
                </div>
                <div>
                    <h4 class="text-xs font-black uppercase tracking-wider text-amber-100">Painel de Gestão Congelado</h4>
                    <p class="text-xs font-bold text-white leading-tight mt-0.5">Faça cadastro/login para liberar o despacho de itens e gestão do estoque.</p>
                </div>
            </div>
            <span class="px-3.5 py-2 rounded-xl bg-white text-amber-900 font-extrabold text-xs shadow group-hover:bg-amber-50 transition shrink-0">
                Habilitar
            </span>
        </div>
    @endguest

    <!-- Cabeçalho da Instituição -->
    <div class="bg-white rounded-3xl p-4 sm:p-5 border border-slate-200 shadow-sm flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 rounded-2xl bg-brand-50 border border-brand-100 flex items-center justify-center text-brand-600 shrink-0 shadow-sm">
                <i data-lucide="building-2" class="w-6 h-6"></i>
            </div>
            <div class="min-w-0 flex-1">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block">Painel de Gestão</span>
                <h2 class="text-base sm:text-lg font-extrabold text-slate-900 leading-tight break-words">{{ $instituicao->nome_instituicao }}</h2>
                <p class="text-xs sm:text-sm font-medium text-slate-600 flex items-center gap-1.5 mt-0.5 truncate">
                    <i data-lucide="map-pin" class="w-4 h-4 text-slate-400 shrink-0"></i>
                    {{ Str::limit($instituicao->endereco, 34) }}
                </p>
            </div>
        </div>
        <a href="{{ route('triagem.index') }}" class="p-3 bg-slate-100 hover:bg-brand-50 hover:text-brand-600 rounded-2xl text-slate-600 transition shrink-0" title="Ir para Triagem">
            <i data-lucide="scan-line" class="w-5 h-5"></i>
        </a>
    </div>

    <!-- Indicadores Numéricos de Estoque Geral -->
    <div class="grid grid-cols-3 gap-2.5">
        <div class="bg-white p-3.5 rounded-2xl border border-slate-200 shadow-sm text-center">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block mb-1">Total</span>
            <span class="text-2xl sm:text-3xl font-black text-slate-800">{{ $totalItens }}</span>
        </div>
        <div class="bg-white p-3.5 rounded-2xl border border-slate-200 shadow-sm text-center">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 block mb-1">Alimentos</span>
            <span class="text-2xl sm:text-3xl font-black text-emerald-600">{{ $totalAlimentos }}</span>
        </div>
        <div class="bg-white p-3.5 rounded-2xl border border-slate-200 shadow-sm text-center">
            <span class="text-xs font-bold uppercase tracking-wider text-blue-700 block mb-1">Agasalhos</span>
            <span class="text-2xl sm:text-3xl font-black text-blue-600">{{ $totalAgasalhos }}</span>
        </div>
    </div>

    <!-- REQ 3: Demandas Urgentes e Críticas -->
    <div class="bg-white rounded-3xl p-4 sm:p-5 border border-slate-200 shadow-sm space-y-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 rounded-full bg-rose-500 animate-pulse"></div>
                <h3 class="text-sm font-extrabold uppercase tracking-wide text-slate-900">Demandas Urgentes</h3>
            </div>
            <span class="text-xs font-semibold text-slate-500">Tempo Real</span>
        </div>

        <div class="space-y-3">
            <!-- Card de Demanda Crítica: Agasalhos G/GG -->
            <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 flex items-center justify-between gap-2">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 rounded-xl bg-rose-600 text-white flex items-center justify-center shrink-0 shadow-sm">
                        <i data-lucide="shirt" class="w-5 h-5"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <h4 class="text-sm font-bold text-slate-900">Agasalhos (G e GG)</h4>
                            <span class="px-2 py-0.5 bg-rose-600 text-white text-[11px] font-black uppercase rounded-md tracking-wider">Urgente!</span>
                        </div>
                        <p class="text-xs font-medium text-rose-800 mt-0.5 leading-snug">Estoque crítico em virtude do inverno</p>
                    </div>
                </div>
                <span class="text-sm sm:text-base font-black text-rose-700 shrink-0">{{ $totalAgasalhosG }} un</span>
            </div>

            <!-- Card de Demanda Crítica: Cestas Básicas / Mantimentos -->
            <div class="p-3.5 rounded-2xl bg-amber-50 border border-amber-200 flex items-center justify-between gap-2">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 rounded-xl bg-amber-600 text-white flex items-center justify-center shrink-0 shadow-sm">
                        <i data-lucide="utensils" class="w-5 h-5"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <h4 class="text-sm font-bold text-slate-900">Alimentos Não Perecíveis</h4>
                            <span class="px-2 py-0.5 bg-amber-600 text-white text-[11px] font-black uppercase rounded-md tracking-wider">Alta Procura</span>
                        </div>
                        <p class="text-xs font-medium text-amber-800 mt-0.5 leading-snug">Demanda contínua para famílias assistidas</p>
                    </div>
                </div>
                <span class="text-sm sm:text-base font-black text-amber-700 shrink-0">{{ $totalAlimentos }} un</span>
            </div>
        </div>
    </div>

    <!-- REQ 4: Controle de Estoque Granular e Transacional -->
    <div class="bg-white rounded-3xl p-4 sm:p-5 border border-slate-200 shadow-sm space-y-3">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-extrabold uppercase tracking-wide text-slate-900 flex items-center gap-2">
                <i data-lucide="boxes" class="w-4 h-4 text-brand-600"></i>
                Inventário Físico Atual
            </h3>
            <span class="text-xs font-semibold text-slate-600 bg-slate-100 px-2.5 py-1 rounded-full">{{ $itensEstoque->count() }} registros</span>
        </div>

        <div class="overflow-hidden border border-slate-200 rounded-2xl">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100/75 border-b border-slate-200 text-xs uppercase font-extrabold text-slate-600">
                        <th class="p-3">Item / Tipo</th>
                        <th class="p-3 text-center">Tam / Val</th>
                        <th class="p-3 text-center">Estado</th>
                        <th class="p-3 text-right">Ação</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-xs sm:text-sm">
                    @forelse($itensEstoque as $item)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-3 font-semibold text-slate-900">
                                <span class="block text-sm font-bold text-slate-900 leading-tight">{{ $item->subcategoria }}</span>
                                <span class="text-xs font-medium text-slate-500">{{ $item->categoria }} • {{ $item->local_destino }}</span>
                            </td>
                            <td class="p-3 text-center">
                                @if($item->tamanho)
                                    <span class="inline-block px-2.5 py-1 bg-slate-200 text-slate-800 rounded-lg font-bold text-xs">
                                        {{ $item->tamanho }}
                                    </span>
                                @elseif($item->data_validade)
                                    <span class="text-xs font-medium text-slate-700">
                                        {{ \Carbon\Carbon::parse($item->data_validade)->format('d/m/y') }}
                                    </span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="p-3 text-center">
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold {{ $item->estado_item === 'Excelente' ? 'bg-emerald-100 text-emerald-800' : ($item->estado_item === 'Bom' ? 'bg-blue-100 text-blue-800' : 'bg-rose-100 text-rose-800') }}">
                                    {{ $item->estado_item }}
                                </span>
                            </td>
                            <td class="p-3 text-right">
                                @guest
                                    <button type="button" onclick="openAuthModal('register')" class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-xl transition" title="Faça login para dar saída/despachar">
                                        <i data-lucide="lock" class="w-4 h-4"></i>
                                    </button>
                                @else
                                    <form action="{{ route('instituicao.despachar', $item->id_item) }}" method="POST" onsubmit="return confirm('Confirmar saída/distribuição deste item à comunidade?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition" title="Distribuir / Dar Saída">
                                            <i data-lucide="external-link" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                @endguest
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-slate-500 text-sm font-medium">
                                Nenhum item registrado no inventário físico até o momento.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection