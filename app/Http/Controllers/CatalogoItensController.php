<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemCatalogoRequest;
use App\Http\Requests\UpdateItemCatalogoRequest;
use App\Models\ItemCatalogo;
use App\Services\CatalogoService;
use Illuminate\Http\Request;

class CatalogoItensController extends Controller
{
    protected $catalogoService;

    public function __construct(CatalogoService $catalogoService)
    {
        $this->catalogoService = $catalogoService;
    }

    public function index()
    {
        $itens = $this->catalogoService->listarItens();
        return view('catalogo.index', compact('itens'));
    }

    public function create()
    {
        return view('catalogo.create');
    }

    public function store(StoreItemCatalogoRequest $request)
    {
        $this->catalogoService->criarItem($request->validated());

        return redirect()->route('catalogo.index')
            ->with('success', 'Item criado com sucesso!');
    }

    public function edit(ItemCatalogo $item)
    {
        return view('catalogo.edit', compact('item'));
    }

    public function update(UpdateItemCatalogoRequest $request, ItemCatalogo $item)
    {
        $this->catalogoService->atualizarItem($item, $request->validated());

        return redirect()->route('catalogo.index')
            ->with('success', 'Item atualizado com sucesso!');
    }

    public function destroy(ItemCatalogo $item)
    {
        $this->catalogoService->deletarItem($item);

        return redirect()->route('catalogo.index')
            ->with('success', 'Item removido com sucesso!');
    }
}
