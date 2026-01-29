<?php

namespace App\Http\Controllers;

use App\Services\CalendarioService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarioController extends Controller
{
    protected $calendarioService;

    public function __construct(CalendarioService $calendarioService)
    {
        $this->calendarioService = $calendarioService;
    }

    /**
     * Exibe o calendário unificado de eventos.
     */
    public function index(Request $request)
    {
        $mes = $request->get('mes', now()->month);
        $ano = $request->get('ano', now()->year);
        
        $start = Carbon::createFromDate($ano, $mes, 1)->startOfMonth();

        $eventosPorDia = $this->calendarioService->obterEventos($ano, $mes);

        return view('calendario.index', compact('eventosPorDia', 'start', 'mes', 'ano'));
    }
}
