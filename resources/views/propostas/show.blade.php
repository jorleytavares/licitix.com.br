<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Proposta #{{ $proposta->codigo ?? $proposta->id }}
            </h2>
            <span class="px-3 py-1 rounded-full text-sm font-bold 
                {{ $proposta->status === 'ganhou' ? 'bg-green-100 text-green-800' : 
                  ($proposta->status === 'perdeu' ? 'bg-red-100 text-red-800' : 
                  ($proposta->status === 'enviada' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')) }}">
                {{ ucfirst(str_replace('_', ' ', $proposta->status)) }}
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Ações -->
            <div class="bg-white p-4 shadow sm:rounded-lg flex flex-wrap gap-4 items-center justify-between">
                <div class="flex gap-4">
                    <a href="{{ route('propostas.imprimir', $proposta->id) }}" target="_blank" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 flex items-center shadow-sm transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Imprimir / PDF
                    </a>

                    <a href="{{ route('propostas.gerar-docx', $proposta->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex items-center shadow-sm transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Baixar DOCX
                    </a>

                    <a href="{{ route('propostas.simular', $proposta->id) }}" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 flex items-center font-bold shadow-sm transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        Calculadora de Lucro
                    </a>
                </div>

                <div class="flex gap-2">
                    @if($proposta->status === 'identificada' || $proposta->status === 'em_analise')
                        <form action="{{ route('propostas.enviar', $proposta->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 shadow-sm font-bold transition-colors">
                                Enviar Proposta
                            </button>
                        </form>
                    @endif

                    @if($proposta->status === 'enviada')
                        <form action="{{ route('propostas.ganhar', $proposta->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 font-bold shadow-sm transition-colors">
                                Marcar como GANHA
                            </button>
                        </form>

                        <form action="{{ route('propostas.perder', $proposta->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 font-bold shadow-sm transition-colors">
                                Marcar como PERDIDA
                            </button>
                        </form>
                    @endif

                    @if(in_array($proposta->status, ['ganhou', 'contrato_ativo', 'faturado', 'recebido']))
                        <a href="{{ route('financeiro.index') }}" class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 font-bold flex items-center shadow-sm transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                                <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" />
                            </svg>
                            Ir para Financeiro
                        </a>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Coluna Esquerda: Detalhes da Proposta -->
                <div class="md:col-span-2 space-y-6">
                    <!-- Dados da Licitação -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-bold mb-4 text-gray-800 border-b pb-2">Licitação de Referência</h3>
                            @if($proposta->licitacao)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-500">Órgão</p>
                                        <p class="font-medium">{{ $proposta->licitacao->orgao }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Edital</p>
                                        <p class="font-medium">{{ $proposta->licitacao->numero_edital }}</p>
                                    </div>
                                    <div class="md:col-span-2">
                                        <p class="text-sm text-gray-500">Objeto</p>
                                        <p class="font-medium">{{ $proposta->licitacao->objeto }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Local</p>
                                        <p class="font-medium">{{ $proposta->licitacao->cidade }} / {{ $proposta->licitacao->uf }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Abertura</p>
                                        <p class="font-medium">{{ $proposta->licitacao->data_abertura ? $proposta->licitacao->data_abertura->format('d/m/Y H:i') : '-' }}</p>
                                    </div>
                                </div>
                            @else
                                <p class="text-gray-500 italic">Licitação não vinculada ou excluída.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Itens da Proposta -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-bold mb-4 text-gray-800 border-b pb-2">Itens da Proposta</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Descrição</th>
                                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Qtd</th>
                                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">V. Unit.</th>
                                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($proposta->itens as $item)
                                        <tr>
                                            <td class="px-4 py-2 text-sm text-gray-900">{{ $loop->iteration }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-600">{{ $item->descricao }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-600 text-right">{{ number_format($item->quantidade, 0, ',', '.') }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-600 text-right">R$ {{ number_format($item->valor_unitario, 2, ',', '.') }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-900 font-medium text-right">R$ {{ number_format($item->valor_total, 2, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-gray-50">
                                        <tr>
                                            <td colspan="4" class="px-4 py-3 text-right font-bold text-gray-700">Valor Total da Proposta:</td>
                                            <td class="px-4 py-3 text-right font-bold text-blue-700 text-lg">R$ {{ number_format($proposta->valor_total, 2, ',', '.') }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Coluna Direita: Arquivos e Resumo -->
                <div class="space-y-6">
                    <!-- Documentos da Proposta -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-bold mb-4 text-gray-800 border-b pb-2">Documentos da Proposta</h3>
                            
                            <!-- Lista de Documentos -->
                            @if($proposta->documentos->count() > 0)
                                <div class="space-y-3 mb-6">
                                    @foreach($proposta->documentos as $doc)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded border border-gray-200">
                                        <div class="flex items-center gap-3 overflow-hidden">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <div class="min-w-0">
                                                <p class="text-sm font-medium text-gray-700 truncate" title="{{ $doc->nome_original }}">{{ $doc->nome_original }}</p>
                                                <p class="text-xs text-gray-500">{{ $doc->tipo }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <a href="{{ asset('storage/' . $doc->caminho_arquivo) }}" target="_blank" class="text-blue-600 hover:text-blue-800" title="Baixar">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('propostas.documentos.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja remover este documento?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800" title="Remover">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 italic mb-4 text-sm">Nenhum documento anexado.</p>
                            @endif

                            <!-- Form de Upload -->
                            <form action="{{ route('propostas.documentos.store', $proposta->id) }}" method="POST" enctype="multipart/form-data" class="border-t pt-4">
                                @csrf
                                <div class="space-y-3">
                                    <div>
                                        <label for="tipo" class="block text-xs font-medium text-gray-700">Tipo de Documento</label>
                                        <select name="tipo" id="tipo" class="mt-1 block w-full py-1.5 text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                            <option value="Habilitação">Habilitação</option>
                                            <option value="Proposta Técnica">Proposta Técnica</option>
                                            <option value="Proposta Comercial">Proposta Comercial</option>
                                            <option value="Outros">Outros</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="arquivo" class="block text-xs font-medium text-gray-700">Arquivo</label>
                                        <input type="file" name="arquivo" id="arquivo" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                                    </div>
                                    <button type="submit" class="w-full bg-blue-600 text-white px-3 py-1.5 rounded text-sm hover:bg-blue-700 font-medium">
                                        Adicionar Documento
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Arquivos da Licitação -->
                    @if($proposta->licitacao && $proposta->licitacao->arquivos->count() > 0)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-bold mb-4 text-gray-800 border-b pb-2">Arquivos e Editais</h3>
                            <div class="space-y-3">
                                @foreach($proposta->licitacao->arquivos as $arquivo)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded border border-gray-200">
                                    <div class="flex items-center gap-3 overflow-hidden">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        <div class="min-w-0">
                                            <p class="text-sm font-medium text-gray-700 truncate" title="{{ $arquivo->titulo }}">{{ $arquivo->titulo }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ $arquivo->url }}" target="_blank" class="text-blue-600 hover:text-blue-800" title="Baixar">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Resumo Financeiro -->
                     <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-bold mb-4 text-gray-800 border-b pb-2">Resumo Financeiro</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Impostos ({{ $proposta->impostos_percentual }}%)</span>
                                    <span class="font-medium">R$ {{ number_format($proposta->valor_total * ($proposta->impostos_percentual/100), 2, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Custos Extras</span>
                                    <span class="font-medium">R$ {{ number_format($proposta->custos_extras_valor, 2, ',', '.') }}</span>
                                </div>
                                <div class="border-t pt-2 mt-2 flex justify-between font-bold text-green-700">
                                    <span>Lucro Estimado</span>
                                    <span>R$ {{ number_format($proposta->lucro_estimado ?? 0, 2, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Faturamento / Notas Fiscais -->
                    @if(in_array($proposta->status, ['ganhou', 'contrato_ativo', 'faturado', 'recebido']))
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-bold mb-4 text-gray-800 border-b pb-2 flex justify-between items-center">
                                <span>Faturamento</span>
                                <span class="text-xs font-normal bg-green-100 text-green-800 px-2 py-1 rounded">
                                    Faturado: R$ {{ number_format($proposta->faturamentos->sum('valor'), 2, ',', '.') }}
                                </span>
                            </h3>

                            <!-- Lista de Faturas -->
                            @if($proposta->faturamentos->count() > 0)
                                <div class="space-y-3 mb-6">
                                    @foreach($proposta->faturamentos as $fatura)
                                    <div class="p-3 bg-gray-50 rounded border border-gray-200">
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="font-bold text-gray-700">NF {{ $fatura->numero_nf }}</span>
                                            <span class="text-xs font-bold uppercase px-2 py-0.5 rounded {{ $fatura->status == 'pago' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                                {{ ucfirst($fatura->status) }}
                                            </span>
                                        </div>
                                        <div class="flex justify-between text-sm text-gray-600">
                                            <span>Emissão: {{ $fatura->data_emissao ? $fatura->data_emissao->format('d/m/Y') : '-' }}</span>
                                            <span>Venc: {{ $fatura->data_vencimento ? $fatura->data_vencimento->format('d/m/Y') : '-' }}</span>
                                        </div>
                                        <div class="mt-2 text-right font-bold text-gray-800">
                                            R$ {{ number_format($fatura->valor, 2, ',', '.') }}
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 italic mb-4 text-sm">Nenhuma fatura registrada.</p>
                            @endif

                            <!-- Form de Faturamento -->
                            <div x-data="{ open: false }">
                                <button @click="open = !open" type="button" class="w-full bg-green-600 text-white px-3 py-2 rounded text-sm hover:bg-green-700 font-medium flex justify-center items-center gap-2 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Gerar Nova Fatura
                                </button>

                                <div x-show="open" class="mt-4 border-t pt-4" style="display: none;" x-transition>
                                    <form action="{{ route('financeiro.faturar', $proposta->id) }}" method="POST">
                                        @csrf
                                        <div class="space-y-3">
                                            <div>
                                                <label for="numero_nf" class="block text-xs font-medium text-gray-700">Número NF</label>
                                                <input type="text" name="numero_nf" id="numero_nf" class="mt-1 block w-full py-1.5 text-sm border-gray-300 rounded-md shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50" required>
                                            </div>
                                            <div>
                                                <label for="valor" class="block text-xs font-medium text-gray-700">Valor (R$)</label>
                                                <input type="number" step="0.01" name="valor" id="valor" value="{{ number_format($proposta->valor_total - $proposta->faturamentos->sum('valor'), 2, '.', '') }}" class="mt-1 block w-full py-1.5 text-sm border-gray-300 rounded-md shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50" required>
                                            </div>
                                            <div class="grid grid-cols-2 gap-2">
                                                <div>
                                                    <label for="data_emissao" class="block text-xs font-medium text-gray-700">Emissão</label>
                                                    <input type="date" name="data_emissao" id="data_emissao" value="{{ date('Y-m-d') }}" class="mt-1 block w-full py-1.5 text-sm border-gray-300 rounded-md shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50" required>
                                                </div>
                                                <div>
                                                    <label for="data_vencimento" class="block text-xs font-medium text-gray-700">Vencimento</label>
                                                    <input type="date" name="data_vencimento" id="data_vencimento" value="{{ date('Y-m-d', strtotime('+30 days')) }}" class="mt-1 block w-full py-1.5 text-sm border-gray-300 rounded-md shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50" required>
                                                </div>
                                            </div>
                                            <button type="submit" class="w-full bg-green-700 text-white px-3 py-1.5 rounded text-sm hover:bg-green-800 font-bold mt-2 shadow-sm transition-colors">
                                                Confirmar Faturamento
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
