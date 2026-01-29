<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\EmpresaScope;

class Faturamento extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected static function booted()
    {
        static::addGlobalScope(new EmpresaScope);

        static::creating(function ($model) {
            if (auth()->check()) {
                $model->empresa_id = auth()->user()->empresa_id;
            }
        });
    }

    protected $casts = [
        'valor' => 'decimal:2',
        'data_emissao' => 'date',
        'data_vencimento' => 'date',
        'data_pagamento' => 'date',
    ];

    public function proposta()
    {
        return $this->belongsTo(Proposta::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
