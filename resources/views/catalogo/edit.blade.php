<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Item: {{ $item->nome }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('catalogo.update', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <h3 class="text-lg font-bold mb-4 border-b pb-2">Informações Básicas</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="codigo">Código Interno</label>
                                <input type="text" name="codigo" id="codigo" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('codigo', $item->codigo) }}">
                                @error('codigo') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="codigo_barras">Código de Barras (EAN/GTIN)</label>
                                <input type="text" name="codigo_barras" id="codigo_barras" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('codigo_barras', $item->codigo_barras ?? '') }}">
                                @error('codigo_barras') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="nome">Nome do Item</label>
                            <input type="text" name="nome" id="nome" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('nome', $item->nome) }}">
                            @error('nome') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="descricao">Descrição Detalhada</label>
                            <textarea name="descricao" id="descricao" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('descricao', $item->descricao) }}</textarea>
                            @error('descricao') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                        </div>

                        <h3 class="text-lg font-bold mb-4 border-b pb-2 mt-6">Dados para Licitação</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="codigo_catmat">CATMAT (Material)</label>
                                <input type="text" name="codigo_catmat" id="codigo_catmat" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('codigo_catmat', $item->codigo_catmat ?? '') }}">
                                @error('codigo_catmat') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="codigo_catser">CATSER (Serviço)</label>
                                <input type="text" name="codigo_catser" id="codigo_catser" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('codigo_catser', $item->codigo_catser ?? '') }}">
                                @error('codigo_catser') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="ncm">NCM</label>
                                <input type="text" name="ncm" id="ncm" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('ncm', $item->ncm ?? '') }}">
                                @error('ncm') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <h3 class="text-lg font-bold mb-4 border-b pb-2 mt-6">Detalhes do Produto</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="marca">Marca</label>
                                <input type="text" name="marca" id="marca" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('marca', $item->marca ?? '') }}">
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="modelo">Modelo</label>
                                <input type="text" name="modelo" id="modelo" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('modelo', $item->modelo ?? '') }}">
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="fornecedor_padrao">Fornecedor Padrão</label>
                                <input type="text" name="fornecedor_padrao" id="fornecedor_padrao" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('fornecedor_padrao', $item->fornecedor_padrao ?? '') }}">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="preco_custo">Preço de Custo (R$)</label>
                                <input type="number" step="0.01" name="preco_custo" id="preco_custo" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('preco_custo', $item->preco_custo) }}">
                                @error('preco_custo') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="unidade_medida">Unidade de Medida</label>
                                <input type="text" name="unidade_medida" id="unidade_medida" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('unidade_medida', $item->unidade_medida) }}">
                                @error('unidade_medida') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-4">
                             <button type="button" onclick="if(confirm('Tem certeza que deseja excluir este item?')) document.getElementById('form-delete').submit();" class="text-red-600 hover:text-red-800 font-bold">
                                Excluir Item
                            </button>

                            <div class="flex items-center">
                                <a href="{{ route('catalogo.index') }}" class="text-gray-600 hover:underline mr-4">Cancelar</a>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    Atualizar Item
                                </button>
                            </div>
                        </div>
                    </form>

                    <form id="form-delete" action="{{ route('catalogo.destroy', $item->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
