<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'perfil')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('perfil', ['doador', 'agente_triagem', 'gestor'])
                      ->nullable()
                      ->default('doador')
                      ->after('email');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'perfil')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('perfil');
            });
        }
    }
};
