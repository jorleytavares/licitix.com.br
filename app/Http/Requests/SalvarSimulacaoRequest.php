<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalvarSimulacaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'impostos_percentual' => ['required', 'numeric', 'min:0'],
            'frete_percentual' => ['required', 'numeric', 'min:0'],
            'taxas_extras_percentual' => ['required', 'numeric', 'min:0'],
            'custos_extras_valor' => ['required', 'numeric', 'min:0'],
            'itens' => ['array'],
            'itens.*.id' => ['required', 'exists:proposta_itens,id'],
            'itens.*.custo_unitario' => ['required', 'numeric', 'min:0'],
            'itens.*.valor_unitario' => ['required', 'numeric', 'min:0'],
        ];
    }
}
