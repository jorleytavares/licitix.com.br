<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Simulação de Lucro - Proposta #' . $proposta->codigo) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('propostas.salvar-simulacao', $proposta->id) }}" method="POST">
                @csrf
                
                <!-- Parâmetros Globais -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-bold mb-4 border-b pb-2">Parâmetros de Custo (%)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Impostos (%)</label>
                                <input type="number" step="0.01" name="impostos_percentual" value="{{ old('impostos_percentual', $simulacao['parametros']['impostos_percentual']) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Frete (%)</label>
                                <input type="number" step="0.01" name="frete_percentual" value="{{ old('frete_percentual', $simulacao['parametros']['frete_percentual']) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Taxas Extras/Adm (%)</label>
                                <input type="number" step="0.01" name="taxas_extras_percentual" value="{{ old('taxas_extras_percentual', $simulacao['parametros']['taxas_extras_percentual']) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Custos Extras Fixos (R$)</label>
                                <input type="number" step="0.01" name="custos_extras_valor" value="{{ old('custos_extras_valor', $simulacao['parametros']['custos_extras_valor']) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Itens da Proposta -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-bold mb-4 border-b pb-2">Detalhamento por Item</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Qtd</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Venda Unit.</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-blue-600 uppercase tracking-wider">Custo Unit. (R$)</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buscar no Catálogo</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Impostos+Frete+Taxas</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Lucro Est.</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Margem</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($simulacao['itens'] as $index => $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $item['descricao'] }}
                                                <input type="hidden" name="itens[{{ $index }}][id]" value="{{ $item['item_id'] }}">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                                {{ number_format($item['quantidade'], 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                                <div class="flex items-center justify-end">
                                                    <span class="mr-1">R$</span>
                                                    <input type="number" step="0.01" name="itens[{{ $index }}][valor_unitario]" value="{{ $item['valor_unitario'] }}" class="w-32 text-right rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <input type="number" step="0.01" name="itens[{{ $index }}][custo_unitario]" id="custo-{{ $index }}" value="{{ $item['custo_unitario'] }}" class="w-32 text-right rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-left">
                                                <select onchange="atualizarCusto(this, {{ $index }})" class="w-48 text-xs rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                    <option value="">Selecione do Catálogo...</option>
                                                    @foreach($itensCatalogo as $catalogoItem)
                                                        <option value="{{ $catalogoItem->preco_custo }}">
                                                            {{ $catalogoItem->nome }} 
                                                            @if($catalogoItem->marca) ({{ $catalogoItem->marca }}) @endif
                                                            - R$ {{ number_format($catalogoItem->preco_custo, 2, ',', '.') }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                                R$ {{ number_format($item['impostos'] + $item['frete'] + $item['taxas'], 2, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold {{ $item['lucro'] >= 0 ? 'text-green-600' : 'text-red-600' }} text-right">
                                                R$ {{ number_format($item['lucro'], 2, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold {{ $item['margem'] >= 0 ? 'text-green-600' : 'text-red-600' }} text-right">
                                                {{ $item['margem'] }}%
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Resumo Geral -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-bold mb-4 border-b pb-2">Resumo da Operação</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-500">Receita Bruta</p>
                                <p class="text-xl font-bold text-gray-800">R$ {{ number_format($simulacao['totais']['receita_bruta'], 2, ',', '.') }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-500">Custo Produtos</p>
                                <p class="text-xl font-bold text-red-600">R$ {{ number_format($simulacao['totais']['custo_produtos'], 2, ',', '.') }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-500">Despesas (Imp/Frete/Taxas)</p>
                                <p class="text-xl font-bold text-red-600">R$ {{ number_format($simulacao['totais']['impostos'] + $simulacao['totais']['frete'] + $simulacao['totais']['taxas_extras'] + $simulacao['totais']['custos_fixos_extras'], 2, ',', '.') }}</p>
                            </div>
                            <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                                <p class="text-sm text-blue-600 font-bold">Lucro Líquido Estimado</p>
                                <p class="text-2xl font-bold {{ $simulacao['totais']['lucro_estimado'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    R$ {{ number_format($simulacao['totais']['lucro_estimado'], 2, ',', '.') }}
                                </p>
                                <p class="text-sm font-bold {{ $simulacao['totais']['margem_lucro_percentual'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    Margem: {{ $simulacao['totais']['margem_lucro_percentual'] }}%
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end">
                    <a href="{{ route('propostas.index') }}" class="mr-4 text-gray-600 hover:text-gray-900">Voltar</a>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 shadow-lg font-bold">
                        Recalcular e Salvar Simulação
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function atualizarCusto(selectElement, index) {
            const precoCusto = selectElement.value;
            if (precoCusto) {
                const inputCusto = document.getElementById('custo-' + index);
                inputCusto.value = precoCusto;
                // Opcional: Adicionar uma classe visual para indicar que foi atualizado
                inputCusto.classList.add('bg-green-50');
                setTimeout(() => inputCusto.classList.remove('bg-green-50'), 1000);
            }
        }
    </script>
</x-app-layout>