<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportarArquivoRequest;
use App\Services\ImportService;

class ImportacaoController extends Controller
{
    protected ImportService $importService;

    public function __construct(ImportService $importService)
    {
        $this->importService = $importService;
    }

    public function index()
    {
        return view('importacao.index');
    }

    public function importarCatalogo(ImportarArquivoRequest $request)
    {
        try {
            $result = $this->importService->importarCatalogo($request->file('arquivo_catalogo'));

            $msg = "Importação concluída! {$result['count']} itens processados.";
            if (count($result['errors']) > 0) {
                $msg .= " Alguns erros ocorreram: " . implode(' | ', array_slice($result['errors'], 0, 5));
            }

            return redirect()->route('importacao.index')->with('success', $msg);

        } catch (\Exception $e) {
            return back()->with('error', 'Erro crítico na importação: ' . $e->getMessage());
        }
    }

    public function importarLicitacoes(ImportarArquivoRequest $request)
    {
        try {
            $result = $this->importService->importarLicitacoes($request->file('arquivo_licitacao'));

            $msg = "Importação concluída! {$result['count']} licitações processadas.";
            if (count($result['errors']) > 0) {
                $msg .= " Alguns erros ocorreram: " . implode(' | ', array_slice($result['errors'], 0, 5));
            }

            return redirect()->route('importacao.index')->with('success', $msg);

        } catch (\Exception $e) {
            return back()->with('error', 'Erro crítico na importação: ' . $e->getMessage());
        }
    }
}
