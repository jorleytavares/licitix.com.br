<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportarArquivoRequest extends FormRequest
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
        $field = $this->hasFile('arquivo_catalogo') ? 'arquivo_catalogo' : 'arquivo_licitacao';

        return [
            $field => ['required', 'file', 'mimes:csv,txt', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'O arquivo é obrigatório.',
            'mimes' => 'O arquivo deve ser um CSV ou TXT.',
            'max' => 'O arquivo não pode ser maior que 10MB.',
        ];
    }
}
