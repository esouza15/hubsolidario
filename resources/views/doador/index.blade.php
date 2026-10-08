@extends('layouts.app')

@section('content')
<div class="space-y-4">

    <!-- Card de Boas-Vindas & Demanda Ativa -->
    <div class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-3xl p-5 text-white shadow-lg shadow-emerald-900/10">
        <div class="flex items-start justify-between gap-3">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-white/20 text-white backdrop-blur mb-2">
                    <i data-lucide="sparkles" class="w-3.5 h-3.5"></i> Match Solidário
                </span>
                <h2 class="text-xl font-extrabold leading-tight">Olá, Doador!</h2>
                <p class="text-xs sm:text-sm text-emerald-100 mt-1">Conectamos sua doação a quem mais necessita em Campo Grande.</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center shrink-0 backdrop-blur">
                <i data-lucide="heart" class="w-6 h-6 text-white"></i>
            </div>
        </div>

        <!-- Banner de Demanda Crítica Ativa (REQ 3 / Match) -->
        <div class="mt-4 p-3.5 bg-white/10 rounded-2xl border border-white/15 backdrop-blur flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-rose-500 flex items-center justify-center shrink-0 shadow-md">
                <i data-lucide="flame" class="w-5 h-5 text-white"></i>
            </div>
            <div class="text-xs sm:text-sm min-w-0">
                <span class="font-extrabold text-rose-200 uppercase text-[11px] tracking-wider block">Alta Necessidade Atual</span>
                <span class="font-bold text-white leading-snug block">Agasalhos (G/GG) e Alimentos de Cesta Básica</span>
            </div>
        </div>
    </div>

    <!-- Card Banner de Alerta se Estiver Congelado (Visitante / Guest) -->
    @guest
        <div onclick="openAuthModal('register')" class="cursor-pointer bg-gradient-to-r from-amber-500 to-orange-500 rounded-3xl p-4 text-white shadow-md flex items-center justify-between gap-3 hover:brightness-105 active:scale-[0.99] transition group">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center shrink-0">
                    <i data-lucide="lock" class="w-5 h-5 text-white"></i>
                </div>
                <div>
                    <h4 class="text-xs font-black uppercase tracking-wider text-amber-100">Campos Desabilitados</h4>
                    <p class="text-xs font-bold text-white leading-tight mt-0.5">Cadastre-se para liberar os campos e doar.</p>
                </div>
            </div>
            <span class="px-3.5 py-2 rounded-xl bg-white text-amber-900 font-extrabold text-xs shadow group-hover:bg-amber-50 transition shrink-0">
                Habilitar
            </span>
        </div>
    @endguest

    <!-- Formulário do Doador (REQ 1 - Match Direto) -->
    <div class="relative">
        @guest
            <!-- Overlay Transparente para Capturar Clique e Abrir Modal -->
            <div onclick="openAuthModal('register')" class="absolute inset-0 z-20 cursor-pointer rounded-3xl" title="Clique para se cadastrar e liberar a aplicação"></div>
        @endguest

        <form action="{{ route('doador.store') }}" method="POST" class="bg-white rounded-3xl p-5 border border-slate-200 shadow-sm space-y-4 relative">
            @csrf

            <fieldset @guest disabled class="opacity-60 select-none pointer-events-none" @endguest class="space-y-4">

            <!-- Dados Básicos de Contato -->
            <div class="space-y-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                    <i data-lucide="user" class="w-4 h-4 text-brand-600"></i>
                    Seus Dados para Contato
                </h3>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nome Completo</label>
                    <input type="text" name="nome" required value="{{ old('nome', auth()->user()->name ?? ($doador->nome ?? '')) }}" placeholder="Ex: Maria Silva Santos" class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3 text-sm font-medium text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-brand-500 focus:outline-none transition">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">E-mail</label>
                        <input type="email" name="email" required value="{{ old('email', auth()->user()->email ?? ($doador->email ?? '')) }}" placeholder="maria@exemplo.com" class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3 text-sm font-medium text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-brand-500 focus:outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Celular / WhatsApp</label>
                        <input type="tel" name="celular" required value="{{ old('celular', $doador->celular ?? '') }}" placeholder="(67) 99123-4567" class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3 text-sm font-medium text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-brand-500 focus:outline-none transition">
                    </div>
                </div>
            </div>

            <hr class="border-slate-100 my-2">

            <!-- Seleção do Item por Match -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2 flex items-center gap-1.5">
                    <i data-lucide="gift" class="w-4 h-4 text-brand-600"></i>
                    O que você deseja doar?
                </h3>
                <div class="grid grid-cols-3 gap-2 sm:gap-3">
                    @php
                        $tipos = [
                            ['val' => 'Agasalho', 'label' => 'Roupa', 'icon' => 'shirt', 'tag' => 'Urgente'],
                            ['val' => 'Alimento Não Perecível', 'label' => 'Alimento', 'icon' => 'utensils', 'tag' => 'Crítico'],
                            ['val' => 'Calçado', 'label' => 'Calçado', 'icon' => 'footprints', 'tag' => null],
                        ];
                    @endphp

                    @foreach($tipos as $tipo)
                        <label class="relative flex flex-col items-center justify-center p-3.5 rounded-2xl border border-slate-200 cursor-pointer hover:bg-slate-50 transition has-[:checked]:border-brand-600 has-[:checked]:bg-brand-50 text-center shadow-sm">
                            <input type="radio" name="categoria" value="{{ $tipo['val'] }}" {{ $loop->first ? 'checked' : '' }} class="sr-only">
                            @if($tipo['tag'])
                                <span class="absolute top-1.5 right-1.5 px-1.5 py-0.5 bg-rose-100 text-rose-800 text-[10px] font-black rounded-md">
                                    {{ $tipo['tag'] }}
                                </span>
                            @endif
                            <i data-lucide="{{ $tipo['icon'] }}" class="w-6 h-6 text-slate-700 mb-1.5"></i>
                            <span class="text-xs sm:text-sm font-bold text-slate-900 leading-tight">{{ $tipo['label'] }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Descrição / Detalhe -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Descrição / Quantidade Estimada</label>
                <input type="text" name="subcategoria" required placeholder="Ex: 2 jaquetas de moletom, 5kg de arroz" class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3 text-sm font-medium text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-brand-500 focus:outline-none transition">
            </div>

            <!-- Agendamento Logístico (Estágio Intermediário) -->
            <div class="space-y-3 pt-1">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                    <i data-lucide="calendar" class="w-4 h-4 text-brand-600"></i>
                    Agendamento de Entrega / Coleta
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Data Preferencial</label>
                        <input type="date" name="data_agendamento" class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3 text-sm font-medium text-slate-900 focus:ring-2 focus:ring-brand-500 focus:outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Horário / Turno</label>
                        <select name="horario_agendamento" class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3 text-xs sm:text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-brand-500 focus:outline-none transition">
                            <option value="">Selecione o turno (Opcional)</option>
                            <option value="Manhã (08:00 às 12:00)">Manhã (08:00 às 12:00)</option>
                            <option value="Tarde (13:00 às 17:00)">Tarde (13:00 às 17:00)</option>
                            <option value="Noite (18:00 às 20:00)">Noite (18:00 às 20:00)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Ponto de Coleta com Match -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1 flex items-center justify-between">
                    <span>Ponto de Entrega Sugerido</span>
                    <span class="text-xs text-brand-700 font-bold">Match Direto</span>
                </label>
                <select name="id_instituicao" required class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3 text-xs sm:text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-brand-500 focus:outline-none transition">
                    @foreach($instituicoes as $inst)
                        <option value="{{ $inst->id_instituicao }}">
                            {{ $inst->nome_instituicao }} — {{ $inst->endereco }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Confirmação -->
            <button type="submit" @guest disabled @endguest class="w-full py-4 px-4 bg-brand-600 hover:bg-brand-700 active:scale-[0.98] text-white font-extrabold text-base rounded-2xl shadow-lg shadow-brand-500/25 flex items-center justify-center gap-2 transition mt-3 @guest opacity-50 cursor-not-allowed @endguest">
                <i data-lucide="{{ auth()->check() ? 'check-circle' : 'lock' }}" class="w-5 h-5"></i>
                <span>{{ auth()->check() ? 'Confirmar Doação' : 'Cadastre-se para Doar' }}</span>
            </button>

            </fieldset>
        </form>
    </div>

    <!-- Histórico de Doações -->
    @if(isset($historico) && $historico->count() > 0)
        <div class="bg-white rounded-3xl p-5 border border-slate-200 shadow-sm space-y-3">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                <i data-lucide="clock" class="w-4 h-4 text-brand-600"></i>
                Suas Contribuições Recentes
            </h3>
            <div class="space-y-2.5">
                @foreach($historico as $item)
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3.5 rounded-2xl bg-slate-50 border border-slate-100 gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-700 shrink-0 shadow-sm">
                                <i data-lucide="package" class="w-4 h-4"></i>
                            </div>
                            <div class="min-w-0">
                                <span class="text-xs sm:text-sm font-bold text-slate-900 block truncate">Doação #{{ $item->id_doacao }}</span>
                                <span class="text-xs font-medium text-slate-500 block">
                                    Intenção: {{ \Carbon\Carbon::parse($item->data_intencao)->format('d/m/Y') }}
                                    @if($item->data_agendamento)
                                        • Agendado: {{ \Carbon\Carbon::parse($item->data_agendamento)->format('d/m/Y') }} {{ $item->horario_agendamento ? '('.$item->horario_agendamento.')' : '' }}
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 self-end sm:self-auto">
                            @if($item->status_entrega === 'Pendente')
                                <details class="relative">
                                    <summary class="cursor-pointer text-xs bg-brand-50 text-brand-700 border border-brand-200 font-bold px-2.5 py-1 rounded-xl hover:bg-brand-100 transition list-none">
                                        + Agendar
                                    </summary>
                                    <form action="{{ route('doador.agendar', $item->id_doacao) }}" method="POST" class="absolute right-0 mt-2 w-64 bg-white p-3 rounded-2xl shadow-xl border border-slate-200 z-20 space-y-2">
                                        @csrf
                                        <span class="block text-xs font-bold text-slate-700">Agendar Entrega</span>
                                        <input type="date" name="data_agendamento" required class="w-full text-xs bg-slate-50 border border-slate-300 rounded-xl px-2.5 py-1.5">
                                        <select name="horario_agendamento" required class="w-full text-xs bg-slate-50 border border-slate-300 rounded-xl px-2.5 py-1.5">
                                            <option value="Manhã (08:00 às 12:00)">Manhã (08:00 - 12:00)</option>
                                            <option value="Tarde (13:00 às 17:00)">Tarde (13:00 - 17:00)</option>
                                        </select>
                                        <button type="submit" class="w-full text-xs bg-brand-600 text-white font-bold py-1.5 rounded-xl hover:bg-brand-700 transition">
                                            Confirmar Horário
                                        </button>
                                    </form>
                                </details>
                            @endif
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold shrink-0 
                                {{ $item->status_entrega === 'Recebido' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                {{ $item->status_entrega === 'Agendado' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $item->status_entrega === 'Pendente' ? 'bg-amber-100 text-amber-800' : '' }}">
                                {{ $item->status_entrega }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection