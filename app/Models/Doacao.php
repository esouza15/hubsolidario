<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Doacao extends Model
{
    use HasFactory;

    protected $table = 'tb_doacao';
    protected $primaryKey = 'id_doacao';

    protected $fillable = [
        'id_doador',
        'data_intencao',
        'data_agendamento',
        'horario_agendamento',
        'status_entrega',
    ];

    protected $casts = [
        'data_intencao' => 'date',
        'data_agendamento' => 'date',
    ];

    /**
     * Relacionamento N:1 - A Doação pertence a um Doador
     */
    public function doador(): BelongsTo
    {
        return $this->belongsTo(Doador::class, 'id_doador', 'id_doador');
    }

    /**
     * Relacionamento 1:N - A Doação contém múltiplos Itens
     */
    public function itens(): HasMany
    {
        return $this->hasMany(ItemDoacao::class, 'id_doacao', 'id_doacao');
    }
}