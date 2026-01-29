<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePropostaRequest;
use App\Http\Requests\UpdatePropostaRequest;
use App\Http\Requests\SalvarSimulacaoRequest;
use App\Services\PropostaService;
use Illuminate\Http\Request;

class PropostasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // EmpresaScope já aplica o filtro por empresa_id automaticamente
        $propostas = \App\Models\Proposta::with('licitacao')->get();
        return view('propostas.index', compact('propostas'));
    }

    public function create()
    {
        $licitacoes = \App\Models\Licitacao::all();
        return view('propostas.create', compact('licitacoes'));
    }

    public function criar()
    {
        return $this->create();
    }

    public function store(StorePropostaRequest $request, PropostaService $service)
    {
        $service->criarProposta($request->validated());

        return redirect()->route('propostas.index')->with('success', 'Proposta criada com sucesso!');
    }

    public function show($id)
    {
        $proposta = \App\Models\Proposta::with('itens')->findOrFail($id);
        return view('propostas.show', compact('proposta'));
    }

    public function detalhes($id)
    {
        return $this->show($id);
    }

    public function edit($id)
    {
        $proposta = \App\Models\Proposta::with('itens')->findOrFail($id);
        return view('propostas.edit', compact('proposta'));
    }

    public function editar($id)
    {
        return $this->edit($id);
    }

    public function update(UpdatePropostaRequest $request, $id)
    {
        $proposta = \App\Models\Proposta::findOrFail($id);

        $proposta->update($request->validated());

        return redirect()->route('propostas.index')->with('success', 'Proposta atualizada com sucesso!');
    }

    public function destroy($id)
    {
        $proposta = \App\Models\Proposta::findOrFail($id);
        $proposta->delete();

        return redirect()->route('propostas.index')->with('success', 'Proposta excluída com sucesso!');
    }
    /**
     * Simula o lucro da proposta.
     */
    public function simularLucro($id, \App\Services\CalculadoraLucroService $calculadora)
    {
        $proposta = \App\Models\Proposta::with('itens')->findOrFail($id);
        $simulacao = $calculadora->calcular($proposta);
        $itensCatalogo = \App\Models\ItemCatalogo::all();

        return view('propostas.simular', compact('proposta', 'simulacao', 'itensCatalogo'));
    }

    /**
     * Salva a simulação de lucro da proposta.
     */
    public function salvarSimulacao(SalvarSimulacaoRequest $request, $id, PropostaService $service)
    {
        $proposta = \App\Models\Proposta::findOrFail($id);

        $service->salvarSimulacao($proposta, $request->validated());

        return redirect()->route('propostas.simular', $proposta->id)->with('success', 'Simulação salva com sucesso!');
    }

    public function enviar($id)
    {
        $proposta = \App\Models\Proposta::findOrFail($id);
        $proposta->update(['status' => 'enviada']);
        return back()->with('success', 'Proposta enviada com sucesso!');
    }

    public function gerarPdf($id)
    {
        $proposta = \App\Models\Proposta::with(['itens', 'licitacao'])->findOrFail($id);
        return view('propostas.print', compact('proposta'));
    }

    public function marcarComoGanha($id, PropostaService $service)
    {
        $proposta = \App\Models\Proposta::findOrFail($id);
        $service->marcarComoGanha($proposta);
        return back()->with('success', 'Proposta marcada como GANHA! Contrato disponível no módulo Financeiro.');
    }

    public function marcarComoPerdida($id, PropostaService $service)
    {
        $proposta = \App\Models\Proposta::findOrFail($id);
        $service->marcarComoPerdida($proposta);
        return back()->with('success', 'Proposta marcada como PERDIDA.');
    }

    public function gerarDocx($id, \App\Services\PropostaDocGeneratorService $generator)
    {
        $proposta = \App\Models\Proposta::with(['itens', 'empresa', 'licitacao'])->findOrFail($id);
        
        try {
            $path = $generator->gerarDocx($proposta);
            return response()->download($path)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao gerar documento: ' . $e->getMessage());
        }
    }

    public function storeDocumento(Request $request, $id, PropostaService $service)
    {
        $request->validate([
            'tipo' => 'required|string',
            'arquivo' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,zip,jpg,png|max:10240', // 10MB
        ]);

        $proposta = \App\Models\Proposta::findOrFail($id);
        
        $service->adicionarDocumento($proposta, $request->only('tipo'), $request->file('arquivo'));

        return back()->with('success', 'Documento adicionado com sucesso!');
    }

    public function destroyDocumento($id, PropostaService $service)
    {
        $documento = \App\Models\PropostaDocumento::findOrFail($id);
        $service->removerDocumento($documento);

        return back()->with('success', 'Documento removido com sucesso!');
    }
}
