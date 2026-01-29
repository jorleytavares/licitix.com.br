<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateEmpresaRequest;
use App\Services\EmpresaService;
use Illuminate\Http\Request;

class ConfiguracoesEmpresaController extends Controller
{
    protected $empresaService;

    public function __construct(EmpresaService $empresaService)
    {
        $this->empresaService = $empresaService;
    }

    public function index()
    {
        $empresa = auth()->user()->empresa;
        return view('configuracoes.empresa', compact('empresa'));
    }

    public function update(UpdateEmpresaRequest $request)
    {
        $empresa = auth()->user()->empresa;

        $this->empresaService->atualizarEmpresa(
            $empresa,
            $request->validated(),
            $request->file('logo')
        );

        return back()->with('success', 'Informações da empresa atualizadas com sucesso!');
    }
}
