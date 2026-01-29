<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Painel Geral') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-[1600px] mx-auto sm:px-6 lg:px-8">
            
            <!-- Smart Radar Widget (Preferências do Usuário) -->
            <div class="bg-indigo-600 rounded-2xl p-6 shadow-lg mb-8 text-white relative overflow-hidden">
                <!-- Background Decoration -->
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-48 h-48 bg-white opacity-5 rounded-full blur-2xl"></div>

                <div class="relative z-10">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                        <div>
                            <h3 class="font-bold text-xl flex items-center">
                                <svg class="w-6 h-6 mr-2 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                Radar Inteligente
                            </h3>
                            <p class="text-indigo-100 text-sm mt-1">
                                Monitoramento automático baseado nas suas preferências
                            </p>
                        </div>
                        <button onclick="document.getElementById('modalConfigRadar').classList.remove('hidden')" class="bg-white/10 hover:bg-white/20 border border-white/20 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center backdrop-blur-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Configurar Preferências
                        </button>
                    </div>

                    @if(!empty($indicadores['smart_widget']['termos']) || !empty($indicadores['smart_widget']['locais']))
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <!-- Stats -->
                            <div class="md:col-span-1 space-y-4">
                                <a href="{{ route('radar.index', ['smart_filter' => 1, 'periodo' => 'hoje']) }}" class="block bg-white/10 rounded-xl p-4 backdrop-blur-sm border border-white/10 hover:bg-white/20 transition-colors cursor-pointer group">
                                    <div class="flex justify-between items-start">
                                        <p class="text-indigo-200 text-xs uppercase font-bold tracking-wider mb-1 group-hover:text-white transition-colors">Encontradas Hoje</p>
                                        <svg class="w-4 h-4 text-indigo-300 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    </div>
                                    <div class="flex items-baseline gap-2">
                                        <p class="text-3xl font-black text-white">{{ $indicadores['smart_widget']['novas_hoje'] }}</p>
                                        <span class="text-xs text-indigo-200 group-hover:text-white transition-colors">licitações</span>
                                    </div>
                                </a>
                                <a href="{{ route('radar.index', ['smart_filter' => 1]) }}" class="block bg-white/10 rounded-xl p-4 backdrop-blur-sm border border-white/10 hover:bg-white/20 transition-colors cursor-pointer group">
                                    <div class="flex justify-between items-start">
                                        <p class="text-indigo-200 text-xs uppercase font-bold tracking-wider mb-1 group-hover:text-white transition-colors">Total Ativo</p>
                                        <svg class="w-4 h-4 text-indigo-300 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    </div>
                                    <div class="flex items-baseline gap-2">
                                        <p class="text-3xl font-black text-white">{{ $indicadores['smart_widget']['total'] }}</p>
                                        <span class="text-xs text-indigo-200 group-hover:text-white transition-colors">oportunidades</span>
                                    </div>
                                </a>
                            </div>

                            <!-- Preview List -->
                            <div class="md:col-span-3">
                                <div class="bg-white/10 rounded-xl p-1 backdrop-blur-sm border border-white/10 h-full">
                                    @if(count($indicadores['smart_widget']['amostras'] ?? []) > 0)
                                        <div class="flex flex-col h-full">
                                            @foreach($indicadores['smart_widget']['amostras'] as $amostra)
                                                <div class="flex items-center justify-between p-3 hover:bg-white/5 rounded-lg transition-colors border-b border-white/5 last:border-0">
                                                    <div class="min-w-0 flex-1 pr-4">
                                                        <div class="flex items-center gap-2 mb-1">
                                                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-white/20 text-white">{{ $amostra->estado }}</span>
                                                            <span class="text-xs text-indigo-200 truncate">{{ $amostra->orgao }}</span>
                                                        </div>
                                                        <h4 class="text-sm font-medium text-white truncate" title="{{ $amostra->objeto }}">{{ $amostra->objeto }}</h4>
                                                    </div>
                                                    <a href="{{ route('radar.detalhes', $amostra->id) }}" class="flex-shrink-0 bg-white text-indigo-600 hover:bg-indigo-50 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                                                        Ver
                                                    </a>
                                                </div>
                                            @endforeach
                                            <div class="mt-auto pt-3 px-3 pb-2 text-center border-t border-white/10">
                                                <a href="{{ route('radar.index', ['smart_filter' => 1]) }}" class="text-xs text-indigo-200 hover:text-white transition-colors flex items-center justify-center gap-1">
                                                    Ver todas as {{ $indicadores['smart_widget']['total'] }} oportunidades
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="h-full flex flex-col items-center justify-center text-center p-6 text-indigo-200">
                                            <svg class="w-10 h-10 mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                            <p class="text-sm">Nenhuma licitação encontrada com seus termos hoje.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-4 backdrop-blur-sm">
                                <svg class="w-8 h-8 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                            </div>
                            <h4 class="text-lg font-bold text-white mb-2">Configure seu Radar Inteligente</h4>
                            <p class="text-indigo-100 max-w-md mx-auto mb-6 text-sm">Defina palavras-chave, estados e cidades de interesse para que o sistema monitore e destaque as melhores oportunidades para você automaticamente.</p>
                            <button onclick="document.getElementById('modalConfigRadar').classList.remove('hidden')" class="bg-white text-indigo-600 hover:bg-indigo-50 px-6 py-2.5 rounded-xl font-bold shadow-lg transition-all transform hover:scale-105">
                                Começar Agora
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Resumo de Oportunidades Widget (Geral) -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-8">
                <h3 class="font-bold text-lg text-gray-800 mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Novas Oportunidades (Radar)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Hoje -->
                    <a href="{{ route('radar.index', ['periodo' => 'hoje']) }}" class="flex items-center justify-between p-5 bg-indigo-50/50 rounded-xl border border-indigo-100 hover:shadow-md transition-shadow cursor-pointer group">
                        <div>
                            <p class="text-sm font-medium text-indigo-600 mb-1 group-hover:text-indigo-800 transition-colors">Hoje</p>
                            <div class="flex items-baseline gap-2">
                                <p class="text-3xl font-bold text-gray-900">{{ $indicadores['resumo_periodo']['hoje']['total'] }}</p>
                                <span class="text-xs text-gray-500">novas licitações</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                                <span class="font-bold text-indigo-700">{{ $indicadores['resumo_periodo']['hoje']['pregoes'] }}</span> Pregões identificados
                            </p>
                        </div>
                        <div class="p-3 bg-white rounded-lg shadow-sm text-indigo-500 group-hover:scale-110 transition-transform">
                           <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </a>

                    <!-- Semana -->
                    <a href="{{ route('radar.index', ['periodo' => 'semana']) }}" class="flex items-center justify-between p-5 bg-emerald-50/50 rounded-xl border border-emerald-100 hover:shadow-md transition-shadow cursor-pointer group">
                        <div>
                            <p class="text-sm font-medium text-emerald-600 mb-1 group-hover:text-emerald-800 transition-colors">Esta Semana</p>
                            <div class="flex items-baseline gap-2">
                                <p class="text-3xl font-bold text-gray-900">{{ $indicadores['resumo_periodo']['semana']['total'] }}</p>
                                <span class="text-xs text-gray-500">novas licitações</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span class="font-bold text-emerald-700">{{ $indicadores['resumo_periodo']['semana']['pregoes'] }}</span> Pregões identificados
                            </p>
                        </div>
                        <div class="p-3 bg-white rounded-lg shadow-sm text-emerald-500 group-hover:scale-110 transition-transform">
                           <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    </a>

                    <!-- Mês -->
                    <a href="{{ route('radar.index', ['periodo' => 'mes']) }}" class="flex items-center justify-between p-5 bg-blue-50/50 rounded-xl border border-blue-100 hover:shadow-md transition-shadow cursor-pointer group">
                        <div>
                            <p class="text-sm font-medium text-blue-600 mb-1 group-hover:text-blue-800 transition-colors">Este Mês</p>
                            <div class="flex items-baseline gap-2">
                                <p class="text-3xl font-bold text-gray-900">{{ $indicadores['resumo_periodo']['mes']['total'] }}</p>
                                <span class="text-xs text-gray-500">novas licitações</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                <span class="font-bold text-blue-700">{{ $indicadores['resumo_periodo']['mes']['pregoes'] }}</span> Pregões identificados
                            </p>
                        </div>
                        <div class="p-3 bg-white rounded-lg shadow-sm text-blue-500 group-hover:scale-110 transition-transform">
                           <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                    </a>
                </div>
            </div>

            <!-- KPIs Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <x-card :titulo="__('Licitações Abertas')" class="transform hover:scale-105 transition-transform duration-200">
                    <span class="text-3xl font-bold text-gray-800">{{ $indicadores['licitacoes_abertas'] }}</span>
                </x-card>

                <x-card :titulo="__('Propostas Criadas')" class="transform hover:scale-105 transition-transform duration-200">
                    <span class="text-3xl font-bold text-indigo-600">{{ $indicadores['propostas_criadas'] }}</span>
                </x-card>

                <x-card :titulo="__('Propostas Ganhas')" class="transform hover:scale-105 transition-transform duration-200">
                    <span class="text-3xl font-bold text-emerald-600">{{ $indicadores['propostas_ganhas'] }}</span>
                </x-card>

                <x-card :titulo="__('Valor Contratado')" class="transform hover:scale-105 transition-transform duration-200">
                    <span class="text-xl font-bold text-gray-800">R$ {{ number_format($indicadores['valor_contratado'], 2, ',', '.') }}</span>
                </x-card>

                <x-card :titulo="__('Valor Recebido')">
                    <span class="text-xl font-bold text-blue-600">R$ {{ number_format($indicadores['valor_recebido'], 2, ',', '.') }}</span>
                </x-card>

                <x-card :titulo="__('Contas a Receber')">
                    <span class="text-xl font-bold text-blue-500">R$ {{ number_format($indicadores['contas_a_receber'], 2, ',', '.') }}</span>
                </x-card>

                <x-card :titulo="__('Inadimplência')">
                    <span class="text-xl font-bold {{ $indicadores['inadimplentes'] > 0 ? 'text-red-500' : 'text-gray-400' }}">
                        R$ {{ number_format($indicadores['inadimplentes'], 2, ',', '.') }}
                    </span>
                </x-card>

                <x-card :titulo="__('Lucro Líquido Est.')">
                    <span class="text-xl font-bold text-emerald-500">R$ {{ number_format($indicadores['lucro_estimado'], 2, ',', '.') }}</span>
                </x-card>
            </div>

            <!-- Agenda (Próximos 7 Dias) -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-8">
                <h3 class="font-bold text-lg text-gray-800 mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Agenda (Próximos 7 Dias)
                </h3>
                
                @if(count($indicadores['agenda']) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($indicadores['agenda'] as $item)
                            <div class="flex items-center p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors border border-gray-100 group">
                                <!-- Date Badge -->
                                <div class="flex-shrink-0 w-14 text-center mr-4 bg-white rounded-lg p-1 shadow-sm border border-gray-100 group-hover:border-indigo-100 transition-colors">
                                    <div class="text-[10px] text-gray-500 uppercase font-bold">{{ $item['data']->format('M') }}</div>
                                    <div class="text-xl font-black text-gray-800 leading-none my-0.5">{{ $item['data']->format('d') }}</div>
                                    <div class="text-[10px] text-gray-400">{{ $item['data']->format('D') }}</div>
                                </div>
                                
                                <!-- Content -->
                                <div class="flex-grow min-w-0 mr-2">
                                    <div class="flex items-center gap-2 mb-1">
                                        @php
                                            $badgeColor = match($item['tipo']) {
                                                'licitacao' => 'bg-blue-100 text-blue-700',
                                                'documento' => 'bg-red-100 text-red-700',
                                                'financeiro' => 'bg-green-100 text-green-700',
                                                default => 'bg-gray-100 text-gray-700'
                                            };
                                            $tipoLabel = match($item['tipo']) {
                                                'licitacao' => 'Licitação',
                                                'documento' => 'Documento',
                                                'financeiro' => 'Financeiro',
                                                default => ucfirst($item['tipo'])
                                            };
                                        @endphp
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide {{ $badgeColor }}">
                                            {{ $tipoLabel }}
                                        </span>
                                    </div>
                                    <h4 class="text-sm font-bold text-gray-800 truncate" title="{{ $item['titulo'] }}">
                                        {{ $item['titulo'] }}
                                    </h4>
                                    <p class="text-xs text-gray-500">
                                        {{ $item['data']->format('H:i') }}
                                    </p>
                                </div>
                                
                                <!-- Action -->
                                <a href="{{ $item['url'] }}" class="flex-shrink-0 p-2 text-gray-300 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-400 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                        <svg class="w-12 h-12 mx-auto mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <p class="font-medium">Nenhum compromisso para os próximos 7 dias.</p>
                        <p class="text-sm mt-1">Sua agenda está livre!</p>
                    </div>
                @endif
            </div>

            <!-- Gráficos -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Gráfico de Status -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6">
                    <h3 class="font-bold text-lg text-gray-800 mb-6 flex items-center">
                        <span class="w-1 h-6 bg-indigo-500 rounded-full mr-3"></span>
                        Status das Propostas
                    </h3>
                    <div class="h-72 relative">
                        <canvas id="chartPropostas"></canvas>
                    </div>
                </div>

                <!-- Gráfico de Faturamento -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6">
                    <h3 class="font-bold text-lg text-gray-800 mb-6 flex items-center">
                        <span class="w-1 h-6 bg-blue-500 rounded-full mr-3"></span>
                        Faturamento (Últimos 6 Meses)
                    </h3>
                    <div class="h-72 relative">
                        <canvas id="chartFaturamento"></canvas>
                    </div>
                </div>
            </div>

            <!-- Funil de Vendas CRM -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 mb-8">
                <div class="p-8">
                    <h3 class="font-bold text-lg text-gray-800 mb-8 flex items-center">
                        <span class="w-1 h-6 bg-purple-500 rounded-full mr-3"></span>
                        Funil de Vendas (CRM)
                    </h3>
                    
                    <div class="relative">
                        <!-- Connecting Line (Desktop) -->
                        <div class="hidden md:block absolute top-1/2 left-0 w-full h-1 bg-gray-100 -z-0 transform -translate-y-1/2 rounded-full"></div>

                        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 relative z-10">
                            <!-- Step 1 -->
                            <a href="{{ route('crm.index') }}" class="block bg-white border-2 border-blue-50 p-4 rounded-xl text-center hover:border-blue-200 transition-colors shadow-sm group cursor-pointer">
                                <div class="w-8 h-8 mx-auto bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-bold mb-3 group-hover:bg-blue-600 group-hover:text-white transition-colors">1</div>
                                <p class="text-xs text-blue-600 uppercase font-bold tracking-widest mb-1">Interesse</p>
                                <p class="text-2xl font-black text-gray-800">{{ $indicadores['funil']['interesse'] ?? 0 }}</p>
                            </a>

                            <!-- Step 2 -->
                            <a href="{{ route('crm.index') }}" class="block bg-white border-2 border-indigo-50 p-4 rounded-xl text-center hover:border-indigo-200 transition-colors shadow-sm group cursor-pointer">
                                <div class="w-8 h-8 mx-auto bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-xs font-bold mb-3 group-hover:bg-indigo-600 group-hover:text-white transition-colors">2</div>
                                <p class="text-xs text-indigo-600 uppercase font-bold tracking-widest mb-1">Em Análise</p>
                                <p class="text-2xl font-black text-gray-800">{{ $indicadores['funil']['em_analise'] ?? 0 }}</p>
                            </a>

                            <!-- Step 3 -->
                            <a href="{{ route('crm.index') }}" class="block bg-white border-2 border-purple-50 p-4 rounded-xl text-center hover:border-purple-200 transition-colors shadow-sm group cursor-pointer">
                                <div class="w-8 h-8 mx-auto bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-xs font-bold mb-3 group-hover:bg-purple-600 group-hover:text-white transition-colors">3</div>
                                <p class="text-xs text-purple-600 uppercase font-bold tracking-widest mb-1">Preparação</p>
                                <p class="text-2xl font-black text-gray-800">{{ $indicadores['funil']['preparacao'] ?? 0 }}</p>
                            </a>

                            <!-- Step 4 -->
                            <a href="{{ route('crm.index') }}" class="block bg-white border-2 border-orange-50 p-4 rounded-xl text-center hover:border-orange-200 transition-colors shadow-sm group cursor-pointer">
                                <div class="w-8 h-8 mx-auto bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-xs font-bold mb-3 group-hover:bg-orange-600 group-hover:text-white transition-colors">4</div>
                                <p class="text-xs text-orange-600 uppercase font-bold tracking-widest mb-1">Enviada</p>
                                <p class="text-2xl font-black text-gray-800">{{ $indicadores['funil']['proposta_enviada'] ?? 0 }}</p>
                            </a>

                            <!-- Step 5 -->
                            <a href="{{ route('crm.index') }}" class="block bg-white border-2 border-emerald-50 p-4 rounded-xl text-center hover:border-emerald-200 transition-colors shadow-sm group cursor-pointer">
                                <div class="w-8 h-8 mx-auto bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-xs font-bold mb-3 group-hover:bg-emerald-600 group-hover:text-white transition-colors">5</div>
                                <p class="text-xs text-emerald-600 uppercase font-bold tracking-widest mb-1">Resultado</p>
                                <p class="text-2xl font-black text-gray-800">{{ $indicadores['funil']['resultado'] ?? 0 }}</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alertas de Documentos -->
            @if(isset($docsVencidos) && ($docsVencidos > 0 || $docsVencendo > 0))
            <div class="mb-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($docsVencidos > 0)
                    <div class="bg-white border-l-4 border-red-500 p-6 rounded-r-xl shadow-sm flex justify-between items-center group hover:shadow-md transition-shadow">
                        <div class="flex items-start gap-4">
                            <div class="p-2 bg-red-100 rounded-full text-red-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <div>
                                <p class="font-bold text-red-700 text-lg">Documentos Vencidos</p>
                                <p class="text-sm text-gray-600 mt-1">Você possui <span class="font-bold text-red-600">{{ $docsVencidos }}</span> documento(s) vencido(s).</p>
                            </div>
                        </div>
                        <a href="{{ route('documentos.index') }}" class="text-sm bg-red-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-red-700 transition-colors shadow-sm">Resolver</a>
                    </div>
                    @endif

                    @if($docsVencendo > 0)
                    <div class="bg-white border-l-4 border-yellow-500 p-6 rounded-r-xl shadow-sm flex justify-between items-center group hover:shadow-md transition-shadow">
                        <div class="flex items-start gap-4">
                            <div class="p-2 bg-yellow-100 rounded-full text-yellow-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <p class="font-bold text-yellow-700 text-lg">Vencimento Próximo</p>
                                <p class="text-sm text-gray-600 mt-1">Você possui <span class="font-bold text-yellow-600">{{ $docsVencendo }}</span> documento(s) vencendo.</p>
                            </div>
                        </div>
                        <a href="{{ route('documentos.index') }}" class="text-sm bg-yellow-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-yellow-700 transition-colors shadow-sm">Verificar</a>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
    <!-- Modal Configuração Radar -->
    <div id="modalConfigRadar" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('modalConfigRadar').classList.add('hidden')"></div>

            <!-- Modal panel -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="relative z-50 inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" onclick="event.stopPropagation()">
                <form action="{{ route('configuracoes.radar.update') }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Configurar Radar Inteligente</h3>
                                <div class="mt-4 space-y-4">
                                    <!-- Termos de Busca -->
                                    <div>
                                        <label for="termos_busca" class="block text-sm font-medium text-gray-700">Palavras-chave</label>
                                        <p class="text-xs text-gray-500 mb-1">Separe por vírgulas (ex: papel, caneta, limpeza)</p>
                                        @php
                                            $config = \App\Models\RadarConfiguracao::where('user_id', auth()->id())->first();
                                            $termos = $config ? implode(', ', $config->termos_busca ?? []) : '';
                                            $cidades = $config ? implode(', ', $config->cidades ?? []) : '';
                                            $estados = $config->estados ?? [];
                                        @endphp
                                        <textarea name="termos_busca" id="termos_busca" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ $termos }}</textarea>
                                    </div>

                                    <!-- Estados -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Estados de Interesse</label>
                                        <div class="grid grid-cols-4 gap-2 max-h-32 overflow-y-auto p-2 border border-gray-200 rounded-md">
                                            @foreach(['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'] as $uf)
                                                <label class="inline-flex items-center">
                                                    <input type="checkbox" name="estados[]" value="{{ $uf }}" {{ in_array($uf, $estados) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                    <span class="ml-2 text-sm text-gray-600">{{ $uf }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Cidades -->
                                    <div>
                                        <label for="cidades" class="block text-sm font-medium text-gray-700">Cidades Específicas</label>
                                        <p class="text-xs text-gray-500 mb-1">Separe por vírgulas (ex: São Paulo, Rio de Janeiro)</p>
                                        <input type="text" name="cidades" id="cidades" value="{{ $cidades }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Salvar Preferências
                        </button>
                        <button type="button" onclick="document.getElementById('modalConfigRadar').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chart defaults
            Chart.defaults.font.family = "'Inter', sans-serif";
            Chart.defaults.color = '#6B7280';
            
            // Gráfico de Propostas (Doughnut with modern styling)
            const ctxPropostas = document.getElementById('chartPropostas').getContext('2d');
            new Chart(ctxPropostas, {
                type: 'doughnut',
                data: {
                    labels: ['Ganhas', 'Perdidas', 'Em Análise', 'Rascunho'],
                    datasets: [{
                        data: [
                            {{ $indicadores['grafico_propostas']['ganhas'] }},
                            {{ $indicadores['grafico_propostas']['perdidas'] }},
                            {{ $indicadores['grafico_propostas']['em_analise'] }},
                            {{ $indicadores['grafico_propostas']['rascunho'] }}
                        ],
                        backgroundColor: [
                            '#10B981', // Emerald 500
                            '#EF4444', // Red 500
                            '#F59E0B', // Amber 500
                            '#9CA3AF'  // Gray 400
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.9)',
                            titleColor: '#1F2937',
                            bodyColor: '#4B5563',
                            borderColor: '#E5E7EB',
                            borderWidth: 1,
                            padding: 12,
                            boxPadding: 4,
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    let value = context.raw;
                                    let total = context.chart._metasets[context.datasetIndex].total;
                                    let percentage = Math.round((value / total) * 100) + '%';
                                    return label + value + ' (' + percentage + ')';
                                }
                            }
                        }
                    }
                }
            });

            // Gráfico de Faturamento (Bar with gradient)
            const ctxFaturamento = document.getElementById('chartFaturamento').getContext('2d');
            const gradientFaturamento = ctxFaturamento.createLinearGradient(0, 0, 0, 300);
            gradientFaturamento.addColorStop(0, '#3B82F6');
            gradientFaturamento.addColorStop(1, '#93C5FD');

            const faturamentoData = @json($indicadores['grafico_faturamento']);
            
            new Chart(ctxFaturamento, {
                type: 'bar',
                data: {
                    labels: Object.keys(faturamentoData),
                    datasets: [{
                        label: 'Recebido (R$)',
                        data: Object.values(faturamentoData),
                        backgroundColor: gradientFaturamento,
                        borderRadius: 6,
                        borderSkipped: false,
                        barThickness: 30
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                borderDash: [2, 4],
                                color: '#F3F4F6'
                            },
                            ticks: {
                                callback: function(value) {
                                    if(value >= 1000) return 'R$ ' + value/1000 + 'k';
                                    return value;
                                },
                                font: {
                                    size: 11
                                }
                            },
                            border: {
                                display: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            border: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.9)',
                            titleColor: '#1F2937',
                            bodyColor: '#4B5563',
                            borderColor: '#E5E7EB',
                            borderWidth: 1,
                            padding: 12,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
