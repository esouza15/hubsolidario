<?php

namespace Database\Seeders;

use App\Models\Doador;
use Illuminate\Database\Seeder;

class DoadorSeeder extends Seeder
{
    public function run(): void
    {
        Doador::create([
            'nome' => 'Esthefison Souza',
            'email' => 'esthefison.doador@exemplo.com',
            'celular' => '67991234567',
        ]);

        Doador::create([
            'nome' => 'Maria Silva Santos',
            'email' => 'maria.santos@exemplo.com',
            'celular' => '67998765432',
        ]);
    }
}