<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Radar extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::addGlobalScope(new \App\Models\Traits\EmpresaScope);

        static::creating(function ($model) {
            if (auth()->check()) {
                $model->empresa_id = auth()->user()->empresa_id;
            }
            if (empty($model->codigo)) {
                $model->codigo = \Illuminate\Support\Str::uuid();
            }
        });
    }

    protected $fillable = [
        'empresa_id',
        'codigo',
        'nome',
        'ativo',
        'filtros',
    ];

    protected $casts = [
        'filtros' => 'array',
    ];
}
