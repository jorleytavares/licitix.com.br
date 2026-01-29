<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Licitacao extends Model
{
    use HasFactory;

    protected $table = 'licitacoes';

    protected $fillable = [
        'empresa_id',
        'numero_edital',
        'codigo_radar',
        'orgao',
        'modalidade',
        'estado',
        'municipio',
        'data_abertura',
        'data_encerramento',
        'objeto',
        'informacao_complementar',
        'origem_dado',
        'link_sistema_origem',
        'link_original',
        'valor_estimado',
        'etapa_crm',
        'probabilidade_ganho',
        'anotacoes_crm',
        'tarefa_atual',
        'data_vencimento_tarefa',
        'monitorada',
        'status',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new \App\Models\Traits\EmpresaScope);

        static::creating(function ($model) {
            if (auth()->check()) {
                $model->empresa_id = auth()->user()->empresa_id;
            }
        });
    }

    protected $casts = [
        'data_abertura' => 'date',
        'data_encerramento' => 'date',
        'valor_estimado' => 'decimal:2',
        'data_vencimento_tarefa' => 'datetime',
        'monitorada' => 'boolean',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(LicitacaoItem::class);
    }
    
    public function propostas(): HasMany
    {
        return $this->hasMany(Proposta::class);
    }

    public function arquivos(): HasMany
    {
        return $this->hasMany(LicitacaoArquivo::class);
    }
}
