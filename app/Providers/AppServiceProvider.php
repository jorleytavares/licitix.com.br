<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\Route::resourceVerbs([
            'create' => 'criar',
            'edit' => 'editar',
        ]);

        // Compartilha notificações com o menu de navegação
        \Illuminate\Support\Facades\View::composer('layouts.navigation', function ($view) {
            $notifications = collect();
            
            if (\Illuminate\Support\Facades\Auth::check()) {
                $empresaId = \Illuminate\Support\Facades\Auth::user()->empresa_id;

                // 1. Documentos Vencidos/Vencendo (Próximos 15 dias)
                $docs = \App\Models\Documento::where('empresa_id', $empresaId)
                    ->where('validade', '<=', now()->addDays(15))
                    ->get();
                
                foreach($docs as $doc) {
                    $notifications->push([
                        'tipo' => 'documento',
                        'titulo' => 'Documento Vencendo/Vencido',
                        'mensagem' => $doc->nome . ' (' . $doc->validade->format('d/m/Y') . ')',
                        'url' => route('documentos.index'),
                        'urgente' => $doc->validade < now()
                    ]);
                }

                // 2. Financeiro Inadimplente (Atrasado)
                $faturas = \App\Models\Faturamento::whereHas('proposta', fn($q) => $q->where('empresa_id', $empresaId))
                    ->where('status', 'pendente')
                    ->where('data_vencimento', '<', now())
                    ->get();

                foreach($faturas as $fatura) {
                    $notifications->push([
                        'tipo' => 'financeiro',
                        'titulo' => 'Fatura em Atraso',
                        'mensagem' => 'NF ' . $fatura->numero_nf . ' - R$ ' . number_format($fatura->valor, 2, ',', '.'),
                        'url' => route('financeiro.index'),
                        'urgente' => true
                    ]);
                }

                // 3. Licitações Abrindo Hoje
                $licitacoes = \App\Models\Licitacao::whereDate('data_abertura', now())
                    ->get(); // Licitacao não tem escopo de empresa global ainda, mas ok mostrar todas do radar
                
                foreach($licitacoes as $licitacao) {
                    $notifications->push([
                        'tipo' => 'licitacao',
                        'titulo' => 'Licitação Hoje',
                        'mensagem' => $licitacao->orgao . ' - ' . \Illuminate\Support\Str::limit($licitacao->objeto, 30),
                        'url' => route('radar.detalhes', $licitacao->id),
                        'urgente' => false
                    ]);
                }
            }

            $view->with('globalNotifications', $notifications);
        });
    }
}
