<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Proposta;

class UpdatePropostaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'valor_total' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'string', 'in:' . implode(',', array_keys(Proposta::STATUSES))],
        ];
    }
}
