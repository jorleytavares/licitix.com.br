<?php

namespace App\Services\Integrations;

interface LicitacaoIntegrationInterface
{
    public function buscarLicitacoes(array $filtros);
    public function detalharLicitacao($id);
}
