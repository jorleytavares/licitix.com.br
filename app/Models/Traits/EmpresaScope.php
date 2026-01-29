<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class EmpresaScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if (auth()->check()) {
            // Garante que o usuário só veja dados da sua própria empresa
            $builder->where($model->getTable() . '.empresa_id', auth()->user()->empresa_id);
        }
    }
}
