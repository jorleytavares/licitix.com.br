<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemCatalogoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:255'],
            'codigo' => ['nullable', 'string', 'max:50'],
            'codigo_barras' => ['nullable', 'string', 'max:50'],
            'codigo_catmat' => ['nullable', 'string', 'max:50'],
            'codigo_catser' => ['nullable', 'string', 'max:50'],
            'ncm' => ['nullable', 'string', 'max:20'],
            'marca' => ['nullable', 'string', 'max:100'],
            'modelo' => ['nullable', 'string', 'max:100'],
            'fornecedor_padrao' => ['nullable', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'preco_custo' => ['nullable', 'numeric', 'min:0'],
            'unidade_medida' => ['required', 'string', 'max:10'],
        ];
    }
}
