<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_item_doacao', function (Blueprint $table) {
            $table->increments('id_item');
            $table->unsignedInteger('id_doacao');
            $table->unsignedInteger('id_instituicao');
            $table->enum('categoria', ['Alimento Não Perecível', 'Agasalho', 'Calçado', 'Outro']);
            $table->string('subcategoria', 100);
            $table->string('tamanho', 20)->nullable();
            $table->date('data_validade')->nullable();
            $table->enum('estado_item', ['Excelente', 'Bom', 'Avariado']);
            $table->string('local_destino', 100);
            $table->timestamps();

            $table->foreign('id_doacao')
                  ->references('id_doacao')
                  ->on('tb_doacao')
                  ->onDelete('cascade');

            $table->foreign('id_instituicao')
                  ->references('id_instituicao')
                  ->on('tb_instituicao')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_item_doacao');
    }
};