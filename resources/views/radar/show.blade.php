<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detalhes da Licitação #{{ $licitacao->id ?? $licitacao->codigo_radar ?? '' }}
            </h2>
            <a href="{{ route('radar.criar-proposta', $licitacao->id ?? $licitacao->codigo_radar) }}" style="background-color: #0B2D5B;" class="text-white px-4 py-2 rounded hover:bg-blue-800 shadow-sm font-bold text-sm">
                Criar Proposta
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-azul-primario">{{ $licitacao->objeto }}</h1>
                            <p class="text-lg text-gray-600">{{ $licitacao->orgao }}</p>
                        </div>
                        <a href="{{ route('radar.criar-proposta', $licitacao->id ?? $licitacao->codigo_radar) }}" style="background-color: #0B2D5B;" class="text-white px-4 py-2 rounded hover:bg-blue-800">
                            Criar Proposta
                        </a>
                    </div>

                    <!-- TABS NAVIGATION -->
                    <div class="mb-6 border-b border-gray-200">
                        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="myTab" role="tablist">
                            <li class="mr-2" role="presentation">
                                <button class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 border-blue-600 text-blue-600" id="resumo-tab" data-tabs-target="#resumo" type="button" role="tab" aria-controls="resumo" aria-selected="true" onclick="openTab(event, 'resumo')">
                                    Resumo e Itens
                                </button>
                            </li>
                            <li class="mr-2" role="presentation">
                                <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="arquivos-tab" data-tabs-target="#arquivos" type="button" role="tab" aria-controls="arquivos" aria-selected="false" onclick="openTab(event, 'arquivos')">
                                    Arquivos e Edital
                                </button>
                            </li>
                            <li class="mr-2" role="presentation">
                                <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="original-tab" data-tabs-target="#original" type="button" role="tab" aria-controls="original" aria-selected="false" onclick="openTab(event, 'original')">
                                    Visualização Original
                                </button>
                            </li>
                        </ul>
                    </div>

                    <!-- TAB CONTENTS -->
                    <div id="myTabContent">
                        
                        <!-- ABA RESUMO (O que já existia) -->
                        <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="resumo" role="tabpanel" aria-labelledby="resumo-tab">
                            
                            @if(isset($licitacao->status) && $licitacao->status == 'ENCERRADA')
                            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm" role="alert">
                                <p class="font-bold">Licitação Encerrada</p>
                                <p>Esta licitação já foi encerrada. Alguns detalhes, como a lista de itens e arquivos, podem não estar mais disponíveis na API.</p>
                            </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                <div class="bg-white p-4 rounded shadow-sm">
                                    <h3 class="font-bold text-gray-700 mb-2">Dados Gerais</h3>
                                    <p><strong>Edital:</strong> {{ $licitacao->numero_edital }}</p>
                                    <p><strong>Modalidade:</strong> {{ $licitacao->modalidade }}</p>
                                    <p><strong>Status:</strong> {{ strtoupper($licitacao->status) }}</p>
                                    <p><strong>Valor Estimado:</strong> R$ {{ number_format($licitacao->valor_estimado, 2, ',', '.') }}</p>
                                </div>
                                <div class="bg-white p-4 rounded shadow-sm">
                                    <h3 class="font-bold text-gray-700 mb-2">Local e Data</h3>
                                    <p><strong>Estado:</strong> {{ $licitacao->uf }}</p>
                                    <p><strong>Cidade:</strong> {{ $licitacao->cidade }}</p>
                                    <p><strong>Abertura:</strong> {{ $licitacao->data_abertura ? $licitacao->data_abertura->format('d/m/Y H:i') : 'Indefinido' }}</p>
                                </div>
                            </div>

                            @if(!empty($licitacao->informacao_complementar))
                            <div class="bg-white p-4 rounded shadow-sm mb-8">
                                <h3 class="font-bold text-gray-700 mb-2">Informações Complementares</h3>
                                <p class="text-gray-600 text-sm whitespace-pre-line">{{ $licitacao->informacao_complementar }}</p>
                            </div>
                            @endif
        
                            <h3 class="text-xl font-bold mb-4">Itens da Licitação</h3>
                            
                            <form action="{{ route('radar.criar-proposta', $licitacao->id ?? $licitacao->codigo_radar) }}" method="GET" id="form-criar-proposta">
                                <div class="overflow-x-auto mb-6 bg-white rounded shadow-sm">
                                    <table class="min-w-full text-sm text-left text-gray-500">
                                        <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                            <tr>
                                                <th class="px-6 py-3 w-10">
                                                    <input type="checkbox" checked onclick="toggleAll(this)" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                </th>
                                                <th class="px-6 py-3">Item</th>
                                                <th class="px-6 py-3">Descrição</th>
                                                <th class="px-6 py-3">Qtd</th>
                                                <th class="px-6 py-3">Unid</th>
                                                <th class="px-6 py-3">Valor Ref.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($licitacao->items as $item)
                                            <tr class="bg-white border-b hover:bg-gray-50 cursor-pointer" onclick="toggleRow(this)">
                                                <td class="px-6 py-4" onclick="event.stopPropagation()">
                                                    <input type="checkbox" name="itens_selecionados[]" value="{{ $item->id ?? $item->numero_item }}" checked class="item-checkbox rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                </td>
                                                <td class="px-6 py-4">{{ $item->numero_item }}</td>
                                                <td class="px-6 py-4">{{ $item->descricao }}</td>
                                                <td class="px-6 py-4">{{ number_format($item->quantidade, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4">{{ $item->unidade }}</td>
                                                <td class="px-6 py-4">R$ {{ $item->valor_referencia ? number_format($item->valor_referencia, 2, ',', '.') : '-' }}</td>
                                            </tr>
                                            @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center bg-gray-50">
                                            <div class="flex flex-col items-center justify-center max-w-2xl mx-auto">
                                                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                                </svg>
                                                <h3 class="text-lg font-medium text-gray-900 mb-2">Itens não estruturados na API</h3>
                                                <p class="text-gray-500 mb-6">O órgão responsável não disponibilizou a lista de itens via API para esta licitação. É necessário consultar o portal de origem.</p>
                                                
                                                <button type="button" onclick="document.getElementById('original-tab').click()" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                    </svg>
                                                    Visualizar Itens e Edital no Portal Original
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                        </tbody>
                                    </table>
                                </div>
        
                                <div class="flex justify-end mt-8 pb-4">
                                    <button type="submit" style="background-color: #0B2D5B;" class="text-white px-6 py-4 rounded-lg hover:bg-blue-800 shadow-xl font-bold text-lg flex items-center gap-3 transition-colors duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Criar Proposta com Itens Selecionados</span>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- ABA ARQUIVOS -->
                        <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="arquivos" role="tabpanel" aria-labelledby="arquivos-tab">
                            <h3 class="text-xl font-bold mb-4">Arquivos e Anexos</h3>
                            @if(isset($licitacao->arquivos) && count($licitacao->arquivos) > 0)
                                <div class="grid grid-cols-1 gap-4">
                                    @foreach($licitacao->arquivos as $arquivo)
                                    <div class="flex items-center justify-between p-4 bg-white rounded shadow-sm border border-gray-200">
                                        <div class="flex items-center gap-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                            <div>
                                                <h4 class="font-bold text-gray-800">{{ $arquivo->titulo }}</h4>
                                                <p class="text-sm text-gray-500">{{ $arquivo->tamanho }}</p>
                                            </div>
                                        </div>
                                        <a href="{{ $arquivo->url }}" target="_blank" class="px-4 py-2 text-sm font-medium text-blue-600 bg-blue-100 rounded hover:bg-blue-200">
                                            Baixar / Visualizar
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center p-8 bg-white rounded shadow-sm">
                                    <p class="text-gray-500 mb-4">Nenhum arquivo listado diretamente na API do PNCP.</p>
                                    @if(isset($licitacao->link_sistema_origem) && !empty($licitacao->link_sistema_origem))
                                        <a href="{{ $licitacao->link_sistema_origem }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                            Buscar Arquivos no Sistema de Origem
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- ABA ORIGINAL -->
                        <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="original" role="tabpanel" aria-labelledby="original-tab">
                             <div class="w-full h-screen bg-white rounded shadow-sm overflow-hidden">
                                @if(isset($licitacao->link_sistema_origem) && !empty($licitacao->link_sistema_origem))
                                    <iframe src="{{ $licitacao->link_sistema_origem }}" class="w-full h-full border-0" title="Visualização Original"></iframe>
                                @elseif(isset($licitacao->link_original))
                                    <iframe src="{{ $licitacao->link_original }}" class="w-full h-full border-0" title="Visualização Original"></iframe>
                                @else
                                    <div class="flex items-center justify-center h-full">
                                        <p class="text-gray-500">Link original indisponível.</p>
                                    </div>
                                @endif
                             </div>
                             <div class="mt-2 text-right">
                                <p class="text-xs text-gray-500">Se o conteúdo não carregar (bloqueio de segurança), <a href="{{ $licitacao->link_sistema_origem ?? $licitacao->link_original ?? '#' }}" target="_blank" class="text-blue-600 underline">clique aqui para abrir em nova janela</a>.</p>
                             </div>
                        </div>

                    </div>
                    
                    <script>
                        // Inicializa Tabs
                        document.addEventListener('DOMContentLoaded', function() {
                            // Mostra a primeira aba por padrão
                            document.getElementById('resumo').classList.remove('hidden');
                        });

                        function openTab(evt, tabName) {
                            var i, tabcontent, tablinks;
                            
                            // Esconde todos os conteudos
                            tabcontent = document.querySelectorAll("[role='tabpanel']");
                            for (i = 0; i < tabcontent.length; i++) {
                                tabcontent[i].classList.add("hidden");
                            }
                            
                            // Remove classe ativa de todos os botoes
                            tablinks = document.querySelectorAll("[role='tab']");
                            for (i = 0; i < tablinks.length; i++) {
                                tablinks[i].classList.remove("border-blue-600", "text-blue-600");
                                tablinks[i].classList.add("border-transparent", "hover:text-gray-600", "hover:border-gray-300");
                                tablinks[i].setAttribute("aria-selected", "false");
                            }
                            
                            // Mostra a aba atual e adiciona classe ativa
                            document.getElementById(tabName).classList.remove("hidden");
                            evt.currentTarget.classList.remove("border-transparent", "hover:text-gray-600", "hover:border-gray-300");
                            evt.currentTarget.classList.add("border-blue-600", "text-blue-600");
                            evt.currentTarget.setAttribute("aria-selected", "true");
                        }
                    </script>
                    
                    <!-- Form Antigo (Removido daqui pois foi movido para dentro da aba Resumo) -->


                    <script>
                        function toggleAll(source) {
                            checkboxes = document.querySelectorAll('.item-checkbox');
                            for(var i=0, n=checkboxes.length;i<n;i++) {
                                checkboxes[i].checked = source.checked;
                            }
                        }
                        
                        function toggleRow(row) {
                            const checkbox = row.querySelector('.item-checkbox');
                            checkbox.checked = !checkbox.checked;
                        }
                    </script>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>