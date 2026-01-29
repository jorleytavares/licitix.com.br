<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFaturamentoRequest;
use App\Http\Requests\StoreRecebimentoRequest;
use App\Services\FinanceiroService;
use Illuminate\Http\Request;

class FinanceiroController extends Controller
{
    protected $financeiroService;

    public function __construct(FinanceiroService $financeiroService)
    {
        $this->financeiroService = $financeiroService;
    }

    /**
     * Listagem financeira (Contratos e Faturamentos).
     */
    public function index()
    {
        $contratos = $this->financeiroService->listarContratos();
        $contasReceber = $this->financeiroService->listarContasReceber();

        return view('financeiro.index', compact('contratos', 'contasReceber'));
    }

    public function detalhes($id)
    {
        // MVP: Redirecionar para index
        return redirect()->route('financeiro.index');
    }

    /**
     * Registrar um novo faturamento (Nota Fiscal).
     */
    public function registrarFaturamento(StoreFaturamentoRequest $request, $id)
    {
        $proposta = \App\Models\Proposta::findOrFail($id);

        $this->financeiroService->registrarFaturamento($proposta, $request->validated());

        return back()->with('success', 'Faturamento registrado com sucesso!');
    }

    /**
     * Registrar recebimento de um faturamento.
     */
    public function registrarRecebimento(StoreRecebimentoRequest $request, $id)
    {
        // Garante que o faturamento pertence a uma proposta da empresa atual (via whereHas e escopo global)
        $faturamento = \App\Models\Faturamento::whereHas('proposta')->findOrFail($id);

        $this->financeiroService->registrarRecebimento($faturamento, $request->validated());

        return back()->with('success', 'Recebimento registrado!');
    }
}
