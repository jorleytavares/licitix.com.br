<?php

namespace Database\Factories;

use App\Models\Empresa;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmpresaFactory extends Factory
{
    protected $model = Empresa::class;

    public function definition()
    {
        return [
            'razao_social' => $this->faker->company(),
            'nome_fantasia' => $this->faker->companySuffix(),
            'cnpj' => $this->faker->cnpj(false), // Requer fakerphp/faker pt_BR provider ou similar, mas o padrao pode falhar se não tiver
            // Vamos usar numerify para garantir 14 digitos se o provider cnpj nao existir
            // 'cnpj' => $this->faker->numerify('##############'),
            'logo_path' => null,
            'email_contato' => $this->faker->companyEmail(),
            'telefone_contato' => $this->faker->phoneNumber(),
            'endereco' => $this->faker->address(),
            'website' => $this->faker->url(),
            'plano' => 'basic',
            'status' => 'ativa',
        ];
    }
}
