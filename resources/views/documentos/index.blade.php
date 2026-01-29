<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Documentos da Empresa
            </h2>
            <button onclick="document.getElementById('modal-upload').showModal()" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-sm transition-all duration-200 transform hover:-translate-y-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                Novo Documento
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Dashboard Widgets -->
            @php
                $vencidos = $documentos->filter(fn($d) => $d->validade && $d->validade->isPast())->count();
                $alerta = $documentos->filter(fn($d) => $d->validade && !$d->validade->isPast() && $d->validade->diffInDays(now()) < 30)->count();
                $ok = $documentos->count() - $vencidos - $alerta;
            @endphp
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Vencidos -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group">
                    <div class="absolute right-0 top-0 h-full w-1/2 bg-gradient-to-l from-red-50 to-transparent opacity-50"></div>
                    <div class="relative z-10">
                        <div class="text-sm font-medium text-gray-500 mb-1">Documentos Vencidos</div>
                        <div class="text-3xl font-bold text-red-600">{{ $vencidos }}</div>
                        <div class="mt-2 text-xs text-red-500 font-medium flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Requer atenção imediata
                        </div>
                    </div>
                </div>

                <!-- Alerta -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group">
                    <div class="absolute right-0 top-0 h-full w-1/2 bg-gradient-to-l from-amber-50 to-transparent opacity-50"></div>
                    <div class="relative z-10">
                        <div class="text-sm font-medium text-gray-500 mb-1">Vencem em 30 dias</div>
                        <div class="text-3xl font-bold text-amber-500">{{ $alerta }}</div>
                        <div class="mt-2 text-xs text-amber-600 font-medium flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Renovar em breve
                        </div>
                    </div>
                </div>

                <!-- Em Dia -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group">
                    <div class="absolute right-0 top-0 h-full w-1/2 bg-gradient-to-l from-emerald-50 to-transparent opacity-50"></div>
                    <div class="relative z-10">
                        <div class="text-sm font-medium text-gray-500 mb-1">Em dia / Sem Validade</div>
                        <div class="text-3xl font-bold text-emerald-600">{{ $ok }}</div>
                        <div class="mt-2 text-xs text-emerald-600 font-medium flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Status regular
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents Grid -->
            <div>
                <h3 class="font-bold text-lg text-gray-800 mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Arquivo Digital
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($documentos as $doc)
                    @php
                        $isExpired = $doc->validade && $doc->validade->isPast();
                        $isWarning = $doc->validade && !$isExpired && $doc->validade->diffInDays(now()) < 30;
                        
                        $cardClasses = "bg-white rounded-2xl p-5 shadow-sm border transition-all duration-300 hover:shadow-lg hover:-translate-y-1 group relative overflow-hidden";
                        if ($isExpired) $cardClasses .= " border-red-200";
                        elseif ($isWarning) $cardClasses .= " border-amber-200";
                        else $cardClasses .= " border-gray-100 hover:border-indigo-100";
                    @endphp
                    
                    <div class="{{ $cardClasses }}">
                        <!-- Status Strip -->
                        @if($isExpired)
                            <div class="absolute top-0 left-0 w-1 h-full bg-red-500"></div>
                        @elseif($isWarning)
                            <div class="absolute top-0 left-0 w-1 h-full bg-amber-500"></div>
                        @else
                            <div class="absolute top-0 left-0 w-1 h-full bg-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        @endif

                        <div class="flex justify-between items-start mb-3 pl-2">
                            <span class="inline-flex items-center px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wide bg-gray-50 text-gray-600 border border-gray-100">
                                {{ $doc->tipo }}
                            </span>
                            
                            @if($doc->validade)
                                <span class="flex items-center gap-1 text-xs font-bold {{ $isExpired ? 'text-red-600 bg-red-50 px-2 py-1 rounded-full' : ($isWarning ? 'text-amber-600 bg-amber-50 px-2 py-1 rounded-full' : 'text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full') }}">
                                    @if($isExpired)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @endif
                                    {{ $doc->validade->format('d/m/Y') }}
                                </span>
                            @endif
                        </div>

                        <div class="pl-2 mb-4">
                            <h4 class="font-bold text-gray-800 text-sm leading-tight mb-1 line-clamp-2" title="{{ $doc->nome }}">{{ $doc->nome }}</h4>
                            <p class="text-xs text-gray-400">Enviado em {{ $doc->created_at->format('d/m/Y') }}</p>
                        </div>

                        <div class="pl-2 pt-3 mt-2 border-t border-gray-50 flex justify-between items-center">
                            <form action="{{ route('documentos.destroy', $doc) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este documento?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-medium text-gray-400 hover:text-red-600 transition-colors flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Excluir
                                </button>
                            </form>
                            
                            <a href="{{ route('documentos.download', $doc) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 hover:text-indigo-700 rounded-lg text-xs font-bold transition-all duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                Baixar
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-12 flex flex-col items-center justify-center text-gray-400 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-4 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="text-sm font-medium">Nenhum documento arquivado.</span>
                        <p class="text-xs mt-1">Clique em "Novo Documento" para começar.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Upload Modal -->
            <dialog id="modal-upload" class="p-0 rounded-2xl shadow-2xl backdrop:bg-gray-900/50 w-[600px] max-w-[90vw]">
                <div class="bg-white">
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <h3 class="font-bold text-lg text-gray-800">Novo Documento</h3>
                        <button onclick="document.getElementById('modal-upload').close()" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <form action="{{ route('documentos.enviar') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-700 mb-1">Nome do Documento</label>
                                <input type="text" name="nome" class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required placeholder="Ex: Certidão Negativa Federal">
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Tipo de Documento</label>
                                <select name="tipo" class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="certidao">Certidão</option>
                                    <option value="atestado">Atestado de Capacidade</option>
                                    <option value="contrato_social">Contrato Social</option>
                                    <option value="balanco">Balanço Patrimonial</option>
                                    <option value="procuracao">Procuração</option>
                                    <option value="outros">Outros</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Validade (Opcional)</label>
                                <input type="date" name="validade" class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-700 mb-1">Arquivo (PDF ou Imagem)</label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:bg-gray-50 transition-colors cursor-pointer relative">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600 justify-center">
                                            <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                                <span>Upload de arquivo</span>
                                                <input id="file-upload" name="arquivo" type="file" class="sr-only" required>
                                            </label>
                                            <p class="pl-1">ou arraste e solte</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PDF, PNG, JPG até 10MB</p>
                                    </div>
                                    <script>
                                        // Simple script to show filename after selection
                                        const fileInput = document.getElementById('file-upload');
                                        fileInput.addEventListener('change', function(e) {
                                            const fileName = e.target.files[0].name;
                                            const label = this.closest('div').querySelector('p.text-xs');
                                            label.textContent = "Selecionado: " + fileName;
                                            label.classList.add('text-indigo-600', 'font-bold');
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 flex justify-end gap-3 border-t border-gray-100 mt-4">
                            <button type="button" onclick="document.getElementById('modal-upload').close()" class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
                                Cancelar
                            </button>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 shadow-sm transition-colors">
                                Enviar Documento
                            </button>
                        </div>
                    </form>
                </div>
            </dialog>

        </div>
    </div>
</x-app-layout>