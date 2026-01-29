<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LicitacaoItem extends Model
{
    protected $table = 'licitacao_itens';

    protected $fillable = [
        'licitacao_id',
        'numero_item',
        'descricao',
        'codigo_catser',
        'quantidade',
        'unidade',
        'valor_referencia',
    ];

    public function licitacao()
    {
        return $this->belongsTo(Licitacao::class);
    }
}
