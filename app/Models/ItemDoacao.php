<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemDoacao extends Model
{
    use HasFactory;

    protected $table = 'tb_item_doacao';
    protected $primaryKey = 'id_item';

    protected $fillable = [
        'id_doacao',
        'id_instituicao',
        'categoria',
        'subcategoria',
        'tamanho',
        'data_validade',
        'estado_item',
        'local_destino',
    ];

    protected $casts = [
        'data_validade' => 'date',
    ];

    /**
     * Relacionamento N:1 - O Item pertence a uma Doação (Cabeçalho)
     */
    public function doacao(): BelongsTo
    {
        return $this->belongsTo(Doacao::class, 'id_doacao', 'id_doacao');
    }

    /**
     * Relacionamento N:1 - O Item é gerenciado por uma Instituição
     */
    public function instituicao(): BelongsTo
    {
        return $this->belongsTo(Instituicao::class, 'id_instituicao', 'id_instituicao');
    }
}