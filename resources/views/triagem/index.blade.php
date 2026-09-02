@extends('layouts.app')

@section('content')
<div class="space-y-4">

    <!-- Card de Identificação da Doação em Triagem -->
    <div class="bg-white rounded-3xl p-4 sm:p-5 border border-slate-200 shadow-sm flex items-center justify-between gap-3">
        <div class="flex items-center space-x-3 min-w-0 flex-1">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 shrink-0 shadow-sm">
                <i data-lucide="package" class="w-6 h-6"></i>
            </div>
            <div class="min-w-0 flex-1">
                <span class="text-xs font-bold uppercase tracking-wider text-blue-600 block mb-0.5">Triagem de Entrada</span>
                <h2 class="text-base sm:text-lg font-extrabold text-slate-900 leading-tight">
                    Doação #{{ $doacao->id_doacao ?? '101' }}
                </h2>
                <p class="text-xs sm:text-sm font-medium text-slate-500 truncate mt-0.5">
                    {{ $doacao->doador->nome ?? 'Doador Comunitário' }}
                </p>
            </div>
        </div>
        <span class="px-3 py-1.5 bg-amber-50 text-amber-800 text-xs font-bold rounded-xl border border-amber-200 shrink-0">
            {{ $doacao->status_entrega ?? 'Pendente' }}
        </span>
    </div>

    <!-- Formulário de Triagem Tátil (REQ 2) -->
    <form action="{{ route('triagem.store') }}" method="POST" class="bg-white rounded-3xl p-5 border border-slate-200 shadow-sm space-y-5">
        @csrf
        <input type="hidden" name="id_doacao" value="{{ $doacao->id_doacao ?? 1 }}">

        <!-- Ponto de Destino / Instituição -->
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5 flex items-center gap-1.5">
                <i data-lucide="building-2" class="w-4 h-4 text-brand-600"></i>
                Instituição Destino
            </label>
            <select name="id_instituicao" required class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-brand-500 focus:outline-none transition">
                @foreach($instituicoes as $inst)
                    <option value="{{ $inst->id_instituicao }}" {{ $inst->nome_instituicao == 'Instituto Musical e Artístico Sol do Pantanal' ? 'selected' : '' }}>
                        {{ $inst->nome_instituicao }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Categoria do Item (Seletores Táteis Grandes) -->
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2 flex items-center gap-1.5">
                <i data-lucide="tag" class="w-4 h-4 text-brand-600"></i>
                Categoria do Item
            </label>
            <div class="grid grid-cols-2 gap-2.5">
                @php
                    $categorias = [
                        ['val' => 'Agasalho', 'label' => 'Agasalho / Roupa', 'icon' => 'shirt'],
                        ['val' => 'Alimento Não Perecível', 'label' => 'Alimento', 'icon' => 'utensils'],
                        ['val' => 'Calçado', 'label' => 'Calçado', 'icon' => 'footprints'],
                        ['val' => 'Outro', 'label' => 'Outro', 'icon' => 'box'],
                    ];
                @endphp

                @foreach($categorias as $cat)
                    <label class="relative flex items-center gap-3 p-3.5 rounded-2xl border border-slate-200 cursor-pointer hover:bg-slate-50 transition peer-checked:border-brand-600 peer-checked:bg-brand-50 has-[:checked]:border-brand-600 has-[:checked]:bg-brand-50 shadow-sm">
                        <input type="radio" name="categoria" value="{{ $cat['val'] }}" {{ $loop->first ? 'checked' : '' }} onchange="toggleCategoryFields(this.value)" class="sr-only">
                        <i data-lucide="{{ $cat['icon'] }}" class="w-5 h-5 text-slate-700"></i>
                        <span class="text-xs sm:text-sm font-bold text-slate-900">{{ $cat['label'] }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Descrição / Subcategoria -->
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                Subcategoria / Item
            </label>
            <input type="text" name="subcategoria" required placeholder="Ex: Jaqueta Moletom, Feijão Preto 1kg, Tênis" class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3 text-sm font-medium text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-brand-500 focus:outline-none transition">
        </div>

        <!-- Seletores de Tamanho (Touch Targets Amplos) -->
        <div id="secao-tamanho">
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2 flex items-center gap-1.5">
                <i data-lucide="maximize-2" class="w-4 h-4 text-brand-600"></i>
                Tamanho da Peça
            </label>
            <div class="grid grid-cols-4 gap-2.5">
                @foreach(['P', 'M', 'G', 'GG'] as $tam)
                    <label class="flex items-center justify-center py-3.5 rounded-2xl border border-slate-200 text-sm font-black cursor-pointer hover:bg-slate-50 has-[:checked]:bg-brand-600 has-[:checked]:text-white has-[:checked]:border-brand-600 transition shadow-sm">
                        <input type="radio" name="tamanho" value="{{ $tam }}" {{ $tam === 'M' ? 'checked' : '' }} class="sr-only">
                        {{ $tam }}
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Data de Validade (Condicional Alimentos) -->
        <div id="secao-validade" class="hidden">
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5 flex items-center gap-1.5">
                <i data-lucide="calendar" class="w-4 h-4 text-brand-600"></i>
                Data de Validade (Alimentos)
            </label>
            <input type="date" name="data_validade" class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3 text-sm font-medium text-slate-900 focus:ring-2 focus:ring-brand-500 focus:outline-none transition">
        </div>

        <!-- Estado de Conservação -->
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2 flex items-center gap-1.5">
                <i data-lucide="sparkles" class="w-4 h-4 text-brand-600"></i>
                Estado de Conservação
            </label>
            <div class="grid grid-cols-3 gap-2.5">
                <label class="flex items-center justify-center py-3 px-1 rounded-2xl border border-emerald-200 text-xs font-extrabold text-emerald-800 cursor-pointer has-[:checked]:bg-emerald-600 has-[:checked]:text-white has-[:checked]:border-emerald-600 transition shadow-sm">
                    <input type="radio" name="estado_item" value="Excelente" checked class="sr-only">
                    Excelente
                </label>
                <label class="flex items-center justify-center py-3 px-1 rounded-2xl border border-blue-200 text-xs font-extrabold text-blue-800 cursor-pointer has-[:checked]:bg-blue-600 has-[:checked]:text-white has-[:checked]:border-blue-600 transition shadow-sm">
                    <input type="radio" name="estado_item" value="Bom" class="sr-only">
                    Bom
                </label>
                <label class="flex items-center justify-center py-3 px-1 rounded-2xl border border-rose-200 text-xs font-extrabold text-rose-800 cursor-pointer has-[:checked]:bg-rose-600 has-[:checked]:text-white has-[:checked]:border-rose-600 transition shadow-sm">
                    <input type="radio" name="estado_item" value="Avariado" class="sr-only">
                    Avariado
                </label>
            </div>
        </div>

        <!-- Armazenamento Interno -->
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                Local de Armazenamento
            </label>
            <input type="text" name="local_destino" value="Depósito Principal" required class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3 text-sm font-medium text-slate-900 focus:ring-2 focus:ring-brand-500 focus:outline-none transition">
        </div>

        <!-- Botão de Ação Primária -->
        <div class="pt-2">
            <button type="submit" class="w-full py-4 px-4 bg-brand-600 hover:bg-brand-700 active:scale-[0.98] text-white font-extrabold text-base rounded-2xl shadow-lg shadow-brand-500/25 flex items-center justify-center gap-2 transition">
                <i data-lucide="check" class="w-5 h-5"></i>
                Confirmar e Próximo Item
            </button>
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