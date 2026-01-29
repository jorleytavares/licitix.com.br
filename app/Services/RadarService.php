<?php

namespace App\Services;

class RadarService
{
    protected $simulator;
    protected $integrations = [];

    public function __construct(RadarSimulatorService $simulator)
    {
        $this->simulator = $simulator;
    }

    /**
     * Salva uma nova configuração de radar.
     */
    public function salvarConfiguracaoRadar(array $dados): \App\Models\Radar
    {
        return \App\Models\Radar::create([
            'nome' => $dados['nome'],
            'filtros' => $dados['filtros'],
            'ativo' => true,
            'empresa_id' => auth()->user()->empresa_id ?? null,
        ]);
    }

    /**
     * Processa a busca diária de oportunidades para todos os usuários ou para a busca geral.
     * Centraliza a lógica que antes estava no Command.
     * 
     * @return int Total de novas licitações encontradas
     */
    public function processarBuscaDiaria()
    {
        $pncp = app(\App\Services\Integrations\PncpIntegration::class);
        $configs = \App\Models\RadarConfiguracao::with('user')->get();
        $countTotal = 0;

        // 1. Busca Baseada em Preferências dos Usuários (Radar Inteligente)
        foreach ($configs as $config) {
            if (!$config->user) {
                continue;
            }
            
            $empresaId = $config->user->empresa_id;
            if (!$empresaId) {
                continue;
            }

            $novasLicitacoes = [];
            $countAntes = $countTotal;

            // Monta filtros baseados na config
            $termos = $config->termos_busca ?? [];
            $locais = array_merge($config->estados ?? [], $config->cidades ?? []);
            
            if (empty($termos) && empty($locais)) {
                continue;
            }

            // Busca por termos
            foreach ($termos as $termo) {
                $filtros = [
                    'busca' => $termo,
                    'periodo' => 'hoje',
                    'situacao' => 'ABERTA',
                    'dataInicial' => now()->format('Y-m-d')
                ];
                
                if (!empty($config->estados)) {
                    foreach ($config->estados as $uf) {
                        $filtros['uf'] = $uf;
                        $novas = $this->executarBuscaPersistente($pncp, $filtros, $empresaId);
                        $countTotal += count($novas);
                        $novasLicitacoes = array_merge($novasLicitacoes, $novas);
                    }
                } else {
                    $novas = $this->executarBuscaPersistente($pncp, $filtros, $empresaId);
                    $countTotal += count($novas);
                    $novasLicitacoes = array_merge($novasLicitacoes, $novas);
                }
            }

            // Busca por locais
            if (empty($termos) && !empty($config->cidades)) {
                foreach ($config->cidades as $cidade) {
                    $filtros = [
                        'cidade' => $cidade,
                        'periodo' => 'hoje',
                        'situacao' => 'ABERTA',
                        'dataInicial' => now()->format('Y-m-d')
                    ];
                    $novas = $this->executarBuscaPersistente($pncp, $filtros, $empresaId);
                    $countTotal += count($novas);
                    $novasLicitacoes = array_merge($novasLicitacoes, $novas);
                }
            }

            if (empty($termos) && empty($config->cidades) && !empty($config->estados)) {
                foreach ($config->estados as $uf) {
                    $filtros = [
                        'uf' => $uf,
                        'periodo' => 'hoje',
                        'situacao' => 'ABERTA',
                        'dataInicial' => now()->format('Y-m-d')
                    ];
                    $novas = $this->executarBuscaPersistente($pncp, $filtros, $empresaId);
                    $countTotal += count($novas);
                    $novasLicitacoes = array_merge($novasLicitacoes, $novas);
                }
            }
            
            // Notificar usuário se houver novas licitações
            if (!empty($novasLicitacoes)) {
                $alerts = [];
                // Limitar a 5 alertas para não poluir o email, o resto vai no link "Ver todas"
                foreach (array_slice($novasLicitacoes, 0, 5) as $licitacao) {
                    $alerts[] = [
                        'titulo' => "Nova Oportunidade: {$licitacao->orgao}",
                        'mensagem' => \Illuminate\Support\Str::limit($licitacao->objeto, 100),
                        'url' => route('radar.detalhes', $licitacao->id)
                    ];
                }
                
                try {
                    $config->user->notify(new \App\Notifications\DailyAlertNotification($alerts));
                } catch (\Exception $e) {
                    \Log::error("Falha ao enviar notificação para usuário {$config->user->id}: " . $e->getMessage());
                }
            }
        }

        // 2. Busca Geral do Dia (Admin/System Cache)
        $filtrosGerais = [
            'situacao' => 'ABERTA',
            'periodo' => 'hoje',
            'dataInicial' => now()->format('Y-m-d')
        ];
        $countTotal += count($this->executarBuscaPersistente($pncp, array_merge($filtrosGerais, ['page' => 1]), 1));
        $countTotal += count($this->executarBuscaPersistente($pncp, array_merge($filtrosGerais, ['page' => 2]), 1));

        return $countTotal;
    }

    /**
     * Helper para executar a busca e persistir os resultados.
     */
    private function executarBuscaPersistente($pncp, $filtros, $empresaId)
    {
        try {
            $filtros['page'] = $filtros['page'] ?? 1;
            $resultado = $pncp->buscarLicitacoes($filtros);
            $items = $resultado->items();
            $novasLicitacoes = [];

            foreach ($items as $item) {
                // Verifica duplicidade para a empresa específica
                $exists = \App\Models\Licitacao::withoutGlobalScopes()
                    ->where('codigo_radar', $item->codigo_radar)
                    ->where('empresa_id', $empresaId)
                    ->exists();
                
                if (!$exists) {
                    $licitacao = \App\Models\Licitacao::create([
                        'codigo_radar' => $item->codigo_radar,
                        'numero_edital' => $item->numero_edital,
                        'orgao' => $item->orgao,
                        'objeto' => $item->objeto,
                        'informacao_complementar' => $item->informacao_complementar,
                        'valor_estimado' => $item->valor_estimado,
                        'modalidade' => $item->modalidade,
                        'status' => 'aberta',
                        'estado' => $item->estado,
                        'municipio' => $item->cidade,
                        'data_abertura' => $item->data_abertura,
                        'data_encerramento' => $item->data_encerramento,
                        'origem_dado' => 'pncp',
                        'link_sistema_origem' => $item->link_sistema_origem,
                        'link_original' => $item->link_detalhes,
                        'empresa_id' => $empresaId
                    ]);
                    $novasLicitacoes[] = $licitacao;
                }
            }

            return $novasLicitacoes;

        } catch (\Exception $e) {
            \Log::error("Erro na busca persistente: " . $e->getMessage());
            return [];
        }
    }

    public function buscarNovasLicitacoes(array $filtros)
    {
        // Se integrações estiverem ativas, busca no PNCP (Real)
        if (config('licitix.integracoes_ativas')) {
            try {
                $pncp = app(\App\Services\Integrations\PncpIntegration::class);
                return $pncp->buscarLicitacoes($filtros);
            } catch (\Exception $e) {
                \Log::error("Erro na busca de novas licitações (API Real): " . $e->getMessage());
                // Em caso de erro, se estivermos em debug, pode cair no simulador ou retornar vazio
            }
        }

        // Se dados simulados estiverem ativos (fallback ou dev)
        if (config('licitix.dados_simulados')) {
             // O simulador retorna uma Collection, precisamos paginar ou adaptar
             $items = $this->simulator->gerarLicitacoes(15);
             return new \Illuminate\Pagination\LengthAwarePaginator($items, 15, 15, 1);
        }

        return new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15, 1);
    }

    public function prepararParaProposta(string|int $id): ?\App\Models\Licitacao
    {
        // Tenta encontrar por ID (inteiro) ou codigo_radar
        $licitacao = \App\Models\Licitacao::where('id', $id)
            ->orWhere('codigo_radar', $id)
            ->first();

        // Se estamos em modo REAL (não simulado) e encontramos uma licitação SIMULADA ou com ITENS SIMULADOS,
        // devemos descartá-la para buscar os dados reais novamente.
        if ($licitacao && !config('licitix.dados_simulados')) {
            $hasSimulatedItems = $licitacao->items()->where('descricao', 'like', '%Item Simulado%')->exists();
            
            if ($licitacao->origem_dado === 'simulado' || $hasSimulatedItems) {
                // Remove os itens e a licitação para forçar recarga da API
                $licitacao->items()->delete();
                $licitacao->delete();
                $licitacao = null;
            }
        }

        // Se já existe e é válida, retorna
        if ($licitacao) {
            return $licitacao;
        }

        // Se não existir no banco, busca na integração
        $dados = $this->detalhar($id);

        // Se falhou em buscar, retorna null
        if (!$dados) {
            return null;
        }

        // Se for um objeto de dados (stdClass) da API, persistimos
        if (!($dados instanceof \App\Models\Licitacao)) {
            // Converte data se necessário
            $dataAbertura = $dados->data_abertura;
            if ($dataAbertura instanceof \DateTime) {
                $dataAbertura = $dataAbertura->format('Y-m-d H:i:s');
            }

            // Determina a origem do dado
            $origem = 'simulado';
            if (isset($dados->origem)) {
                $origem = strtolower($dados->origem);
            } elseif (str_starts_with($id, 'PNCP')) {
                $origem = 'pncp';
            }

            $licitacao = \App\Models\Licitacao::create([
                'codigo_radar' => $dados->codigo_radar ?? $id,
                'numero_edital' => $dados->numero_edital,
                'orgao' => $dados->orgao,
                'objeto' => $dados->objeto,
                'informacao_complementar' => $dados->informacao_complementar ?? null,
                'modalidade' => $dados->modalidade ?? 'Não informada',
                'estado' => $dados->estado ?? $dados->uf ?? null,
                'municipio' => $dados->municipio ?? $dados->cidade ?? null,
                'data_abertura' => $dataAbertura,
                'data_encerramento' => $dados->data_encerramento ?? null,
                'valor_estimado' => $dados->valor_estimado ?? 0,
                'origem_dado' => $origem,
                'link_sistema_origem' => $dados->link_sistema_origem ?? null,
                'link_original' => $dados->link_detalhes ?? null,
                'empresa_id' => auth()->user()->empresa_id ?? 1
            ]);

            // Salva Itens se houver
            if (!empty($dados->items)) {
                foreach ($dados->items as $item) {
                    $itemObj = (object) $item;
                    $licitacao->items()->create([
                        'numero_item' => $itemObj->numero_item,
                        'descricao' => $itemObj->descricao,
                        'quantidade' => $itemObj->quantidade,
                        'unidade' => $itemObj->unidade,
                        'valor_referencia' => $itemObj->valor_referencia,
                    ]);
                }
            }

            // Salva Arquivos se houver
            if (!empty($dados->arquivos)) {
                foreach ($dados->arquivos as $arquivo) {
                    $arqObj = (object) $arquivo;
                    $licitacao->arquivos()->create([
                        'titulo' => $arqObj->titulo,
                        'url' => $arqObj->url,
                        'tamanho' => $arqObj->tamanho ?? null,
                    ]);
                }
            }
        } else {
            // Se detalhar retornou um Model, usamos ele
            $licitacao = $dados;
        }

        return $licitacao;
    }

    public function detalhar($id)
    {
        // Quando o ID é numérico, priorizamos o banco local
        if (is_numeric($id)) {
            $licitacao = \App\Models\Licitacao::with('items')->find($id);

            // Se encontrou no banco
            if ($licitacao) {
                // Se a licitação tem codigo_radar mas não tem itens ou links, tenta atualizar da API
                if (config('licitix.integracoes_ativas') && 
                    $licitacao->codigo_radar && 
                    ($licitacao->items->isEmpty() || empty($licitacao->link_original))) {
                    
                    try {
                        // Tenta buscar na API usando o código do radar (ID original)
                        $pncp = new \App\Services\Integrations\PncpIntegration();
                        $dadosApi = $pncp->detalharLicitacao($licitacao->codigo_radar);

                        if ($dadosApi) {
                            // Atualiza links
                            $licitacao->update([
                                'link_sistema_origem' => $dadosApi['link_sistema_origem'] ?? null,
                                'link_original' => $dadosApi['link_original'] ?? null,
                            ]);

                            // Se não tinha itens e a API retornou itens, salva eles
                            if ($licitacao->items->isEmpty() && !empty($dadosApi['items'])) {
                                foreach ($dadosApi['items'] as $item) {
                                    // items vem como array de objetos do PncpIntegration
                                    $itemObj = (object) $item;
                                    $licitacao->items()->create([
                                        'numero_item' => $itemObj->numero_item,
                                        'descricao' => $itemObj->descricao,
                                        'quantidade' => $itemObj->quantidade,
                                        'unidade' => $itemObj->unidade,
                                        'valor_referencia' => $itemObj->valor_referencia,
                                    ]);
                                }
                            }

                            // Se não tinha arquivos e a API retornou arquivos, salva eles
                            if ($licitacao->arquivos()->doesntExist() && !empty($dadosApi['arquivos'])) {
                                foreach ($dadosApi['arquivos'] as $arquivo) {
                                    $arqObj = (object) $arquivo;
                                    $licitacao->arquivos()->create([
                                        'titulo' => $arqObj->titulo,
                                        'url' => $arqObj->url,
                                        'tamanho' => $arqObj->tamanho ?? null,
                                    ]);
                                }
                            }

                            $licitacao->load(['items', 'arquivos']); // Recarrega relacionamentos
                        }
                    } catch (\Exception $e) {
                        // Silencia erro de API para não quebrar a view, mantém dados locais
                        \Log::warning("Erro ao tentar atualizar licitação {$id} via API: " . $e->getMessage());
                    }
                }
                return $licitacao;
            }
            
            // Se não encontrou por ID numérico (pode ter sido deletado ou erro), falha
            abort(404);
        }

        if (config('licitix.integracoes_ativas')) {
            // Detecção de integração pelo prefixo do ID
            if (str_starts_with($id, 'CN-')) {
                $integration = new \App\Services\Integrations\ComprasNetIntegration();
                return (object) $integration->detalharLicitacao($id);
            }
            
            // Padrão PNCP (IDs compostos ou numéricos)
            $pncp = new \App\Services\Integrations\PncpIntegration();
            return (object) $pncp->detalharLicitacao($id);
        }

        return \App\Models\Licitacao::with('items')->findOrFail($id);
    }

    public function listar(array $filtros = [])
    {
        // Se houver filtros locais específicos (periodo, smart_filter) ou se a fonte não for externa,
        // devemos priorizar a busca no banco de dados local.
        // O PainelService usa Licitacao::count() localmente, então a listagem deve refletir isso.
        $usaFiltroLocal = isset($filtros['periodo']) || isset($filtros['smart_filter']);
        
        if (config('licitix.integracoes_ativas') && !$usaFiltroLocal) {
            $fonte = $filtros['fonte'] ?? 'PNCP';
            
            // Lógica de Seleção de Fonte
            if ($fonte === 'ComprasNet') {
                $integration = new \App\Services\Integrations\ComprasNetIntegration();
            } elseif ($fonte === 'BB') {
                // Banco do Brasil via PNCP
                $integration = new \App\Services\Integrations\PncpIntegration();
                $filtros['cnpjOrgao'] = '00000000000191'; // CNPJ do Banco do Brasil
            } else {
                // Padrão PNCP
                $integration = new \App\Services\Integrations\PncpIntegration();
            }
            
            return $integration->buscarLicitacoes($filtros);
        }

        return \App\Models\Licitacao::query()
            ->when($filtros['estado'] ?? null, fn($q, $v) => $q->where('estado', $v))
            ->when($filtros['periodo'] ?? null, function($q, $v) {
                if ($v === 'hoje') {
                    $q->whereDate('data_abertura', now());
                } elseif ($v === 'semana') {
                    $q->whereBetween('data_abertura', [now()->startOfWeek(), now()->endOfWeek()]);
                } elseif ($v === 'mes') {
                    $q->whereMonth('data_abertura', now()->month)->whereYear('data_abertura', now()->year);
                }
            })
            ->when($filtros['smart_filter'] ?? null, function($q) {
                if (auth()->check()) {
                    $config = \App\Models\RadarConfiguracao::where('user_id', auth()->id())->first();
                    if ($config) {
                        // Filtro por Termos (OR)
                        if (!empty($config->termos_busca)) {
                            $q->where(function($subQ) use ($config) {
                                foreach ($config->termos_busca as $termo) {
                                    $subQ->orWhere('objeto', 'like', "%{$termo}%")
                                         ->orWhere('informacao_complementar', 'like', "%{$termo}%");
                                }
                            });
                        }
                        // Filtro por Locais
                        if (!empty($config->estados) || !empty($config->cidades)) {
                            $q->where(function($subQ) use ($config) {
                                if (!empty($config->estados)) {
                                    $subQ->orWhereIn('estado', $config->estados);
                                }
                                if (!empty($config->cidades)) {
                                    foreach ($config->cidades as $cidade) {
                                        $subQ->orWhere('municipio', 'like', "%{$cidade}%");
                                    }
                                }
                            });
                        }
                    }
                    
                    // Contagem de hoje no widget também deve ser consistente
                    if (request('periodo') === 'hoje') {
                         $q->whereDate('data_abertura', now());
                    }
                }
            })
            ->latest('data_abertura')
            ->paginate(15);
    }
}
