# Documentação das Regras de Negócio e Cálculos Financeiros

## 1. Calculadora de Lucro (`CalculadoraLucroService`)

Este serviço (`app/Services/CalculadoraLucroService.php`) é responsável por realizar as projeções financeiras de uma proposta.

### Fórmulas Implementadas

1. **Custos Variáveis e Despesas:**
    * **Impostos**: 15% do Preço de Venda (Estimado).
    * **Frete**: 5% do Preço de Venda (Estimado).
    * **Taxas Administrativas**: 2% do Preço de Venda.

2. **Preço Mínimo:**
    > `Preço Mínimo = Custo Produto + Impostos + Frete + Taxas`

3. **Lucro Estimado:**
    > `Lucro = Preço Venda - Preço Mínimo`

4. **Margem de Lucro (%):**
    > `Margem = (Lucro / Preço Venda) * 100`

### Estrutura de Retorno

O serviço retorna um array contendo:
* `itens`: Detalhamento do cálculo por item da proposta.
* `totais`: Consolidação dos valores (Receita Bruta, Custos Totais, Lucro Estimado, Margem Geral).

---

## 2. Controle Financeiro de Contratos (`FinanceiroService`)

Gerencia o ciclo de vida financeiro de um contrato ganho.

### Lógica de Saldos

Implementada em `app/Services/FinanceiroService.php`:

1. **Saldo a Receber:**
    > `Saldo = Valor Contrato - Valor Recebido`

2. **Status de Pagamento:**
    * `recebido`: Se `Valor Recebido >= Valor Contrato`.
    * `parcial`: Se `Valor Recebido > 0` e menor que o total.
    * `pendente`: Se nenhum valor foi recebido.

### Banco de Dados (`financeiro_licitacoes`)

Estrutura criada para armazenar o estado financeiro:
* `valor_contrato`
* `valor_faturado`
* `valor_recebido`
* `saldo`
* `status_pagamento`
