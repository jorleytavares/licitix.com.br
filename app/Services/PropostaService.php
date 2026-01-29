<?php

namespace App\Services;

use App\Models\Licitacao;
use App\Models\Proposta;
use Illuminate\Support\Facades\DB;

class PropostaService
{
    /**
     * Cria uma nova proposta.
     */
    public function criarProposta(array $dados): Proposta
    {
        return DB::transaction(function () use ($dados) {
            $proposta = Proposta::create([
                'licitacao_id' => $dados['licitacao_id'],
                'status' => $dados['status'],
                'valor_total' => $dados['valor_total'],
                'codigo' => 'PROP-' . date('YmdHis'),
            ]);

            if (!empty($dados['itens'])) {
                // Criação manual
                foreach ($dados['itens'] as $item) {
                    $proposta->itens()->create([
                        'descricao' => $item['descricao'],
                        'quantidade' => $item['quantidade'],
                        'valor_unitario' => $item['valor_unitario'],
                        'valor_total' => $item['quantidade'] * $item['valor_unitario'],
                    ]);
                }
            } else {
                // Criação automática a partir da Licitação
                $licitacao = Licitacao::with('items')->find($dados['licitacao_id']);
                
                if ($licitacao && $licitacao->items->count() > 0) {
                    $itensParaCopiar = $licitacao->items;
                    
                    if (!empty($dados['itens_selecionados'])) {
                        $itensParaCopiar = $itensParaCopiar->whereIn('id', $dados['itens_selecionados']);
                    }

                    foreach ($itensParaCopiar as $item) {
                        $proposta->itens()->create([
                            'descricao' => $item->descricao,
                            'quantidade' => $item->quantidade,
                            'unidade' => $item->unidade,
                            'valor_unitario' => $item->valor_referencia ?? 0,
                            'valor_total' => $item->quantidade * ($item->valor_referencia ?? 0),
                            'custo_unitario' => 0 
                        ]);
                    }
                    
                    $proposta->valor_total = $proposta->itens()->sum('valor_total');
                    $proposta->save();
                }
            }

            // Marca licitação como monitorada se já não for
            if ($proposta->licitacao && !$proposta->licitacao->monitorada) {
                $proposta->licitacao->update(['monitorada' => true]);
            }

            return $proposta;
        });
    }

    /**
     * Salva a simulação de lucro da proposta.
     */
    public function salvarSimulacao(Proposta $proposta, array $dados): Proposta
    {
        return DB::transaction(function () use ($proposta, $dados) {
            // Atualiza parâmetros globais
            $proposta->update([
                'impostos_percentual' => $dados['impostos_percentual'],
                'frete_percentual' => $dados['frete_percentual'],
                'taxas_extras_percentual' => $dados['taxas_extras_percentual'],
                'custos_extras_valor' => $dados['custos_extras_valor'],
            ]);

            // Atualiza custos e preços dos itens
            if (!empty($dados['itens'])) {
                foreach ($dados['itens'] as $itemData) {
                    $item = $proposta->itens()->where('id', $itemData['id'])->first();
                    if ($item) {
                        $item->update([
                            'custo_unitario' => $itemData['custo_unitario'],
                            'valor_unitario' => $itemData['valor_unitario'],
                            'valor_total' => $item->quantidade * $itemData['valor_unitario']
                        ]);
                    }
                }
            }
            
            // Recalcula o valor total da proposta
            $proposta->valor_total = $proposta->itens()->sum('valor_total');
            $proposta->save();

            return $proposta;
        });
    }

    /**
     * Atualiza uma proposta existente.
     */
    public function atualizarProposta(Proposta $proposta, array $dados): Proposta
    {
        return DB::transaction(function () use ($proposta, $dados) {
            $proposta->update($dados);
            return $proposta;
        });
    }

    /**
     * Exclui uma proposta.
     */
    public function excluirProposta(Proposta $proposta): void
    {
        DB::transaction(function () use ($proposta) {
            $proposta->itens()->delete();
            $proposta->delete();
        });
    }

    /**
     * Marca a proposta como enviada.
     */
    public function enviarProposta(Proposta $proposta): Proposta
    {
        return DB::transaction(function () use ($proposta) {
            $proposta->update(['status' => 'enviada']);
            
            if ($proposta->licitacao) {
                $proposta->licitacao->update(['etapa_crm' => 'proposta_enviada']);
            }
            
            return $proposta;
        });
    }

    /**
     * Marca a proposta como ganha.
     */
    public function marcarComoGanha(Proposta $proposta): Proposta
    {
        return DB::transaction(function () use ($proposta) {
            $proposta->update(['status' => 'ganhou']);
            
            if ($proposta->licitacao) {
                $proposta->licitacao->update([
                    'etapa_crm' => 'resultado',
                    'status' => 'concluida'
                ]);
            }
            
            return $proposta;
        });
    }

    /**
     * Marca a proposta como perdida.
     */
    public function marcarComoPerdida(Proposta $proposta): Proposta
    {
        return DB::transaction(function () use ($proposta) {
            $proposta->update(['status' => 'perdeu']); // Fixed status from 'perdida' to 'perdeu' based on DB enum/usage
            
            if ($proposta->licitacao) {
                $proposta->licitacao->update([
                    'etapa_crm' => 'resultado'
                ]);
            }
            
            return $proposta;
        });
    }

    /**
     * Adiciona um documento à proposta.
     */
    public function adicionarDocumento(Proposta $proposta, array $dados, $arquivo): \App\Models\PropostaDocumento
    {
        return DB::transaction(function () use ($proposta, $dados, $arquivo) {
            $path = $arquivo->store('propostas/' . $proposta->id, 'public');

            return $proposta->documentos()->create([
                'tipo' => $dados['tipo'],
                'nome_original' => $arquivo->getClientOriginalName(),
                'caminho_arquivo' => $path,
            ]);
        });
    }

    /**
     * Remove um documento da proposta.
     */
    public function removerDocumento(\App\Models\PropostaDocumento $documento): void
    {
        DB::transaction(function () use ($documento) {
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($documento->caminho_arquivo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($documento->caminho_arquivo);
            }
            $documento->delete();
        });
    }
}
