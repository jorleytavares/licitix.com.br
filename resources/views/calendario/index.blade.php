<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Calendário') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-[1600px] mx-auto sm:px-6 lg:px-8">
            
            <!-- Main Calendar Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-3xl border border-gray-100">
                <div class="p-8">

                    <!-- Calendar Header -->
                    <div class="flex flex-col md:flex-row justify-between items-center mb-8">
                        <div class="flex items-center gap-4">
                            <h3 class="text-3xl font-bold capitalize text-gray-900 tracking-tight">
                                {{ $start->translatedFormat('F Y') }}
                            </h3>
                            <div class="flex items-center bg-gray-100 rounded-full p-1">
                                <a href="{{ route('calendario.index', ['mes' => $start->copy()->subMonth()->month, 'ano' => $start->copy()->subMonth()->year]) }}" 
                                   class="w-8 h-8 flex items-center justify-center rounded-full text-gray-500 hover:bg-white hover:text-indigo-600 hover:shadow-sm transition-all duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                </a>
                                <a href="{{ route('calendario.index', ['mes' => now()->month, 'ano' => now()->year]) }}" 
                                   class="px-4 py-1 text-xs font-bold text-gray-600 hover:text-indigo-600 transition-colors uppercase tracking-wider">
                                    Hoje
                                </a>
                                <a href="{{ route('calendario.index', ['mes' => $start->copy()->addMonth()->month, 'ano' => $start->copy()->addMonth()->year]) }}" 
                                   class="w-8 h-8 flex items-center justify-center rounded-full text-gray-500 hover:bg-white hover:text-indigo-600 hover:shadow-sm transition-all duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Legend / Filter -->
                        <div class="flex flex-wrap items-center gap-3 mt-4 md:mt-0">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                <span class="w-2 h-2 rounded-full bg-blue-500 mr-1.5"></span> Licitações
                            </span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-100">
                                <span class="w-2 h-2 rounded-full bg-red-500 mr-1.5"></span> Documentos
                            </span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 mr-1.5"></span> Financeiro
                            </span>
                        </div>
                    </div>

                    <!-- Calendar Grid -->
                    <div class="border border-gray-200 rounded-2xl overflow-hidden bg-white shadow-sm ring-1 ring-black/5">
                        
                        <!-- Weekdays Header -->
                        <div class="grid grid-cols-7 bg-white border-b border-gray-200">
                            @foreach(['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'] as $index => $diaSemana)
                            <div class="py-4 text-center">
                                <span class="text-[11px] font-bold uppercase tracking-widest text-gray-400">
                                    <span class="hidden md:inline">{{ $diaSemana }}</span>
                                    <span class="md:hidden">{{ substr($diaSemana, 0, 1) }}</span>
                                </span>
                            </div>
                            @endforeach
                        </div>

                        <!-- Days Grid -->
                        <div class="grid grid-cols-7 bg-gray-200 gap-px">
                            @php
                            $firstDayOfWeek = $start->dayOfWeek;
                            $daysInMonth = $start->daysInMonth;
                            $today = now()->format('Y-m-d');
                            @endphp

                            {{-- Empty Cells (Previous Month) --}}
                            @for($i = 0; $i < $firstDayOfWeek; $i++)
                                <div class="bg-gray-50/30 h-32 md:h-40 p-3 relative"></div>
                            @endfor

                            {{-- Active Days --}}
                            @for($i = 1; $i <= $daysInMonth; $i++)
                                @php
                                $currentDate = \Carbon\Carbon::create($ano, $mes, $i);
                                $dateStr = $currentDate->format('Y-m-d');
                                $isToday = $dateStr == $today;
                                $isWeekend = $currentDate->isWeekend();
                                $eventsToday = $eventosPorDia->get($dateStr) ?? collect();
                                @endphp
                                
                                <div class="bg-white min-h-[8rem] md:h-40 p-3 transition-colors hover:bg-gray-50 relative group flex flex-col {{ $isWeekend ? 'bg-gray-50/40' : '' }}">
                                    <!-- Day Number -->
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="text-sm font-medium w-8 h-8 flex items-center justify-center rounded-full transition-all duration-200 
                                            {{ $isToday ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200 scale-110' : ($isWeekend ? 'text-gray-400' : 'text-gray-700') }}">
                                            {{ $i }}
                                        </span>
                                        
                                        @if($eventsToday->count() > 0)
                                            <span class="hidden md:flex h-5 items-center justify-center px-1.5 rounded-full bg-gray-100 text-[10px] font-bold text-gray-500">
                                                {{ $eventsToday->count() }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Events List -->
                                    <div class="flex-1 overflow-y-auto custom-scrollbar space-y-1.5 pr-1">
                                        @foreach($eventsToday as $evento)
                                            @php
                                                // Map old classes to new styling
                                                $baseClass = "block px-2 py-1.5 rounded-md text-[10px] font-semibold border-l-[3px] shadow-sm hover:opacity-80 transition-opacity truncate leading-tight";
                                                $styleClass = "";
                                                
                                                if (str_contains($evento['cor'], 'blue')) {
                                                    $styleClass = "bg-blue-50 text-blue-700 border-blue-500";
                                                } elseif (str_contains($evento['cor'], 'red')) {
                                                    $styleClass = "bg-red-50 text-red-700 border-red-500";
                                                } elseif (str_contains($evento['cor'], 'green')) {
                                                    $styleClass = "bg-emerald-50 text-emerald-700 border-emerald-500";
                                                } else {
                                                    $styleClass = "bg-gray-50 text-gray-700 border-gray-500";
                                                }
                                            @endphp
                                            
                                            <a href="{{ $evento['url'] }}" 
                                               class="{{ $baseClass }} {{ $styleClass }}"
                                               title="{{ $evento['titulo'] }}">
                                                {{ $evento['titulo'] }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endfor

                            {{-- Empty Cells (Next Month) --}}
                            @php
                            $remainingCells = 7 - (($firstDayOfWeek + $daysInMonth) % 7);
                            if($remainingCells == 7) $remainingCells = 0;
                            @endphp
                            @for($i = 0; $i < $remainingCells; $i++)
                                <div class="bg-gray-50/30 h-32 md:h-40 p-3"></div>
                            @endfor
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end items-center text-xs text-gray-400 italic">
                        <svg class="w-4 h-4 mr-1 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Seus prazos e vencimentos são sincronizados automaticamente.
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
