<?php

namespace App\Services;

use App\Models\Licitacao;
use App\Models\Documento;
use App\Models\Faturamento;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class CalendarioService
{
    /**
     * Retorna eventos agrupados por dia para o calendário.
     *
     * @param int $ano
     * @param int $mes
     * @return Collection
     */
    public function obterEventos(int $ano, int $mes): Collection
    {
        $start = Carbon::createFromDate($ano, $mes, 1)->startOfMonth();
        $end = $start->copy()->endOfMonth();

        $eventos = collect();

        // 1. Licitações (Data de Abertura)
        $this->adicionarLicitacoes($eventos, $start, $end);

        // 2. Documentos (Validade)
        $this->adicionarDocumentos($eventos, $start, $end);

        // 3. Faturamentos (Vencimento)
        $this->adicionarFaturamentos($eventos, $start, $end);

        return $eventos->groupBy(function ($evento) {
            return $evento['data']->format('Y-m-d');
        });
    }

    private function adicionarLicitacoes(Collection $eventos, Carbon $start, Carbon $end): void
    {
        $licitacoes = Licitacao::whereBetween('data_abertura', [$start, $end])
            ->where(function($q) {
                $q->where('monitorada', true)
                  ->orWhereHas('propostas');
            })
            ->get();

        foreach ($licitacoes as $licitacao) {
            $eventos->push([
                'id' => $licitacao->id,
                'titulo' => "Abertura: {$licitacao->orgao}",
                'data' => $licitacao->data_abertura,
                'tipo' => 'licitacao',
                'cor' => 'bg-blue-100 text-blue-800 border-blue-200',
                'url' => route('radar.detalhes', $licitacao),
            ]);
        }
    }

    private function adicionarDocumentos(Collection $eventos, Carbon $start, Carbon $end): void
    {
        $documentos = Documento::whereBetween('validade', [$start, $end])->get();
        
        foreach ($documentos as $doc) {
            $eventos->push([
                'id' => $doc->id,
                'titulo' => "Vencimento: {$doc->nome}",
                'data' => $doc->validade,
                'tipo' => 'documento',
                'cor' => 'bg-red-100 text-red-800 border-red-200',
                'url' => route('documentos.index'),
            ]);
        }
    }

    private function adicionarFaturamentos(Collection $eventos, Carbon $start, Carbon $end): void
    {
        $faturamentos = Faturamento::whereBetween('data_vencimento', [$start, $end])
            ->whereHas('proposta')
            ->get();
            
        foreach ($faturamentos as $fat) {
            $eventos->push([
                'id' => $fat->id,
                'titulo' => "Receber NF {$fat->numero_nf}: R$ " . number_format($fat->valor, 2, ',', '.'),
                'data' => $fat->data_vencimento,
                'tipo' => 'financeiro',
                'cor' => 'bg-green-100 text-green-800 border-green-200',
                'url' => route('financeiro.index'),
            ]);
        }
    }
}
