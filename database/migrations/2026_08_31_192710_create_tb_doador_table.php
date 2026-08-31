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
        Schema::create('tb_doador', function (Blueprint $table) {
            $table->increments('id_doador'); 
            $table->string('nome', 150); 
            $table->string('email', 150)->unique(); 
            $table->string('celular', 20); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_doador');
    }
};
