<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Proposta #{{ $proposta->codigo ?? $proposta->id }}</title>
    <style>
        @page {
            margin: 2cm;
        }
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #333;
            background: #fff;
        }
        .container {
            width: 100%;
            max-width: 21cm; /* A4 width */
            margin: 0 auto;
        }
        .header {
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            display: table; /* Flexbox support in PDF generators is tricky, table is safer */
            width: 100%;
        }
        .header-left {
            display: table-cell;
            vertical-align: top;
            width: 60%;
        }
        .header-right {
            display: table-cell;
            vertical-align: top;
            text-align: right;
            width: 40%;
        }
        .logo-container {
            margin-bottom: 10px;
        }
        .logo-container img {
            max-height: 60px;
            max-width: 200px;
        }
        .company-info h1 {
            font-size: 16pt;
            margin: 0;
            color: #000;
            text-transform: uppercase;
        }
        .company-info p {
            margin: 2px 0;
            font-size: 10pt;
            color: #555;
        }
        .doc-title {
            text-align: right;
        }
        .doc-title h2 {
            font-size: 24pt;
            margin: 0;
            color: #0B2D5B;
        }
        .doc-title p {
            margin: 5px 0 0;
            font-size: 12pt;
            color: #777;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 12pt;
            font-weight: bold;
            color: #0B2D5B;
            border-bottom: 1px solid #ccc;
            margin-bottom: 10px;
            padding-bottom: 3px;
            text-transform: uppercase;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .info-item label {
            display: block;
            font-size: 9pt;
            font-weight: bold;
            color: #777;
            text-transform: uppercase;
        }
        .info-item span {
            display: block;
            font-size: 11pt;
            color: #000;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 10pt;
        }
        th {
            background-color: #f0f0f0;
            border-bottom: 2px solid #0B2D5B;
            text-align: left;
            padding: 8px;
            font-weight: bold;
            color: #0B2D5B;
        }
        td {
            border-bottom: 1px solid #ddd;
            padding: 8px;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .totals {
            margin-top: 20px;
            width: 40%;
            margin-left: auto;
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }
        .totals-row.final {
            border-top: 2px solid #000;
            border-bottom: none;
            font-weight: bold;
            font-size: 14pt;
            margin-top: 10px;
            padding-top: 10px;
        }
        
        .footer {
            margin-top: 50px;
            border-top: 1px solid #ccc;
            padding-top: 20px;
            font-size: 9pt;
            text-align: center;
            color: #777;
        }
        .signatures {
            margin-top: 60px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }
        .signature-box {
            border-top: 1px solid #000;
            padding-top: 10px;
            text-align: center;
        }
        
        .no-print {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #fff;
            padding: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 5px;
            z-index: 1000;
        }
        .btn {
            background-color: #0B2D5B;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
        }
        .btn-secondary {
            background-color: #6c757d;
        }
        
        @media print {
            .no-print { display: none; }
            body { background: white; }
            .container { max-width: 100%; width: 100%; margin: 0; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" class="btn">🖨️ Imprimir / Salvar PDF</button>
        <a href="{{ route('propostas.detalhes', $proposta->id) }}" class="btn btn-secondary">Voltar</a>
    </div>

    <div class="container">
        <!-- Cabeçalho -->
        <div class="header">
            <div class="header-left">
                <div class="company-info">
                    @if(isset($proposta->empresa->logo_path) && $proposta->empresa->logo_path)
                        <div class="logo-container">
                            <img src="{{ public_path('storage/' . $proposta->empresa->logo_path) }}" alt="Logo">
                        </div>
                    @endif
                    <h1>{{ $proposta->empresa->razao_social ?? 'Sua Empresa' }}</h1>
                    <p>CNPJ: {{ $proposta->empresa->cnpj ?? '00.000.000/0000-00' }}</p>
                    @if($proposta->empresa->endereco)<p>{{ $proposta->empresa->endereco }}</p>@endif
                    @if($proposta->empresa->email_contato)<p>{{ $proposta->empresa->email_contato }} | {{ $proposta->empresa->telefone_contato }}</p>@endif
                    @if($proposta->empresa->website)<p>{{ $proposta->empresa->website }}</p>@endif
                </div>
            </div>
            <div class="header-right">
                <div class="doc-title">
                    <h2>PROPOSTA</h2>
                    <p>Nº {{ $proposta->codigo ?? $proposta->id }}</p>
                    <p style="font-size: 10pt; margin-top: 5px;">Data: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Dados do Cliente / Licitação -->
        <div class="section">
            <div class="section-title">Dados do Cliente / Licitação</div>
            <div class="info-grid">
                <div class="info-item">
                    <label>Órgão / Cliente</label>
                    <span>{{ $proposta->licitacao->orgao ?? 'Cliente não informado' }}</span>
                </div>
                <div class="info-item">
                    <label>Modalidade / Edital</label>
                    <span>
                        {{ $proposta->licitacao->modalidade ?? 'Dispensa' }} 
                        {{ $proposta->licitacao->numero_edital ? '- Edital ' . $proposta->licitacao->numero_edital : '' }}
                    </span>
                </div>
                <div class="info-item">
                    <label>Objeto</label>
                    <span>{{ $proposta->licitacao->objeto ?? 'Fornecimento de produtos/serviços diversos.' }}</span>
                </div>
                <div class="info-item">
                    <label>UASG / Código</label>
                    <span>{{ $proposta->licitacao->codigo_radar ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Itens da Proposta -->
        <div class="section">
            <div class="section-title">Itens e Valores</div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 45%;">Descrição</th>
                        <th class="text-center" style="width: 10%;">Unid.</th>
                        <th class="text-center" style="width: 10%;">Qtd.</th>
                        <th class="text-right" style="width: 15%;">V. Unit.</th>
                        <th class="text-right" style="width: 15%;">V. Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proposta->itens as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $item->nome ?? 'Item ' . ($index + 1) }}</strong><br>
                            <span style="font-size: 9pt; color: #555;">{{ $item->descricao }}</span>
                            @if($item->marca) <br><span style="font-size: 8pt;">Marca: {{ $item->marca }}</span> @endif
                        </td>
                        <td class="text-center">{{ $item->unidade ?? 'UN' }}</td>
                        <td class="text-center">{{ number_format($item->quantidade, 2, ',', '.') }}</td>
                        <td class="text-right">R$ {{ number_format($item->valor_unitario, 2, ',', '.') }}</td>
                        <td class="text-right">R$ {{ number_format($item->valor_total, 2, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Nenhum item cadastrado nesta proposta.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Totais -->
        <div class="totals">
            <div class="totals-row">
                <span>Subtotal Itens:</span>
                <span>R$ {{ number_format($proposta->itens->sum('valor_total'), 2, ',', '.') }}</span>
            </div>
            
            @if($proposta->frete_percentual > 0)
            <div class="totals-row">
                <span>Frete ({{ $proposta->frete_percentual }}%):</span>
                <span>R$ {{ number_format($proposta->itens->sum('valor_total') * ($proposta->frete_percentual/100), 2, ',', '.') }}</span>
            </div>
            @endif

            @if($proposta->impostos_percentual > 0)
            <div class="totals-row">
                <span>Impostos ({{ $proposta->impostos_percentual }}%):</span>
                <span>R$ {{ number_format($proposta->itens->sum('valor_total') * ($proposta->impostos_percentual/100), 2, ',', '.') }}</span>
            </div>
            @endif

            <div class="totals-row final">
                <span>VALOR TOTAL:</span>
                <span>R$ {{ number_format($proposta->valor_total, 2, ',', '.') }}</span>
            </div>
        </div>

        <!-- Validade e Observações -->
        <div class="section" style="margin-top: 40px;">
            <div class="section-title">Observações</div>
            <p style="font-size: 10pt;">
                <strong>Validade da Proposta:</strong> 60 dias a partir da data de emissão.<br>
                <strong>Prazo de Entrega:</strong> Conforme edital.<br>
                <strong>Pagamento:</strong> Conforme edital.
            </p>
        </div>

        <!-- Assinaturas -->
        <div class="signatures">
            <div class="signature-box">
                <p><strong>{{ $proposta->empresa->razao_social ?? 'Contratada' }}</strong></p>
                <p style="font-size: 9pt;">Representante Legal</p>
            </div>
            <div class="signature-box">
                <p><strong>{{ $proposta->licitacao->orgao ?? 'Contratante' }}</strong></p>
                <p style="font-size: 9pt;">Responsável</p>
            </div>
        </div>

        <!-- Rodapé -->
        <div class="footer">
            <p>Gerado por Licitix - Sistema de Gestão de Licitações</p>
            <p>{{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>