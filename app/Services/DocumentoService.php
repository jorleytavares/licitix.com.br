<?php

namespace App\Services;

use App\Models\Documento;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DocumentoService
{
    /**
     * Lista todos os documentos da empresa.
     * 
     * @return Collection
     */
    public function listarDocumentos(): Collection
    {
        return Documento::orderBy('validade', 'asc')->get();
    }

    /**
     * Faz upload e cria registro do documento.
     * 
     * @param array $dados
     * @param UploadedFile $arquivo
     * @return Documento
     */
    public function criarDocumento(array $dados, UploadedFile $arquivo): Documento
    {
        return DB::transaction(function () use ($dados, $arquivo) {
            $path = $arquivo->store('documentos', 'public');

            return Documento::create([
                'nome' => $dados['nome'],
                'tipo' => $dados['tipo'],
                'validade' => $dados['validade'] ?? null,
                'caminho_arquivo' => $path,
            ]);
        });
    }

    /**
     * Remove documento e seu arquivo físico.
     * 
     * @param Documento $documento
     * @return bool|null
     */
    public function deletarDocumento(Documento $documento): ?bool
    {
        return DB::transaction(function () use ($documento) {
            if (Storage::disk('public')->exists($documento->caminho_arquivo)) {
                Storage::disk('public')->delete($documento->caminho_arquivo);
            }
            
            return $documento->delete();
        });
    }
}
