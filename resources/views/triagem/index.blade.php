@extends('layouts.app')

@section('content')
<div class="space-y-4">

    <!-- Card de Identificação da Doação em Triagem -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600">
                <i data-lucide="package" class="w-5 h-5"></i>
            </div>
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-blue-600">Incoming Doação</span>
                <h2 class="text-sm font-bold text-slate-900">Doação #{{ $doacao->id_doacao ?? '101' }}</h2>
                <p class="text-xs text-slate-500">{{ $doacao->doador->nome ?? 'Doador Comunitário' }}</p>
            </div>
        </div>
        <span class="px-2.5 py-1 bg-amber-50 text-amber-700 text-xs font-semibold rounded-lg border border-amber-200/60">
            {{ $doacao->status_entrega ?? 'Pendente' }}
        </span>
    </div>

    <!-- Formulário Transacional de Triagem Rápida -->
    <form action="{{ route('triagem.store') }}" method="POST" class="bg-white rounded-3xl p-5 border border-slate-200 shadow-sm space-y-5">
        @csrf
        <input type="hidden" name="id_doacao" value="{{ $doacao->id_doacao ?? 1 }}">

        <!-- Ponto de Destino / Instituição -->
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5 flex items-center gap-1.5">
                <i data-lucide="building-2" class="w-3.5 h-3.5 text-brand-600"></i>
                Instituição Destino
            </label>
            <select name="id_instituicao" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs font-medium text-slate-800 focus:ring-2 focus:ring-brand-500 focus:outline-none transition">
                @foreach($instituicoes as $inst)
                    <option value="{{ $inst->id_instituicao }}" {{ $inst->nome_instituicao == 'Instituto Musical e Artístico Sol do Pantanal' ? 'selected' : '' }}>
                        {{ $inst->nome_instituicao }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- 1. Categoria (Seletores Visuais) -->
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2 flex items-center gap-1.5">
                <i data-lucide="tag" class="w-3.5 h-3.5 text-brand-600"></i>
                Categoria do Item
            </label>
            <div class="grid grid-cols-2 gap-2" id="categoria-selector">
                @php
                    $categorias = [
                        ['val' => 'Agasalho', 'label' => 'Agasalho / Roupa', 'icon' => 'shirt'],
                        ['val' => 'Alimento Não Perecível', 'label' => 'Alimento', 'icon' => 'utensils'],
                        ['val' => 'Calçado', 'label' => 'Calçado', 'icon' => 'footprints'],
                        ['val' => 'Outro', 'label' => 'Outro', 'icon' => 'box'],
                    ];
                @endphp

                @foreach($categorias as $cat)
                    <label class="relative flex items-center gap-2.5 p-3 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50 transition peer-checked:border-brand-600 peer-checked:bg-brand-50 has-[:checked]:border-brand-600 has-[:checked]:bg-brand-50">
                        <input type="radio" name="categoria" value="{{ $cat['val'] }}" {{ $loop->first ? 'checked' : '' }} onchange="toggleCategoryFields(this.value)" class="sr-only">
                        <i data-lucide="{{ $cat['icon'] }}" class="w-4 h-4 text-slate-600"></i>
                        <span class="text-xs font-semibold text-slate-800">{{ $cat['label'] }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Subcategoria / Descrição Curta -->
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                Subcategoria / Item
            </label>
            <input type="text" name="subcategoria" required placeholder="Ex: Moletom com Capuz, Arroz 5kg, Tênis" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs font-medium text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-brand-500 focus:outline-none transition">
        </div>

        <!-- 2. Seletores de Tamanho (REQ 2 - Roupas/Agasalhos/Calçados) -->
        <div id="secao-tamanho">
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2 flex items-center gap-1.5">
                <i data-lucide="maximize-2" class="w-3.5 h-3.5 text-brand-600"></i>
                Tamanho
            </label>
            <div class="grid grid-cols-4 gap-2">
                @foreach(['P', 'M', 'G', 'GG'] as $tam)
                    <label class="flex items-center justify-center p-2.5 rounded-xl border border-slate-200 text-xs font-bold cursor-pointer hover:bg-slate-50 has-[:checked]:bg-brand-600 has-[:checked]:text-white has-[:checked]:border-brand-600 transition shadow-sm">
                        <input type="radio" name="tamanho" value="{{ $tam }}" {{ $tam === 'M' ? 'checked' : '' }} class="sr-only">
                        {{ $tam }}
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Data de Validade (Condicional para Alimentos) -->
        <div id="secao-validade" class="hidden">
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5 flex items-center gap-1.5">
                <i data-lucide="calendar" class="w-3.5 h-3.5 text-brand-600"></i>
                Data de Validade (Alimentos)
            </label>
            <input type="date" name="data_validade" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs font-medium text-slate-800 focus:ring-2 focus:ring-brand-500 focus:outline-none transition">
        </div>

        <!-- 3. Estado de Conservação -->
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2 flex items-center gap-1.5">
                <i data-lucide="sparkles" class="w-3.5 h-3.5 text-brand-600"></i>
                Estado do Item
            </label>
            <div class="grid grid-cols-3 gap-2">
                <label class="flex items-center justify-center py-2 px-1 rounded-xl border border-emerald-200 text-[11px] font-bold text-emerald-700 cursor-pointer has-[:checked]:bg-emerald-600 has-[:checked]:text-white has-[:checked]:border-emerald-600 transition">
                    <input type="radio" name="estado_item" value="Excelente" checked class="sr-only">
                    Excelente
                </label>
                <label class="flex items-center justify-center py-2 px-1 rounded-xl border border-blue-200 text-[11px] font-bold text-blue-700 cursor-pointer has-[:checked]:bg-blue-600 has-[:checked]:text-white has-[:checked]:border-blue-600 transition">
                    <input type="radio" name="estado_item" value="Bom" class="sr-only">
                    Bom
                </label>
                <label class="flex items-center justify-center py-2 px-1 rounded-xl border border-rose-200 text-[11px] font-bold text-rose-700 cursor-pointer has-[:checked]:bg-rose-600 has-[:checked]:text-white has-[:checked]:border-rose-600 transition">
                    <input type="radio" name="estado_item" value="Avariado" class="sr-only">
                    Avariado
                </label>
            </div>
        </div>

        <!-- Local Físico de Destino / Depósito -->
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                Local de Armazenamento Interno
            </label>
            <input type="text" name="local_destino" value="Depósito Principal" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs font-medium text-slate-800 focus:ring-2 focus:ring-brand-500 focus:outline-none transition">
        </div>

        <!-- Ações Táteis de Submissão -->
        <div class="pt-2 space-y-2">
            <button type="submit" class="w-full py-3.5 px-4 bg-brand-600 hover:bg-brand-700 active:scale-[0.98] text-white font-bold text-sm rounded-2xl shadow-lg shadow-brand-500/25 flex items-center justify-center gap-2 transition">
                <i data-lucide="check" class="w-4 h-4"></i>
                Processar & Próximo Item
            </button>
            <a href="#" class="w-full py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-xs rounded-xl flex items-center justify-center gap-2 transition">
                <i data-lucide="alert-triangle" class="w-3.5 h-3.5 text-amber-600"></i>
                Reportar Inconsistência
            </a>
        </div>
    </form>
</div>

<script>
    function toggleCategoryFields(categoria) {
        const secaoTamanho = document.getElementById('secao-tamanho');
        const secaoValidade = document.getElementById('secao-validade');

        if (categoria === 'Alimento Não Perecível') {
            secaoTamanho.classList.add('hidden');
            secaoValidade.classList.remove('hidden');
        } else {
            secaoTamanho.classList.remove('hidden');
            secaoValidade.classList.add('hidden');
        }
    }
</script>
@endsection