<?php

namespace App\Services;

use App\Models\FinanceiroLicitacao;
use App\Models\Proposta;
use App\Models\Faturamento;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class FinanceiroService
{
    /**
     * Retorna contratos ativos ou ganhos com seus dados financeiros.
     * Garante a criação do registro financeiro se não existir.
     *
     * @return Collection
     */
    public function listarContratos(): Collection
    {
        $contratos = Proposta::whereIn('status', ['ganhou', 'contrato_ativo', 'faturado', 'recebido'])
            ->with(['licitacao', 'empresa', 'faturamentos', 'financeiro'])
            ->get();

        // Garante que existe registro financeiro para cada contrato (Lazy Creation)
        foreach ($contratos as $contrato) {
            if (!$contrato->financeiro) {
                $financeiro = FinanceiroLicitacao::create([
                    'proposta_id' => $contrato->id,
                    'valor_contrato' => $contrato->valor_total,
                    'valor_faturado' => 0,
                    'valor_recebido' => 0,
                    'saldo' => $contrato->valor_total,
                    'status_pagamento' => 'pendente'
                ]);
                $contrato->setRelation('financeiro', $financeiro);
            }
        }

        return $contratos;
    }

    /**
     * Retorna contas a receber (faturas pendentes).
     *
     * @return Collection
     */
    public function listarContasReceber(): Collection
    {
        // EmpresaScope aplicado automaticamente em Faturamento
        return Faturamento::where('status', 'pendente')
            ->with(['proposta.licitacao'])
            ->orderBy('data_vencimento', 'asc')
            ->get();
    }

    /**
     * Registra um novo faturamento para uma proposta.
     *
     * @param Proposta $proposta
     * @param array $dados
     * @return Faturamento
     */
    public function registrarFaturamento(Proposta $proposta, array $dados): Faturamento
    {
        return DB::transaction(function () use ($proposta, $dados) {
            $faturamento = Faturamento::create([
                'proposta_id' => $proposta->id,
                'empresa_id' => $proposta->empresa_id, // Copia explicitamente da proposta
                'numero_nf' => $dados['numero_nf'],
                'valor' => $dados['valor'],
                'data_emissao' => $dados['data_emissao'],
                'data_vencimento' => $dados['data_vencimento'],
                'status' => 'pendente'
            ]);

            // Atualiza status da proposta se for o primeiro
            if ($proposta->status !== 'faturado') {
                $proposta->update(['status' => 'faturado']);
            }
            
            // Atualiza totais financeiros
            if ($proposta->financeiro) {
                 $proposta->financeiro->valor_faturado += $faturamento->valor;
                 $proposta->financeiro->save();
                 
                 $this->atualizarSaldos($proposta->financeiro);
            }

            return $faturamento;
        });
    }

    /**
     * Registra o recebimento de um faturamento.
     *
     * @param Faturamento $faturamento
     * @param array $dados
     * @return Faturamento
     */
    public function registrarRecebimento(Faturamento $faturamento, array $dados): Faturamento
    {
        return DB::transaction(function () use ($faturamento, $dados) {
            $faturamento->update([
                'data_pagamento' => $dados['data_pagamento'],
                'status' => 'pago'
            ]);
            
            $proposta = $faturamento->proposta;
            
            // Garante que o registro financeiro existe antes de atualizar
            if ($proposta && !$proposta->financeiro) {
                $proposta->financeiro()->create([
                    'valor_contrato' => $proposta->valor_total,
                    'valor_faturado' => $proposta->faturamentos()->sum('valor'),
                    'valor_recebido' => 0,
                    'saldo' => $proposta->valor_total,
                    'status_pagamento' => 'pendente'
                ]);
                $proposta->load('financeiro');
            }

            if ($proposta && $proposta->financeiro) {
                 $proposta->financeiro->valor_recebido += $faturamento->valor;
                 $proposta->financeiro->save();
                 
                 $this->atualizarSaldos($proposta->financeiro);
            }

            return $faturamento;
        });
    }

    /**
     * Atualiza e retorna o resumo financeiro de um contrato.
     *
     * @param FinanceiroLicitacao $financeiro
     * @return FinanceiroLicitacao
     */
    public function atualizarSaldos(FinanceiroLicitacao $financeiro): FinanceiroLicitacao
    {
        // Saldo a receber = Valor Global do Contrato - Total Recebido
        $financeiro->saldo = $financeiro->valor_contrato - $financeiro->valor_recebido;

        // Atualiza status básico
        if ($financeiro->valor_recebido >= $financeiro->valor_contrato) {
            $financeiro->status_pagamento = 'recebido';
        } elseif ($financeiro->valor_recebido > 0) {
            $financeiro->status_pagamento = 'parcial';
        } else {
            $financeiro->status_pagamento = 'pendente';
        }

        $financeiro->save();

        return $financeiro;
    }
}
