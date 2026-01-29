<?php

namespace Tests\Feature\Integrations;

use App\Services\Integrations\PncpIntegration;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PncpIntegrationTest extends TestCase
{
    public function test_buscar_licitacoes_realiza_requisicao_http_correta()
    {
        // Mock da resposta da API do PNCP
        Http::fake([
            'pncp.gov.br/*' => Http::response([
                'totalPaginas' => 1,
                'totalRecords' => 1,
                'data' => [
                    [
                        'sequencialContratacao' => 123,
                        'anoCompra' => 2026,
                        'orgaoEntidade' => [
                            'cnpj' => '00000000000191',
                            'razaoSocial' => 'Ministério da Testagem'
                        ],
                        'numeroCompra' => '999',
                        'objetoCompra' => 'Aquisição de Servidores',
                        'informacaoComplementar' => 'Licitação de Teste',
                        'unidadeOrgao' => [
                            'ufSigla' => 'DF',
                            'municipioNome' => 'Brasília'
                        ],
                        'modalidadeNome' => 'Pregão Eletrônico',
                        'dataPublicacaoPncp' => '2026-01-20T09:00:00',
                        'dataEncerramentoProposta' => '2026-02-10T17:00:00',
                        'situacaoCompraNome' => 'Divulgada no PNCP'
                    ]
                ]
            ], 200)
        ]);

        $integration = new PncpIntegration();
        
        // Filtros de busca - ESPECIFICANDO modalidade para evitar loop de 4 chamadas
        $filtros = [
            'dataInicial' => '2026-01-01',
            'uf' => 'DF',
            'modalidade' => 6 // Pregão
        ];

        $resultado = $integration->buscarLicitacoes($filtros);

        // Verifica se houve requisição
        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'pncp.gov.br/api/consulta/v1/contratacoes/publicacao') &&
                   $request['uf'] == 'DF' &&
                   $request['dataInicial'] == '20260101' &&
                   $request['codigoModalidadeContratacao'] == 6;
        });

        // Verifica o retorno formatado
        $this->assertCount(1, $resultado);
        $item = $resultado->first();
        
        $this->assertEquals('Ministério da Testagem', $item->orgao);
        $this->assertEquals('Aquisição de Servidores', $item->objeto);
        $this->assertEquals('DF', $item->estado);
        $this->assertEquals('ABERTA', $item->status); // Baseado na data de encerramento futura
    }

    public function test_detalhar_licitacao_busca_itens_api()
    {
        // Mock sequence para responder sucesso na busca do detalhe (contratação) E depois nos itens
        Http::fake([
            // 1. Mock do detalhe da contratação
            'pncp.gov.br/api/consulta/v1/contratacoes/00000000000191/2026/123' => Http::response([
                'numeroItem' => 123,
                'anoCompra' => 2026,
                'numeroCompra' => '999',
                'objetoCompra' => 'Aquisição de Servidores',
                'informacaoComplementar' => '',
                'orgaoEntidade' => [
                    'cnpj' => '00000000000191',
                    'razaoSocial' => 'Ministério da Testagem'
                ],
                'unidadeOrgao' => [
                    'ufSigla' => 'DF',
                    'municipioNome' => 'Brasília'
                ],
                'modalidadeNome' => 'Pregão',
                'dataPublicacaoPncp' => '2026-01-20T09:00:00',
                'valorTotalEstimado' => 30000.00
            ], 200),
            
            // 2. Mock dos itens
            '*/itens' => Http::response([
                [
                    'numeroItem' => 1,
                    'descricao' => 'Servidor Rack',
                    'quantidade' => 2,
                    'unidadeMedida' => 'UN',
                    'valorUnitarioEstimado' => 15000.00
                ]
            ], 200),
            
            // 3. Mock dos arquivos
            '*/arquivos' => Http::response([], 200)
        ]);

        $integration = new PncpIntegration();
        
        // ID fictício no formato PNCP (sequencial-ano-cnpj)
        $id = '123-2026-00000000000191'; 
        
        $detalhes = $integration->detalharLicitacao($id);
        
        // Converter array para objeto se necessário, pois o método retorna array
        $detalhesObj = (object) $detalhes;

        $this->assertIsArray($detalhes);
        $this->assertEquals('Aquisição de Servidores', $detalhes['objeto']);
        $this->assertNotEmpty($detalhes['items']);
        $this->assertEquals('Servidor Rack', $detalhes['items'][0]->descricao);
    }
}
