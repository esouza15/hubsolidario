<?php

namespace Database\Seeders;

use App\Models\Doacao;
use App\Models\Doador;
use App\Models\Instituicao;
use App\Models\ItemDoacao;
use Illuminate\Database\Seeder;

class DoacaoSeeder extends Seeder
{
    public function run(): void
    {
        $doadorEsthefison = Doador::where('email', 'esthefison.doador@exemplo.com')->first();
        $doadoraMaria = Doador::where('email', 'maria.santos@exemplo.com')->first();

        $institutoSol = Instituicao::where('nome_instituicao', 'like', '%Sol do Pantanal%')->first();
        $comunidadeEsperanca = Instituicao::where('nome_instituicao', 'like', '%Esperança%')->first();

        // Doação 1: Agasalhos entregues no Instituto Sol do Pantanal
        $doacao1 = Doacao::create([
            'id_doador' => $doadorEsthefison->id_doador,
            'data_intencao' => '2026-05-02',
            'status_entrega' => 'Recebido',
        ]);

        ItemDoacao::create([
            'id_doacao' => $doacao1->id_doacao,
            'id_instituicao' => $institutoSol->id_instituicao,
            'categoria' => 'Agasalho',
            'subcategoria' => 'Jaqueta Moletom',
            'tamanho' => 'G',
            'data_validade' => null,
            'estado_item' => 'Excelente',
            'local_destino' => 'Depósito Sol',
        ]);

        ItemDoacao::create([
            'id_doacao' => $doacao1->id_doacao,
            'id_instituicao' => $institutoSol->id_instituicao,
            'categoria' => 'Agasalho',
            'subcategoria' => 'Casaco de Lã',
            'tamanho' => 'M',
            'data_validade' => null,
            'estado_item' => 'Bom',
            'local_destino' => 'Depósito Sol',
        ]);

        // Doação 2: Alimentos não perecíveis (Demanda de Cesta Básica)
        $doacao2 = Doacao::create([
            'id_doador' => $doadoraMaria->id_doador,
            'data_intencao' => '2026-05-15',
            'status_entrega' => 'Pendente',
        ]);

        ItemDoacao::create([
            'id_doacao' => $doacao2->id_doacao,
            'id_instituicao' => $comunidadeEsperanca->id_instituicao,
            'categoria' => 'Alimento Não Perecível',
            'subcategoria' => 'Arroz Tipo 1 (5kg)',
            'tamanho' => null,
            'data_validade' => '2027-02-28',
            'estado_item' => 'Excelente',
            'local_destino' => 'Despensa de Mantimentos',
        ]);
    }
}