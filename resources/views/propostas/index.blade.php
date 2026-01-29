<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Minhas Propostas
            </h2>
            <a href="{{ route('propostas.criar') }}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nova Proposta
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-[1600px] mx-auto sm:px-6 lg:px-8">
            
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total de Propostas</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $propostas->count() }}</p>
                    </div>
                    <div class="p-3 bg-indigo-50 rounded-xl text-indigo-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Em Análise</p>
                        <p class="text-3xl font-bold text-amber-600 mt-1">{{ $propostas->where('status', 'Em Análise')->count() }}</p>
                    </div>
                    <div class="p-3 bg-amber-50 rounded-xl text-amber-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Ganhas</p>
                        <p class="text-3xl font-bold text-emerald-600 mt-1">{{ $propostas->where('status', 'Ganha')->count() }}</p>
                    </div>
                    <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Valor Total</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">R$ {{ number_format($propostas->sum('valor_total'), 2, ',', '.') }}</p>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-xl text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                        <div class="relative w-full sm:w-96">
                            <input type="text" placeholder="Buscar propostas..." class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 text-sm bg-gray-50 focus:bg-white transition-colors">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                        
                        <div class="flex gap-2">
                            <button class="px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100 hover:text-gray-800 transition-colors flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                Filtros
                            </button>
                            <button class="px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100 hover:text-gray-800 transition-colors flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Exportar
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100">
                                    <th class="px-6 py-4 font-semibold text-gray-600 uppercase tracking-wider text-xs rounded-tl-xl">Proposta / Licitação</th>
                                    <th class="px-6 py-4 font-semibold text-gray-600 uppercase tracking-wider text-xs">Órgão</th>
                                    <th class="px-6 py-4 font-semibold text-gray-600 uppercase tracking-wider text-xs">Status</th>
                                    <th class="px-6 py-4 font-semibold text-gray-600 uppercase tracking-wider text-xs">Valor Total</th>
                                    <th class="px-6 py-4 font-semibold text-gray-600 uppercase tracking-wider text-xs text-center rounded-tr-xl">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($propostas as $proposta)
                                <tr class="group hover:bg-gray-50/50 transition-colors duration-200">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold text-xs">
                                                #{{ $proposta->id }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-800">Edital {{ $proposta->licitacao->numero_edital ?? 'N/A' }}</div>
                                                <div class="text-xs text-gray-500">Criado em {{ $proposta->created_at->format('d/m/Y') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-gray-700 font-medium">{{ Str::limit($proposta->licitacao->orgao ?? 'N/A', 40) }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusClasses = [
                                                'Ganha' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                                'Perdida' => 'bg-red-100 text-red-700 border-red-200',
                                                'Em Análise' => 'bg-amber-100 text-amber-700 border-amber-200',
                                                'Rascunho' => 'bg-gray-100 text-gray-700 border-gray-200',
                                            ];
                                            $currentClass = $statusClasses[$proposta->status] ?? 'bg-indigo-100 text-indigo-700 border-indigo-200';
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $currentClass }}">
                                            {{ $proposta->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-800">R$ {{ number_format($proposta->valor_total, 2, ',', '.') }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                            <a href="{{ route('propostas.detalhes', $proposta->id) }}" class="p-2 bg-white border border-gray-200 rounded-lg text-gray-600 hover:text-indigo-600 hover:border-indigo-200 hover:shadow-sm transition-all" title="Ver Detalhes">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('propostas.simular', $proposta->id) }}" class="p-2 bg-white border border-gray-200 rounded-lg text-gray-600 hover:text-emerald-600 hover:border-emerald-200 hover:shadow-sm transition-all" title="Simular Lucro">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('propostas.editar', $proposta->id) }}" class="p-2 bg-white border border-gray-200 rounded-lg text-gray-600 hover:text-amber-600 hover:border-amber-200 hover:shadow-sm transition-all" title="Editar">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <p class="text-lg font-medium text-gray-600">Nenhuma proposta encontrada</p>
                                            <p class="text-sm text-gray-400 mt-1">Comece criando sua primeira proposta para uma licitação.</p>
                                            <a href="{{ route('propostas.criar') }}" class="mt-4 px-4 py-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition-colors font-medium">
                                                Criar Nova Proposta
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
