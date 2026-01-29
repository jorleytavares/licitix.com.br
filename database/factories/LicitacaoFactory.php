<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Licitacao>
 */
class LicitacaoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'orgao' => $this->faker->company(),
            'objeto' => $this->faker->sentence(),
            'valor_estimado' => $this->faker->randomFloat(2, 1000, 100000),
            'status' => 'aberta',
            'data_abertura' => now(),
            'codigo_radar' => $this->faker->unique()->uuid(),
            'numero_edital' => $this->faker->numerify('##/2024'),
            'modalidade' => 'Pregão Eletrônico',
            'estado' => $this->faker->stateAbbr(),
            'municipio' => $this->faker->city(),
            'origem_dado' => 'simulado',
        ];
    }
}
