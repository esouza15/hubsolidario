<?php

namespace App\Http\Controllers;

use App\Models\Instituicao;
use App\Models\ItemDoacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstituicaoController extends Controller
{
    public function dashboard()
    {
        // Carrega a instituição principal do piloto (Sol do Pantanal ou primeira cadastrada)
        $instituicao = Instituicao::where('nome_instituicao', 'like', '%Sol do Pantanal%')->first() ?? Instituicao::first();

        // Agregações de estoque (REQ 4)
        $itensEstoque = ItemDoacao::where('id_instituicao', $instituicao->id_instituicao)
            ->latest('id_item')
            ->get();

        $totalItens = $itensEstoque->count();
        $totalAlimentos = $itensEstoque->where('categoria', 'Alimento Não Perecível')->count();
        $totalAgasalhos = $itensEstoque->where('categoria', 'Agasalho')->count();

        // Quantitativo de demandas urgentes específicas (REQ 3)
        $totalAgasalhosG = $itensEstoque->where('categoria', 'Agasalho')
            ->whereIn('tamanho', ['G', 'GG'])
            ->count();

        return view('instituicao.dashboard', compact(
            'instituicao',
            'itensEstoque',
            'totalItens',
            'totalAlimentos',
            'totalAgasalhos',
            'totalAgasalhosG'
        ));
    }

    /**
     * Registra saída/distribuição de item do inventário físico (ACID)
     */
    public function despachar($id)
    {
        DB::transaction(function () use ($id) {
            $item = ItemDoacao::findOrFail($id);
            $item->delete();
        });

        return redirect()->route('instituicao.dashboard')->with('success', 'Item despachado e distribuído à comunidade com sucesso!');
    }
}