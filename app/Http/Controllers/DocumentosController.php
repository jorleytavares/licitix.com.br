<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocumentoRequest;
use App\Models\Documento;
use App\Services\DocumentoService;
use Illuminate\Http\Request;

class DocumentosController extends Controller
{
    protected $documentoService;

    public function __construct(DocumentoService $documentoService)
    {
        $this->documentoService = $documentoService;
    }

    /**
     * Listagem de documentos da empresa.
     */
    public function index()
    {
        $documentos = $this->documentoService->listarDocumentos();
        return view('documentos.index', compact('documentos'));
    }

    /**
     * Upload de novo documento.
     */
    public function store(StoreDocumentoRequest $request)
    {
        if ($request->hasFile('arquivo')) {
            $this->documentoService->criarDocumento(
                $request->validated(),
                $request->file('arquivo')
            );

            return back()->with('success', 'Documento anexado com sucesso!');
        }

        return back()->with('error', 'Falha no upload.');
    }

    public function download(Documento $documento)
    {
        // EmpresaScope já protege o acesso
        return response()->download(storage_path('app/public/' . $documento->caminho_arquivo));
    }

    public function destroy(Documento $documento)
    {
        $this->documentoService->deletarDocumento($documento);

        return back()->with('success', 'Documento removido com sucesso.');
    }
}
