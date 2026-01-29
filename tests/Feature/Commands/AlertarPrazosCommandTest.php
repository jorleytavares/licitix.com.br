<?php

namespace Tests\Feature\Commands;

use App\Console\Commands\AlertarPrazosCommand;
use App\Models\Empresa;
use App\Models\Licitacao;
use App\Models\User;
use App\Notifications\DailyAlertNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AlertarPrazosCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_envia_alertas_para_prazos_proximos()
    {
        Notification::fake();

        $empresa = Empresa::factory()->create();
        $user = User::factory()->create(['empresa_id' => $empresa->id]);

        // 1. Licitação vencendo amanhã (deve alertar)
        Licitacao::factory()->create([
            'empresa_id' => $empresa->id,
            'monitorada' => true,
            'status' => 'aberta',
            'data_encerramento' => now()->addDay(),
            'numero_edital' => 'ED-001'
        ]);

        // 2. Licitação vencendo em 10 dias (NÃO deve alertar)
        Licitacao::factory()->create([
            'empresa_id' => $empresa->id,
            'monitorada' => true,
            'status' => 'aberta',
            'data_encerramento' => now()->addDays(10),
            'numero_edital' => 'ED-002'
        ]);

        // 3. Tarefa CRM vencendo hoje (deve alertar)
        Licitacao::factory()->create([
            'empresa_id' => $empresa->id,
            'monitorada' => true,
            'tarefa_atual' => 'Ligar para Pregoeiro',
            'data_vencimento_tarefa' => now(), // Hoje
            'numero_edital' => 'ED-003'
        ]);

        $this->artisan('licitacoes:alertar-prazos')
             ->assertExitCode(0);

        Notification::assertSentTo(
            [$user],
            DailyAlertNotification::class,
            function ($notification, $channels) {
                // Deve ter 2 alertas (1 licitação vencendo + 1 tarefa)
                return count($notification->alerts) === 2;
            }
        );
    }
}
