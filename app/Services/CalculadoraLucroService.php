<?php

namespace App\Services;

use App\Models\Proposta;

class CalculadoraLucroService
{
    /**
     * Calcula o lucro estimado e totais da proposta.
     *
     * @param Proposta $proposta
     * @return array
     */
    public function calcular(Proposta $proposta): array
    {
        $simulacoes = [];
        $receitaTotal = 0;
        $custoTotal = 0;
        $impostosTotal = 0;
        $freteTotal = 0;
        $taxasTotal = 0;
        $lucroTotal = 0;

        // Parâmetros da proposta ou padrões
        $impostoPerc = $proposta->impostos_percentual ?? 15.0;
        $fretePerc = $proposta->frete_percentual ?? 5.0;
        $taxasPerc = $proposta->taxas_extras_percentual ?? 2.0;

        foreach ($proposta->itens as $item) {
            // Se custo unitário não definido, assume 60% do valor unitário como fallback
            $custoUnitario = $item->custo_unitario ?? ($item->valor_unitario * 0.6);
            
            $custo = $item->quantidade * $custoUnitario;
            $precoVenda = $item->quantidade * $item->valor_unitario;

            // Componentes de custo baseados no preço de venda
            $impostos = $precoVenda * ($impostoPerc / 100);
            $frete = $precoVenda * ($fretePerc / 100);
            $taxas = $precoVenda * ($taxasPerc / 100);

            $precoMinimo = $custo + $impostos + $frete + $taxas;
            $lucro = $precoVenda - $precoMinimo;
            $margem = $precoVenda > 0 ? ($lucro / $precoVenda) * 100 : 0;

            // Acumulando totais
            $receitaTotal += $precoVenda;
            $custoTotal += $custo;
            $impostosTotal += $impostos;
            $freteTotal += $frete;
            $taxasTotal += $taxas;
            $lucroTotal += $lucro;

            $simulacoes[] = [
                'item_id' => $item->id,
                'descricao' => $item->descricao,
                'quantidade' => $item->quantidade,
                'valor_unitario' => $item->valor_unitario,
                'custo_unitario' => $custoUnitario, // Retorna o custo usado
                'custo_total' => $custo,
                'impostos' => $impostos,
                'frete' => $frete,
                'taxas' => $taxas,
                'preco_minimo' => $precoMinimo,
                'lucro' => $lucro,
                'margem' => round($margem, 2),
            ];
        }

        // Custos extras fixos
        $custosExtras = $proposta->custos_extras_valor ?? 0;
        $lucroTotal -= $custosExtras;
        $precoMinimoTotal = $custoTotal + $impostosTotal + $freteTotal + $taxasTotal + $custosExtras;

        $margemGeral = $receitaTotal > 0 ? ($lucroTotal / $receitaTotal) * 100 : 0;

        return [
            'itens' => $simulacoes,
            'totais' => [
                'receita_bruta' => $receitaTotal,
                'custo_produtos' => $custoTotal,
                'impostos' => $impostosTotal,
                'frete' => $freteTotal,
                'taxas_extras' => $taxasTotal,
                'custos_fixos_extras' => $custosExtras,
                'preco_minimo_total' => $precoMinimoTotal,
                'lucro_estimado' => $lucroTotal,
                'margem_lucro_percentual' => round($margemGeral, 2),
            ],
            'parametros' => [
                'impostos_percentual' => $impostoPerc,
                'frete_percentual' => $fretePerc,
                'taxas_extras_percentual' => $taxasPerc,
                'custos_extras_valor' => $custosExtras,
            ]
        ];
    }
}
