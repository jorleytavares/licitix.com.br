# Licitix - Plataforma de Gestão de Licitações

<p align="center"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></p>

## Sobre o Projeto

O **Licitix** é uma plataforma SaaS desenvolvida para otimizar a gestão de licitações, oferecendo ferramentas para:
- Radar de oportunidades (busca e filtragem).
- Análise de viabilidade e cálculo de lucro.
- Gestão de propostas e documentação.
- CRM especializado para vendas públicas.

## Documentação

A documentação completa do projeto pode ser encontrada na pasta `docs/`:

- **[Documentação Técnica Detalhada](docs/TECHNICAL_DOCUMENTATION.md)**: Guia de instalação, arquitetura e troubleshooting.
- **[Regras de Negócio](docs/BUSINESS_LOGIC.md)**: Detalhes sobre cálculos financeiros e lógica do sistema.
- **[Implementação do Simulador](docs/SIMULATOR_IMPLEMENTATION.md)**: Como funciona o modo de dados simulados.

## Instalação Rápida (Desenvolvimento)

1. **Configurar Ambiente (.env):**
   ```bash
   cp .env.example .env
   # Configure DB_CONNECTION=mysql e crie o banco 'licitix'
   ```

2. **Instalar Dependências:**
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Banco de Dados:**
   ```bash
   php artisan migrate --seed
   ```

4. **Executar:**
   ```bash
   php artisan serve --port=8001
   ```
   Acesse: http://127.0.0.1:8001

## Requisitos do Produto (PRD)

O arquivo de requisitos `PRD.json` está disponível na raiz do projeto (ou diretório pai) e pode ser visualizado através da rota `/prd/view` quando o sistema estiver rodando.

---
Desenvolvido com Laravel 12.x e PHP 8.4.
