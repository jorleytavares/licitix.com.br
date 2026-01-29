<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Proposta extends Model
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

    protected $fillable = [
        'empresa_id',
        'licitacao_id',
        'codigo',
        'status',
        'valor_total',
        'impostos_percentual',
        'frete_percentual',
        'taxas_extras_percentual',
        'custos_extras_valor',
    ];

    protected $casts = [
        'valor_total' => 'decimal:2',
        'impostos_percentual' => 'decimal:2',
        'frete_percentual' => 'decimal:2',
        'taxas_extras_percentual' => 'decimal:2',
        'custos_extras_valor' => 'decimal:2',
    ];

    const STATUSES = [
        'identificada' => 'Identificada',
        'em_analise' => 'Em análise',
        'autorizada' => 'Autorizada',
        'proposta_enviada' => 'Proposta enviada',
        'habilitacao' => 'Habilitação',
        'classificacao' => 'Classificação',
        'ganhou' => 'Ganhou',
        'contrato_ativo' => 'Contrato ativo',
        'faturado' => 'Faturado',
        'recebido' => 'Recebido',
        'perdida' => 'Perdida',
    ];

    public function licitacao()
    {
        return $this->belongsTo(Licitacao::class);
    }

    public function itens()
    {
        return $this->hasMany(PropostaItem::class);
    }

    public function documentos()
    {
        return $this->hasMany(PropostaDocumento::class);
    }

    public function faturamentos()
    {
        return $this->hasMany(Faturamento::class);
    }

    public function financeiro()
    {
        return $this->hasOne(FinanceiroLicitacao::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
