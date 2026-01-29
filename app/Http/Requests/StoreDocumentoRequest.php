<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentoRequest extends FormRequest
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
            'nome' => ['required', 'string', 'max:255'],
            'tipo' => ['required', 'string'],
            'validade' => ['nullable', 'date'],
            'arquivo' => ['required', 'file', 'mimes:pdf,jpg,png,doc,docx', 'max:10240'], // 10MB
        ];
    }
}
