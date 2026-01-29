<?php

namespace Tests\Feature\Integration;

use App\Models\Empresa;
use App\Models\Licitacao;
use App\Models\User;
use App\Services\CrmService;
use App\Services\PropostaService;
use App\Services\RadarService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FluxoCompletoLicitacaoTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $empresa;
    protected $radarService;
    protected $crmService;
    protected $propostaService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->empresa = Empresa::factory()->create();
        $this->user = User::factory()->create(['empresa_id' => $this->empresa->id]);
        $this->actingAs($this->user);

        $this->radarService = app(RadarService::class);
        $this->crmService = app(CrmService::class);
        $this->propostaService = app(PropostaService::class);
    }

    public function test_fluxo_completo_licitacao_radar_crm_proposta()
    {
        // 1. Radar: Encontrar/Simular uma Licitação
        // Criamos uma licitação simulada como se viesse do Radar
        $licitacao = Licitacao::factory()->create([
            'empresa_id' => $this->empresa->id,
            'monitorada' => false,
            // 'etapa_crm' => null, // Removido pois a coluna não aceita null (tem default 'interesse')
            'origem_dado' => 'pncp'
        ]);

        // 2. CRM: Monitorar Licitação
        $monitorada = $this->crmService->monitorarLicitacao($licitacao->id);
        
        $this->assertTrue($monitorada->fresh()->monitorada);
        $this->assertEquals('interesse', $monitorada->fresh()->etapa_crm);

        // 3. CRM: Avançar etapa para "Em Análise"
        $this->crmService->atualizarEtapa($licitacao->id, 'em_analise');
        $this->assertEquals('em_analise', $licitacao->fresh()->etapa_crm);

        // 4. Proposta: Criar Proposta
        $dadosProposta = [
            'licitacao_id' => $licitacao->id,
            'status' => 'rascunho',
            'valor_total' => 5000.00,
            'itens' => [
                ['descricao' => 'Serviço XPTO', 'quantidade' => 1, 'valor_unitario' => 5000.00]
            ]
        ];
        
        $proposta = $this->propostaService->criarProposta($dadosProposta);
        $this->assertDatabaseHas('propostas', ['id' => $proposta->id]);

        // 5. Proposta: Enviar Proposta
        // EXPECTATIVA: Ao enviar a proposta, a etapa do CRM deve mudar automaticamente para 'proposta_enviada'
        $this->propostaService->enviarProposta($proposta);
        
        $this->assertEquals('enviada', $proposta->fresh()->status);
        $this->assertEquals('proposta_enviada', $licitacao->fresh()->etapa_crm, 'A etapa do CRM deveria atualizar para proposta_enviada ao enviar a proposta.');

        // 6. Proposta: Ganhar Proposta
        // EXPECTATIVA: Ao ganhar, etapa CRM -> 'resultado' e Licitação Status -> 'concluida' (ou similar)
        $this->propostaService->marcarComoGanha($proposta);

        $this->assertEquals('ganhou', $proposta->fresh()->status);
        $this->assertEquals('resultado', $licitacao->fresh()->etapa_crm, 'A etapa do CRM deveria atualizar para resultado ao ganhar a proposta.');
    }
}
