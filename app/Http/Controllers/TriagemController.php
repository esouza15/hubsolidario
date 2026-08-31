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
        $doacao = Doacao::with('doador')->where('status_entrega', 'Pendente')->first() ?? Doacao::with('doador')->first();
        $instituicoes = Instituicao::all();

        return view('triagem.index', compact('doacao', 'instituicoes'));
    }

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

        // Isolamento Transacional Atômico (ACID)
        DB::transaction(function () use ($validated) {
            ItemDoacao::create($validated);

            // Atualiza status da doação para 'Recebido'
            Doacao::where('id_doacao', $validated['id_doacao'])->update([
                'status_entrega' => 'Recebido'
            ]);
        });

        return redirect()->route('triagem.index')->with('success', 'Item triado e integrado ao estoque com sucesso!');
    }
}