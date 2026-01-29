<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LicitacaoArquivo extends Model
{
    protected $fillable = [
        'licitacao_id',
        'titulo',
        'url',
        'tamanho',
    ];

    public function licitacao(): BelongsTo
    {
        return $this->belongsTo(Licitacao::class);
    }
}
