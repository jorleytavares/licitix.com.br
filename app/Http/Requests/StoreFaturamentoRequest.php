<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFaturamentoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'numero_nf' => ['required', 'string', 'max:255'],
            'valor' => ['required', 'numeric', 'min:0'],
            'data_emissao' => ['required', 'date'],
            'data_vencimento' => ['required', 'date', 'after_or_equal:data_emissao'],
        ];
    }
}
