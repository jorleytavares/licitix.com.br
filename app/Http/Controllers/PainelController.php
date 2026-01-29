<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PainelController extends Controller
{
    public function index(\App\Services\PainelService $service)
    {
        $indicadores = $service->indicadores();

        // Alertas de Documentos
        $alertas = $service->alertasDocumentos();
        $docsVencidos = $alertas['vencidos'];
        $docsVencendo = $alertas['vencendo'];

        return view('painel.index', compact('indicadores', 'docsVencidos', 'docsVencendo'));
    }
}
