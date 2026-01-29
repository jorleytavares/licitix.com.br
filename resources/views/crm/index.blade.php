<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Pipeline de Licitações (CRM)') }}
            </h2>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 text-xs font-medium text-indigo-700 bg-indigo-50 rounded-full border border-indigo-100">
                    {{ $pipeline->flatten()->count() }} oportunidades
                </span>
            </div>
        </div>
    </x-slot>

    <div class="h-[calc(100vh-65px)] overflow-x-auto overflow-y-hidden bg-gray-50/50">
        <div class="h-full px-4 py-6 sm:px-6 lg:px-8">
            <div class="flex h-full gap-6">
                @foreach($etapas as $key => $label)
                @php
                    $colors = match($key) {
                        'interesse' => [
                            'bg' => 'bg-gray-100/80',
                            'border' => 'border-gray-200',
                            'text' => 'text-gray-700',
                            'badge_bg' => 'bg-white',
                            'badge_text' => 'text-gray-600',
                            'icon_bg' => 'bg-gray-200',
                            'icon_text' => 'text-gray-500'
                        ],
                        'em_analise' => [
                            'bg' => 'bg-blue-50/80',
                            'border' => 'border-blue-100',
                            'text' => 'text-blue-800',
                            'badge_bg' => 'bg-white',
                            'badge_text' => 'text-blue-600',
                            'icon_bg' => 'bg-blue-100',
                            'icon_text' => 'text-blue-500'
                        ],
                        'preparacao' => [
                            'bg' => 'bg-amber-50/80',
                            'border' => 'border-amber-100',
                            'text' => 'text-amber-800',
                            'badge_bg' => 'bg-white',
                            'badge_text' => 'text-amber-600',
                            'icon_bg' => 'bg-amber-100',
                            'icon_text' => 'text-amber-500'
                        ],
                        'proposta_enviada' => [
                            'bg' => 'bg-purple-50/80',
                            'border' => 'border-purple-100',
                            'text' => 'text-purple-800',
                            'badge_bg' => 'bg-white',
                            'badge_text' => 'text-purple-600',
                            'icon_bg' => 'bg-purple-100',
                            'icon_text' => 'text-purple-500'
                        ],
                        'resultado' => [
                            'bg' => 'bg-emerald-50/80',
                            'border' => 'border-emerald-100',
                            'text' => 'text-emerald-800',
                            'badge_bg' => 'bg-white',
                            'badge_text' => 'text-emerald-600',
                            'icon_bg' => 'bg-emerald-100',
                            'icon_text' => 'text-emerald-500'
                        ],
                        default => [
                            'bg' => 'bg-gray-100',
                            'border' => 'border-gray-200',
                            'text' => 'text-gray-700',
                            'badge_bg' => 'bg-gray-200',
                            'badge_text' => 'text-gray-600',
                            'icon_bg' => 'bg-gray-200',
                            'icon_text' => 'text-gray-500'
                        ]
                    };
                    
                    $count = $pipeline->get($key)?->count() ?? 0;
                @endphp

                <div class="flex flex-col h-full w-80 shrink-0">
                    <!-- Coluna Header -->
                    <div class="flex items-center justify-between mb-3 px-1">
                        <div class="flex items-center gap-2">
                            <div class="flex items-center justify-center w-6 h-6 rounded-full {{ $colors['icon_bg'] }} {{ $colors['icon_text'] }}">
                                <span class="text-xs font-bold">{{ $loop->iteration }}</span>
                            </div>
                            <h3 class="font-bold text-sm {{ $colors['text'] }} uppercase tracking-wide">
                                {{ $label }}
                            </h3>
                        </div>
                        <span class="{{ $colors['badge_bg'] }} {{ $colors['badge_text'] }} px-2.5 py-0.5 rounded-full text-xs font-bold shadow-sm border {{ $colors['border'] }}">
                            {{ $count }}
                        </span>
                    </div>

                    <!-- Coluna Body -->
                    <div class="flex-1 p-3 rounded-2xl {{ $colors['bg'] }} border {{ $colors['border'] }} shadow-inner overflow-hidden flex flex-col">
                        <div id="column-{{ $key }}" 
                             data-etapa="{{ $key }}" 
                             class="kanban-column flex-1 overflow-y-auto custom-scrollbar space-y-3 pr-1 pb-2">
                            
                            @forelse($pipeline->get($key) ?? [] as $licitacao)
                            <div data-id="{{ $licitacao->id }}" 
                                 class="group relative bg-white p-4 rounded-xl shadow-sm border border-gray-100 hover:shadow-lg hover:border-indigo-100 hover:-translate-y-0.5 transition-all duration-200 cursor-move">
                                
                                <!-- Drag Handle & ID -->
                                <div class="flex justify-between items-start mb-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded-md bg-gray-50 text-xs font-medium text-gray-500 border border-gray-100 group-hover:bg-indigo-50 group-hover:text-indigo-600 group-hover:border-indigo-100 transition-colors">
                                        #{{ $licitacao->id }}
                                    </span>
                                    <div class="flex items-center gap-1">
                                        <span class="text-[10px] font-medium text-gray-400 bg-gray-50 px-1.5 py-0.5 rounded">
                                            {{ \Carbon\Carbon::parse($licitacao->data_abertura)->format('d/m') }}
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Conteúdo -->
                                <div class="mb-4">
                                    <h4 class="text-sm font-semibold text-gray-800 leading-snug mb-1 line-clamp-3 group-hover:text-indigo-700 transition-colors" title="{{ $licitacao->objeto }}">
                                        {{ $licitacao->objeto }}
                                    </h4>
                                    <div class="flex items-center gap-1.5 mt-2">
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <p class="text-xs text-gray-500 font-medium truncate" title="{{ $licitacao->orgao }}">
                                            {{ $licitacao->orgao }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Footer Card -->
                                <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] text-gray-400 font-medium uppercase tracking-wider">Valor Est.</span>
                                        <span class="text-sm font-bold text-gray-900">
                                            R$ {{ number_format($licitacao->valor_estimado, 2, ',', '.') }}
                                        </span>
                                    </div>
                                    
                                    @if($licitacao->propostas->count() > 0)
                                        <div class="flex items-center justify-center w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100" title="Proposta Criada">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    @else
                                        <a href="{{ route('radar.criar-proposta', $licitacao->id) }}" 
                                           class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 text-blue-600 border border-blue-100 hover:bg-blue-100 hover:scale-110 transition-all" 
                                           title="Criar Proposta">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                        </a>
                                    @endif
                                </div>

                                <!-- Actions Hover Overlay -->
                                <div class="absolute top-2 right-2 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <a href="{{ route('radar.detalhes', $licitacao->id) }}" class="p-1.5 bg-white text-gray-400 hover:text-blue-600 rounded-lg shadow-sm border border-gray-100 hover:border-blue-200 transition-colors" title="Ver Detalhes">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    
                                    <form action="{{ route('crm.destroy', $licitacao->id) }}" method="POST" class="inline" onsubmit="return confirm('Remover do CRM?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 bg-white text-gray-400 hover:text-red-600 rounded-lg shadow-sm border border-gray-100 hover:border-red-200 transition-colors" title="Remover">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @empty
                            <div class="flex flex-col items-center justify-center py-10 px-4 text-center border-2 border-dashed border-gray-200 rounded-xl bg-white/30">
                                <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center mb-3 text-gray-300">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-gray-400">Arraste oportunidades</p>
                                <p class="text-xs text-gray-400 mt-1">ou adicione do Radar</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const columns = document.querySelectorAll('.kanban-column');
            
            columns.forEach(column => {
                new Sortable(column, {
                    group: 'kanban',
                    animation: 200,
                    ghostClass: 'opacity-50',
                    dragClass: 'rotate-2',
                    delay: 0,
                    forceFallback: true,
                    fallbackClass: 'sortable-fallback',
                    onStart: function(evt) {
                        document.body.style.cursor = 'grabbing';
                    },
                    onEnd: function (evt) {
                        document.body.style.cursor = 'default';
                        const itemEl = evt.item;
                        const newEtapa = evt.to.getAttribute('data-etapa');
                        const licitacaoId = itemEl.getAttribute('data-id');
                        
                        if (evt.from === evt.to) return;

                        fetch(`/crm-licitacoes/${licitacaoId}/alterar-etapa`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-HTTP-Method-Override': 'PATCH'
                            },
                            body: JSON.stringify({
                                etapa: newEtapa
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                alert('Erro ao atualizar etapa.');
                                window.location.reload();
                            }
                        })
                        .catch(error => {
                            console.error('Erro:', error);
                            alert('Erro de conexão.');
                        });
                    }
                });
            });
        });
    </script>
    
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.3);
            border-radius: 20px;
        }
        .custom-scrollbar:hover::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.5);
        }
        .sortable-fallback {
            opacity: 1 !important;
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            transform: scale(1.02);
            cursor: grabbing;
        }
    </style>
</x-app-layout>