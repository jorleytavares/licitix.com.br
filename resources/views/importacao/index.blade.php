<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
            </svg>
            {{ __('Importação de Dados') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Mensagens de Feedback -->
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r shadow-sm flex items-start gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <strong class="font-bold text-green-800">Sucesso!</strong>
                        <p class="text-green-700 text-sm mt-1">{{ session('success') }}</p>
                    </div>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r shadow-sm flex items-start gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <strong class="font-bold text-red-800">Erro!</strong>
                        <p class="text-red-700 text-sm mt-1">{{ session('error') }}</p>
                    </div>
                </div>
            @endif
            @if(session('info'))
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r shadow-sm flex items-start gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <strong class="font-bold text-blue-800">Info:</strong>
                        <p class="text-blue-700 text-sm mt-1">{{ session('info') }}</p>
                    </div>
                </div>
            @endif
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Card Catálogo -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col h-full hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3 mb-4 pb-4 border-b border-gray-100">
                        <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">Catálogo de Itens</h3>
                    </div>

                    <p class="text-gray-600 text-sm mb-6 flex-grow">
                        Importe produtos ou serviços em massa para seu catálogo. O arquivo deve ser <strong>CSV</strong> ou <strong>TXT</strong> (separado por ponto e vírgula).
                    </p>
                    
                    <div class="bg-gray-800 rounded-lg p-4 mb-6 text-xs font-mono text-gray-300 overflow-x-auto shadow-inner">
                        <p class="font-bold text-gray-100 mb-2 border-b border-gray-700 pb-1">Estrutura Esperada (Cabeçalho)</p>
                        <span class="text-green-400">nome</span>; <span class="text-blue-400">codigo</span>; <span class="text-purple-400">preco_custo</span>; <span class="text-yellow-400">unidade_medida</span>...
                        
                        <p class="font-bold text-gray-100 mt-4 mb-2 border-b border-gray-700 pb-1">Exemplo de Dados</p>
                        Parafuso Sextavado;PAR001;0.50;un<br>
                        Cimento CP II;CIM002;35.00;saco
                    </div>

                    <form action="{{ route('importacao.catalogo') }}" method="POST" enctype="multipart/form-data" class="mt-auto">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Selecione o arquivo</label>
                            <input type="file" name="arquivo_catalogo" accept=".csv,.txt" class="block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-indigo-50 file:text-indigo-700
                                hover:file:bg-indigo-100
                                cursor-pointer border border-gray-200 rounded-lg p-1 bg-gray-50
                            " required>
                        </div>
                        
                        <button type="submit" class="w-full flex justify-center items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-4 rounded-xl transition-all shadow-sm hover:shadow active:scale-95">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            Importar Itens
                        </button>
                    </form>
                </div>

                <!-- Card Licitações -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col h-full hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3 mb-4 pb-4 border-b border-gray-100">
                        <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">Licitações Externas</h3>
                    </div>

                    <p class="text-gray-600 text-sm mb-6 flex-grow">
                        Importe avisos de licitação manualmente caso não tenham sido capturados automaticamente pelo Radar.
                    </p>
                    
                    <div class="bg-gray-800 rounded-lg p-4 mb-6 text-xs font-mono text-gray-300 overflow-x-auto shadow-inner">
                        <p class="font-bold text-gray-100 mb-2 border-b border-gray-700 pb-1">Estrutura Esperada (Cabeçalho)</p>
                        <span class="text-green-400">edital</span>; <span class="text-blue-400">orgao</span>; <span class="text-purple-400">objeto</span>; <span class="text-yellow-400">data_abertura</span>...
                        
                        <p class="font-bold text-gray-100 mt-4 mb-2 border-b border-gray-700 pb-1">Exemplo de Dados</p>
                        10/2026;Prefeitura SP;Papel A4;01/02/2026;50000.00
                    </div>
                    
                    <form action="{{ route('importacao.licitacoes') }}" method="POST" enctype="multipart/form-data" class="mt-auto">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Selecione o arquivo</label>
                            <input type="file" name="arquivo_licitacao" accept=".csv,.txt" class="block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-blue-50 file:text-blue-700
                                hover:file:bg-blue-100
                                cursor-pointer border border-gray-200 rounded-lg p-1 bg-gray-50
                            " required>
                        </div>
                        
                        <button type="submit" class="w-full flex justify-center items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded-xl transition-all shadow-sm hover:shadow active:scale-95">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            Importar Licitações
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>