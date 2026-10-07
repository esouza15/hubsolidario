<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_doacao', function (Blueprint $table) {
            $table->date('data_agendamento')->nullable()->after('data_intencao');
            $table->string('horario_agendamento', 50)->nullable()->after('data_agendamento');
        });

        DB::statement("ALTER TABLE tb_doacao MODIFY COLUMN status_entrega ENUM('Pendente', 'Agendado', 'Recebido', 'Triado', 'Concluído', 'Cancelado') NOT NULL DEFAULT 'Pendente'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE tb_doacao MODIFY COLUMN status_entrega ENUM('Pendente', 'Recebido', 'Cancelado') NOT NULL DEFAULT 'Pendente'");

        Schema::table('tb_doacao', function (Blueprint $table) {
            $table->dropColumn(['data_agendamento', 'horario_agendamento']);
        });
    }
};

