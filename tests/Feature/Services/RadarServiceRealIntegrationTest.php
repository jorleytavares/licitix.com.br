<?php

namespace Tests\Feature\Services;

use App\Services\RadarService;
use App\Services\RadarSimulatorService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RadarServiceRealIntegrationTest extends TestCase
{
    public function test_listar_usa_integracao_pncp_quando_configurado_como_real()
    {
        // 1. Configurar para usar API Real (Integracoes Ativas)
        Config::set('licitix.integracoes_ativas', true);
        Config::set('licitix.dados_simulados', false);

        // 2. Mock da resposta HTTP do PNCP
        Http::fake([
            'pncp.gov.br/*' => Http::response([
                'totalPaginas' => 1,
                'totalRecords' => 1,
                'data' => [
                    [
                        'sequencialContratacao' => 999,
                        'anoCompra' => 2026,
                        'orgaoEntidade' => [
                            'cnpj' => '12345678000199',
                            'razaoSocial' => 'Prefeitura de Teste Real'
                        ],
                        'numeroCompra' => '100/2026',
                        'objetoCompra' => 'Objeto Real da API',
                        'unidadeOrgao' => [
                            'ufSigla' => 'SP',
                            'municipioNome' => 'São Paulo'
                        ],
                        'modalidadeNome' => 'Pregão',
                        'dataPublicacaoPncp' => '2026-01-29T10:00:00',
                        'situacaoCompraNome' => 'Divulgada no PNCP'
                    ]
                ]
            ], 200)
        ]);

        // 3. Instanciar Service (com dependência do simulador, mesmo não usando)
        $service = new RadarService(new RadarSimulatorService());

        // 4. Executar listar sem filtros locais (para forçar ida à API)
        // Adicionando modalidade para evitar loop de 4 chamadas padrão do PncpIntegration
        $resultado = $service->listar(['fonte' => 'PNCP', 'modalidade' => 6]);

        // 5. Verificações
        // Deve retornar um Paginator
        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $resultado);
        
        // Deve conter 1 item
        $this->assertCount(1, $resultado);
        
        // O item deve ter os dados do Mock
        $item = $resultado->first();
        $this->assertEquals('Prefeitura de Teste Real', $item->orgao);
        $this->assertEquals('Objeto Real da API', $item->objeto);
        $this->assertEquals('SP', $item->estado);
        
        // Verifica se a API foi chamada
        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'pncp.gov.br');
        });
    }

    public function test_listar_usa_comprasnet_via_pncp()
    {
        Config::set('licitix.integracoes_ativas', true);
        
        Http::fake([
            'pncp.gov.br/*' => Http::response(['totalPaginas' => 0, 'data' => []], 200)
        ]);

        $service = new RadarService(new RadarSimulatorService());
        
        // Filtro Fonte = ComprasNet
        $service->listar(['fonte' => 'ComprasNet']);

        // Verifica se chamou PNCP com esferaId = 1 (Federal -> ComprasNet)
        Http::assertSent(function ($request) {
            return $request['esferaId'] == '1';
        });
    }

    use \Illuminate\Foundation\Testing\RefreshDatabase;

    public function test_preparar_para_proposta_cria_licitacao_com_dados_reais()
    {
        // Cria ambiente
        $empresa = \App\Models\Empresa::factory()->create();
        $user = \App\Models\User::factory()->create(['empresa_id' => $empresa->id]);
        $this->actingAs($user);

        Config::set('licitix.integracoes_ativas', true);
        
        // Mock das respostas sequenciais do PNCP (Detalhes -> Itens -> Arquivos)
        Http::fake([
            'pncp.gov.br/*' => Http::sequence()
                ->push([
                    'sequencialContratacao' => 888,
                    'anoCompra' => 2026,
                    'orgaoEntidade' => [
                        'cnpj' => '12345678000199',
                        'razaoSocial' => 'Órgão Real Detalhado'
                    ],
                    'numeroCompra' => '200/2026',
                    'objetoCompra' => 'Objeto Detalhado Real',
                    'unidadeOrgao' => [
                        'ufSigla' => 'RJ',
                        'municipioNome' => 'Rio de Janeiro'
                    ],
                    'modalidadeNome' => 'Concorrência',
                    'dataPublicacaoPncp' => '2026-02-01T10:00:00',
                    'situacaoCompraNome' => 'Aberta',
                    'valorTotalEstimado' => 50000.00,
                    'id' => 12345
                ], 200) // 1. Detalhes
                ->push([
                    [
                        'numeroItem' => 1,
                        'descricao' => 'Item 1 Real',
                        'quantidade' => 10,
                        'unidadeMedida' => 'UN',
                        'valorUnitarioEstimado' => 100.00
                    ]
                ], 200) // 2. Itens
                ->push([
                    [
                        'titulo' => 'Edital.pdf',
                        'url' => 'https://pncp.gov.br/arquivo/1',
                        'tamanho' => 1024
                    ]
                ], 200) // 3. Arquivos
        ]);

        $service = new RadarService(new RadarSimulatorService());
        
        // ID fictício no formato que o detalhar espera para PNCP
        // sequencial-ano-cnpj
        $idPncp = '888-2026-12345678000199';
        
        $licitacao = $service->prepararParaProposta($idPncp);

        // Verificações
        $this->assertInstanceOf(\App\Models\Licitacao::class, $licitacao);
        $this->assertEquals('Órgão Real Detalhado', $licitacao->orgao);
        $this->assertEquals('Objeto Detalhado Real', $licitacao->objeto);
        $this->assertEquals(50000.00, $licitacao->valor_estimado);
        $this->assertEquals('RJ', $licitacao->estado);
        
        // Verifica se salvou no banco
        $this->assertDatabaseHas('licitacoes', [
            'codigo_radar' => 'PNCP-888',
            'orgao' => 'Órgão Real Detalhado'
        ]);

        // Verifica se salvou itens
        $this->assertDatabaseHas('licitacao_itens', [
            'licitacao_id' => $licitacao->id,
            'numero_item' => 1,
            'descricao' => 'Item 1 Real'
        ]);

        // Verifica se salvou arquivos
        $this->assertDatabaseHas('licitacao_arquivos', [
            'licitacao_id' => $licitacao->id,
            'titulo' => 'Edital.pdf',
            'url' => 'https://pncp.gov.br/arquivo/1'
        ]);
    }
}
