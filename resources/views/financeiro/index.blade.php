<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Gestão Financeira
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Contas a Receber Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <span class="p-2 rounded-lg bg-orange-100 text-orange-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                        Contas a Receber (Pendentes)
                    </h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-500 font-medium uppercase text-xs tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Vencimento</th>
                                <th class="px-6 py-4">Nº NF</th>
                                <th class="px-6 py-4">Cliente / Licitação</th>
                                <th class="px-6 py-4 text-right">Valor</th>
                                <th class="px-6 py-4 text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($contasReceber as $conta)
                            <tr class="hover:bg-gray-50/80 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium {{ $conta->data_vencimento < now() ? 'text-red-600' : 'text-gray-700' }}">
                                            {{ $conta->data_vencimento->format('d/m/Y') }}
                                        </span>
                                        @if($conta->data_vencimento < now())
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-50 text-red-600 border border-red-100">
                                                VENCIDO
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-mono text-gray-600">{{ $conta->numero_nf }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $conta->proposta->licitacao->orgao ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-500 truncate max-w-xs" title="{{ $conta->proposta->licitacao->objeto ?? '' }}">
                                        {{ $conta->proposta->licitacao->objeto ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-gray-900">
                                    R$ {{ number_format($conta->valor, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('financeiro.receber', $conta->id) }}" method="POST" onsubmit="return confirm('Confirmar recebimento desta fatura hoje?')">
                                        @csrf
                                        <input type="hidden" name="data_pagamento" value="{{ date('Y-m-d') }}">
                                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 hover:text-emerald-700 rounded-lg text-xs font-semibold transition-all duration-200 border border-emerald-100 shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Receber
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span class="text-sm">Nenhuma conta a receber pendente.</span>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Contratos Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <span class="p-2 rounded-lg bg-indigo-100 text-indigo-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </span>
                        Contratos Ativos e Faturamento
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-500 font-medium uppercase text-xs tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Contrato / Licitação</th>
                                <th class="px-6 py-4 text-right">Valor Total</th>
                                <th class="px-6 py-4 text-right">Faturado</th>
                                <th class="px-6 py-4 text-right">Recebido</th>
                                <th class="px-6 py-4 text-right">Saldo</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($contratos as $contrato)
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1">
                                        <div class="font-bold text-gray-900 line-clamp-2" title="{{ $contrato->licitacao->objeto }}">
                                            {{ $contrato->licitacao->objeto }}
                                        </div>
                                        <div class="flex items-center gap-2 text-xs text-gray-500">
                                            <span class="px-1.5 py-0.5 rounded bg-gray-100 text-gray-600 font-mono">#{{ $contrato->id }}</span>
                                            <span class="truncate max-w-[200px]" title="{{ $contrato->licitacao->orgao }}">
                                                {{ $contrato->licitacao->orgao }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-gray-900 whitespace-nowrap">
                                    R$ {{ number_format($contrato->valor_total, 2, ',', '.') }}
                                </td>
                                
                                <td class="px-6 py-4 text-right text-indigo-600 font-medium whitespace-nowrap">
                                    R$ {{ number_format($contrato->financeiro->valor_faturado ?? 0, 2, ',', '.') }}
                                </td>
                                
                                <td class="px-6 py-4 text-right text-emerald-600 font-medium whitespace-nowrap">
                                    R$ {{ number_format($contrato->financeiro->valor_recebido ?? 0, 2, ',', '.') }}
                                </td>
                                
                                <td class="px-6 py-4 text-right font-medium whitespace-nowrap {{ ($contrato->financeiro->saldo ?? $contrato->valor_total) > 0 ? 'text-orange-500' : 'text-gray-400' }}">
                                    R$ {{ number_format($contrato->financeiro->saldo ?? $contrato->valor_total, 2, ',', '.') }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @php
                                        $status = $contrato->financeiro->status_pagamento ?? 'pendente';
                                        $badges = [
                                            'recebido' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                            'parcial' => 'bg-amber-50 text-amber-700 border-amber-100',
                                            'pendente' => 'bg-gray-50 text-gray-600 border-gray-100'
                                        ];
                                        $badgeClass = $badges[$status] ?? $badges['pendente'];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border {{ $badgeClass }}">
                                        {{ strtoupper($status) }}
                                    </span>
                                </td>
                                
                                <td class="px-6 py-4 text-center">
                                    <button onclick="document.getElementById('modal-faturar-{{ $contrato->id }}').showModal()" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white text-indigo-600 hover:bg-indigo-50 hover:text-indigo-700 rounded-lg text-xs font-semibold transition-all duration-200 border border-indigo-200 shadow-sm group">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Faturar
                                    </button>

                                    <!-- Modern Modal -->
                                    <dialog id="modal-faturar-{{ $contrato->id }}" class="p-0 rounded-2xl shadow-2xl backdrop:bg-gray-900/50 w-[480px] max-w-[90vw]">
                                        <div class="bg-white">
                                            <!-- Header -->
                                            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                                                <h3 class="font-bold text-lg text-gray-800">Faturar Contrato #{{ $contrato->id }}</h3>
                                                <button onclick="document.getElementById('modal-faturar-{{ $contrato->id }}').close()" class="text-gray-400 hover:text-gray-600 transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                            
                                            <div class="p-6 space-y-6">
                                                <!-- Histórico -->
                                                @if($contrato->faturamentos->count() > 0)
                                                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                                        <h4 class="text-xs font-bold text-gray-500 uppercase mb-3 flex items-center gap-2">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            Histórico de Faturamento
                                                        </h4>
                                                        <ul class="space-y-2">
                                                            @foreach($contrato->faturamentos as $fatura)
                                                                <li class="flex justify-between items-center bg-white p-2 rounded border border-gray-100 text-xs">
                                                                    <div class="flex items-center gap-2">
                                                                        <span class="font-mono text-gray-500">NF {{ $fatura->numero_nf }}</span>
                                                                        <span class="text-gray-400">|</span>
                                                                        <span class="text-gray-600">{{ $fatura->data_emissao->format('d/m/Y') }}</span>
                                                                        
                                                                        @if($fatura->status === 'pago')
                                                                            <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-green-50 text-green-600 border border-green-100 font-bold">PAGO</span>
                                                                        @else
                                                                            <form action="{{ route('financeiro.receber', $fatura->id) }}" method="POST" class="inline" onsubmit="return confirm('Confirmar recebimento desta fatura hoje?')">
                                                                                @csrf
                                                                                <input type="hidden" name="data_pagamento" value="{{ date('Y-m-d') }}">
                                                                                <button type="submit" class="text-indigo-600 hover:text-indigo-800 hover:underline font-medium ml-1">
                                                                                    Receber
                                                                                </button>
                                                                            </form>
                                                                        @endif
                                                                    </div>
                                                                    <span class="font-bold text-gray-800">R$ {{ number_format($fatura->valor, 2, ',', '.') }}</span>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                        <div class="mt-3 pt-3 border-t border-gray-200 flex justify-end items-center gap-2">
                                                            <span class="text-xs text-gray-500">Total Faturado:</span>
                                                            <span class="text-sm font-bold text-indigo-600">R$ {{ number_format($contrato->faturamentos->sum('valor'), 2, ',', '.') }}</span>
                                                        </div>
                                                    </div>
                                                @endif

                                                <!-- Form -->
                                                <form action="{{ route('financeiro.faturar', $contrato) }}" method="POST" class="space-y-4">
                                                    @csrf
                                                    <div class="grid grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="block text-xs font-bold text-gray-700 mb-1">Número da NF</label>
                                                            <input type="text" name="numero_nf" class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required placeholder="000000">
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs font-bold text-gray-700 mb-1">Valor (R$)</label>
                                                            <input type="number" step="0.01" name="valor" class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required placeholder="0.00">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="grid grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="block text-xs font-bold text-gray-700 mb-1">Data de Emissão</label>
                                                            <input type="date" name="data_emissao" class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs font-bold text-gray-700 mb-1">Data de Vencimento</label>
                                                            <input type="date" name="data_vencimento" class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                                        </div>
                                                    </div>

                                                    <div class="pt-4 flex justify-end gap-3">
                                                        <button type="button" onclick="document.getElementById('modal-faturar-{{ $contrato->id }}').close()" class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
                                                            Cancelar
                                                        </button>
                                                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 shadow-sm transition-colors">
                                                            Salvar Faturamento
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </dialog>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span class="text-sm">Nenhum contrato ativo para faturamento.</span>
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
</x-app-layout>