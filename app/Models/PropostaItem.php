<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropostaItem extends Model
{
    protected $table = 'proposta_itens';
    protected $fillable = [
        'proposta_id',
        'descricao',
        'quantidade',
        'unidade',
        'valor_unitario',
        'custo_unitario',
        'valor_total',
    ];

    protected $casts = [
        'quantidade' => 'decimal:2',
        'valor_unitario' => 'decimal:2',
        'custo_unitario' => 'decimal:2',
        'valor_total' => 'decimal:2',
    ];
}
