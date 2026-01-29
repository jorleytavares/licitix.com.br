<?php

namespace App\Services\Integrations;

class BancoBrasilIntegration implements IntegrationInterface
{
    public function buscarLicitacoes(array $filtros)
    {
        // Simulação de busca no Banco do Brasil (Licitações-e)
        return [
            [
                'origem' => 'BancoBrasil',
                'codigo' => 'BB-' . rand(1000, 9999),
                'objeto' => 'Licitação encontrada via Licitações-e',
            ]
        ];
    }

    public function detalharLicitacao(string $id): array
    {
        return [
            'id' => $id,
            'detalhes' => 'Detalhes da licitação Banco do Brasil...'
        ];
    }
}
