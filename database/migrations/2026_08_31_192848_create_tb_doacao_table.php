<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_doacao', function (Blueprint $table) {
            $table->increments('id_doacao');
            $table->unsignedInteger('id_doador');
            $table->date('data_intencao');
            $table->enum('status_entrega', ['Pendente', 'Recebido', 'Cancelado'])->default('Pendente');
            $table->timestamps();

            $table->foreign('id_doador')
                  ->references('id_doador')
                  ->on('tb_doador')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_doacao');
    }
};