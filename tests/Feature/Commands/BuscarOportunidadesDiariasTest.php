<?php

namespace Tests\Feature\Commands;

use App\Console\Commands\BuscarOportunidadesDiarias;
use App\Services\RadarService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class BuscarOportunidadesDiariasTest extends TestCase
{
    use RefreshDatabase;

    public function test_comando_delega_para_radar_service()
    {
        // Mock do RadarService
        $mockService = Mockery::mock(RadarService::class);
        $mockService->shouldReceive('processarBuscaDiaria')
            ->once()
            ->andReturn(10); // Simula 10 licitações encontradas

        // Injeta o mock no container
        $this->app->instance(RadarService::class, $mockService);

        // Executa o comando
        $this->artisan('radar:buscar-diarias')
            ->expectsOutput('Iniciando busca de oportunidades diárias...')
            ->expectsOutput('Concluído! Total de 10 licitações processadas/atualizadas.')
            ->assertExitCode(0);
    }
}
