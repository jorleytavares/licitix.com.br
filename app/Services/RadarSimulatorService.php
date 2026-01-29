<?php

namespace App\Services;

class RadarSimulatorService
{
    public function gerarLicitacoes(int $quantidade = 5)
    {
        $dados = [];

        for ($i = 0; $i < $quantidade; $i++) {
            $estado = fake()->stateAbbr;
            $municipio = fake()->city;
            
            $dados[] = [
                'codigo_radar' => 'RAD-2026-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT),
                'numero_edital' => rand(1, 500) . '/' . now()->year,
                'orgao' => fake()->company,
                'modalidade' => fake()->randomElement(['Pregão Eletrônico', 'Concorrência']),
                'estado' => $estado,
                'uf' => $estado,
                'municipio' => $municipio,
                'cidade' => $municipio,
                'status' => 'aberta',
                'valor_estimado' => fake()->randomFloat(2, 10000, 1000000),
                'data_abertura' => now()->addDays(rand(5, 20)),
                'data_encerramento' => now()->addDays(rand(21, 40)),
                'objeto' => fake()->sentence(8),
                'origem_dado' => 'simulado'
            ];
        }

        return $dados;
    }
}
