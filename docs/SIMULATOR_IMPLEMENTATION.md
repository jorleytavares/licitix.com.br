# Documentação da Implementação do Simulador e Radar

## Visão Geral
Este documento descreve a arquitetura implementada para o sistema de simulação de licitações e a lógica de negócios associada ao Radar de Licitações do Licitix.

## 1. Configuração do Ambiente

O sistema suporta um modo de simulação controlado via variáveis de ambiente.

**Arquivo `.env`:**
```ini
LICITIX_DADOS_SIMULADOS=true
INTEGRACOES_ATIVAS=false
```

**Arquivo de Configuração (`config/licitix.php`):**
```php
return [
    'integracoes_ativas' => env('INTEGRACOES_ATIVAS', false),
    'dados_simulados' => env('LICITIX_DADOS_SIMULADOS', true),
];
```

## 2. Serviços

### RadarService (`app/Services/RadarService.php`)
Atua como a camada de abstração entre o Controller e as fontes de dados (Simulador ou Integrações).

- **Método `listar(array $filtros)`**:
    - Se `dados_simulados` for `true`: Chama `RadarSimulatorService`.
    - Se `false`: Consulta o banco de dados local (`Licitacao` model).

### RadarSimulatorService (`app/Services/RadarSimulatorService.php`)
Responsável por gerar dados fictícios para testes e demonstrações.

- **Método `gerarLicitacoes(int $quantidade)`**:
    - Usa a biblioteca `Faker` para criar licitações realistas.
    - Gera campos como Órgão, Modalidade, Objeto, Datas, etc.
    - Marca a origem dos dados como `'simulado'`.

## 3. Banco de Dados

### Tabela `licitacoes`
Foi adicionado o campo `origem_dado` para distinguir registros reais de simulados.

```php
$table->enum('origem_dado', ['real', 'simulado'])->default('real');
```

## 4. Integrações (Stub)

Estrutura preparada para futuras integrações reais em `app/Services/Integrations/`:
- `IntegrationInterface`: Contrato padrão.
- `PncpIntegration`: Stub para o Portal Nacional de Contratações Públicas.
- `ComprasGovIntegration`: Stub para o Compras.gov.br.
- `BancoBrasilIntegration`: Stub para Licitações-e.

## 5. Seeders e Dados de Teste

- **`LicitacoesSeeder`**: Cria um registro de licitação "âncora" específico (`RAD-2026-00001`) e limpa a tabela antes de rodar.
- **`LicitacaoItensSeeder`**: Adiciona itens a essa licitação âncora.
- **`EmpresaSeeder` & `AdminSeeder`**: Configuram o ambiente multi-tenant com uma empresa "Licitix" e usuário admin.
