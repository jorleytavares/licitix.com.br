<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Criar Proposta para: {{ $licitacao->numero_edital }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('propostas.store') }}" method="POST">
                        @csrf
                        
                        <!-- ID da Licitação (Hidden) -->
                        <input type="hidden" name="licitacao_id" value="{{ $licitacao->id }}">

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Órgão</label>
                            <div class="mt-1 p-2 bg-gray-100 rounded-md">
                                {{ $licitacao->orgao }}
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Objeto</label>
                            <div class="mt-1 p-2 bg-gray-100 rounded-md">
                                {{ $licitacao->objeto }}
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status Inicial</label>
                            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                @foreach(\App\Models\Proposta::STATUSES as $key => $label)
                                    <option value="{{ $key }}" {{ $key == 'identificada' ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="valor_total" class="block text-sm font-medium text-gray-700">Valor Total Estimado (R$)</label>
                            <input type="number" step="0.01" name="valor_total" id="valor_total" value="{{ $licitacao->valor_estimado }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        </div>

                        <!-- Arquivos e Anexos -->
                        @if(isset($licitacao->arquivos) && count($licitacao->arquivos) > 0)
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Arquivos e Anexos (Referência)</h3>
                            <div class="grid grid-cols-1 gap-2 bg-gray-50 p-4 rounded-md border">
                                @foreach($licitacao->arquivos as $arquivo)
                                <div class="flex items-center justify-between bg-white p-3 rounded shadow-sm border border-gray-100">
                                    <div class="flex items-center gap-2 overflow-hidden">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        <div class="min-w-0">
                                            <p class="text-sm font-medium text-gray-800 truncate" title="{{ $arquivo->titulo }}">{{ $arquivo->titulo }}</p>
                                            @if($arquivo->tamanho)
                                            <p class="text-xs text-gray-500">{{ $arquivo->tamanho }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <a href="{{ $arquivo->url }}" target="_blank" class="text-xs font-semibold text-blue-600 hover:text-blue-800 whitespace-nowrap ml-2">
                                        Abrir
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Seleção de Itens -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Selecione os Itens para Cotar</h3>
                            <div class="bg-gray-50 border rounded-md overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-2 text-left w-10">
                                                <input type="checkbox" checked onclick="toggleAll(this)" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            </th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Descrição</th>
                                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Qtd</th>
                                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Valor Ref.</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse($licitacao->items as $item)
                                            @php
                                                // Se vieram itens selecionados da tela anterior, usa essa seleção.
                                                // Caso contrário (acesso direto ou sem seleção), marca todos por padrão.
                                                // Verifica tanto pelo ID do banco quanto pelo numero_item (para casos vindos da API/PNCP)
                                                $isChecked = empty($itensSelecionados) || 
                                                             in_array($item->id, $itensSelecionados) || 
                                                             in_array($item->numero_item, $itensSelecionados);
                                            @endphp
                                            <tr>
                                                <td class="px-4 py-2">
                                                    <input type="checkbox" name="itens_selecionados[]" value="{{ $item->id }}" {{ $isChecked ? 'checked' : '' }} class="item-checkbox rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                </td>
                                                <td class="px-4 py-2 text-sm text-gray-900">{{ $item->numero_item }}</td>
                                                <td class="px-4 py-2 text-sm text-gray-600">{{ $item->descricao }}</td>
                                                <td class="px-4 py-2 text-sm text-gray-600 text-right">{{ number_format($item->quantidade, 0, ',', '.') }}</td>
                                                <td class="px-4 py-2 text-sm text-gray-600 text-right">R$ {{ number_format($item->valor_referencia, 2, ',', '.') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500 bg-yellow-50">
                                                    <div class="flex flex-col items-center justify-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                        </svg>
                                                        <p class="font-medium text-gray-700">Nenhum item importado automaticamente.</p>
                                                        <p class="mt-1">Você pode prosseguir com a criação da proposta e adicionar itens manualmente depois.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">* Desmarque os itens que você não deseja incluir na proposta.</p>
                        </div>

                        <div class="flex items-center justify-between mt-4">
                            <a href="{{ route('radar.detalhes', $licitacao->id) }}" class="text-gray-600 underline">Cancelar</a>
                            
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                Criar Proposta
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleAll(source) {
            checkboxes = document.querySelectorAll('.item-checkbox');
            for(var i=0, n=checkboxes.length;i<n;i++) {
                checkboxes[i].checked = source.checked;
            }
        }
    </script>
</x-app-layout>