<?php

namespace App\Http\Controllers;

use App\Models\Doacao;
use App\Models\Doador;
use App\Models\Instituicao;
use App\Models\ItemDoacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DoadorController extends Controller
{
    public function index()
    {
        $instituicoes = Instituicao::all();
        $doador = null;
        $historico = collect();

        // Isolamento de Privacidade: Carrega doador e histórico APENAS da sessão deste dispositivo
        if (session()->has('doador_id')) {
            $doador = Doador::find(session('doador_id'));
            if ($doador) {
                $historico = Doacao::where('id_doador', $doador->id_doador)
                    ->with('itens')
                    ->latest('id_doacao')
                    ->take(5)
                    ->get();
            }
        }

        return view('doador.index', compact('instituicoes', 'doador', 'historico'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:150',
            'email' => 'required|email|max:150',
            'celular' => 'required|string|max:20',
            'categoria' => 'required|in:Alimento Não Perecível,Agasalho,Calçado,Outro',
            'subcategoria' => 'required|string|max:100',
            'id_instituicao' => 'required|exists:tb_instituicao,id_instituicao',
            'data_agendamento' => 'nullable|date',
            'horario_agendamento' => 'nullable|string|max:50',
        ]);

        $doador = null;
        DB::transaction(function () use ($validated, &$doador) {
            // 1. Cadastra ou recupera o doador pelo email (Cadastro Simplificado)
            $doador = Doador::firstOrCreate(
                ['email' => $validated['email']],
                ['nome' => $validated['nome'], 'celular' => $validated['celular']]
            );

            $status = !empty($validated['data_agendamento']) ? 'Agendado' : 'Pendente';

            // 2. Cria o cabeçalho da doação (Doacao)
            $doacao = Doacao::create([
                'id_doador' => $doador->id_doador,
                'data_intencao' => now()->toDateString(),
                'data_agendamento' => $validated['data_agendamento'] ?? null,
                'horario_agendamento' => $validated['horario_agendamento'] ?? null,
                'status_entrega' => $status,
            ]);

            // 3. Registra o item inicial associado ao ponto de coleta com match
            ItemDoacao::create([
                'id_doacao' => $doacao->id_doacao,
                'id_instituicao' => $validated['id_instituicao'],
                'categoria' => $validated['categoria'],
                'subcategoria' => $validated['subcategoria'],
                'estado_item' => 'Bom',
                'local_destino' => 'Ponto de Coleta Principal',
            ]);
        });

        // Salva o ID do doador APENAS na sessão atual deste dispositivo
        if ($doador) {
            session(['doador_id' => $doador->id_doador]);
        }

        return redirect()->route('doador.index')->with('success', 'Intenção de doação registrada com sucesso! Obrigado pelo apoio.');
    }

    /**
     * Submete / Atualiza o agendamento logístico de uma doação existente
     */
    public function agendar(Request $request, $id)
    {
        $validated = $request->validate([
            'data_agendamento' => 'required|date',
            'horario_agendamento' => 'required|string|max:50',
        ]);

        DB::transaction(function () use ($id, $validated) {
            $doacao = Doacao::findOrFail($id);
            $doacao->update([
                'data_agendamento' => $validated['data_agendamento'],
                'horario_agendamento' => $validated['horario_agendamento'],
                'status_entrega' => 'Agendado',
            ]);
        });

        return redirect()->route('doador.index')->with('success', 'Agendamento logístico realizado com sucesso!');
    }
}