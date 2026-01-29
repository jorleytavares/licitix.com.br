<?php

namespace App\Services;

use App\Models\ItemCatalogo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class CatalogoService
{
    /**
     * Lista todos os itens do catálogo.
     * 
     * @return Collection
     */
    public function listarItens(): Collection
    {
        return ItemCatalogo::all();
    }

    /**
     * Cria um novo item no catálogo.
     * 
     * @param array $dados
     * @return ItemCatalogo
     */
    public function criarItem(array $dados): ItemCatalogo
    {
        return DB::transaction(function () use ($dados) {
            if (empty($dados['codigo'])) {
                $dados['codigo'] = 'ITEM-' . strtoupper(uniqid());
            }

            return ItemCatalogo::create($dados);
        });
    }

    /**
     * Atualiza um item existente.
     * 
     * @param ItemCatalogo $item
     * @param array $dados
     * @return ItemCatalogo
     */
    public function atualizarItem(ItemCatalogo $item, array $dados): ItemCatalogo
    {
        return DB::transaction(function () use ($item, $dados) {
            $item->update($dados);
            return $item;
        });
    }

    /**
     * Remove um item do catálogo.
     * 
     * @param ItemCatalogo $item
     * @return bool|null
     */
    public function deletarItem(ItemCatalogo $item): ?bool
    {
        return DB::transaction(function () use ($item) {
            return $item->delete();
        });
    }
}
