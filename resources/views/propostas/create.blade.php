<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nova Proposta</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Área de Importação Rápida -->
                    <div class="mb-8 p-4 bg-blue-50 border border-blue-200 rounded-md">
                        <h3 class="text-md font-bold text-blue-800 mb-2">Importar Licitação do Radar</h3>
                        <p class="text-sm text-blue-600 mb-3">Se a licitação não estiver na lista abaixo, digite o código do Radar (ex: RAD-2026-XXXX) para importá-la e iniciar a proposta.</p>
                        <form action="#" method="GET" onsubmit="importarRadar(event)" class="flex gap-2">
                            <input type="text" id="codigo_radar" placeholder="Código RAD-..." class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <button type="submit" class="bg-azul-primario text-white px-4 py-2 rounded-md hover:bg-azul-secundario">
                                Buscar e Importar
                            </button>
                        </form>
                    </div>

                    <hr class="mb-6 border-gray-200">

                    <form action="{{ route('propostas.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="licitacao_id" class="block text-sm font-medium text-gray-700">Licitação (Já cadastrada)</label>
                            <select name="licitacao_id" id="licitacao_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="">Selecione uma licitação...</option>
                                @foreach($licitacoes as $licitacao)
                                    <option value="{{ $licitacao->id }}">{{ $licitacao->numero_edital }} - {{ $licitacao->orgao }} ({{ $licitacao->codigo_radar ?? 'Manual' }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                @foreach(\App\Models\Proposta::STATUSES as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="valor_total" class="block text-sm font-medium text-gray-700">Valor Total (R$)</label>
                            <input type="number" step="0.01" name="valor_total" id="valor_total" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="bg-azul-primario text-white px-4 py-2 rounded-md hover:bg-azul-secundario">
                                Salvar Proposta
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function importarRadar(event) {
            event.preventDefault();
            const codigo = document.getElementById('codigo_radar').value.trim();
            if (codigo) {
                // Redireciona para a rota de criação via Radar, que trata a persistência
                window.location.href = `/radar-licitacoes/${codigo}/criar-proposta`;
            } else {
                alert('Por favor, digite um código válido.');
            }
        }
    </script>
</x-app-layout>