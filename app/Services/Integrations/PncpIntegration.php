<?php

namespace App\Services\Integrations;

use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class PncpIntegration implements IntegrationInterface
{
    protected $baseUrl = 'https://pncp.gov.br/api/consulta/v1/contratacoes/publicacao';

    public function buscarLicitacoes(array $filtros)
    {
        // Define datas: Apenas de 2026 em diante
        $dtFinal = now();
        
        if (!empty($filtros['dataInicial'])) {
            $dtInicial = \Carbon\Carbon::parse($filtros['dataInicial']);
        } else {
            $dtInicial = \Carbon\Carbon::create(2026, 1, 1);
            // Se a data calculada de 30 dias atrás for maior que 01/01/2026, usamos ela
            // Mas como estamos em jan/2026, vamos forçar buscar desde o início do ano
            if (now()->subDays(30)->gt($dtInicial)) {
                $dtInicial = now()->subDays(30);
            }
        }

        // Parâmetros base
        $tamanhoPagina = 50; 
        $modalidadeFiltro = $filtros['modalidade'] ?? null;
        
        // Modalidades padrão para buscar se nenhuma for especificada
        // 6: Pregão, 8: Dispensa, 13: Concorrência, 9: Inexigibilidade
        $modalidades = !empty($modalidadeFiltro) ? [$modalidadeFiltro] : [6, 13, 8, 9];

        $params = [
            'dataInicial' => $dtInicial->format('Ymd'),
            'dataFinal' => $dtFinal->format('Ymd'),
            'tamanhoPagina' => $tamanhoPagina,
        ];

        if (!empty($filtros['uf'])) {
            $params['uf'] = $filtros['uf'];
        }

        if (!empty($filtros['cnpjOrgao'])) {
            $params['cnpjOrgao'] = $filtros['cnpjOrgao'];
        }

        if (!empty($filtros['esferaId'])) {
            $params['esferaId'] = $filtros['esferaId'];
        }

        $allItems = collect([]);
        $isFilteringLocally = (!empty($filtros['busca']) || !empty($filtros['cidade']) || !empty($filtros['situacao']));
        
        // Se estiver filtrando localmente, limitamos a menos páginas para não estourar tempo,
        // mas precisamos iterar modalidades, então reduzimos maxPages para compensar
        $maxPages = $isFilteringLocally ? 5 : 1; 
        
        $totalItensApi = 0;

        try {
            // Loop por modalidades (Exigido pela API)
            foreach ($modalidades as $mod) {
                $params['codigoModalidadeContratacao'] = $mod;

                // Se não estamos filtrando localmente, usamos a página solicitada diretamente
                if (!$isFilteringLocally) {
                    $params['pagina'] = $filtros['page'] ?? 1;
                }

                for ($p = 1; $p <= $maxPages; $p++) {
                    // Se estamos filtrando localmente, iteramos as páginas
                    if ($isFilteringLocally) {
                        $params['pagina'] = $p;
                    }
                    
                    $response = Http::withOptions(['verify' => false])->get($this->baseUrl, $params);
    
                    if (!$response->successful()) {
                        // Se falhar uma modalidade, tenta as outras
                        break;
                    }
    
                    if ($response->successful()) {
                        $data = $response->json();
                        
                        // Soma total de itens de todas as modalidades
                        $totalPaginasApi = $data['totalPaginas'] ?? 1;
                        $totalRecs = $data['totalRecords'] ?? ($totalPaginasApi * $tamanhoPagina);
                        // Apenas somamos ao total geral se for a primeira página do loop local ou se não tiver loop local
                        if (!$isFilteringLocally || $p === 1) {
                             $totalItensApi += $totalRecs;
                        }

                        $pageItems = collect($data['data'] ?? [])->map(function ($item) {
                            // Tenta sequencialContratacao, se não existir tenta sequencialCompra
                            $sequencial = $item['sequencialContratacao'] ?? $item['sequencialCompra'] ?? 0;
                            $ano = $item['anoCompra'] ?? date('Y');
                            $cnpj = $item['orgaoEntidade']['cnpj'] ?? '00000000000000';
                            
                            $dataEncerramento = isset($item['dataEncerramentoProposta']) ? \Carbon\Carbon::parse($item['dataEncerramentoProposta']) : (isset($item['dataVigenciaFim']) ? \Carbon\Carbon::parse($item['dataVigenciaFim']) : null);
                            $dataSessao = isset($item['dataAberturaProposta']) ? \Carbon\Carbon::parse($item['dataAberturaProposta']) : null;
                            
                            // Verifica status vindo da API se disponível
                            $statusApi = $item['situacaoCompraNome'] ?? $item['situacaoNome'] ?? null;
                            
                            $isEncerrada = false;
                            if ($statusApi) {
                                $isEncerrada = in_array(strtoupper($statusApi), ['ENCERRADA', 'HOMOLOGADA', 'REVOGADA', 'FRACASSADA', 'DESERTA', 'ANULADA']);
                            } elseif ($dataEncerramento) {
                                // Se fecha hoje, consideramos aberta para não esconder do usuário
                                $isEncerrada = $dataEncerramento->lt(now()->startOfDay());
                            }

                            return (object) [
                                'id' => $sequencial . '-' . $ano . '-' . $cnpj,
                                'codigo_radar' => 'PNCP-' . $sequencial,
                                'numero_edital' => ($item['numeroCompra'] ?? 'S/N') . '/' . $ano,
                                'objeto' => $item['objetoCompra'] ?? 'Objeto não informado',
                                'informacao_complementar' => $item['informacaoComplementar'] ?? '',
                                'orgao' => $item['orgaoEntidade']['razaoSocial'] ?? 'Órgão Desconhecido',
                                'estado' => $item['unidadeOrgao']['ufSigla'] ?? 'BR',
                                'uf' => $item['unidadeOrgao']['ufSigla'] ?? 'BR',
                                'cidade' => $item['unidadeOrgao']['municipioNome'] ?? '',
                                'data_abertura' => isset($item['dataPublicacaoPncp']) ? \Carbon\Carbon::parse($item['dataPublicacaoPncp']) : now(),
                                'data_sessao' => $dataSessao,
                                'data_encerramento' => $dataEncerramento,
                                'modalidade' => $item['modalidadeNome'] ?? 'Outros',
                                'status' => $isEncerrada ? 'ENCERRADA' : 'ABERTA', 
                                'origem' => 'PNCP',
                                'valor_estimado' => $item['valorTotalEstimado'] ?? $item['valorTotalHomologado'] ?? 0,
                                'link_detalhes' => 'https://pncp.gov.br/app/editais/' . ($item['id'] ?? $sequencial),
                                'link_sistema_origem' => $item['linkSistemaOrigem'] ?? null,
                            ];
                        });

                        $allItems = $allItems->merge($pageItems);

                        // Se a página retornou menos que o tamanho, acabou esta modalidade
                        if (count($pageItems) < $tamanhoPagina) {
                            break;
                        }
                    } else {
                        break;
                    }
                    
                    // Se não estamos filtrando localmente, não fazemos loop de páginas aqui, 
                    // pois a paginação é controlada externamente via parametro 'page'
                    if (!$isFilteringLocally) {
                        break;
                    }
                }
            }


            // Filtragem Local
            $items = $allItems;

            if (!empty($filtros['busca'])) {
                $busca = strtolower($filtros['busca']);
                $items = $items->filter(function ($item) use ($busca) {
                    return str_contains(strtolower($item->objeto), $busca) || 
                           str_contains(strtolower($item->orgao), $busca);
                });
            }

            if (!empty($filtros['uf'])) {
                $uf = strtoupper($filtros['uf']);
                $items = $items->filter(function ($item) use ($uf) {
                    return $item->uf === $uf;
                });
            }

            if (!empty($filtros['cidade'])) {
                $cidade = strtolower($filtros['cidade']);
                $items = $items->filter(function ($item) use ($cidade) {
                    return str_contains(strtolower($item->cidade), $cidade);
                });
            }
            
            if (!empty($filtros['situacao'])) {
                $situacao = strtoupper($filtros['situacao']);
                $items = $items->filter(function ($item) use ($situacao) {
                    // Se o filtro for ABERTA, inclui as que fecham hoje também
                    if ($situacao === 'ABERTA') {
                        return $item->status === 'ABERTA';
                    }
                    return $item->status === $situacao;
                });
            }

            // Ordenação por data (sempre mais recente primeiro)
            $items = $items->sortByDesc('data_abertura')->values();

            // Paginação Manual dos Resultados Filtrados
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $perPage = 15; // Mantém consistente com o controller
            
            if ($isFilteringLocally) {
                // Se filtramos localmente, paginamos a coleção filtrada
                $currentItems = $items->slice(($currentPage - 1) * $perPage, $perPage)->values();
                $total = $items->count();
            } else {
                // Se não filtramos, os itens já são da página correta (mas limitados ao tamanho da página da API)
                // O PNCP retorna 50 por página (definido em $tamanhoPagina)
                // Aqui ajustamos para mostrar todos os itens retornados pela API nesta página
                $currentItems = $items; 
                // Usamos o total da API se disponível, senão estimamos
                $total = isset($totalItensApi) && $totalItensApi > 0 ? $totalItensApi : $items->count(); 
                
                // NOTA: Como a API retorna 50 e o nosso frontend espera 15, isso pode causar confusão visual
                // O ideal seria alinhar o $tamanhoPagina da API com o $perPage do Laravel,
                // ou aceitar que mostraremos 50 itens por página quando não houver filtro local.
                // Vamos optar por alinhar para 15 na requisição API se possível, mas definimos 50 lá em cima.
                // Para simplificar: mostramos tudo que veio (50) nesta página.
                $perPage = $items->count() > 0 ? $items->count() : 15;

                // Garante que o total nunca seja menor que a quantidade de itens exibidos
                if ($total < $items->count()) {
                    $total = $items->count();
                }
            }

            return new LengthAwarePaginator(
                $currentItems,
                $total,
                $perPage,
                $currentPage,
                ['path' => route('radar.index'), 'query' => request()->query()]
            );

        } catch (\Exception $e) {
            // \Log::error('Erro PNCP: ' . $e->getMessage());
        }

        return new LengthAwarePaginator([], 0, 15, 1);
    }

    public function detalharLicitacao(string $id): array
    {
        $parts = explode('-', $id);
        if (count($parts) >= 3) {
            $sequencial = $parts[0];
            $ano = $parts[1];
            $cnpj = $parts[2];
            
            // 1. Tenta endpoint de Contratação (preferido, mais completo)
            // Geralmente usado quando temos sequencialContratacao
            $urlContratacao = "https://pncp.gov.br/api/consulta/v1/contratacoes/{$cnpj}/{$ano}/{$sequencial}";
            
            try {
                $response = Http::withOptions(['verify' => false])->get($urlContratacao);

                if ($response->successful()) {
                    return $this->mapDetalhes($response->json(), $id, $sequencial, $cnpj, $ano, 'contratacao');
                }
            } catch (\Exception $e) {}

            // 2. Se falhar (ex: 404), tenta endpoint de Compra/Edital (Avisos de Licitação)
            // Geralmente usado quando temos apenas sequencialCompra
            $urlCompra = "https://pncp.gov.br/api/consulta/v1/orgaos/{$cnpj}/compras/{$ano}/{$sequencial}";
            
            try {
                $response = Http::withOptions(['verify' => false])->get($urlCompra);

                if ($response->successful()) {
                    return $this->mapDetalhes($response->json(), $id, $sequencial, $cnpj, $ano, 'compra');
                }
            } catch (\Exception $e) {}
        }

        // Fallback final de erro
        return [
            'id' => $id,
            'codigo_radar' => 'PNCP-' . ($parts[0] ?? '0'),
            'numero_edital' => 'N/A',
            'objeto' => 'Não foi possível carregar os detalhes do PNCP (ID inválido ou incompleto). Necessário: sequencial-ano-cnpj.',
            'orgao' => 'Sistema',
            'uf' => 'BR',
            'cidade' => 'Indefinido',
            'data_abertura' => now(),
            'modalidade' => 'Desconhecida',
            'status' => 'ERRO',
            'origem' => 'PNCP',
            'valor_estimado' => 0,
            'items' => [],
            'link_original' => '#'
        ];
    }

    private function mapDetalhes($item, $id, $sequencial, $cnpj, $ano, $tipo) {
        // Busca Itens
        $items = [];
        try {
            $urlItems = "";
            if ($tipo === 'contratacao') {
                $urlItems = "https://pncp.gov.br/api/consulta/v1/contratacoes/{$cnpj}/{$ano}/{$sequencial}/itens";
            } else {
                $urlItems = "https://pncp.gov.br/api/consulta/v1/orgaos/{$cnpj}/compras/{$ano}/{$sequencial}/itens";
            }
            
            $responseItems = Http::withOptions(['verify' => false])->get($urlItems);
            
            if ($responseItems->successful()) {
                $itemsData = $responseItems->json();
                $items = collect($itemsData)->map(function ($i) {
                    return (object) [
                        'id' => $i['numeroItem'],
                        'numero_item' => $i['numeroItem'],
                        'descricao' => $i['descricao'] ?? 'Sem descrição',
                        'quantidade' => $i['quantidade'] ?? 0,
                        'unidade' => $i['unidadeMedida'] ?? 'UN',
                        'valor_referencia' => $i['valorUnitarioEstimado'] ?? 0,
                    ];
                })->toArray();
            }
        } catch (\Exception $e) {}

        // Busca Arquivos
        $arquivos = [];
        try {
            $urlArquivos = "";
            if ($tipo === 'contratacao') {
                $urlArquivos = "https://pncp.gov.br/api/consulta/v1/contratacoes/{$cnpj}/{$ano}/{$sequencial}/arquivos";
            } else {
                $urlArquivos = "https://pncp.gov.br/api/consulta/v1/orgaos/{$cnpj}/compras/{$ano}/{$sequencial}/arquivos";
            }
            
            $responseArquivos = Http::withOptions(['verify' => false])->get($urlArquivos);
            
            if ($responseArquivos->successful()) {
                $arquivosData = $responseArquivos->json();
                $arquivos = collect($arquivosData)->map(function ($a) {
                    return (object) [
                        'id' => $a['id'] ?? uniqid(),
                        'titulo' => $a['titulo'] ?? $a['nomeArquivo'] ?? 'Arquivo Anexo',
                        'url' => $a['url'] ?? $a['uri'] ?? '#', // PNCP retorna url pública
                        'tamanho' => isset($a['tamanho']) ? round($a['tamanho'] / 1024, 2) . ' KB' : 'N/A'
                    ];
                })->toArray();
            }
        } catch (\Exception $e) {}

        // Link Original
        $linkId = $item['id'] ?? $item['numeroControlePNCP'] ?? $sequencial;
        
        // Concatena Informações Complementares ao Objeto se não estiverem presentes
        $objeto = $item['objetoCompra'] ?? 'Detalhes indisponíveis';
        $infoComplementar = $item['informacaoComplementar'] ?? '';
        
        // Mantemos o objeto limpo e passamos infoComplementar separado
        // if (!empty($infoComplementar) && strlen($infoComplementar) > 5 && strpos($objeto, $infoComplementar) === false) {
        //      $objeto .= "\n\nInformações Complementares: " . $infoComplementar;
        // }

        $dataEncerramento = isset($item['dataVigenciaFim']) ? \Carbon\Carbon::parse($item['dataVigenciaFim']) : (isset($item['dataEncerramentoProposta']) ? \Carbon\Carbon::parse($item['dataEncerramentoProposta']) : null);
        $isEncerrada = $dataEncerramento && $dataEncerramento->isPast();

        return [
            'id' => $id,
            'codigo_radar' => 'PNCP-' . $sequencial,
            'numero_edital' => ($item['numeroCompra'] ?? $item['numeroControlePNCP'] ?? 'S/N') . '/' . ($item['anoCompra'] ?? $ano),
            'objeto' => $objeto,
            'informacao_complementar' => $infoComplementar,
            'orgao' => $item['orgaoEntidade']['razaoSocial'] ?? 'Órgão',
            'uf' => $item['unidadeOrgao']['ufSigla'] ?? 'BR',
            'cidade' => $item['unidadeOrgao']['municipioNome'] ?? '',
            'data_abertura' => isset($item['dataPublicacaoPncp']) ? \Carbon\Carbon::parse($item['dataPublicacaoPncp']) : now(),
            'data_encerramento' => $dataEncerramento,
            'modalidade' => $item['modalidadeNome'] ?? 'Outros',
            'status' => $isEncerrada ? 'ENCERRADA' : 'ABERTA',
            'origem' => 'PNCP',
            'descricao' => $objeto,
            'valor_estimado' => $item['valorTotalEstimado'] ?? $item['valorTotalHomologado'] ?? 0,
            'items' => $items,
            'arquivos' => $arquivos,
            'link_sistema_origem' => $item['linkSistemaOrigem'] ?? null,
            'link_original' => 'https://pncp.gov.br/app/editais/' . $linkId
        ];
    }
}
