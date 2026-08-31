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
}