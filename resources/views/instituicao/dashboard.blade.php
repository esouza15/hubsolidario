@extends('layouts.app')

@section('content')
<div class="space-y-5">

    <!-- Cabeçalho da Instituição -->
    <div class="bg-white rounded-3xl p-5 border border-slate-200 shadow-sm flex items-center justify-between">
        <div class="flex items-center space-x-3.5">
            <div class="w-12 h-12 rounded-2xl bg-brand-50 border border-brand-100 flex items-center justify-center text-brand-600 shadow-sm">
                <i data-lucide="building-2" class="w-6 h-6"></i>
            </div>
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Painel de Gestão</span>
                <h2 class="text-base font-bold text-slate-900 leading-tight">{{ $instituicao->nome_instituicao }}</h2>
                <p class="text-xs text-slate-500 flex items-center gap-1 mt-0.5">
                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400"></i>
                    {{ Str::limit($instituicao->endereco, 32) }}
                </p>
            </div>
        </div>
        <a href="{{ route('triagem.index') }}" class="p-2.5 bg-slate-100 hover:bg-brand-50 hover:text-brand-600 rounded-xl text-slate-600 transition" title="Ir para Triagem">
            <i data-lucide="scan-line" class="w-5 h-5"></i>
        </a>
    </div>

    <!-- Indicadores Numéricos de Estoque Geral -->
    <div class="grid grid-cols-3 gap-2.5">
        <div class="bg-white p-3.5 rounded-2xl border border-slate-200 shadow-sm text-center">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-0.5">Total Itens</span>
            <span class="text-xl font-black text-slate-800">{{ $totalItens }}</span>
        </div>
        <div class="bg-white p-3.5 rounded-2xl border border-slate-200 shadow-sm text-center">
            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600 block mb-0.5">Alimentos</span>
            <span class="text-xl font-black text-emerald-700">{{ $totalAlimentos }}</span>
        </div>
        <div class="bg-white p-3.5 rounded-2xl border border-slate-200 shadow-sm text-center">
            <span class="text-[10px] font-bold uppercase tracking-wider text-blue-600 block mb-0.5">Agasalhos</span>
            <span class="text-xl font-black text-blue-700">{{ $totalAgasalhos }}</span>
        </div>
    </div>

    <!-- REQ 3: Demandas Urgentes e Críticas -->
    <div class="bg-white rounded-3xl p-5 border border-slate-200 shadow-sm space-y-3.5">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-pulse"></div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800">Demandas Urgentes</h3>
            </div>
            <span class="text-[10px] font-semibold text-slate-400">Tempo Real</span>
        </div>

        <div class="space-y-2.5">
            <!-- Card de Demanda Crítica: Agasalhos G/GG -->
            <div class="p-3.5 rounded-2xl bg-rose-50/70 border border-rose-200 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-rose-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                        <i data-lucide="shirt" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h4 class="text-xs font-bold text-rose-950">Agasalhos (G e GG)</h4>
                            <span class="px-1.5 py-0.2 bg-rose-600 text-white text-[9px] font-black uppercase rounded tracking-wider">Urgente!</span>
                        </div>
                        <p class="text-[11px] text-rose-700 mt-0.5">Estoque crítico em virtude do inverno</p>
                    </div>
                </div>
                <span class="text-xs font-black text-rose-700">{{ $totalAgasalhosG }} un</span>
            </div>

            <!-- Card de Demanda Crítica: Cestas Básicas / Mantimentos -->
            <div class="p-3.5 rounded-2xl bg-amber-50/70 border border-amber-200 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                        <i data-lucide="utensils" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h4 class="text-xs font-bold text-amber-950">Alimentos Não Perecíveis</h4>
                            <span class="px-1.5 py-0.2 bg-amber-600 text-white text-[9px] font-black uppercase rounded tracking-wider">Alta Procura</span>
                        </div>
                        <p class="text-[11px] text-amber-700 mt-0.5">Demanda contínua para famílias assistidas</p>
                    </div>
                </div>
                <span class="text-xs font-black text-amber-700">{{ $totalAlimentos }} un</span>
            </div>
        </div>
    </div>

    <!-- REQ 4: Controle de Estoque Granular e Transacional -->
    <div class="bg-white rounded-3xl p-5 border border-slate-200 shadow-sm space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 flex items-center gap-2">
                <i data-lucide="boxes" class="w-4 h-4 text-brand-600"></i>
                Inventário Físico Atual
            </h3>
            <span class="text-[11px] font-semibold text-slate-500">{{ $itensEstoque->count() }} registros</span>
        </div>

        <div class="overflow-hidden border border-slate-100 rounded-2xl">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[10px] uppercase font-bold text-slate-400">
                        <th class="p-3">Item / Subcategoria</th>
                        <th class="p-3 text-center">Tam / Validade</th>
                        <th class="p-3 text-center">Estado</th>
                        <th class="p-3 text-right">Ação</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($itensEstoque as $item)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="p-3">
                                <span class="font-bold text-slate-800 block">{{ $item->subcategoria }}</span>
                                <span class="text-[10px] text-slate-400">{{ $item->categoria }} • {{ $item->local_destino }}</span>
                            </td>
                            <td class="p-3 text-center">
                                @if($item->tamanho)
                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-700 rounded-md font-bold text-[10px] border border-slate-200">
                                        {{ $item->tamanho }}
                                    </span>
                                @elseif($item->data_validade)
                                    <span class="text-[10px] font-medium text-slate-600">
                                        {{ \Carbon\Carbon::parse($item->data_validade)->format('d/m/y') }}
                                    </span>
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                            <td class="p-3 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $item->estado_item === 'Excelente' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($item->estado_item === 'Bom' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-rose-50 text-rose-700 border border-rose-200') }}">
                                    {{ $item->estado_item }}
                                </span>
                            </td>
                            <td class="p-3 text-right">
                                <form action="{{ route('instituicao.despachar', $item->id_item) }}" method="POST" onsubmit="return confirm('Confirmar saída/distribuição deste item à comunidade?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Distribuir / Dar Saída">
                                        <i data-lucide="external-link" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-slate-400 text-xs">
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