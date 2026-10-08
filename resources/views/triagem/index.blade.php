@extends('layouts.app')

@section('content')
<div class="space-y-5">

    <!-- Card de Alerta / Sucesso Global -->
    <div id="alerta-sucesso-global" class="{{ session('success') ? '' : 'hidden' }} p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-bold flex items-center gap-2 shadow-sm transition">
        <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 shrink-0"></i>
        <span id="alerta-sucesso-texto">{{ session('success') }}</span>
    </div>

    <!-- Card de Alerta de Erro Global -->
    <div id="alerta-erro-global" class="{{ session('error') ? '' : 'hidden' }} p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm font-bold flex items-center gap-2 shadow-sm transition">
        <i data-lucide="alert-circle" class="w-5 h-5 text-rose-600 shrink-0"></i>
        <span id="alerta-erro-texto">{{ session('error') }}</span>
    </div>

    <!-- Banner de Congelamento Módulo Triagem (Visitantes / Guest) -->
    @guest
        <div onclick="openAuthModal('register')" class="cursor-pointer bg-gradient-to-r from-amber-500 to-orange-500 rounded-3xl p-4 text-white shadow-md flex items-center justify-between gap-3 hover:brightness-105 active:scale-[0.99] transition group">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center shrink-0">
                    <i data-lucide="lock" class="w-5 h-5 text-white"></i>
                </div>
                <div>
                    <h4 class="text-xs font-black uppercase tracking-wider text-amber-100">Módulo de Triagem Congelado</h4>
                    <p class="text-xs font-bold text-white leading-tight mt-0.5">Faça cadastro/login para liberar a recepção física e a especificação de doações.</p>
                </div>
            </div>
            <span class="px-3.5 py-2 rounded-xl bg-white text-amber-900 font-extrabold text-xs shadow group-hover:bg-amber-50 transition shrink-0">
                Descongelar
            </span>
        </div>
    @endguest

    <!-- FASE 1: Fila de Recepção Física no Ponto de Coleta -->
    <div class="bg-white rounded-3xl p-5 border border-slate-200 shadow-sm space-y-3">
        <div class="flex items-center justify-between">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                <i data-lucide="truck" class="w-4 h-4 text-brand-600"></i>
                Fase 1: Fila de Recepção Física (Ponto de Coleta)
            </h3>
            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700" id="badge-contador-agendadas">
                {{ isset($doacoesAgendadas) ? $doacoesAgendadas->count() : 0 }} aguardando recepção
            </span>
        </div>

        @if(isset($doacoesAgendadas) && $doacoesAgendadas->count() > 0)
            <div class="space-y-2.5" id="lista-lotes-agendados">
                @foreach($doacoesAgendadas as $itemAgendado)
                    @php
                        $itemInicial = $itemAgendado->itens->first();
                        $nomeRaw = $itemAgendado->doador->nome ?? 'Doador Comunitário';
                        $nomeFormatado = \App\Models\Doador::formatarNomeAbreviado($nomeRaw);
                    @endphp
                    <div id="card-lote-{{ $itemAgendado->id_doacao }}" 
                        data-doador-nome="{{ $nomeRaw }}"
                        data-doador-formatado="{{ $nomeFormatado }}"
                        data-categoria-declarada="{{ $itemInicial->categoria ?? 'Agasalho' }}"
                        data-subcategoria-declarada="{{ $itemInicial->subcategoria ?? 'Item doado' }}"
                        class="flex flex-col sm:flex-row sm:items-center justify-between p-3.5 rounded-2xl bg-slate-50 border border-slate-200 gap-3 transition">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-2xl bg-amber-50 border border-amber-200 flex items-center justify-center text-amber-600 shrink-0 shadow-sm">
                                <i data-lucide="package-check" class="w-5 h-5"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs sm:text-sm font-extrabold text-slate-900 truncate">Doação #{{ $itemAgendado->id_doacao }}</span>
                                    <span id="badge-status-{{ $itemAgendado->id_doacao }}" class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase {{ $itemAgendado->status_entrega === 'Agendado' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ $itemAgendado->status_entrega }}
                                    </span>
                                </div>
                                <p class="text-xs font-medium text-slate-600 mt-0.5">
                                    Doador: <strong class="text-slate-900 font-bold">{{ $nomeFormatado }}</strong>
                                    @if($itemAgendado->data_agendamento)
                                        • Agendado: <strong>{{ \Carbon\Carbon::parse($itemAgendado->data_agendamento)->format('d/m/Y') }}</strong> {{ $itemAgendado->horario_agendamento ? '('.$itemAgendado->horario_agendamento.')' : '' }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <button type="button" 
                            id="btn-confirmar-chegada-{{ $itemAgendado->id_doacao }}"
                            @guest onclick="openAuthModal('register')" @else onclick="confirmarChegadaFisica({{ $itemAgendado->id_doacao }})" @endguest
                            class="py-2 px-3.5 bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] text-white font-extrabold text-xs rounded-xl shadow-md shadow-emerald-600/20 flex items-center justify-center gap-1.5 transition shrink-0 self-end sm:self-auto">
                            <i data-lucide="{{ auth()->check() ? 'check-circle' : 'lock' }}" class="w-4 h-4"></i>
                            <span>{{ auth()->check() ? 'Confirmar Chegada Física' : 'Descongelar para Receber' }}</span>
                        </button>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-4 rounded-2xl bg-slate-50 text-center text-xs text-slate-500 font-medium border border-dashed border-slate-200">
                Nenhuma doação agendada aguardando recepção física no momento.
            </div>
        @endif
    </div>

    <hr class="border-slate-200 my-2">

    <!-- FASE 2: Triagem Tátil & Entrada no Estoque -->
    <div class="bg-white rounded-3xl p-5 border border-slate-200 shadow-sm space-y-5">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-brand-600 block">Fase 2: Triagem Tátil & Estoque</span>
                <h2 class="text-base sm:text-lg font-extrabold text-slate-900 leading-tight">
                    Especificação do Item
                </h2>
            </div>
            <div id="card-lote-ativo-badge" class="px-3 py-1.5 rounded-xl border text-xs font-extrabold shrink-0 {{ isset($doacao) && $doacao ? 'bg-emerald-100 text-emerald-800 border-emerald-300' : 'bg-slate-100 text-slate-500 border-slate-200' }}">
                {{ isset($doacao) && $doacao ? 'Lote Ativo: #'.$doacao->id_doacao.' (RECEBIDO)' : 'Nenhum lote ativo' }}
            </div>
        </div>

        <!-- Banner Informativo da Fase 2 (Resumo do Doador LGPD - UX Enxuta) -->
        <div id="banner-informativo-fase2" class="{{ isset($doacao) && $doacao ? '' : 'hidden' }} p-4 rounded-2xl bg-emerald-50/70 border border-emerald-200/80 space-y-3 transition">
            @if(isset($doacao) && $doacao)
                @php
                    $itemInicial = $doacao?->itens?->first();
                @endphp
                <!-- Linha 1: Identificação do Doador & Data do Recebimento -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                    <div class="text-xs sm:text-sm text-slate-900 leading-tight">
                        <span id="banner-id-doacao" class="font-extrabold text-slate-900">Doação #{{ $doacao?->id_doacao }}</span>
                        <span class="text-slate-400 font-normal mx-1 sm:mx-1.5">•</span>
                        Doador: <strong id="banner-doador-nome" class="text-emerald-950 font-black">{{ \App\Models\Doador::formatarNomeAbreviado($doacao->doador?->nome ?? '') }}</strong>
                    </div>
                    <div id="banner-data-recebimento" class="text-xs font-semibold text-emerald-800 shrink-0">
                        Recebido em {{ now()->format('d/m/y') }}
                    </div>
                </div>

                <!-- Bloco Interno (Registro do Doador) -->
                <div class="p-3 rounded-xl bg-white/90 border border-emerald-100 text-xs text-slate-700 shadow-sm space-y-0.5">
                    <span class="font-bold text-emerald-800 uppercase text-[10px] tracking-wider block">Registro do Doador:</span>
                    <p class="text-xs font-semibold text-slate-900">
                        <span id="banner-categoria-declarada" class="font-black text-brand-700">{{ $itemInicial?->categoria ?? 'Agasalho' }}</span> —
                        "<span id="banner-subcategoria-declarada" class="italic text-slate-700">{{ $itemInicial?->subcategoria ?? 'Casaco moletom e calça' }}</span>"
                    </p>
                </div>
            @else
                <!-- Template Inicial Oculto para Injeção JS em Novos Lotes -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                    <div class="text-xs sm:text-sm text-slate-900 leading-tight">
                        <span id="banner-id-doacao" class="font-extrabold text-slate-900">Doação #—</span>
                        <span class="text-slate-400 font-normal mx-1 sm:mx-1.5">•</span>
                        Doador: <strong id="banner-doador-nome" class="text-emerald-950 font-black">Doador Comunitário</strong>
                    </div>
                    <div id="banner-data-recebimento" class="text-xs font-semibold text-emerald-800 shrink-0">
                        Recebido em —
                    </div>
                </div>
                <div class="p-3 rounded-xl bg-white/90 border border-emerald-100 text-xs text-slate-700 shadow-sm space-y-0.5">
                    <span class="font-bold text-emerald-800 uppercase text-[10px] tracking-wider block">Registro do Doador:</span>
                    <p class="text-xs font-semibold text-slate-900">
                        <span id="banner-categoria-declarada" class="font-black text-brand-700">Agasalho</span> —
                        "<span id="banner-subcategoria-declarada" class="italic text-slate-700">Item doado</span>"
                    </p>
                </div>
            @endif
        </div>

        @if((!isset($doacao) || !$doacao) && (!isset($doacoesAgendadas) || $doacoesAgendadas->isEmpty()))
            <!-- Card de Estado Vazio quando não há lotes pendentes -->
            <div id="card-estado-vazio" class="p-5 rounded-2xl bg-slate-50 border border-slate-200 text-center space-y-2">
                <div class="w-10 h-10 bg-slate-100 text-slate-400 rounded-xl flex items-center justify-center mx-auto">
                    <i data-lucide="inbox" class="w-5 h-5"></i>
                </div>
                <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-700">Nenhum lote pendente para triagem no momento</h4>
                <p class="text-xs text-slate-500 font-medium max-w-sm mx-auto">
                    Todas as doações registradas já foram triadas e integradas ao estoque. Novos lotes aparecerão conforme forem entregues.
                </p>
            </div>
        @endif

        <form id="form-triagem-fase2" action="{{ route('triagem.store') }}" method="POST" class="space-y-5">
            @csrf
            <input type="hidden" name="id_doacao" id="input_id_doacao" value="{{ $doacao->id_doacao ?? '' }}">

            <!-- Container de Campos com Controle de Enable/Disable (Modo Congelado Por Padrão se Sem Lote) -->
            <fieldset id="fieldset-fase2" class="space-y-5 transition-all duration-300 {{ isset($doacao) && $doacao ? '' : 'opacity-50 pointer-events-none' }}" {{ isset($doacao) && $doacao ? '' : 'disabled="disabled"' }}>

                <!-- Ponto de Destino / Instituição -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5 flex items-center gap-1.5">
                        <i data-lucide="building-2" class="w-4 h-4 text-brand-600"></i>
                        Instituição Destino
                    </label>
                    <select name="id_instituicao" id="id_instituicao" required class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-brand-500 focus:outline-none transition input-fase2">
                        @foreach($instituicoes as $inst)
                            <option value="{{ $inst->id_instituicao }}" {{ $inst->nome_instituicao == 'Instituto Musical e Artístico Sol do Pantanal' ? 'selected' : '' }}>
                                {{ $inst->nome_instituicao }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Categoria do Item -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2 flex items-center gap-1.5">
                        <i data-lucide="tag" class="w-4 h-4 text-brand-600"></i>
                        Categoria do Item *
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
                            <label class="relative flex items-center gap-3 p-3.5 rounded-2xl border border-slate-200 cursor-pointer hover:bg-slate-50 transition has-[:checked]:border-brand-600 has-[:checked]:bg-brand-50 shadow-sm">
                                <input type="radio" name="categoria" value="{{ $cat['val'] }}" {{ $loop->first ? 'checked' : '' }} onchange="toggleCategoryFields(this.value)" class="sr-only input-fase2">
                                <i data-lucide="{{ $cat['icon'] }}" class="w-5 h-5 text-slate-700"></i>
                                <span class="text-xs sm:text-sm font-bold text-slate-900">{{ $cat['label'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Subcategoria / Descrição -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                        Subcategoria / Item *
                    </label>
                    <input type="text" name="subcategoria" id="subcategoria" required placeholder="Ex: Jaqueta Moletom, Feijão Preto 1kg, Tênis" class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3 text-sm font-medium text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-brand-500 focus:outline-none transition input-fase2">
                </div>

                <!-- Seletores de Tamanho (Agasalho / Calçado / Outro) -->
                <div id="secao-tamanho">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2 flex items-center gap-1.5">
                        <i data-lucide="maximize-2" class="w-4 h-4 text-brand-600"></i>
                        Tamanho da Peça *
                    </label>
                    <div class="grid grid-cols-4 gap-2.5">
                        @foreach(['P', 'M', 'G', 'GG'] as $tam)
                            <label class="flex items-center justify-center py-3.5 rounded-2xl border border-slate-200 text-sm font-black cursor-pointer hover:bg-slate-50 has-[:checked]:bg-brand-600 has-[:checked]:text-white has-[:checked]:border-brand-600 transition shadow-sm">
                                <input type="radio" name="tamanho" value="{{ $tam }}" class="sr-only input-fase2">
                                {{ $tam }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Data de Validade (Condicional Alimentos) -->
                <div id="secao-validade" class="hidden">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5 flex items-center gap-1.5">
                        <i data-lucide="calendar" class="w-4 h-4 text-brand-600"></i>
                        Data de Validade (Alimentos) *
                    </label>
                    <input type="date" name="data_validade" id="data_validade" class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3 text-sm font-medium text-slate-900 focus:ring-2 focus:ring-brand-500 focus:outline-none transition input-fase2">
                </div>

                <!-- Estado de Conservação -->
                <div id="secao-estado">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2 flex items-center gap-1.5">
                        <i data-lucide="sparkles" class="w-4 h-4 text-brand-600"></i>
                        Estado de Conservação *
                    </label>
                    <div class="grid grid-cols-3 gap-2.5">
                        <label class="flex items-center justify-center py-3 px-1 rounded-2xl border border-emerald-200 text-xs font-extrabold text-emerald-800 cursor-pointer has-[:checked]:bg-emerald-600 has-[:checked]:text-white has-[:checked]:border-emerald-600 transition shadow-sm">
                            <input type="radio" name="estado_item" value="Excelente" class="sr-only input-fase2">
                            Excelente
                        </label>
                        <label class="flex items-center justify-center py-3 px-1 rounded-2xl border border-blue-200 text-xs font-extrabold text-blue-800 cursor-pointer has-[:checked]:bg-blue-600 has-[:checked]:text-white has-[:checked]:border-blue-600 transition shadow-sm">
                            <input type="radio" name="estado_item" value="Bom" class="sr-only input-fase2">
                            Bom
                        </label>
                        <label class="flex items-center justify-center py-3 px-1 rounded-2xl border border-rose-200 text-xs font-extrabold text-rose-800 cursor-pointer has-[:checked]:bg-rose-600 has-[:checked]:text-white has-[:checked]:border-rose-600 transition shadow-sm">
                            <input type="radio" name="estado_item" value="Avariado" class="sr-only input-fase2">
                            Avariado
                        </label>
                    </div>
                </div>

                <!-- Armazenamento Interno -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                        Local de Armazenamento *
                    </label>
                    <input type="text" name="local_destino" id="local_destino" value="Depósito Principal" required class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3 text-sm font-medium text-slate-900 focus:ring-2 focus:ring-brand-500 focus:outline-none transition input-fase2">
                </div>

            </fieldset>

            <!-- Botão de Ação Primária da Fase 2 (Controle de Clique Único e Submissão) -->
            <div class="pt-2">
                <button type="submit" 
                    id="btn-integrar-estoque" 
                    disabled="disabled"
                    class="w-full py-4 px-4 bg-brand-600 text-white font-extrabold text-base rounded-2xl opacity-50 cursor-not-allowed flex items-center justify-center gap-2 transition duration-200">
                    <i data-lucide="check" class="w-5 h-5"></i>
                    <span>Confirmar e Integrar ao Estoque</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let loteAtivoId = {{ isset($doacao) && $doacao ? $doacao->id_doacao : 'null' }};
    const lotesRecebidosFila = [];

    document.addEventListener('DOMContentLoaded', function() {
        @if(isset($doacao) && $doacao)
            ativarLoteFase2(
                {{ $doacao?->id_doacao }}, 
                "{{ $doacao->doador?->nome ?? '' }}",
                "{{ \App\Models\Doador::formatarNomeAbreviado($doacao->doador?->nome ?? '') }}",
                "{{ $doacao->itens?->first()?->categoria ?? 'Agasalho' }}",
                "{{ $doacao->itens?->first()?->subcategoria ?? 'Item doado' }}"
            );
        @endif
        
        // Adiciona escuta de eventos reativos em todos os campos da Fase 2
        document.querySelectorAll('.input-fase2, #subcategoria, #data_validade, #local_destino, #id_instituicao').forEach(el => {
            el.addEventListener('input', validarFormularioFase2);
            el.addEventListener('change', validarFormularioFase2);
        });

        // Escuta o envio do formulário da Fase 2 (Prevenção de Duplicidade & Submissão AJAX)
        const formFase2 = document.getElementById('form-triagem-fase2');
        if (formFase2) {
            formFase2.addEventListener('submit', submeterFormularioFase2);
        }
    });

    /**
     * Função Auxiliar de Formatação de Nome (LGPD) no Front-End:
     */
    function formatarNomeAbreviado(nome) {
        if (!nome) return 'Doador Comunitário';
        const partes = nome.trim().split(/\s+/).filter(Boolean);
        if (partes.length <= 1) return partes[0] || 'Doador Comunitário';
        const primeiroNome = partes[0];
        const ultimoSobrenome = partes[partes.length - 1];
        const inicial = ultimoSobrenome.charAt(0).toUpperCase();
        return `${primeiroNome} ${inicial}.`;
    }

    /**
     * Ação do Botão "Confirmar Chegada Física" (PATCH triagem.confirmarChegada)
     */
    async function confirmarChegadaFisica(idDoacao) {
        const btn = document.getElementById(`btn-confirmar-chegada-${idDoacao}`);
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = `<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i><span>Confirmando...</span>`;
            if (window.lucide) lucide.createIcons();
        }

        try {
            const url = "{{ route('triagem.confirmarChegada', ':id') }}".replace(':id', idDoacao);
            const response = await fetch(url, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const data = await response.json();

            if (data.success) {
                // Atualiza status visual do card na Fase 1
                const badge = document.getElementById(`badge-status-${idDoacao}`);
                if (badge) {
                    badge.textContent = 'RECEBIDO';
                    badge.className = 'px-2 py-0.5 rounded-md text-[10px] font-bold uppercase bg-emerald-100 text-emerald-800';
                }

                if (btn) {
                    btn.className = 'py-2 px-3.5 bg-emerald-100 text-emerald-800 font-bold text-xs rounded-xl flex items-center gap-1.5 cursor-default';
                    btn.innerHTML = `<i data-lucide="check" class="w-4 h-4"></i><span>Chegada Confirmada</span>`;
                    if (window.lucide) lucide.createIcons();
                }

                // Adiciona o lote à fila local de recebidos se não for o ativo
                if (!lotesRecebidosFila.includes(data.id_doacao)) {
                    lotesRecebidosFila.push(data.id_doacao);
                }

                // Se não houver lote ativo no momento na Fase 2, ativa este imediatamente
                if (!loteAtivoId) {
                    ativarLoteFase2(
                        data.id_doacao, 
                        data.doador_nome, 
                        data.doador_nome_formatado, 
                        data.categoria_declarada, 
                        data.subcategoria_declarada,
                        data.data_recebimento
                    );
                }
            } else {
                alert('Erro ao confirmar chegada do lote.');
                if (btn) btn.disabled = false;
            }
        } catch (error) {
            console.error('Erro na requisição:', error);
            alert('Falha ao comunicar com o servidor.');
            if (btn) btn.disabled = false;
        }
    }

    /**
     * Ativa os campos da Fase 2 e popula o Banner Informativo do Doador (LGPD)
     */
    function ativarLoteFase2(idDoacao, doadorNome = '', doadorNomeFormatado = '', categoriaDeclarada = '', subcategoriaDeclarada = '', dataRecebimento = '') {
        loteAtivoId = idDoacao;
        document.getElementById('input_id_doacao').value = idDoacao;

        const cardElement = document.getElementById(`card-lote-${idDoacao}`);
        if (cardElement) {
            doadorNome = doadorNome || cardElement.getAttribute('data-doador-nome') || '';
            doadorNomeFormatado = doadorNomeFormatado || cardElement.getAttribute('data-doador-formatado') || formatarNomeAbreviado(doadorNome);
            categoriaDeclarada = categoriaDeclarada || cardElement.getAttribute('data-categoria-declarada') || 'Agasalho';
            subcategoriaDeclarada = subcategoriaDeclarada || cardElement.getAttribute('data-subcategoria-declarada') || 'Item doado';
        }

        if (!doadorNomeFormatado && doadorNome) {
            doadorNomeFormatado = formatarNomeAbreviado(doadorNome);
        }

        const dataFormatada = dataRecebimento || new Date().toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit', year: '2-digit'});

        // Atualiza elementos do Banner Informativo
        document.getElementById('banner-id-doacao').textContent = `Doação #${idDoacao}`;
        document.getElementById('banner-doador-nome').textContent = doadorNomeFormatado || 'Doador Comunitário';
        document.getElementById('banner-categoria-declarada').textContent = categoriaDeclarada || 'Agasalho';
        document.getElementById('banner-subcategoria-declarada').textContent = subcategoriaDeclarada || 'Item doado';
        document.getElementById('banner-data-recebimento').textContent = `Recebido em ${dataFormatada}`;

        // Exibe o Banner Informativo
        const banner = document.getElementById('banner-informativo-fase2');
        if (banner) banner.classList.remove('hidden');

        // Atualiza Badge do Lote Ativo no topo da Fase 2
        const badgeLote = document.getElementById('card-lote-ativo-badge');
        badgeLote.textContent = `Lote Ativo: #${idDoacao} (RECEBIDO)`;
        badgeLote.className = 'px-3 py-1.5 rounded-xl border text-xs font-black shrink-0 bg-emerald-100 text-emerald-800 border-emerald-300';

        // Habilita os campos da Fase 2 (remove opacity-50 e disabled)
        const fieldset = document.getElementById('fieldset-fase2');
        fieldset.disabled = false;
        fieldset.classList.remove('opacity-50', 'pointer-events-none');

        validarFormularioFase2();
    }

    /**
     * DIRETRIZ 1: Submissão do Formulário com Prevenção de Duplicidade (Prevent Double-Click) e Reset Completo
     */
    async function submeterFormularioFase2(event) {
        event.preventDefault();

        const form = event.target;
        const btnSubmit = document.getElementById('btn-integrar-estoque');

        if (!loteAtivoId) return;

        // 1. Desabilitação imediata durante o envio (Prevent Double-Click)
        btnSubmit.disabled = true;
        btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
        btnSubmit.classList.remove('opacity-100', 'cursor-pointer', 'shadow-lg', 'hover:bg-brand-700');
        btnSubmit.innerHTML = `<i data-lucide="loader-2" class="w-5 h-5 animate-spin"></i><span>Integrando ao Estoque...</span>`;
        if (window.lucide) lucide.createIcons();

        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok && data.success) {
                // Exibe alerta de sucesso
                exibirAlertaSucesso(data.message || 'Item triado e integrado ao estoque com sucesso!');

                // Remove o card do lote concluído da Fase 1 se existir
                const idTriado = data.id_doacao || loteAtivoId;
                const cardTriado = document.getElementById(`card-lote-${idTriado}`);
                if (cardTriado) {
                    cardTriado.remove();
                    atualizarContadorAgendadas();
                }

                // Limpa o lote ativo da fila local
                const indexFila = lotesRecebidosFila.indexOf(idTriado);
                if (indexFila !== -1) lotesRecebidosFila.splice(indexFila, 1);

                // Reset completo da interface da Fase 2
                limparFormularioEFase2();

                // Procura e carrega o próximo lote ativo se houver
                carregarProximoLoteDisponivel();

            } else {
                // Trata erro de idempotência / lote já triado (Status 409 Conflict ou 400 Bad Request)
                exibirAlertaErro(data.message || 'Não foi possível integrar o item ao estoque.');
                
                // Se o lote já tiver sido triado, reseta o form
                if (response.status === 409) {
                    limparFormularioEFase2();
                    carregarProximoLoteDisponivel();
                } else {
                    validarFormularioFase2();
                }
            }
        } catch (error) {
            console.error('Erro na submissão:', error);
            exibirAlertaErro('Erro de conexão com o servidor. Tente novamente.');
            validarFormularioFase2();
        }
    }

    /**
     * Limpa completamente o formulário da Fase 2 e esconde o banner
     */
    function limparFormularioEFase2() {
        loteAtivoId = null;
        document.getElementById('input_id_doacao').value = '';

        // Reseta campos do formulário
        document.getElementById('subcategoria').value = '';
        document.getElementById('data_validade').value = '';
        document.getElementById('local_destino').value = 'Depósito Principal';
        
        document.querySelectorAll('input[name="tamanho"]').forEach(r => r.checked = false);
        document.querySelectorAll('input[name="estado_item"]').forEach(r => r.checked = false);

        // Esconde Banner Informativo Verde
        const banner = document.getElementById('banner-informativo-fase2');
        if (banner) banner.classList.add('hidden');

        // Reseta Badge do Lote Ativo
        const badgeLote = document.getElementById('card-lote-ativo-badge');
        badgeLote.textContent = 'Nenhum lote ativo';
        badgeLote.className = 'px-3 py-1.5 rounded-xl border text-xs font-extrabold shrink-0 bg-slate-100 text-slate-500 border-slate-200';
    }

    /**
     * Carrega o próximo lote disponível ou congela os campos da Fase 2
     */
    function carregarProximoLoteDisponivel() {
        if (lotesRecebidosFila.length > 0) {
            const proximoId = lotesRecebidosFila[0];
            ativarLoteFase2(proximoId);
            return;
        }

        // Procura no DOM por algum lote que já esteja no status RECEBIDO
        const proximaBadgeRecebida = document.querySelector('[id^="badge-status-"]');
        if (proximaBadgeRecebida && proximaBadgeRecebida.textContent.trim() === 'RECEBIDO') {
            const proximoId = proximaBadgeRecebida.id.replace('badge-status-', '');
            ativarLoteFase2(proximoId);
            return;
        }

        // Se NÃO houver doações pendentes para triagem, desabilita (modo congelado) a Fase 2
        congelarFase2();
    }

    /**
     * Congela os campos da Fase 2 e desabilita o botão de submissão
     */
    function congelarFase2() {
        const fieldset = document.getElementById('fieldset-fase2');
        fieldset.disabled = true;
        fieldset.classList.add('opacity-50', 'pointer-events-none');

        const btnSubmit = document.getElementById('btn-integrar-estoque');
        desabilitarBotaoSubmit(btnSubmit);
    }

    function atualizarContadorAgendadas() {
        const badge = document.getElementById('badge-contador-agendadas');
        const cards = document.querySelectorAll('#lista-lotes-agendados > div');
        if (badge) {
            badge.textContent = `${cards.length} aguardando recepção`;
        }
    }

    function exibirAlertaSucesso(msg) {
        const box = document.getElementById('alerta-sucesso-global');
        const txt = document.getElementById('alerta-sucesso-texto');
        if (box && txt) {
            txt.textContent = msg;
            box.classList.remove('hidden');
            setTimeout(() => box.classList.add('hidden'), 5000);
        }
    }

    function exibirAlertaErro(msg) {
        const box = document.getElementById('alerta-erro-global');
        const txt = document.getElementById('alerta-erro-texto');
        if (box && txt) {
            txt.textContent = msg;
            box.classList.remove('hidden');
            setTimeout(() => box.classList.add('hidden'), 6000);
        }
    }

    /**
     * Controle condicional de campos de tamanho / validade por categoria
     */
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

        validarFormularioFase2();
    }

    /**
     * Validação Reativa para Ativação do Botão "Confirmar e Integrar ao Estoque"
     */
    function validarFormularioFase2() {
        const btnSubmit = document.getElementById('btn-integrar-estoque');
        
        if (!loteAtivoId) {
            desabilitarBotaoSubmit(btnSubmit);
            return;
        }

        const categoriaEl = document.querySelector('input[name="categoria"]:checked');
        const categoria = categoriaEl ? categoriaEl.value : null;
        const subcategoria = document.getElementById('subcategoria').value.trim();
        const localDestino = document.getElementById('local_destino').value.trim();

        let valido = false;

        if (categoria && subcategoria !== '' && localDestino !== '') {
            if (categoria === 'Agasalho' || categoria === 'Calçado') {
                // Exigir Categoria + Subcategoria + Tamanho + Estado + Local
                const tamanhoEl = document.querySelector('input[name="tamanho"]:checked');
                const estadoEl = document.querySelector('input[name="estado_item"]:checked');
                if (tamanhoEl && estadoEl) {
                    valido = true;
                }
            } else if (categoria === 'Alimento Não Perecível') {
                // Exigir Categoria + Subcategoria + Data de Validade + Local
                const dataValidade = document.getElementById('data_validade').value.trim();
                if (dataValidade !== '') {
                    valido = true;
                }
            } else {
                // Para 'Outro': Categoria + Subcategoria + Estado + Local
                const estadoEl = document.querySelector('input[name="estado_item"]:checked');
                if (estadoEl) {
                    valido = true;
                }
            }
        }

        if (valido) {
            habilitarBotaoSubmit(btnSubmit);
        } else {
            desabilitarBotaoSubmit(btnSubmit);
        }
    }

    function habilitarBotaoSubmit(btn) {
        btn.disabled = false;
        btn.classList.remove('opacity-50', 'cursor-not-allowed');
        btn.classList.add('opacity-100', 'cursor-pointer', 'shadow-lg', 'shadow-brand-500/25', 'hover:bg-brand-700', 'active:scale-[0.98]');
        btn.innerHTML = `<i data-lucide="check" class="w-5 h-5"></i><span>Confirmar e Integrar ao Estoque</span>`;
        if (window.lucide) lucide.createIcons();
    }

    function desabilitarBotaoSubmit(btn) {
        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');
        btn.classList.remove('opacity-100', 'cursor-pointer', 'shadow-lg', 'shadow-brand-500/25', 'hover:bg-brand-700', 'active:scale-[0.98]');
        btn.innerHTML = `<i data-lucide="check" class="w-5 h-5"></i><span>Confirmar e Integrar ao Estoque</span>`;
        if (window.lucide) lucide.createIcons();
    }
</script>
@endsection