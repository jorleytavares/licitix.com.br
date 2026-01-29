<?php

namespace App\Services;

use App\Models\Proposta;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;
use Illuminate\Support\Facades\Storage;

class PropostaDocGeneratorService
{
    public function gerarDocx(Proposta $proposta): string
    {
        $phpWord = new PhpWord();
        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(11);

        // Estilos
        $phpWord->addTitleStyle(1, ['bold' => true, 'size' => 16, 'color' => '333333'], ['alignment' => Jc::CENTER, 'spaceAfter' => 240]);
        $phpWord->addTitleStyle(2, ['bold' => true, 'size' => 14, 'color' => '666666'], ['spaceAfter' => 120]);
        
        $section = $phpWord->addSection();

        // Cabeçalho da Empresa (Se houver logo ou dados)
        if ($proposta->empresa) {
            $header = $section->addHeader();
            if ($proposta->empresa->logo_path && Storage::disk('public')->exists($proposta->empresa->logo_path)) {
                $header->addImage(storage_path('app/public/' . $proposta->empresa->logo_path), [
                    'width' => 100,
                    'height' => 50,
                    'alignment' => Jc::CENTER
                ]);
            }
            $header->addText($proposta->empresa->razao_social ?? 'Sua Empresa', ['bold' => true], ['alignment' => Jc::CENTER]);
            $header->addText('CNPJ: ' . ($proposta->empresa->cnpj ?? ''), [], ['alignment' => Jc::CENTER]);
        }

        // Título do Documento
        $section->addTitle('PROPOSTA COMERCIAL', 1);
        $section->addText('Nº Proposta: ' . ($proposta->codigo ?? $proposta->id), ['bold' => true], ['alignment' => Jc::RIGHT]);
        $section->addText('Data: ' . date('d/m/Y'), [], ['alignment' => Jc::RIGHT]);
        $section->addTextBreak(1);

        // Dados do Cliente / Licitação
        $section->addTitle('1. DADOS DO CLIENTE / LICITAÇÃO', 2);
        $tableStyle = ['borderSize' => 6, 'borderColor' => '999999', 'cellMargin' => 50];
        $phpWord->addTableStyle('ClientTable', $tableStyle);
        $table = $section->addTable('ClientTable');
        
        $table->addRow();
        $table->addCell(2000)->addText('Órgão:', ['bold' => true]);
        $table->addCell(7000)->addText($proposta->licitacao->orgao ?? 'N/A');
        
        $table->addRow();
        $table->addCell(2000)->addText('Edital:', ['bold' => true]);
        $table->addCell(7000)->addText($proposta->licitacao->numero_edital ?? 'N/A');

        $table->addRow();
        $table->addCell(2000)->addText('Objeto:', ['bold' => true]);
        $table->addCell(7000)->addText($proposta->licitacao->objeto ?? 'N/A');

        $section->addTextBreak(1);

        // Itens da Proposta
        $section->addTitle('2. ITENS E VALORES', 2);
        
        $itemTableStyle = ['borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 50];
        $firstRowStyle = ['bgColor' => 'F2F2F2', 'bold' => true];
        $phpWord->addTableStyle('ItemsTable', $itemTableStyle, $firstRowStyle);
        $table = $section->addTable('ItemsTable');

        // Cabeçalho da Tabela
        $table->addRow();
        $table->addCell(500)->addText('#', ['bold' => true]);
        $table->addCell(4500)->addText('Descrição', ['bold' => true]);
        $table->addCell(1000)->addText('Qtd.', ['bold' => true], ['alignment' => Jc::RIGHT]);
        $table->addCell(1500)->addText('V. Unit.', ['bold' => true], ['alignment' => Jc::RIGHT]);
        $table->addCell(1500)->addText('Total', ['bold' => true], ['alignment' => Jc::RIGHT]);

        // Itens
        foreach ($proposta->itens as $index => $item) {
            $table->addRow();
            $table->addCell(500)->addText($index + 1);
            $table->addCell(4500)->addText($item->descricao);
            $table->addCell(1000)->addText(number_format($item->quantidade, 0, ',', '.'), [], ['alignment' => Jc::RIGHT]);
            $table->addCell(1500)->addText('R$ ' . number_format($item->valor_unitario, 2, ',', '.'), [], ['alignment' => Jc::RIGHT]);
            $table->addCell(1500)->addText('R$ ' . number_format($item->valor_total, 2, ',', '.'), [], ['alignment' => Jc::RIGHT]);
        }

        // Total Final
        $table->addRow();
        $table->addCell(7500, ['gridSpan' => 4, 'bgColor' => 'E6E6E6'])->addText('VALOR TOTAL DA PROPOSTA', ['bold' => true], ['alignment' => Jc::RIGHT]);
        $table->addCell(1500, ['bgColor' => 'E6E6E6'])->addText('R$ ' . number_format($proposta->valor_total, 2, ',', '.'), ['bold' => true], ['alignment' => Jc::RIGHT]);

        $section->addTextBreak(1);

        // Validade e Observações
        $section->addTitle('3. CONDIÇÕES GERAIS', 2);
        $section->addText('Validade da Proposta: ' . ($proposta->validade_proposta ? $proposta->validade_proposta->format('d/m/Y') : '60 dias'), ['bold' => true]);
        
        if ($proposta->observacoes) {
            $section->addTextBreak(1);
            $section->addText('Observações:', ['bold' => true]);
            $section->addText($proposta->observacoes);
        }

        $section->addTextBreak(2);
        
        // Assinatura
        $section->addText('___________________________________________________', [], ['alignment' => Jc::CENTER]);
        $section->addText($proposta->empresa->razao_social ?? 'Assinatura do Responsável', [], ['alignment' => Jc::CENTER]);

        // Salvar Arquivo
        $directory = storage_path('app/public/propostas/generated');
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $filename = 'Proposta_' . ($proposta->codigo ?? $proposta->id) . '_' . time() . '.docx';
        $path = $directory . '/' . $filename;

        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($path);

        return $path;
    }
}
