<?php

namespace Tests\Feature\Services;

use App\Models\Empresa;
use App\Models\Licitacao;
use App\Models\RadarConfiguracao;
use App\Models\User;
use App\Services\RadarService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Mockery;
use Tests\TestCase;

class RadarServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Config::set('licitix.integracoes_ativas', true);
    }

    public function test_processar_busca_diaria_cria_licitacoes_para_empresa_correta()
    {
        // Ensure Empresa ID 1 exists for the "Admin/System" check in RadarService
        if (!Empresa::find(1)) {
            Empresa::factory()->create(['id' => 1]);
        }
        
        // 1. Setup: Criar Empresa e Usuário
        $empresa = Empresa::factory()->create();
        $user = User::factory()->create(['empresa_id' => $empresa->id]);

        // 2. Setup: Criar Configuração de Radar
        RadarConfiguracao::create([
            'user_id' => $user->id,
            'termos_busca' => ['computador'],
            'estados' => ['SP'],
            'ativo' => true
        ]);

        // 3. Mock da Integração PNCP
        $mockPncp = Mockery::mock(\App\Services\Integrations\PncpIntegration::class);
        $mockPncp->shouldReceive('buscarLicitacoes')
            ->atLeast()->once() // Espera ser chamado
            ->andReturn(new \Illuminate\Pagination\LengthAwarePaginator([
                (object)[
                    'codigo_radar' => 'TEST-12345',
                    'numero_edital' => '01/2024',
                    'orgao' => 'Prefeitura Teste',
                    'objeto' => 'Aquisição de computadores',
                    'informacao_complementar' => '',
                    'valor_estimado' => 10000.00,
                    'modalidade' => 'Pregão',
                    'estado' => 'SP',
                    'cidade' => 'São Paulo',
                    'data_abertura' => now()->format('Y-m-d H:i:s'),
                    'data_encerramento' => now()->addDays(10)->format('Y-m-d'),
                    'link_sistema_origem' => 'http://teste.com',
                    'link_detalhes' => 'http://teste.com/detalhes'
                ]
            ], 1, 15));

        // Injeta o Mock no Container
        $this->instance(\App\Services\Integrations\PncpIntegration::class, $mockPncp);

        // 4. Executa o Serviço
        $service = app(RadarService::class);
        $total = $service->processarBuscaDiaria();

        // 5. Asserções
        // Verifica se criou a licitação no banco COM O EMPRESA_ID CORRETO
        $this->assertDatabaseHas('licitacoes', [
            'codigo_radar' => 'TEST-12345',
            'empresa_id' => $empresa->id
        ]);
        
        // Verifica se criou a versão para o ADMIN (empresa_id = 1)
        $this->assertDatabaseHas('licitacoes', [
            'codigo_radar' => 'TEST-12345',
            'empresa_id' => 1
        ]);
    }

    public function test_salvar_configuracao_radar()
    {
        $empresa = Empresa::factory()->create();
        $user = User::factory()->create(['empresa_id' => $empresa->id]);
        $this->actingAs($user);

        $service = app(RadarService::class);
        
        $dados = [
            'nome' => 'Busca SP',
            'filtros' => ['uf' => 'SP', 'termo' => 'limpeza']
        ];

        $radar = $service->salvarConfiguracaoRadar($dados);

        $this->assertDatabaseHas('radars', [
            'id' => $radar->id,
            'nome' => 'Busca SP',
            'ativo' => true,
            'empresa_id' => $empresa->id
        ]);
    }
}
