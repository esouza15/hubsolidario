<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_instituicao', function (Blueprint $table) {
            $table->increments('id_instituicao');
            $table->string('nome_instituicao', 150);
            $table->string('endereco', 255);
            $table->string('telefone', 20);
            $table->string('responsavel', 100);
            $table->timestamps();
        });

        // Inserção automática de dados essenciais (Data Seeding de Sistema)
        DB::table('tb_instituicao')->insertOrIgnore([
            'id_instituicao'   => 1,
            'nome_instituicao' => 'Instituto Musical e Artístico Sol do Pantanal',
            'endereco'         => 'Rua Albert Sabin, 662 - Vila Taveirópolis, Campo Grande/MS',
            'telefone'         => '6733214589',
            'responsavel'      => 'Maestro e Diretoria Comunitária',
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_instituicao');
    }
};