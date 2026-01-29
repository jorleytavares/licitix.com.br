<?php

namespace App\Services;

use App\Models\Licitacao;
use App\Models\Proposta;
use App\Models\FinanceiroLicitacao;

class PainelService
{
    /**
     * Retorna todos os indicadores do painel.
     * 
     * @return array
     */
    public function indicadores(): array
    {
        // ... (código existente anterior) ...
        
        // Busca Inteligente (Smart Widget)
        $smartWidget = ['total' => 0, 'novas_hoje' => 0, 'termos' => [], 'locais' => []];
        
        if (auth()->check()) {
            $config = \App\Models\RadarConfiguracao::where('user_id', auth()->id())->first();
            
            if ($config) {
                $smartWidget['termos'] = $config->termos_busca ?? [];
                $smartWidget['locais'] = array_merge($config->estados ?? [], $config->cidades ?? []);
                
                $query = Licitacao::query();
                
                // Filtro por Termos (OR)
                if (!empty($config->termos_busca)) {
                    $query->where(function($q) use ($config) {
                        foreach ($config->termos_busca as $termo) {
                            $q->orWhere('objeto', 'like', "%{$termo}%")
                              ->orWhere('informacao_complementar', 'like', "%{$termo}%");
                        }
                    });
                }
                
                // Filtro por Locais (AND com o grupo de locais, mas OR entre os locais)
                if (!empty($config->estados) || !empty($config->cidades)) {
                    $query->where(function($q) use ($config) {
                        if (!empty($config->estados)) {
                            $q->orWhereIn('estado', $config->estados);
                        }
                        if (!empty($config->cidades)) {
                            foreach ($config->cidades as $cidade) {
                                $q->orWhere('municipio', 'like', "%{$cidade}%");
                            }
                        }
                    });
                }
                
                // Apenas abertas ou recentes
                $query->whereDate('data_encerramento', '>=', now());
                
                // Clone para contagens diferentes
                $queryHoje = clone $query;
                
                $smartWidget['total'] = $query->count();
                $smartWidget['novas_hoje'] = $queryHoje->whereDate('data_abertura', now())->count();
                
                // Pegar algumas amostras para exibir (opcional, mas bom para UX)
                $smartWidget['amostras'] = $query->latest('data_abertura')->take(3)->get();
            }
        }

        $propostasGanhasList = Proposta::whereIn('status', [
            'ganhou', 
            'contrato_ativo', 
            'faturado', 
            'recebido'
        ])->with('itens')->get();
        
        $lucroEstimado = 0;
        $calculadora = new \App\Services\CalculadoraLucroService();
        
        foreach($propostasGanhasList as $prop) {
            $sim = $calculadora->calcular($prop);
            $lucroEstimado += $sim['totais']['lucro_estimado'];
        }

        // Funil CRM - Apenas licitações monitoradas ou com propostas
        $queryBaseCRM = Licitacao::where(function($q) {
            $q->where('monitorada', true)
              ->orWhereHas('propostas');
        });

        $funil = [];
        foreach (\App\Services\CrmService::ETAPAS as $key => $label) {
            $funil[$key] = (clone $queryBaseCRM)->where('etapa_crm', $key)->count();
        }

        // Agenda (Próximos 7 dias)
        $agenda = collect();
        $start = now();
        $end = now()->addDays(7);

        // 1. Licitações
        $licitacoesAgenda = (clone $queryBaseCRM)
            ->whereBetween('data_abertura', [$start, $end])
            ->get();
        
        foreach($licitacoesAgenda as $lic) {
            $agenda->push([
                'tipo' => 'licitacao',
                'titulo' => 'Abertura: ' . $lic->orgao,
                'data' => $lic->data_abertura,
                'cor' => 'blue',
                'url' => route('radar.detalhes', $lic->id)
            ]);
        }

        // 2. Documentos
        $docsAgenda = \App\Models\Documento::whereBetween('validade', [$start, $end])->get();
        foreach($docsAgenda as $doc) {
            $agenda->push([
                'tipo' => 'documento',
                'titulo' => 'Vencimento: ' . $doc->nome,
                'data' => $doc->validade,
                'cor' => 'red',
                'url' => route('documentos.index')
            ]);
        }

        // 3. Financeiro
        $fatsAgenda = \App\Models\Faturamento::whereBetween('data_vencimento', [$start, $end])
             ->whereHas('proposta')
             ->get();
        foreach($fatsAgenda as $fat) {
            $agenda->push([
                'tipo' => 'financeiro',
                'titulo' => 'Receber: R$ ' . number_format($fat->valor, 2, ',', '.'),
                'data' => $fat->data_vencimento,
                'cor' => 'green',
                'url' => route('financeiro.index')
            ]);
        }

        // Ordenar por data
        $agenda = $agenda->sortBy('data')->values();

        return [
            'agenda' => $agenda,
            'smart_widget' => $smartWidget,
            'licitacoes_abertas' => Licitacao::whereDate(
                'data_encerramento',
                '>=',
                now()
            )->count(),

            'propostas_criadas' => Proposta::count(),

            'propostas_ganhas' => $propostasGanhasList->count(),

            'valor_contratado' => FinanceiroLicitacao::sum('valor_contrato'),

            'valor_recebido' => FinanceiroLicitacao::sum('valor_recebido'),

            'contas_a_receber' => \App\Models\Faturamento::where('status', 'pendente')
                ->whereHas('proposta', function($q) {
                    if (auth()->check()) {
                        $q->where('empresa_id', auth()->user()->empresa_id);
                    }
                })->sum('valor'),

            'inadimplentes' => \App\Models\Faturamento::where('status', 'pendente')
                ->where('data_vencimento', '<', now())
                ->whereHas('proposta', function($q) {
                    if (auth()->check()) {
                        $q->where('empresa_id', auth()->user()->empresa_id);
                    }
                })->sum('valor'),

            'lucro_estimado' => $lucroEstimado,
            
            'funil' => $funil,
            
            'grafico_propostas' => [
                'ganhas' => Proposta::whereIn('status', ['ganhou', 'contrato_ativo', 'faturado', 'recebido'])->count(),
                'perdidas' => Proposta::where('status', 'perdeu')->count(),
                'em_analise' => Proposta::whereIn('status', ['enviada', 'em_analise'])->count(),
                'rascunho' => Proposta::where('status', 'identificada')->count(),
            ],

            'grafico_faturamento' => \App\Models\Faturamento::selectRaw("DATE_FORMAT(data_pagamento, '%m/%Y') as mes, sum(valor) as total")
                ->where('status', 'pago')
                ->where('data_pagamento', '>=', now()->subMonths(6))
                ->whereHas('proposta', function($q) {
                    if (auth()->check()) {
                        $q->where('empresa_id', auth()->user()->empresa_id);
                    }
                })
                ->groupBy('mes')
                ->orderBy('mes')
                ->pluck('total', 'mes')
                ->toArray(),
            
            'resumo_periodo' => [
                'hoje' => [
                    'total' => Licitacao::whereDate('data_abertura', now())->count(),
                    'pregoes' => Licitacao::whereDate('data_abertura', now())
                        ->where('modalidade', 'like', '%Pregão%')
                        ->count(),
                ],
                'semana' => [
                    'total' => Licitacao::whereDate('data_abertura', '>=', now()->startOfWeek())->count(),
                    'pregoes' => Licitacao::whereDate('data_abertura', '>=', now()->startOfWeek())
                        ->where('modalidade', 'like', '%Pregão%')
                        ->count(),
                ],
                'mes' => [
                    'total' => Licitacao::whereDate('data_abertura', '>=', now()->startOfMonth())->count(),
                    'pregoes' => Licitacao::whereDate('data_abertura', '>=', now()->startOfMonth())
                        ->where('modalidade', 'like', '%Pregão%')
                        ->count(),
                ],
            ],
        ];
    }

    /**
     * Retorna contagem de documentos vencidos e vencendo.
     */
    public function alertasDocumentos(): array
    {
        return [
            'vencidos' => \App\Models\Documento::where('validade', '<', now())->count(),
            'vencendo' => \App\Models\Documento::where('validade', '>=', now())
                ->where('validade', '<=', now()->addDays(30))
                ->count(),
        ];
    }
}
