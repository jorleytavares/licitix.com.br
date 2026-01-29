<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            Configurações da Empresa
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('configuracoes.empresa.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Column: Logo & Branding -->
                    <div class="lg:col-span-1 space-y-6">
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                            <h3 class="font-bold text-gray-800 mb-4">Identidade Visual</h3>
                            
                            <!-- Logo Upload -->
                            <div class="flex flex-col items-center">
                                <div class="relative group w-40 h-40 mb-4">
                                    <div class="w-full h-full rounded-2xl border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden bg-gray-50 group-hover:bg-gray-100 transition-colors">
                                        @if($empresa->logo_path)
                                            <img src="{{ asset('storage/' . $empresa->logo_path) }}" alt="Logo" class="max-w-full max-h-full object-contain p-2">
                                        @else
                                            <div class="text-center p-4">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <span class="text-xs text-gray-500">Sem Logo</span>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <label for="logo" class="absolute inset-0 w-full h-full cursor-pointer flex items-center justify-center bg-black/50 text-white opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl">
                                        <span class="text-sm font-bold">Alterar Logo</span>
                                        <input type="file" name="logo" id="logo" accept="image/*" class="hidden">
                                    </label>
                                </div>
                                <p class="text-xs text-center text-gray-500 max-w-[200px]">
                                    Recomendado: PNG com fundo transparente. Max 2MB.
                                </p>
                            </div>
                        </div>

                        <!-- Info Box -->
                        <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                            <div class="flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <h4 class="text-sm font-bold text-blue-800">Uso das Informações</h4>
                                    <p class="text-xs text-blue-700 mt-1">
                                        Estes dados aparecerão automaticamente no cabeçalho das Propostas PDF e relatórios gerados pelo sistema.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Form Fields -->
                    <div class="lg:col-span-2">
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                            <h3 class="font-bold text-gray-800 mb-6 pb-2 border-b border-gray-100">Dados Cadastrais</h3>
                            
                            @if (session('success'))
                                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    {{ session('success') }}
                                </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Razão Social -->
                                <div class="col-span-1 md:col-span-2">
                                    <x-input-label for="razao_social" :value="__('Razão Social')" class="text-xs font-bold uppercase text-gray-500" />
                                    <x-text-input id="razao_social" class="block mt-1 w-full" type="text" name="razao_social" :value="old('razao_social', $empresa->razao_social)" required />
                                </div>

                                <!-- Nome Fantasia -->
                                <div>
                                    <x-input-label for="nome_fantasia" :value="__('Nome Fantasia')" class="text-xs font-bold uppercase text-gray-500" />
                                    <x-text-input id="nome_fantasia" class="block mt-1 w-full" type="text" name="nome_fantasia" :value="old('nome_fantasia', $empresa->nome_fantasia)" required />
                                </div>

                                <!-- CNPJ -->
                                <div>
                                    <x-input-label for="cnpj" :value="__('CNPJ')" class="text-xs font-bold uppercase text-gray-500" />
                                    <x-text-input id="cnpj" class="block mt-1 w-full font-mono" type="text" name="cnpj" :value="old('cnpj', $empresa->cnpj)" required />
                                </div>

                                <!-- Email Contato -->
                                <div>
                                    <x-input-label for="email_contato" :value="__('Email de Contato')" class="text-xs font-bold uppercase text-gray-500" />
                                    <div class="relative mt-1">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <x-text-input id="email_contato" class="block w-full pl-10" type="email" name="email_contato" :value="old('email_contato', $empresa->email_contato)" />
                                    </div>
                                </div>

                                <!-- Telefone -->
                                <div>
                                    <x-input-label for="telefone_contato" :value="__('Telefone / WhatsApp')" class="text-xs font-bold uppercase text-gray-500" />
                                    <div class="relative mt-1">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                        </div>
                                        <x-text-input id="telefone_contato" class="block w-full pl-10" type="text" name="telefone_contato" :value="old('telefone_contato', $empresa->telefone_contato)" />
                                    </div>
                                </div>

                                <!-- Website -->
                                <div class="col-span-1 md:col-span-2">
                                    <x-input-label for="website" :value="__('Website')" class="text-xs font-bold uppercase text-gray-500" />
                                    <div class="relative mt-1">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                            </svg>
                                        </div>
                                        <x-text-input id="website" class="block w-full pl-10" type="url" name="website" :value="old('website', $empresa->website)" placeholder="https://www.suaempresa.com.br" />
                                    </div>
                                </div>

                                <!-- Endereço -->
                                <div class="col-span-1 md:col-span-2">
                                    <x-input-label for="endereco" :value="__('Endereço Completo')" class="text-xs font-bold uppercase text-gray-500" />
                                    <x-text-input id="endereco" class="block mt-1 w-full" type="text" name="endereco" :value="old('endereco', $empresa->endereco)" placeholder="Rua, Número, Bairro, Cidade - UF, CEP" />
                                </div>
                            </div>

                            <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Salvar Alterações
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>