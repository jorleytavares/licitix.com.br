<?php

namespace App\Services\Integrations;

interface IntegrationInterface
{
    public function buscarLicitacoes(array $filtros);
}
