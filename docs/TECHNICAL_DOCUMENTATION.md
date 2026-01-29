# Documentação Técnica do Licitix

## 1. Visão Geral
O **Licitix** é uma plataforma SaaS para gestão inteligente de licitações, focada em otimizar o processo de captação, análise e participação em pregões eletrônicos.

### Tecnologias Principais
- **Backend:** PHP 8.4 (Laravel 12.x)
- **Banco de Dados:** MySQL 8.0+
- **Frontend:** Blade Templates + Tailwind CSS
- **Ambiente de Desenvolvimento:** Laragon (Windows)

## 2. Configuração do Ambiente Local

### Pré-requisitos
- **Laragon** instalado (recomendado para Windows).
- **PHP 8.4** (configurado no Laragon).
- **MySQL 8.0+**.
- **Composer** (Gerenciador de dependências PHP).

### Instalação e Execução

1. **Clonar/Baixar o Projeto:**
   O projeto deve estar na pasta `www` do Laragon ou em um diretório acessível.
   Raiz atual: `C:\Users\hosta\Desktop\licitix.com.br`

2. **Configuração do `.env`:**
   Copie o `.env.example` para `.env` e configure o banco de dados:
   ```ini
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=licitix
   DB_USERNAME=root
   DB_PASSWORD=
   ```

   Habilite o modo de simulação para desenvolvimento sem integrações reais (Opcional):
   ```ini
   LICITIX_DADOS_SIMULADOS=false
   INTEGRACOES_ATIVAS=true
   ```

3. **Instalação de Dependências:**
   ```bash
   composer install
   npm install
   npm run build
   ```

4. **Banco de Dados:**
   Execute as migrações e seeders para popular o banco com dados iniciais:
   ```bash
   php artisan migrate --seed
   ```

5. **Execução do Servidor:**
   Utilize o servidor embutido do Laravel para evitar conflitos de porta (80/443):
   ```bash
   php artisan serve --port=8001
   ```
   Acesse em: [http://127.0.0.1:8001](http://127.0.0.1:8001)

## 3. Arquitetura do Sistema

### Estrutura de Pastas
- `app/Http/Controllers`: Controladores das rotas (Radar, Propostas, CRM).
- `app/Services`: Lógica de negócio complexa (Simuladores, Cálculos).
- `app/Models`: Modelos Eloquent (ORM).
- `resources/views`: Templates Blade.
- `routes/web.php`: Definição de rotas web.

### Módulos Principais

#### Radar de Licitações
- **Rota:** `/radar-licitacoes`
- **Controller:** `RadarLicitacoesController`
- **Serviço:** `RadarService`
- **Funcionalidade:** Lista oportunidades de licitação. 
  - **Modo Real (Padrão):** Conecta-se à API do PNCP (Portal Nacional de Contratações Públicas) via `PncpIntegration`. Suporta filtros por Modalidade (Pregão, Dispensa, etc.), Estado (UF) e Cidade.
  - **Modo Simulado:** Se `LICITIX_DADOS_SIMULADOS=true`, utiliza o `RadarSimulatorService` para gerar dados fictícios.

#### Detalhes da Licitação
- **Rota:** `/radar-licitacoes/{id}/detalhes`
- **Funcionalidade:** Exibe informações detalhadas (objeto, órgão, itens).
- **Integração:** Busca detalhes diretamente na API do PNCP usando ID composto (`sequencial-ano-cnpj`). Inclui fallback para dados essenciais em caso de falha na API.

#### Integração PRD
- O arquivo `PRD.json` (localizado um nível acima da raiz do projeto) contém os requisitos do produto.
- **Visualização:** Foi criada uma rota `/prd/view` (via `web.php`) para facilitar a consulta dos requisitos diretamente pelo navegador.

## 4. Troubleshooting (Resolução de Problemas)

### Erro 503 (Service Unavailable)
- **Causa:** Conflito de portas (80/443) com outros serviços ou configuração do Apache no Laragon.
- **Solução:** Parar o Apache do Laragon e usar `php artisan serve --port=8001`.

### Erro 500 (Undefined property)
- **Causa:** Tentativa de acessar propriedades em objetos `stdClass` que não foram definidos no simulador.
- **Solução:** O `RadarSimulatorService` foi atualizado para incluir todos os campos esperados pelas views (`show.blade.php`), como `numero_edital` e estrutura correta de itens.

### Acesso Negado ao criar Virtual Host
- **Causa:** Permissões insuficientes para editar `hosts` ou confs do Apache.
- **Solução:** Utilizar o servidor embutido (`php artisan serve`) que não requer elevação de privilégios para rotas locais.

## 5. Próximos Passos (Roadmap Técnico)
- Implementar integrações reais (PNCP, Compras.gov) substituindo o simulador.
- Melhorar a validação de dados nos formulários de proposta.
- Implementar autenticação multi-tenant completa (já iniciada com `EmpresaScope`).
