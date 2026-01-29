<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Proposta>
 */
class PropostaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'codigo' => Str::random(10),
            'status' => 'rascunho',
            'valor_total' => $this->faker->randomFloat(2, 1000, 50000),
            'impostos_percentual' => 10,
            'frete_percentual' => 5,
        ];
    }
}
