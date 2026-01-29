<?php

namespace App\Services;

use App\Models\Empresa;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EmpresaService
{
    /**
     * Atualiza os dados da empresa.
     * 
     * @param Empresa $empresa
     * @param array $dados
     * @param UploadedFile|null $logo
     * @return Empresa
     */
    public function atualizarEmpresa(Empresa $empresa, array $dados, ?UploadedFile $logo = null): Empresa
    {
        return DB::transaction(function () use ($empresa, $dados, $logo) {
            if ($logo) {
                // Remove logo anterior se existir (opcional, mas boa prática de limpeza)
                if ($empresa->logo_path && Storage::disk('public')->exists($empresa->logo_path)) {
                    Storage::disk('public')->delete($empresa->logo_path);
                }
                
                $path = $logo->store('logos', 'public');
                $dados['logo_path'] = $path;
            }

            // Remove o campo 'logo' dos dados se ele veio no array (pois já tratamos como $logo ou logo_path)
            unset($dados['logo']);

            $empresa->update($dados);

            return $empresa;
        });
    }
}
