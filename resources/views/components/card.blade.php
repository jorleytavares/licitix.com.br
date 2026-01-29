@props(['titulo'])

<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300']) }}>
    @if(isset($titulo))
        <h4 class="text-sm font-medium text-gray-500 mb-1 uppercase tracking-wider">{{ $titulo }}</h4>
    @endif
    
    <div class="text-2xl font-bold text-gray-800">
        {{ $slot }}
    </div>
</div>