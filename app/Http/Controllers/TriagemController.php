<?php

namespace App\Http\Controllers;

use App\Models\Doacao;
use App\Models\Instituicao;
use App\Models\ItemDoacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TriagemController extends Controller
{
    public function index()
    {
        // Fila de recepção física (Fase 1): Doações agendadas ou pendentes aguardando chegada física
        $doacoesAgendadas = Doacao::with('doador', 'itens')
            ->whereIn('status_entrega', ['Agendado', 'Pendente'])
            ->latest('id_doacao')
            ->get();

        // Lotes com recepção física já confirmada ('Recebido') aguardando triagem tátil
        $doacoesRecebidas = Doacao::with('doador', 'itens')
            ->where('status_entrega', 'Recebido')
            ->latest('id_doacao')
            ->get();

        // Se houver um lote ativo vindo da sessão (e que ainda esteja 'Recebido') ou o primeiro recebido
        $loteAtivoId = session('lote_ativo_id');
        $doacao = null;

        if ($loteAtivoId) {
            $doacao = Doacao::with('doador', 'itens')
                ->where('id_doacao', $loteAtivoId)
                ->where('status_entrega', 'Recebido')
                ->first();
        }

        if (!$doacao && $doacoesRecebidas->isNotEmpty()) {
            $doacao = $doacoesRecebidas->first();
        }

        $instituicoes = Instituicao::all();

        return view('triagem.index', compact('doacao', 'doacoesRecebidas', 'doacoesAgendadas', 'instituicoes'));
    }

    /**
     * Ação do Botão "Confirmar Chegada Física" (Fase 1 - Fila de Recepção)
     * Rota PATCH: triagem.confirmarChegada
     * 
     * Atualiza o status do lote para 'Recebido' (Lote Confirmado).
     * NÃO realiza inserção na tabela 'tb_item_doacao'.
     */
    public function confirmarChegada($id, Request $request)
    {
        $doacao = DB::transaction(function () use ($id) {
            $doacao = Doacao::with(['doador', 'itens'])->findOrFail($id);
            if ($doacao->status_entrega !== 'Recebido') {
                $doacao->update([
                    'status_entrega' => 'Recebido'
                ]);
            }
            return $doacao;
        });

        $itemInicial = $doacao->itens->first();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'id_doacao' => (int) $id,
                'status' => 'Recebido',
                'doador_nome' => $doacao->doador->nome ?? 'Doador Comunitário',
                'doador_nome_formatado' => \App\Models\Doador::formatarNomeAbreviado($doacao->doador->nome ?? ''),
                'categoria_declarada' => $itemInicial->categoria ?? 'Agasalho / Roupa',
                'subcategoria_declarada' => $itemInicial->subcategoria ?? 'Item doado',
                'data_recebimento' => now()->format('d/m/y \à\s H:i'),
                'message' => 'Chegada física do lote confirmada com sucesso!'
            ]);
        }

        return redirect()->route('triagem.index')
            ->with('success', 'Entrada física do lote #' . $id . ' confirmada com sucesso!')
            ->with('lote_ativo_id', $id);
    }

    /**
     * Integrar ao Estoque (Fase 2 - Triagem Tátil)
     * 
     * REGRAS DE BACKEND E IDEMPOTÊNCIA:
     * - Verifica se o id_doacao já foi triado/integrado.
     * - Garante transação atômica (ACID).
     * - Atualiza status da doação para 'Triado' no banco de dados.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_doacao' => 'required|exists:tb_doacao,id_doacao',
            'id_instituicao' => 'required|exists:tb_instituicao,id_instituicao',
            'categoria' => 'required|in:Alimento Não Perecível,Agasalho,Calçado,Outro',
            'subcategoria' => 'required|string|max:100',
            'tamanho' => 'nullable|string|max:20',
            'data_validade' => 'nullable|date',
            'estado_item' => 'required|in:Excelente,Bom,Avariado',
            'local_destino' => 'required|string|max:100',
        ]);

        $doacao = Doacao::findOrFail($validated['id_doacao']);

        // Verificação de Idempotência / Integridade Transacional:
        // Se a doação já tiver o status 'Triado' ou 'Concluído', rejeita a duplicação
        if (in_array($doacao->status_entrega, ['Triado', 'Concluído'])) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Lote já triado',
                    'message' => 'Este lote de doação (#' . $doacao->id_doacao . ') já teve seus itens integrados ao estoque.'
                ], 409);
            }

            return redirect()->route('triagem.index')
                ->with('error', 'Este lote de doação (#' . $doacao->id_doacao . ') já foi triado e integrado ao estoque.');
        }

        // Transação Atômica ACID
        DB::transaction(function () use ($validated, $doacao) {
            ItemDoacao::create($validated);

            // Atualiza status da doação para 'Triado'
            $doacao->update([
                'status_entrega' => 'Triado'
            ]);
        });

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'id_doacao' => (int) $doacao->id_doacao,
                'message' => 'Item triado e integrado ao estoque com sucesso!'
            ]);
        }

        return redirect()->route('triagem.index')
            ->with('success', 'Item da doação #' . $doacao->id_doacao . ' triado e integrado ao estoque com sucesso!');
    }
}