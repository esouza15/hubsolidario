<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Doador extends Model
{
    use HasFactory;

    protected $table = 'tb_doador';
    protected $primaryKey = 'id_doador';

    protected $fillable = [
        'nome',
        'email',
        'celular',
    ];

    /**
     * Relacionamento 1:N - Um Doador realiza várias Doações
     */
    public function doacoes(): HasMany
    {
        return $this->hasMany(Doacao::class, 'id_doador', 'id_doador');
    }

    /**
     * Formata o nome do doador para conformidade com a LGPD (Primeiro nome + inicial do último sobrenome).
     * Exemplos:
     * "Ermenegildo Pereira" -> "Ermenegildo P."
     * "Esthefison Souza" -> "Esthefison S."
     * "Maria da Silva Rosa" -> "Maria R."
     */
    public static function formatarNomeAbreviado(?string $nome): string
    {
        if (empty($nome)) {
            return 'Doador Comunitário';
        }

        $partes = array_values(array_filter(explode(' ', trim($nome))));
        if (count($partes) <= 1) {
            return $partes[0] ?? 'Doador Comunitário';
        }

        $primeiroNome = $partes[0];
        $ultimoSobrenome = end($partes);
        $inicial = mb_strtoupper(mb_substr($ultimoSobrenome, 0, 1));

        return "{$primeiroNome} {$inicial}.";
    }

    public function getNomeFormatadoAttribute(): string
    {
        return static::formatarNomeAbreviado($this->nome);
    }
}