<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Instituicao extends Model
{
    use HasFactory;

    protected $table = 'tb_instituicao';
    protected $primaryKey = 'id_instituicao';

    protected $fillable = [
        'nome_instituicao',
        'endereco',
        'telefone',
        'responsavel',
    ];

    /**
     * Relacionamento 1:N - Uma Instituição gerencia vários Itens de Doação
     */
    public function itens(): HasMany
    {
        return $this->hasMany(ItemDoacao::class, 'id_instituicao', 'id_instituicao');
    }
}