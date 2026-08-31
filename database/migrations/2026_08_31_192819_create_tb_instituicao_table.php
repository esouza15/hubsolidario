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
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_instituicao');
    }
};