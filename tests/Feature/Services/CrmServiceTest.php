<?php

namespace Tests\Feature\Services;

use App\Models\Empresa;
use App\Models\Licitacao;
use App\Models\User;
use App\Services\CrmService;
use App\Services\RadarService;
use App\Services\RadarSimulatorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CrmServiceTest extends TestCase
{
    use RefreshDatabase;

    private CrmService $crmService;
    private User $user;
    private Empresa $empresa;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->empresa = Empresa::factory()->create();
        $this->user = User::factory()->create(['empresa_id' => $this->empresa->id]);
        
        $this->actingAs($this->user);
        
        $radarService = app(RadarService::class);
        $this->crmService = new CrmService($radarService);
    }

    public function test_monitorar_licitacao_adiciona_ao_crm()
    {
        $licitacao = Licitacao::factory()->create([
            'empresa_id' => $this->empresa->id,
            'monitorada' => false,
            'origem_dado' => 'pncp' // Prevent RadarService from deleting it as "simulated"
        ]);

        $this->crmService->monitorarLicitacao($licitacao->id);

        $this->assertTrue($licitacao->fresh()->monitorada);
        $this->assertEquals('interesse', $licitacao->fresh()->etapa_crm);
    }

    public function test_listar_pipeline_agrupa_corretamente()
    {
        Licitacao::factory()->create([
            'empresa_id' => $this->empresa->id,
            'monitorada' => true,
            'etapa_crm' => 'interesse'
        ]);
        
        Licitacao::factory()->create([
            'empresa_id' => $this->empresa->id,
            'monitorada' => true,
            'etapa_crm' => 'proposta_enviada'
        ]);

        $pipeline = $this->crmService->listarPipeline();

        $this->assertCount(1, $pipeline['interesse']);
        $this->assertCount(1, $pipeline['proposta_enviada']);
        $this->assertTrue($pipeline->has('em_analise')); // Garante que chaves vazias existem
    }

    public function test_atualizar_etapa()
    {
        $licitacao = Licitacao::factory()->create([
            'empresa_id' => $this->empresa->id,
            'monitorada' => true,
            'etapa_crm' => 'interesse'
        ]);

        $this->crmService->atualizarEtapa($licitacao->id, 'em_analise');

        $this->assertEquals('em_analise', $licitacao->fresh()->etapa_crm);
    }

    public function test_remover_do_monitoramento()
    {
        $licitacao = Licitacao::factory()->create([
            'empresa_id' => $this->empresa->id,
            'monitorada' => true
        ]);

        $this->crmService->removerDoMonitoramento($licitacao->id);

        $this->assertFalse($licitacao->fresh()->monitorada);
    }
}
