# Snapshot do Estado do Sistema - Licitix
**Data:** 28/01/2026
**Status:** Funcionalidades Expandidas (Documentos + CRM)

## Visão Geral
O sistema evoluiu para incluir gestão de documentos ("Doc em Dia") e um pipeline de CRM ("CRM Licitações"), integrados ao fluxo do Radar.

## Módulos Ativos

### 1. Radar de Licitações (`RadarLicitacoesController`)
- **Integração:** PNCP (Principal) e ComprasNet.
- **Melhorias Recentes:**
  - Botão "Monitorar" direto nos cards para envio ao CRM.
  - Correção de UX para licitações sem itens ("0 itens").
  - Lógica de importação automática ao monitorar (sem redirects desnecessários).
- **Status:** Estável e Integrado.

### 2. CRM de Licitações (`CrmLicitacoesController`) - NOVO
- **Objetivo:** Gestão visual (Kanban-like) do funil de vendas.
- **Funcionalidades:**
  - Pipeline com 5 etapas: Interesse, Em Análise, Preparação, Proposta Enviada, Resultado.
  - Importação automática de dados do Radar.
  - Movimentação de cards entre etapas.
  - Exclusão de licitações do monitoramento.
- **Arquivos:** `CrmLicitacoesController.php`, `Licitacao` (novos campos), `views/crm/index.blade.php`.

### 3. Gestão de Documentos ("Doc em Dia") (`DocumentosController`) - NOVO
- **Objetivo:** Controle de validade de certidões e documentos.
- **Funcionalidades:**
  - Upload de arquivos.
  - Definição de data de validade com alertas visuais (Vencido, Próximo, Em dia).
  - Exclusão segura de registros e arquivos físicos.
- **Status:** Operacional.

### 4. Propostas (`PropostaController`)
- **Funcionalidades:**
  - Criação de propostas vinculadas a licitações.
  - Integração com itens importados.
- **Status:** Funcional.

## Estrutura de Banco de Dados (Alterações Recentes)
- **Tabela `licitacoes`:** Adicionados campos `etapa_crm`, `probabilidade_ganho`, `anotacoes_crm`, `tarefa_atual`, `data_vencimento_tarefa`, `monitorada`.
- **Tabela `documentos`:** Em uso pleno.

---
*Sistema pronto para uso com novos fluxos de trabalho: Busca (Radar) -> Monitoramento (CRM) -> Gestão (Documentos/Propostas).*
