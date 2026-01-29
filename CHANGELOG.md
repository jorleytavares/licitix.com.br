# Changelog

Todas as alterações notáveis neste projeto serão documentadas neste arquivo.

## [Não Lançado] - 2026-01-28

### Adicionado
- **Integração PNCP:** Implementação completa da busca de licitações reais via API do Portal Nacional de Contratações Públicas.
- **Filtros Avançados no Radar:**
  - **Modalidade:** Filtro por Pregão (6), Dispensa (8) e Concorrência (13).
  - **Geolocalização:** Lista completa de Estados agrupados por Região e novo campo de busca por Cidade.
- **Visualização de Detalhes:** Página de detalhes conectada ao endpoint `/contratacoes/{cnpj}/{ano}/{sequencial}` do PNCP para dados fidedignos.

### Alterado
- **Configuração Padrão:** O sistema agora prioriza dados reais (`INTEGRACOES_ATIVAS=true`) sobre dados simulados.
- **Interface do Radar:** Layout do formulário de busca otimizado para acomodar novos filtros sem poluir a interface.
- **Tratamento de Erros:** Melhoria na robustez da integração com fallbacks para evitar telas brancas quando a API do PNCP oscila.

### Removido
- Arquivos de teste temporários (`test_pncp.php`, `debug_pncp_class.php`) utilizados durante o desenvolvimento da integração.
