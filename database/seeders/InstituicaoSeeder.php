<?php

namespace Database\Seeders;

use App\Models\Instituicao;
use Illuminate\Database\Seeder;

class InstituicaoSeeder extends Seeder
{
    public function run(): void
    {
        Instituicao::create([
            'nome_instituicao' => 'Instituto Musical e Artístico Sol do Pantanal',
            'endereco' => 'Rua Albert Sabin, 662 - Vila Taveirópolis, Campo Grande/MS',
            'telefone' => '6733214589',
            'responsavel' => 'Maestro e Diretoria Comunitária',
        ]);

        Instituicao::create([
            'nome_instituicao' => 'Comunidade e Ponto de Apoio Esperança',
            'endereco' => 'Av. Guaicurus, 1420 - Jardim Monumento, Campo Grande/MS',
            'telefone' => '6733981122',
            'responsavel' => 'Coordenação Assistencial Local',
        ]);
    }
}