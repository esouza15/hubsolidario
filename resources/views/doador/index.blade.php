@extends('layouts.app')

@section('content')
<div class="space-y-4">

    <!-- Card de Boas-Vindas & Demanda Urgente Ativa -->
    <div class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-3xl p-5 text-white shadow-lg shadow-emerald-900/10">
        <div class="flex items-start justify-between">
            <div>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-white/20 text-white backdrop-blur mb-2">
                    <i data-lucide="sparkles" class="w-3 h-3"></i> Match Solidário
                </span>
                <h2 class="text-lg font-bold leading-tight">Olá, Doador!</h2>
                <p class="text-xs text-emerald-100 mt-0.5">Sua doação é direcionada para quem mais precisa agora.</p>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-white/10 flex items-center justify-center backdrop-blur">
                <i data-lucide="heart" class="w-5 h-5 text-white"></i>
            </div>
        </div>

        <!-- Banner de Urgência (REQ 3 / Match) -->
        <div class="mt-4 p-3 bg-white/10 rounded-2xl border border-white/15 backdrop-blur flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-rose-500 flex items-center justify-center shrink-0 shadow-md">
                <i data-lucide="flame" class="w-4 h-4 text-white"></i>
            </div>
            <div class="text-xs">
                <span class="font-bold text-rose-200 uppercase text-[10px] tracking-wider block">Alta Necessidade</span>
                <span class="font-medium text-white">Agasalhos (G/GG) e Alimentos Não Perecíveis</span>
            </div>
        </div>
    </div>

    <!-- Formulário de Intenção de Doação (REQ 1 - Match Direto) -->
    <form action="{{ route('doador.store') }}" method="POST" class="bg-white rounded-3xl p-5 border border-slate-200 shadow-sm space-y-4">
        @csrf

        <!-- Dados Básicos de Contato (Cadastro Simplificado) -->
        <div class="space-y-3">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 flex items-center gap-1.5">
                <i data-lucide="user" class="w-3.5 h-3.5 text-brand-600"></i>
                Seus Dados de Contato
            </h3>

            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">Nome Completo</label>
                <input type="text" name="nome" required value="{{ old('nome', $doador->nome ?? '') }}" placeholder="Ex: Maria Silva" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs font-medium text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-brand-500 focus:outline-none transition">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">E-mail</label>
                    <input type="email" name="email" required value="{{ old('email', $doador->email ?? '') }}" placeholder="seu@email.com" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs font-medium text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-brand-500 focus:outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">Celular / WhatsApp</label>
                    <input type="tel" name="celular" required value="{{ old('celular', $doador->celular ?? '') }}" placeholder="(67) 99999-9999" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs font-medium text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-brand-500 focus:outline-none transition">
                </div>
            </div>
        </div>

        <hr class="border-slate-100">

        <!-- Seleção do Tipo de Doação -->
        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2 flex items-center gap-1.5">
                <i data-lucide="gift" class="w-3.5 h-3.5 text-brand-600"></i>
                O que você deseja doar?
            </h3>
            <div class="grid grid-cols-3 gap-2">
                @php
                    $tipos = [
                        ['val' => 'Agasalho', 'label' => 'Agasalho', 'icon' => 'shirt', 'tag' => 'Urgente'],
                        ['val' => 'Alimento Não Perecível', 'label' => 'Alimento', 'icon' => 'utensils', 'tag' => 'Crítico'],
                        ['val' => 'Calçado', 'label' => 'Calçado', 'icon' => 'footprints', 'tag' => null],
                    ];
                @endphp

                @foreach($tipos as $tipo)
                    <label class="relative flex flex-col items-center justify-center p-3 rounded-2xl border border-slate-200 cursor-pointer hover:bg-slate-50 transition has-[:checked]:border-brand-600 has-[:checked]:bg-brand-50 text-center">
                        <input type="radio" name="categoria" value="{{ $tipo['val'] }}" {{ $loop->first ? 'checked' : '' }} onchange="filtrarInstituicoes(this.value)" class="sr-only">
                        @if($tipo['tag'])
                            <span class="absolute top-1.5 right-1.5 px-1.5 py-0.2 bg-rose-100 text-rose-700 text-[9px] font-bold rounded-md">
                                {{ $tipo['tag'] }}
                            </span>
                        @endif
                        <i data-lucide="{{ $tipo['icon'] }}" class="w-5 h-5 text-slate-600 mb-1"></i>
                        <span class="text-xs font-semibold text-slate-800">{{ $tipo['label'] }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Descrição / Detalhe do Item -->
        <div>
            <label class="block text-xs font-medium text-slate-700 mb-1">Descrição / Quantidade Estimada</label>
            <input type="text" name="subcategoria" required placeholder="Ex: 2 Casacos Moletom (G), 5kg de Feijão" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs font-medium text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-brand-500 focus:outline-none transition">
        </div>

        <!-- Ponto de Destino Sugerido por Match Direto (REQ 1) -->
        <div>
            <label class="block text-xs font-medium text-slate-700 mb-1 flex items-center justify-between">
                <span>Ponto de Coleta Sugerido</span>
                <span class="text-[10px] text-brand-600 font-bold">Match Automático</span>
            </label>
            <select name="id_instituicao" id="select-instituicao" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs font-medium text-slate-800 focus:ring-2 focus:ring-brand-500 focus:outline-none transition">
                @foreach($instituicoes as $inst)
                    <option value="{{ $inst->id_instituicao }}">
                        {{ $inst->nome_instituicao }} ({{ $inst->endereco }})
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Botão de Confirmação -->
        <button type="submit" class="w-full py-3.5 px-4 bg-brand-600 hover:bg-brand-700 active:scale-[0.98] text-white font-bold text-sm rounded-2xl shadow-lg shadow-brand-500/25 flex items-center justify-center gap-2 transition mt-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i>
            Confirmar Intenção de Doação
        </button>
    </form>

    <!-- Histórico Recente do Doador -->
    @if(isset($historico) && $historico->count() > 0)
        <div class="bg-white rounded-3xl p-5 border border-slate-200 shadow-sm space-y-3">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                <i data-lucide="clock" class="w-3.5 h-3.5 text-brand-600"></i>
                Suas Doações Recentes
            </h3>
            <div class="space-y-2">
                @foreach($historico as $item)
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-600">
                                <i data-lucide="package" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-slate-800 block">Doação #{{ $item->id_doacao }}</span>
                                <span class="text-[10px] text-slate-500">{{ \Carbon\Carbon::parse($item->data_intencao)->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $item->status_entrega === 'Recebido' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                            {{ $item->status_entrega }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection