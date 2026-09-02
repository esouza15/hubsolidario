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
        ]);

        $doador = null;
        DB::transaction(function () use ($validated, &$doador) {
            // 1. Cadastra ou recupera o doador pelo email (Cadastro Simplificado)
            $doador = Doador::firstOrCreate(
                ['email' => $validated['email']],
                ['nome' => $validated['nome'], 'celular' => $validated['celular']]
            );

            // 2. Cria o cabeçalho da doação (Doacao) com status Pendente
            $doacao = Doacao::create([
                'id_doador' => $doador->id_doador,
                'data_intencao' => now()->toDateString(),
                'status_entrega' => 'Pendente',
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
}