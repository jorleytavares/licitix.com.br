<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmpresaRequest extends FormRequest
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
            'nome_fantasia' => ['required', 'string', 'max:255'],
            'razao_social' => ['required', 'string', 'max:255'],
            'cnpj' => ['required', 'string', 'max:18'], // Consider adding custom CNPJ validation rule later
            'email_contato' => ['nullable', 'email', 'max:255'],
            'telefone_contato' => ['nullable', 'string', 'max:20'],
            'endereco' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'logo' => ['nullable', 'image', 'max:2048'], // 2MB Max
        ];
    }
}
