<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\RadarService;
use App\Services\RadarSimulatorService;

class BuscarOportunidadesDiarias extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'radar:buscar-diarias';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Busca novas licitações no PNCP baseadas nas configurações dos usuários e gerais do dia.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando busca de oportunidades diárias...');

        // Instancia o serviço (em um app real usaria injeção de dependência no método handle, 
        // mas o Laravel suporta injeção no handle automaticamente, vamos simplificar)
        
        // Como o RadarService precisa do Simulator e não temos DI automática perfeita aqui sem container,
        // vamos usar o container app()
        $radarService = app(RadarService::class);

        $countTotal = $radarService->processarBuscaDiaria();

        $this->info("Concluído! Total de {$countTotal} licitações processadas/atualizadas.");
    }
}
