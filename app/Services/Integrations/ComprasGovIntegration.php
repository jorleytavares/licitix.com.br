<?php

namespace App\Services\Integrations;

class ComprasGovIntegration implements IntegrationInterface
{
    public function buscarLicitacoes(array $filtros)
    {
        // Simulação de busca no ComprasGov
        return [
            [
                'origem' => 'ComprasGov',
                'codigo' => 'CG-' . rand(1000, 9999),
                'objeto' => 'Licitação encontrada via ComprasGov',
            ]
        ];
    }

    public function detalharLicitacao(string $id): array
    {
        return [
            'id' => $id,
            'detalhes' => 'Detalhes da licitação ComprasGov...'
        ];
    }
}
