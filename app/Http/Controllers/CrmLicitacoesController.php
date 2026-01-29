<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlterarEtapaRequest;
use App\Services\CrmService;
use Illuminate\Http\Request;

class CrmLicitacoesController extends Controller
{
    protected $crmService;

    public function __construct(CrmService $crmService)
    {
        $this->crmService = $crmService;
    }

    /**
     * Exibe o pipeline de vendas/licitações.
     */
    public function index()
    {
        $pipeline = $this->crmService->listarPipeline();
        $etapas = CrmService::ETAPAS;

        return view('crm.index', compact('pipeline', 'etapas'));
    }

    /**
     * Adiciona uma licitação ao CRM (Monitorar).
     */
    public function monitorar(Request $request, $id)
    {
        $licitacao = $this->crmService->monitorarLicitacao($id);
        
        if ($licitacao) {
            return back()->with('success', 'Licitação adicionada ao CRM!');
        }
        
        return back()->with('error', 'Não foi possível importar os dados da licitação para monitoramento.');
    }

    /**
     * Altera a etapa de uma licitação no CRM.
     */
    public function alterarEtapa(AlterarEtapaRequest $request, $id)
    {
        $this->crmService->atualizarEtapa($id, $request->etapa);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Etapa atualizada']);
        }

        return back()->with('success', 'Etapa atualizada com sucesso!');
    }
    
    /**
     * Remove do monitoramento.
     */
    public function destroy($id)
    {
        $licitacao = $this->crmService->removerDoMonitoramento($id);
        
        return back()->with('success', 'Licitação removida do CRM.');
    }
}
