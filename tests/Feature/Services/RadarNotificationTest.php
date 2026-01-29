<?php

namespace Tests\Feature\Services;

use App\Models\Empresa;
use App\Models\Licitacao;
use App\Models\RadarConfiguracao;
use App\Models\User;
use App\Notifications\DailyAlertNotification;
use App\Services\RadarService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RadarNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_envia_notificacao_ao_encontrar_novas_licitacoes()
    {
        Notification::fake();

        $empresa = Empresa::factory()->create();
        $user = User::factory()->create(['empresa_id' => $empresa->id]);
        
        RadarConfiguracao::create([
            'user_id' => $user->id,
            'termos_busca' => ['termo_teste'],
            'estados' => ['SP'],
            'cidades' => [],
            'frequencia' => 'diario'
        ]);

        // Mock do PncpIntegration via container
        $mockPncp = \Mockery::mock(\App\Services\Integrations\PncpIntegration::class);
        $mockPncp->shouldReceive('buscarLicitacoes')->andReturn(new \Illuminate\Pagination\LengthAwarePaginator([
            (object) [
                'codigo_radar' => 'TEST-001',
                'numero_edital' => '01/2024',
                'orgao' => 'Orgão Teste',
                'objeto' => 'Objeto Teste com termo_teste',
                'informacao_complementar' => '',
                'valor_estimado' => 1000,
                'modalidade' => 'Pregão',
                'estado' => 'SP',
                'cidade' => 'São Paulo',
                'data_abertura' => now(),
                'data_encerramento' => now()->addDays(10),
                'link_sistema_origem' => 'http://teste.com',
                'link_detalhes' => 'http://teste.com',
            ]
        ], 1, 10));

        $this->app->instance(\App\Services\Integrations\PncpIntegration::class, $mockPncp);

        $service = app(RadarService::class);
        $service->processarBuscaDiaria();

        Notification::assertSentTo(
            [$user],
            DailyAlertNotification::class,
            function ($notification, $channels) {
                return count($notification->alerts) > 0 
                    && str_contains($notification->alerts[0]['titulo'], 'Nova Oportunidade');
            }
        );
    }
}
