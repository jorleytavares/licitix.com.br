<?php

namespace App\Services;

use App\Models\ItemCatalogo;
use App\Models\Licitacao;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class ImportService
{
    /**
     * Importa catálogo de itens via CSV.
     */
    public function importarCatalogo(UploadedFile $file): array
    {
        $importedCount = 0;
        $errors = [];

        try {
            DB::beginTransaction();

            if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
                // Detectar separador
                $separator = $this->detectSeparator($file->getRealPath());
                
                // Ler cabeçalho
                $header = fgetcsv($handle, 1000, $separator);
                
                if (!$header || count($header) < 2) {
                    fclose($handle);
                    throw new \Exception("Cabeçalho inválido ou separador não detectado.");
                }

                // Normalizar cabeçalho
                $header = array_map(function($h) {
                    return Str::slug($h, '_');
                }, $header);

                // Mapeamento de campos
                $map = [
                    'codigo' => ['codigo', 'cod', 'sku'],
                    'nome' => ['nome', 'produto', 'descricao', 'item'],
                    'preco_custo' => ['preco', 'custo', 'valor', 'preco_custo'],
                    'unidade_medida' => ['unidade', 'un', 'medida', 'und'],
                    'ncm' => ['ncm', 'codigo_ncm'],
                    'marca' => ['marca', 'fabricante'],
                    'modelo' => ['modelo'],
                    'codigo_barras' => ['ean', 'gtin', 'codigo_barras', 'barras'],
                ];

                while (($data = fgetcsv($handle, 1000, $separator)) !== false) {
                    if (empty(array_filter($data))) continue;

                    if (count($header) !== count($data)) {
                        $errors[] = "Linha com número de colunas inválido: " . implode(', ', $data);
                        continue;
                    }

                    $row = array_combine($header, $data);
                    $itemData = $this->mapRow($row, $map);

                    if (empty($itemData['nome'])) {
                        continue;
                    }

                    // Formatar preço
                    if (isset($itemData['preco_custo'])) {
                        $itemData['preco_custo'] = $this->formatPrice($itemData['preco_custo']);
                    }

                    ItemCatalogo::updateOrCreate(
                        ['nome' => $itemData['nome']], 
                        $itemData
                    );

                    $importedCount++;
                }
                fclose($handle);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro na importação de catálogo: ' . $e->getMessage());
            throw $e;
        }

        return ['count' => $importedCount, 'errors' => $errors];
    }

    /**
     * Importa licitações via CSV.
     */
    public function importarLicitacoes(UploadedFile $file): array
    {
        $importedCount = 0;
        $errors = [];

        try {
            DB::beginTransaction();

            if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
                $separator = $this->detectSeparator($file->getRealPath());
                $header = fgetcsv($handle, 1000, $separator);
                
                if (!$header || count($header) < 2) {
                    fclose($handle);
                    throw new \Exception("Cabeçalho inválido ou separador não detectado.");
                }

                $header = array_map(function($h) {
                    return Str::slug($h, '_');
                }, $header);

                $map = [
                    'numero_edital' => ['edital', 'numero', 'num_edital'],
                    'orgao' => ['orgao', 'entidade', 'cliente'],
                    'objeto' => ['objeto', 'descricao', 'resumo'],
                    'data_abertura' => ['abertura', 'data', 'data_inicio', 'data_abertura'],
                    'valor_estimado' => ['valor', 'valor_estimado', 'orcamento'],
                    'modalidade' => ['modalidade', 'tipo'],
                    'estado' => ['uf', 'estado'],
                    'municipio' => ['cidade', 'municipio'],
                    'codigo_radar' => ['codigo', 'id', 'identificador'],
                ];

                while (($data = fgetcsv($handle, 1000, $separator)) !== false) {
                    if (empty(array_filter($data))) continue;

                    if (count($header) !== count($data)) {
                        $errors[] = "Linha com colunas inválidas";
                        continue;
                    }

                    $row = array_combine($header, $data);
                    $licitacaoData = $this->mapRow($row, $map);

                    if (empty($licitacaoData['orgao']) || empty($licitacaoData['objeto'])) {
                        continue;
                    }

                    $licitacaoData['numero_edital'] = $licitacaoData['numero_edital'] ?? 'S/N';
                    $licitacaoData['data_abertura'] = $this->formatDate($licitacaoData['data_abertura'] ?? null);
                    $licitacaoData['origem_dado'] = 'importacao_csv';
                    $licitacaoData['status'] = 'aberta';

                    if (isset($licitacaoData['valor_estimado'])) {
                        $licitacaoData['valor_estimado'] = $this->formatPrice($licitacaoData['valor_estimado']);
                    }

                    // Evitar duplicatas usando numero_edital e orgao como chave composta
                    Licitacao::updateOrCreate(
                        [
                            'numero_edital' => $licitacaoData['numero_edital'],
                            'orgao' => $licitacaoData['orgao']
                        ],
                        $licitacaoData
                    );

                    $importedCount++;
                }
                fclose($handle);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro na importação de licitações: ' . $e->getMessage());
            throw $e;
        }

        return ['count' => $importedCount, 'errors' => $errors];
    }

    private function detectSeparator(string $filePath): string
    {
        $handle = fopen($filePath, 'r');
        $line = fgets($handle);
        fclose($handle);
        
        return (strpos($line, ';') !== false) ? ';' : ',';
    }

    private function mapRow(array $row, array $map): array
    {
        $data = [];
        foreach ($map as $dbField => $csvFields) {
            foreach ($csvFields as $csvField) {
                if (isset($row[$csvField])) {
                    $data[$dbField] = trim($row[$csvField]);
                    break;
                }
            }
        }
        return $data;
    }

    private function formatPrice(string $price): float
    {
        $price = str_replace(['R$', ' ', '.'], '', $price);
        $price = str_replace(',', '.', $price);
        return (float) $price;
    }

    private function formatDate(?string $date): string
    {
        if (!$date) {
            return now()->format('Y-m-d');
        }

        try {
            if (strpos($date, '/') !== false) {
                return Carbon::createFromFormat('d/m/Y', explode(' ', $date)[0])->format('Y-m-d');
            }
            return Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            return now()->format('Y-m-d');
        }
    }
}
