<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Proposta;

class StorePropostaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'licitacao_id' => ['required', 'exists:licitacoes,id'],
            'valor_total' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'string', 'in:' . implode(',', array_keys(Proposta::STATUSES))],
            'itens' => ['nullable', 'array'],
            'itens.*.descricao' => ['required', 'string'],
            'itens.*.quantidade' => ['required', 'numeric', 'min:0.01'],
            'itens.*.valor_unitario' => ['required', 'numeric', 'min:0'],
            'itens_selecionados' => ['nullable', 'array'],
            'itens_selecionados.*' => ['exists:licitacao_itens,id'],
        ];
    }
}
