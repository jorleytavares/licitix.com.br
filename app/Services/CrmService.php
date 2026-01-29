<?php

namespace App\Services;

use App\Models\Licitacao;

class CrmService
{
    protected $radarService;

    const ETAPAS = [
        'interesse' => 'Interesse',
        'em_analise' => 'Em Análise',
        'preparacao' => 'Preparação',
        'proposta_enviada' => 'Proposta Enviada',
        'resultado' => 'Resultado'
    ];

    public function __construct(RadarService $radarService)
    {
        $this->radarService = $radarService;
    }

    /**
     * Retorna o pipeline de licitações agrupado por etapa.
     */
    public function listarPipeline()
    {
        // Busca licitações monitoradas OU que tenham propostas criadas
        // Na prática, criar proposta deve setar monitorada = true
        $licitacoes = Licitacao::where('monitorada', true)
            ->orWhereHas('propostas')
            ->with('propostas') // Carregar propostas para mostrar valor/status
            ->get();

        // Agrupar por etapa_crm
        $pipeline = $licitacoes->groupBy(function($item) {
            return $item->etapa_crm ?? 'interesse';
        });

        // Garantir que todas etapas existam mesmo vazias
        foreach (self::ETAPAS as $key => $label) {
            if (!$pipeline->has($key)) {
                $pipeline->put($key, collect());
            }
        }

        // Ordenar etapas conforme a constante
        $pipeline = $pipeline->sortBy(function ($items, $key) {
            return array_search($key, array_keys(self::ETAPAS));
        });

        return $pipeline;
    }

    /**
     * Adiciona uma licitação ao CRM (Monitorar).
     */
    public function monitorarLicitacao($id)
    {
        $licitacao = $this->radarService->prepararParaProposta($id);
        
        if ($licitacao) {
            $licitacao->update([
                'monitorada' => true,
                'etapa_crm' => $licitacao->etapa_crm ?: 'interesse'
            ]);
            return $licitacao;
        }
        
        return null;
    }

    /**
     * Altera a etapa de uma licitação no CRM.
     */
    public function atualizarEtapa($id, $etapa)
    {
        $licitacao = Licitacao::findOrFail($id);
        $licitacao->update(['etapa_crm' => $etapa]);
        return $licitacao;
    }

    /**
     * Remove do monitoramento.
     */
    public function removerDoMonitoramento($id)
    {
        $licitacao = Licitacao::findOrFail($id);
        
        // Se tem proposta, não deleta, só desmonitora
        $licitacao->update(['monitorada' => false]);
        
        return $licitacao;
    }
}
