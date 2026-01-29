<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\EmpresaScope;

class ItemCatalogo extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::addGlobalScope(new EmpresaScope);

        static::creating(function ($model) {
            if (auth()->check()) {
                $model->empresa_id = auth()->user()->empresa_id;
            }
        });
    }

    protected $guarded = ['id'];

    protected $casts = [
        'preco_custo' => 'decimal:2',
    ];
}
