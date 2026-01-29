<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Radar de Licitações') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                
                <!-- SIDEBAR FILTROS -->
                <div class="md:col-span-4 lg:col-span-3">
                    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 sticky top-6">
                        <div class="p-5 border-b border-gray-50">
                            <h3 class="font-bold text-gray-800 flex items-center gap-2 text-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                Filtros
                            </h3>
                            <p class="text-gray-400 text-xs mt-1">Refine sua busca</p>
                        </div>
                        
                        <div class="p-5">
                            <form method="GET" action="{{ route('radar.index') }}">
                                <!-- Busca Texto -->
                                <div class="mb-5">
                                    <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Palavra-chave</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                        <input type="text" name="busca" value="{{ request('busca') }}" placeholder="Ex: Papel, Notebook..." class="pl-10 w-full border-gray-200 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm text-sm transition-all duration-200">
                                    </div>
                                </div>

                                <!-- Estado -->
                                <div class="mb-5">
                                    <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Estado (UF)</label>
                                    <div class="relative">
                                        <select name="uf" class="w-full border-gray-200 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm text-sm appearance-none cursor-pointer" onchange="this.form.submit()">
                                            <option value="">Todos os Estados</option>
                                            <optgroup label="Sudeste">
                                                <option value="SP" {{ request('uf') == 'SP' ? 'selected' : '' }}>São Paulo</option>
                                                <option value="RJ" {{ request('uf') == 'RJ' ? 'selected' : '' }}>Rio de Janeiro</option>
                                                <option value="MG" {{ request('uf') == 'MG' ? 'selected' : '' }}>Minas Gerais</option>
                                                <option value="ES" {{ request('uf') == 'ES' ? 'selected' : '' }}>Espírito Santo</option>
                                            </optgroup>
                                            <optgroup label="Sul">
                                                <option value="PR" {{ request('uf') == 'PR' ? 'selected' : '' }}>Paraná</option>
                                                <option value="SC" {{ request('uf') == 'SC' ? 'selected' : '' }}>Santa Catarina</option>
                                                <option value="RS" {{ request('uf') == 'RS' ? 'selected' : '' }}>Rio Grande do Sul</option>
                                            </optgroup>
                                            <optgroup label="Centro-Oeste">
                                                <option value="DF" {{ request('uf') == 'DF' ? 'selected' : '' }}>Distrito Federal</option>
                                                <option value="GO" {{ request('uf') == 'GO' ? 'selected' : '' }}>Goiás</option>
                                                <option value="MT" {{ request('uf') == 'MT' ? 'selected' : '' }}>Mato Grosso</option>
                                                <option value="MS" {{ request('uf') == 'MS' ? 'selected' : '' }}>Mato Grosso do Sul</option>
                                            </optgroup>
                                            <optgroup label="Nordeste">
                                                <option value="BA" {{ request('uf') == 'BA' ? 'selected' : '' }}>Bahia</option>
                                                <option value="PE" {{ request('uf') == 'PE' ? 'selected' : '' }}>Pernambuco</option>
                                                <option value="CE" {{ request('uf') == 'CE' ? 'selected' : '' }}>Ceará</option>
                                                <option value="RN" {{ request('uf') == 'RN' ? 'selected' : '' }}>Rio Grande do Norte</option>
                                                <option value="PB" {{ request('uf') == 'PB' ? 'selected' : '' }}>Paraíba</option>
                                                <option value="MA" {{ request('uf') == 'MA' ? 'selected' : '' }}>Maranhão</option>
                                                <option value="AL" {{ request('uf') == 'AL' ? 'selected' : '' }}>Alagoas</option>
                                                <option value="SE" {{ request('uf') == 'SE' ? 'selected' : '' }}>Sergipe</option>
                                                <option value="PI" {{ request('uf') == 'PI' ? 'selected' : '' }}>Piauí</option>
                                            </optgroup>
                                            <optgroup label="Norte">
                                                <option value="AM" {{ request('uf') == 'AM' ? 'selected' : '' }}>Amazonas</option>
                                                <option value="PA" {{ request('uf') == 'PA' ? 'selected' : '' }}>Pará</option>
                                                <option value="RO" {{ request('uf') == 'RO' ? 'selected' : '' }}>Rondônia</option>
                                                <option value="TO" {{ request('uf') == 'TO' ? 'selected' : '' }}>Tocantins</option>
                                                <option value="AC" {{ request('uf') == 'AC' ? 'selected' : '' }}>Acre</option>
                                                <option value="AP" {{ request('uf') == 'AP' ? 'selected' : '' }}>Amapá</option>
                                                <option value="RR" {{ request('uf') == 'RR' ? 'selected' : '' }}>Roraima</option>
                                            </optgroup>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Cidade -->
                                <div class="mb-5">
                                    <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Cidade</label>
                                    <input type="text" name="cidade" value="{{ request('cidade') }}" placeholder="Ex: Curitiba" class="w-full border-gray-200 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm text-sm transition-all duration-200">
                                </div>

                                <!-- Situação -->
                                <div class="mb-5">
                                    <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Situação</label>
                                    <div class="space-y-2 bg-gray-50 p-3 rounded-xl border border-gray-100">
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="situacao" value="" {{ request('situacao') == '' ? 'checked' : '' }} class="rounded-full border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-600">Todas</span>
                                        </label>
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="situacao" value="ABERTA" {{ request('situacao') == 'ABERTA' ? 'checked' : '' }} class="rounded-full border-gray-300 text-emerald-600 shadow-sm focus:border-emerald-300 focus:ring focus:ring-emerald-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-600">Abertas</span>
                                        </label>
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="situacao" value="ENCERRADA" {{ request('situacao') == 'ENCERRADA' ? 'checked' : '' }} class="rounded-full border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-600">Encerradas</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Modalidade -->
                                <div class="mb-5">
                                    <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Modalidade</label>
                                    <div class="space-y-2 bg-gray-50 p-3 rounded-xl border border-gray-100">
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="modalidade" value="" {{ request('modalidade') == '' ? 'checked' : '' }} class="rounded-full border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-600">Todas</span>
                                        </label>
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="modalidade" value="6" {{ request('modalidade') == '6' ? 'checked' : '' }} class="rounded-full border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-600">Pregão Eletrônico</span>
                                        </label>
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="modalidade" value="8" {{ request('modalidade') == '8' ? 'checked' : '' }} class="rounded-full border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-600">Dispensa</span>
                                        </label>
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="modalidade" value="13" {{ request('modalidade') == '13' ? 'checked' : '' }} class="rounded-full border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-600">Concorrência</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Plataforma -->
                                <div class="mb-6">
                                    <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Plataforma</label>
                                    <div class="relative">
                                        <select name="fonte" class="w-full border-gray-200 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm text-sm appearance-none cursor-pointer" onchange="this.form.submit()">
                                            <option value="PNCP" {{ request('fonte') == 'PNCP' ? 'selected' : '' }}>PNCP (Nacional)</option>
                                            <option value="BB" {{ request('fonte') == 'BB' ? 'selected' : '' }}>Banco do Brasil</option>
                                            <option value="ComprasNet" {{ request('fonte') == 'ComprasNet' ? 'selected' : '' }}>ComprasNet</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg shadow-indigo-200 transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    Filtrar
                                </button>
                                
                                @if(request()->anyFilled(['busca', 'uf', 'cidade', 'modalidade']))
                                <a href="{{ route('radar.index') }}" class="block text-center mt-4 text-sm text-gray-500 hover:text-red-500 font-medium transition-colors">Limpar Filtros</a>
                                @endif

                            </form>
                        </div>
                    </div>
                </div>

                <!-- MAIN CONTENT (LISTA DE CARDS) -->
                <div class="md:col-span-8 lg:col-span-9">
                    
                    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 bg-white p-4 rounded-2xl shadow-sm border border-gray-100 gap-4">
                        <h3 class="text-gray-700 font-medium ml-2 flex items-center gap-2">
                            <span class="bg-indigo-50 text-indigo-700 py-1 px-3 rounded-full text-xs font-bold uppercase tracking-wider border border-indigo-100">Total</span>
                            <span class="font-bold text-gray-900 text-lg">{{ $licitacoes->total() == 0 && $licitacoes->count() > 0 ? $licitacoes->count() . '+' : $licitacoes->total() }}</span> <span class="text-gray-400 text-sm font-normal">licitações encontradas</span>
                        </h3>
                        
                        @if(request()->filled('busca'))
                        <form method="POST" action="{{ route('radar.salvar') }}" class="flex gap-2 w-full sm:w-auto">
                            @csrf
                            <input type="hidden" name="filtros[busca]" value="{{ request('busca') }}">
                            <input type="hidden" name="filtros[uf]" value="{{ request('uf') }}">
                            <input type="hidden" name="nome" value="Busca: {{ request('busca') }}">
                            <button type="submit" class="w-full sm:w-auto text-sm bg-white text-indigo-600 px-5 py-2.5 rounded-xl hover:bg-indigo-50 border border-indigo-200 flex items-center justify-center gap-2 font-bold transition-all shadow-sm hover:shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                </svg>
                                Salvar Radar
                            </button>
                        </form>
                        @endif
                    </div>

                    @if(request('fonte') == 'ComprasNet')
                    <div class="mb-6 bg-amber-50 border-l-4 border-amber-400 text-amber-800 p-4 rounded-r-xl shadow-sm flex items-start gap-3" role="alert">
                        <svg class="h-5 w-5 text-amber-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div>
                            <p class="font-bold text-sm">Atenção</p>
                            <p class="text-sm opacity-90">A fonte <strong>ComprasNet</strong> pode apresentar lentidão devido à API legada.</p>
                        </div>
                    </div>
                    @endif

                    <div class="space-y-6">
                        @forelse($licitacoes as $licitacao)
                        @php $obj = (object) $licitacao; @endphp
                        
                        <!-- CARD ITEM -->
                        <div class="group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                            <!-- Header Card -->
                            <div class="bg-gray-50/50 px-6 py-4 border-b border-gray-100 flex flex-wrap justify-between items-center gap-3 group-hover:bg-indigo-50/30 transition-colors">
                                <div class="flex items-center gap-3">
                                    <span class="font-mono text-xs font-bold text-gray-500 bg-white px-2 py-1 rounded border border-gray-200 shadow-sm">#{{ $obj->id ?? $obj->codigo_radar }}</span>
                                    
                                    @if(isset($obj->link_sistema_origem) && (str_contains($obj->link_sistema_origem, 'comprasnet') || str_contains($obj->link_sistema_origem, 'compras.gov.br')))
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100" title="Origem: ComprasNet (via PNCP)">
                                            <span class="w-1 h-1 rounded-full bg-blue-500"></span> C.Net
                                        </span>
                                    @elseif(isset($obj->origem) && $obj->origem == 'PNCP')
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-[10px] font-bold bg-gray-100 text-gray-600 border border-gray-200" title="Origem: PNCP">
                                            PNCP
                                        </span>
                                    @endif
                                    
                                    @if(isset($obj->status))
                                        @if($obj->status == 'ENCERRADA')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-100">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Encerrada
                                            </span>
                                        @elseif($obj->status == 'ABERTA')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Aberta
                                            </span>
                                        @endif
                                    @endif

                                    @if(isset($obj->numero_edital))
                                    <span class="hidden sm:inline-block text-gray-500 text-xs px-2 py-1">Edital: <span class="font-medium text-gray-700">{{ $obj->numero_edital }}</span></span>
                                    @endif
                                </div>
                                    @php
                                        $displayDate = $obj->data_abertura;
                                        $dateLabel = 'Pub.';
                                        if (isset($obj->data_sessao) && $obj->data_sessao) {
                                            $displayDate = $obj->data_sessao;
                                            $dateLabel = 'Sessão';
                                        } elseif (isset($obj->data_encerramento) && $obj->data_encerramento) {
                                            $displayDate = $obj->data_encerramento;
                                            $dateLabel = 'Fim';
                                        }
                                    @endphp
                                    <div class="flex flex-col items-end">
                                        <div class="text-xs text-gray-500 flex items-center gap-1.5 font-medium bg-white px-3 py-1 rounded-full border border-gray-200 shadow-sm" title="Data de {{ $dateLabel }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span class="text-gray-400 text-[10px] uppercase mr-1">{{ $dateLabel }}:</span>
                                            {{ \Carbon\Carbon::parse($displayDate)->format('d/m/Y') }} <span class="text-gray-300">|</span> {{ \Carbon\Carbon::parse($displayDate)->format('H:i') }}
                                        </div>
                                    </div>
                            </div>

                            <!-- Body Card -->
                            <div class="p-6">
                                <h4 class="text-lg font-bold text-gray-800 mb-3 leading-snug group-hover:text-indigo-600 transition-colors">
                                    <a href="{{ route('radar.detalhes', $obj->id ?? $obj->codigo_radar) }}" class="block">{{ $obj->objeto }}</a>
                                </h4>

                                @if(!empty($obj->informacao_complementar))
                                <p class="text-sm text-gray-500 mb-6 leading-relaxed line-clamp-2" title="{{ $obj->informacao_complementar }}">
                                    {{ $obj->informacao_complementar }}
                                </p>
                                @endif
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-600 mb-6">
                                    <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-100">
                                        <p class="mb-2 flex items-start gap-2.5">
                                            <div class="mt-0.5 p-1 bg-white rounded-md shadow-sm text-indigo-500">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                            </div>
                                            <span class="font-semibold text-gray-900 leading-tight">{{ $obj->orgao }}</span>
                                        </p>
                                        <p class="flex items-center gap-2.5 pl-1">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> 
                                            {{ $obj->cidade }} - {{ $obj->estado ?? $obj->uf ?? '' }}
                                        </p>
                                    </div>
                                    <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-100">
                                        <p class="mb-2 flex items-center gap-2.5">
                                            <div class="p-1 bg-white rounded-md shadow-sm text-purple-500">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                            </div>
                                            {{ $obj->modalidade }}
                                        </p>
                                        <p class="flex items-center gap-2.5 pl-1">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                                            <span class="text-emerald-600 font-bold text-base">R$ {{ number_format($obj->valor_estimado ?? 0, 2, ',', '.') }}</span>
                                        </p>
                                    </div>
                                </div>

                                <!-- Actions Footer -->
                                <div class="flex flex-wrap gap-3 pt-4 border-t border-gray-50">
                                    <a href="{{ route('radar.detalhes', $obj->id ?? $obj->codigo_radar) }}" class="flex-1 text-center inline-flex justify-center items-center px-4 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-lg hover:bg-indigo-700 transition-all shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                        Ver Detalhes
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </a>

                                    <!-- Botão Monitorar (CRM) -->
                                    <form action="{{ route('crm.monitorar', $obj->id ?? $obj->codigo_radar) }}" method="POST" class="inline-flex flex-1 sm:flex-none">
                                        @csrf
                                        <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2.5 bg-white text-orange-600 border border-orange-200 text-sm font-semibold rounded-lg hover:bg-orange-50 hover:border-orange-300 transition-all shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2" title="Adicionar ao CRM">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Monitorar
                                        </button>
                                    </form>

                                    <a href="{{ route('radar.detalhes', $obj->id ?? $obj->codigo_radar) }}?tab=arquivos" class="hidden sm:inline-flex items-center px-4 py-2.5 bg-white text-gray-700 border border-gray-200 text-sm font-semibold rounded-lg hover:bg-gray-50 hover:text-indigo-600 hover:border-indigo-200 transition-all shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2" title="Ver Arquivos">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                    
                                    @if(isset($obj->link_sistema_origem) || isset($obj->link_original))
                                    <a href="{{ $obj->link_sistema_origem ?? $obj->link_original }}" target="_blank" class="hidden sm:inline-flex items-center px-3 py-2.5 text-gray-400 hover:text-gray-600 transition-colors ml-auto" title="Ir para site oficial">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="bg-white p-12 rounded-2xl shadow-sm text-center border border-gray-100">
                            <div class="bg-gray-50 rounded-full h-20 w-20 flex items-center justify-center mx-auto mb-4">
                                <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="mt-2 text-lg font-medium text-gray-900">Nenhuma licitação encontrada</h3>
                            <p class="mt-1 text-gray-500">Tente ajustar os filtros ou buscar por outros termos.</p>
                        </div>
                        @endforelse
                    </div>

                    <!-- PAGINAÇÃO -->
                    @if($licitacoes->hasPages())
                    <div class="mt-8 bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
                         <div class="text-sm text-gray-500">
                            Mostrando <span class="font-bold">{{ $licitacoes->firstItem() }}</span> a <span class="font-bold">{{ $licitacoes->lastItem() }}</span> de <span class="font-bold">{{ $licitacoes->total() }}</span> resultados
                         </div>
                         <div class="flex justify-center w-full md:w-auto">
                            {{ $licitacoes->appends(request()->query())->links('pagination.custom') }}
                         </div>
                    </div>
                    @else
                        @if($licitacoes->count() > 0)
                        <div class="mt-8 bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex justify-center text-sm text-gray-500">
                            Mostrando todos os <span class="font-bold mx-1">{{ $licitacoes->count() }}</span> resultados encontrados
                        </div>
                        @endif
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>