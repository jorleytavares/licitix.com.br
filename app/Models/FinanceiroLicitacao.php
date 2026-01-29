<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceiroLicitacao extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::addGlobalScope(new \App\Models\Traits\EmpresaScope);

        static::creating(function ($model) {
            if (auth()->check()) {
                $model->empresa_id = auth()->user()->empresa_id;
            }
        });
    }

    protected $table = 'financeiro_licitacoes';

    protected $fillable = [
        'empresa_id',
        'proposta_id',
        'valor_contrato',
        'valor_faturado',
        'valor_recebido',
        'saldo',
        'status_pagamento',
    ];

    protected $casts = [
        'valor_contrato' => 'decimal:2',
        'valor_faturado' => 'decimal:2',
        'valor_recebido' => 'decimal:2',
        'saldo' => 'decimal:2',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function proposta()
    {
        return $this->belongsTo(Proposta::class);
    }
}
