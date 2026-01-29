<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Proposta #{{ $proposta->id }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('propostas.update', $proposta->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Licitação</label>
                            <div class="mt-1 p-2 bg-gray-100 rounded-md">
                                {{ $proposta->licitacao->numero_edital }} - {{ $proposta->licitacao->orgao }}
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                @foreach(\App\Models\Proposta::STATUSES as $key => $label)
                                    <option value="{{ $key }}" {{ $proposta->status == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="valor_total" class="block text-sm font-medium text-gray-700">Valor Total (R$)</label>
                            <input type="number" step="0.01" name="valor_total" id="valor_total" value="{{ $proposta->valor_total }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        </div>

                        <div class="flex items-center justify-between mt-4">
                            <!-- Botão de Excluir -->
                             <button type="button" onclick="confirm('Tem certeza que deseja excluir esta proposta?') ? document.getElementById('delete-form').submit() : false" class="text-red-600 hover:text-red-900 underline">
                                Excluir Proposta
                            </button>

                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                Atualizar Proposta
                            </button>
                        </div>
                    </form>

                    <!-- Form de Delete separado -->
                    <form id="delete-form" action="{{ route('propostas.destroy', $proposta->id) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>