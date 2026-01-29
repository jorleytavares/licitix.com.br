<?php

namespace App\Services\Integrations;

use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;

class ComprasNetIntegration implements IntegrationInterface
{
    protected $pncpIntegration;

    public function __construct()
    {
        $this->pncpIntegration = new PncpIntegration();
    }

    public function buscarLicitacoes(array $filtros)
    {
        // A API legado do ComprasNet (compras.dados.gov.br) está instável/lenta.
        // Solução oficial: Utilizar o PNCP filtrando pela esfera Federal (1),
        // já que o ComprasNet alimenta o PNCP com as licitações federais.
        
        // Força esfera Federal
        $filtros['esferaId'] = '1';
        
        // Delega a busca para a integração do PNCP
        $paginator = $this->pncpIntegration->buscarLicitacoes($filtros);

        // Ajusta a origem visualmente para o usuário saber que é o filtro "ComprasNet"
        $paginator->getCollection()->transform(function ($item) {
            $item->origem = 'ComprasNet'; // Mantém consistência visual
            // Opcional: Adicionar identificador visual de que é via PNCP se desejar
            return $item;
        });

        return $paginator;
    }

    public function detalharLicitacao(string $id): array
    {
        // Delega para o PNCP, pois agora usamos IDs do PNCP
        return $this->pncpIntegration->detalharLicitacao($id);
    }
}
