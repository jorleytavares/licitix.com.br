<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                Catálogo de Itens
            </h2>
            <a href="{{ route('catalogo.criar') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-sm transition-all duration-200 transform hover:-translate-y-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Novo Item
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- Toolbar/Filter Placeholder -->
                <div class="p-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        <span>{{ count($itens) }} itens cadastrados</span>
                    </div>
                    <!-- Future: Search Input -->
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-500 font-medium uppercase text-xs tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Código / CATMAT</th>
                                <th class="px-6 py-4">Descrição do Item</th>
                                <th class="px-6 py-4">Detalhes</th>
                                <th class="px-6 py-4 text-center">Unidade</th>
                                <th class="px-6 py-4 text-right">Custo Estimado</th>
                                <th class="px-6 py-4 text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($itens as $item)
                            <tr class="hover:bg-gray-50/80 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1">
                                        <span class="font-mono text-gray-900 font-medium">{{ $item->codigo ?? '-' }}</span>
                                        @if($item->codigo_catmat)
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-600 border border-blue-100 w-fit" title="Código CATMAT">
                                                CM: {{ $item->codigo_catmat }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900 line-clamp-2" title="{{ $item->nome }}">{{ $item->nome }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1 text-xs">
                                        @if($item->marca) 
                                            <div class="flex items-center gap-1">
                                                <span class="text-gray-400 font-medium w-8">Marca:</span>
                                                <span class="text-gray-700 bg-gray-100 px-1.5 py-0.5 rounded">{{ $item->marca }}</span>
                                            </div>
                                        @endif
                                        @if($item->modelo) 
                                            <div class="flex items-center gap-1">
                                                <span class="text-gray-400 font-medium w-8">Mod:</span>
                                                <span class="text-gray-700 bg-gray-100 px-1.5 py-0.5 rounded">{{ $item->modelo }}</span>
                                            </div>
                                        @endif
                                        @if(!$item->marca && !$item->modelo)
                                            <span class="text-gray-400 italic">-</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-50 text-gray-600 font-bold text-xs border border-gray-200">
                                        {{ $item->unidade_medida }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="font-bold text-gray-900">R$ {{ number_format($item->preco_custo, 2, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('catalogo.editar', $item->id) }}" class="p-1.5 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Editar Item">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </a>
                                        <!-- Future: Delete Button -->
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        <span class="text-sm font-medium text-gray-500">Nenhum item encontrado no catálogo.</span>
                                        <p class="text-xs mt-1">Clique em "Novo Item" para começar.</p>
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