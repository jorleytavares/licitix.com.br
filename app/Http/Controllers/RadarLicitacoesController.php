<?php

namespace App\Http\Controllers;

use App\Http\Requests\SalvarRadarRequest;
use App\Services\RadarService;
use Illuminate\Http\Request;

class RadarLicitacoesController extends Controller
{
    protected $radarService;

    public function __construct(RadarService $radarService)
    {
        $this->radarService = $radarService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $licitacoes = $this->radarService->listar($request->all());

        return view('radar.index', compact('licitacoes'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $licitacao = $this->radarService->detalhar($id);
        return view('radar.show', compact('licitacao'));
    }

    /**
     * Salvar configurações de busca como Radar.
     */
    public function salvarRadar(SalvarRadarRequest $request)
    {
        $this->radarService->salvarConfiguracaoRadar($request->validated());

        return back()->with('success', 'Radar salvo com sucesso!');
    }

    public function creating()
    {
        abort(404);
    }

    public function store()
    {
        abort(404);
    }

    /**
     * Placeholder para criar proposta.
     */
    public function criarProposta($id)
    {
        $licitacao = $this->radarService->prepararParaProposta($id);

        if (!$licitacao) {
            abort(404);
        }

        // Recupera itens selecionados via GET (se houver)
        $itensSelecionados = request()->input('itens_selecionados', []);

        return view('radar.criar-proposta', compact('licitacao', 'itensSelecionados'));
    }
}
