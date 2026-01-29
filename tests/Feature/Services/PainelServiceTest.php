<?php

namespace Tests\Feature\Services;

use App\Models\Empresa;
use App\Models\Licitacao;
use App\Models\User;
use App\Services\CrmService;
use App\Services\PainelService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PainelServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $empresa;
    protected $painelService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->empresa = Empresa::factory()->create();
        $this->user = User::factory()->create(['empresa_id' => $this->empresa->id]);
        $this->actingAs($this->user);

        $this->painelService = app(PainelService::class);
    }

    public function test_indicadores_funil_crm_refletem_etapas_corretas()
    {
        // Cria licitações em diferentes etapas usando as chaves corretas do CrmService
        Licitacao::factory()->create(['empresa_id' => $this->empresa->id, 'monitorada' => true, 'etapa_crm' => 'interesse']);
        Licitacao::factory()->create(['empresa_id' => $this->empresa->id, 'monitorada' => true, 'etapa_crm' => 'em_analise']);
        Licitacao::factory()->create(['empresa_id' => $this->empresa->id, 'monitorada' => true, 'etapa_crm' => 'preparacao']);
        Licitacao::factory()->create(['empresa_id' => $this->empresa->id, 'monitorada' => true, 'etapa_crm' => 'proposta_enviada']);
        Licitacao::factory()->create(['empresa_id' => $this->empresa->id, 'monitorada' => true, 'etapa_crm' => 'resultado']);

        $indicadores = $this->painelService->indicadores();
        $funil = $indicadores['funil'];

        // Assert que as chaves esperadas existem e têm contagem correta
        $this->assertEquals(1, $funil['interesse'] ?? 0, 'Falha na contagem de Interesse');
        $this->assertEquals(1, $funil['em_analise'] ?? 0, 'Falha na contagem de Em Análise');
        $this->assertEquals(1, $funil['preparacao'] ?? 0, 'Falha na contagem de Preparação');
        $this->assertEquals(1, $funil['proposta_enviada'] ?? 0, 'Falha na contagem de Proposta Enviada');
        $this->assertEquals(1, $funil['resultado'] ?? 0, 'Falha na contagem de Resultado');
    }
}
