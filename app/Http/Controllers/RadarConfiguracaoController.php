<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateRadarConfigRequest;

class RadarConfiguracaoController extends Controller
{
    public function update(UpdateRadarConfigRequest $request)
    {
        $validated = $request->validated();

        $termos = !empty($validated['termos_busca']) 
            ? array_map('trim', explode(',', $validated['termos_busca'])) 
            : [];
            
        $cidades = !empty($validated['cidades']) 
            ? array_map('trim', explode(',', $validated['cidades'])) 
            : [];

        \App\Models\RadarConfiguracao::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'termos_busca' => $termos,
                'estados' => $validated['estados'] ?? [],
                'cidades' => $cidades,
            ]
        );

        return back()->with('success', 'Preferências do Radar Inteligente atualizadas com sucesso!');
    }
}
