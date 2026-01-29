<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PainelController;
use App\Http\Controllers\RadarLicitacoesController;
use App\Http\Controllers\PropostasController;
use App\Http\Controllers\CatalogoItensController;
use App\Http\Controllers\CrmLicitacoesController;
use App\Http\Controllers\FinanceiroController;
use App\Http\Controllers\DocumentosController;
use App\Http\Controllers\CalendarioController;
use App\Http\Controllers\ConfiguracoesEmpresaController;
use App\Http\Controllers\ImportacaoController;

Route::get('/', function () {
    return redirect()->route('painel');
});

Route::middleware(['auth'])->group(function () {
    // Perfil (Mantendo do Breeze)
    // Perfil (Mantendo do Breeze)
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/perfil', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Painel
    Route::get('/painel', [PainelController::class, 'index'])
        ->name('painel');

    // Configurações Radar Inteligente
    Route::post('/configuracoes-radar', [\App\Http\Controllers\RadarConfiguracaoController::class, 'update'])
        ->name('configuracoes.radar.update');

    // Radar de Licitações
    Route::prefix('radar-licitacoes')->group(function () {
        Route::get('/', [RadarLicitacoesController::class, 'index'])
            ->name('radar.index');

        Route::get('{licitacao}/detalhes', [RadarLicitacoesController::class, 'show']) // Adaptando para usar o método show existente ou criando alias
            ->name('radar.detalhes');

        // Mapeando para salvarRadar que já existe se necessário, ou criando novos
        Route::post('salvar', [RadarLicitacoesController::class, 'salvarRadar'])->name('radar.salvar'); // Mantendo

        Route::get('{licitacao}/criar-proposta', [RadarLicitacoesController::class, 'criarProposta'])
            ->name('radar.criar-proposta');
    });

    // Propostas
    Route::prefix('propostas')->group(function () {
        Route::get('/', [PropostasController::class, 'index'])
            ->name('propostas.index');

        Route::get('criar', [PropostasController::class, 'create']) // create padrão
            ->name('propostas.criar');

        Route::post('/', [PropostasController::class, 'store'])
            ->name('propostas.store');

        Route::get('{proposta}/detalhes', [PropostasController::class, 'show']) // show padrão
            ->name('propostas.detalhes');

        Route::get('{proposta}/editar', [PropostasController::class, 'edit']) // edit padrão
            ->name('propostas.editar');

        Route::put('{proposta}', [PropostasController::class, 'update'])->name('propostas.update'); // Update padrão para o form de edição

        Route::delete('{proposta}', [PropostasController::class, 'destroy'])
            ->name('propostas.destroy');

        Route::post('{proposta}/enviar', [PropostasController::class, 'enviar'])
            ->name('propostas.enviar');

        Route::get('{proposta}/imprimir', [PropostasController::class, 'gerarPdf'])
            ->name('propostas.imprimir');
            
        Route::get('{proposta}/gerar-docx', [PropostasController::class, 'gerarDocx'])
            ->name('propostas.gerar-docx');

        Route::post('{proposta}/ganhar', [PropostasController::class, 'marcarComoGanha'])
            ->name('propostas.ganhar');

        Route::post('{proposta}/perder', [PropostasController::class, 'marcarComoPerdida'])
            ->name('propostas.perder');

        Route::get('{proposta}/simular-lucro', [PropostasController::class, 'simularLucro'])
            ->name('propostas.simular');

        Route::post('{proposta}/simular-lucro', [PropostasController::class, 'salvarSimulacao'])
            ->name('propostas.salvar-simulacao');

        // Documentos da Proposta
        Route::post('{proposta}/documentos', [PropostasController::class, 'storeDocumento'])
            ->name('propostas.documentos.store');
        
        Route::delete('documentos/{documento}', [PropostasController::class, 'destroyDocumento'])
            ->name('propostas.documentos.destroy');
    });

    // Catálogo de Itens
    Route::prefix('catalogo-itens')->group(function () {
        Route::get('/', [CatalogoItensController::class, 'index'])
            ->name('catalogo.index');

        Route::get('criar', [CatalogoItensController::class, 'create'])
            ->name('catalogo.criar');

        Route::post('/', [CatalogoItensController::class, 'store'])
            ->name('catalogo.store');

        Route::get('{item}/editar', [CatalogoItensController::class, 'edit'])
            ->name('catalogo.editar');

        Route::put('{item}', [CatalogoItensController::class, 'update'])
            ->name('catalogo.update');

        Route::delete('{item}', [CatalogoItensController::class, 'destroy'])
            ->name('catalogo.destroy');
    });

    // CRM Licitações
    Route::prefix('crm-licitacoes')->group(function () {
        Route::get('/', [CrmLicitacoesController::class, 'index'])
            ->name('crm.index');

        // Monitorar Licitação (Vem do Radar)
        Route::post('{id}/monitorar', [CrmLicitacoesController::class, 'monitorar'])
            ->name('crm.monitorar');

        // Alterar Etapa (Kanban)
        Route::patch('{id}/alterar-etapa', [CrmLicitacoesController::class, 'alterarEtapa'])
            ->name('crm.alterar-etapa');
        
        // Remover do CRM
        Route::delete('{id}', [CrmLicitacoesController::class, 'destroy'])
            ->name('crm.destroy');
    });

    // Financeiro
    Route::prefix('financeiro')->group(function () {
        Route::get('/', [FinanceiroController::class, 'index'])
            ->name('financeiro.index');

        Route::get('{proposta}/detalhes', [FinanceiroController::class, 'show']) // Assumindo show
            ->name('financeiro.detalhes');

        Route::post('{proposta}/registrar-faturamento', [FinanceiroController::class, 'registrarFaturamento'])->name('financeiro.faturar');
        Route::post('faturamento/{faturamento}/registrar-recebimento', [FinanceiroController::class, 'registrarRecebimento'])->name('financeiro.receber');
    });

    // Documentos
    Route::prefix('documentos')->group(function () {
        Route::get('/', [DocumentosController::class, 'index'])
            ->name('documentos.index');

        Route::post('enviar', [DocumentosController::class, 'store']) // store padrão
            ->name('documentos.enviar');

        Route::get('{documento}/baixar', [DocumentosController::class, 'download'])->name('documentos.download');
        
        Route::delete('{documento}', [DocumentosController::class, 'destroy'])->name('documentos.destroy');
    });

    // Calendário
    Route::get('/calendario', [CalendarioController::class, 'index'])
        ->name('calendario.index');

    // Configurações Empresa
    Route::get('/configuracoes-empresa', [ConfiguracoesEmpresaController::class, 'index'])
        ->name('configuracoes.empresa');
    Route::put('/configuracoes-empresa', [ConfiguracoesEmpresaController::class, 'update'])
        ->name('configuracoes.empresa.update');

    // Importação
    Route::prefix('importacao')->group(function () {
        Route::get('/', [ImportacaoController::class, 'index'])->name('importacao.index');
        Route::post('/catalogo', [ImportacaoController::class, 'importarCatalogo'])->name('importacao.catalogo');
        Route::post('/licitacoes', [ImportacaoController::class, 'importarLicitacoes'])->name('importacao.licitacoes');
    });
});

require __DIR__ . '/auth.php';
