<?php

namespace App\Console\Commands;

use App\Models\Licitacao;
use App\Models\User;
use App\Notifications\DailyAlertNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AlertarPrazosCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'licitacoes:alertar-prazos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia alertas de prazos de licitações e tarefas do CRM';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando verificação de prazos...');

        // Agrupar verificações por empresa para otimizar
        // Mas como a notificação é por usuário, vamos iterar usuários que têm configurações ou estão ativos
        // Para simplificar MVP, vamos pegar usuários que são donos de conta (ou tem empresa)
        
        $users = User::whereNotNull('empresa_id')->get();

        foreach ($users as $user) {
            $alerts = [];
            
            // 1. Licitações Monitoradas encerrando em breve (2 dias)
            // Apenas status 'aberta' e monitorada=true
            $licitacoesVencendo = Licitacao::withoutGlobalScope(\App\Models\Traits\EmpresaScope::class)
                ->where('empresa_id', $user->empresa_id)
                ->where('monitorada', true)
                ->where('status', 'aberta')
                ->whereBetween('data_encerramento', [now(), now()->addDays(2)])
                ->get();

            foreach ($licitacoesVencendo as $licitacao) {
                $dias = Carbon::parse($licitacao->data_encerramento)->diffInDays(now());
                $msgDias = $dias == 0 ? "HOJE" : "em {$dias} dias";
                
                $alerts[] = [
                    'titulo' => "URGENTE: Licitação Encerrando {$msgDias}",
                    'mensagem' => "A licitação {$licitacao->numero_edital} ({$licitacao->orgao}) encerra em breve. Verifique sua proposta!",
                    'url' => route('radar.detalhes', $licitacao->id)
                ];
            }

            // 2. Tarefas do CRM vencendo hoje ou atrasadas
            // Status diferentes de concluída (assumindo que status da licitação não reflete tarefa, mas a tarefa em si)
            // O campo é 'data_vencimento_tarefa'. Vamos pegar as que vencem hoje ou estão atrasadas e a licitação não está concluída
            $tarefasVencendo = Licitacao::withoutGlobalScope(\App\Models\Traits\EmpresaScope::class)
                ->where('empresa_id', $user->empresa_id)
                ->whereNotNull('tarefa_atual')
                ->whereNotNull('data_vencimento_tarefa')
                ->where('status', '!=', 'concluida') // Ignora se já ganhou/perdeu
                ->where('data_vencimento_tarefa', '<=', now()->endOfDay())
                ->get();

            foreach ($tarefasVencendo as $tarefa) {
                $dataVenc = Carbon::parse($tarefa->data_vencimento_tarefa);
                $statusPrazo = $dataVenc->isPast() ? "ATRASADA" : "HOJE";
                
                $alerts[] = [
                    'titulo' => "Tarefa CRM: {$statusPrazo}",
                    'mensagem' => "Tarefa '{$tarefa->tarefa_atual}' para {$tarefa->orgao} vence {$statusPrazo}.",
                    'url' => route('radar.detalhes', $tarefa->id) // Idealmente levaria para aba CRM
                ];
            }

            if (!empty($alerts)) {
                try {
                    $user->notify(new DailyAlertNotification($alerts));
                    $this->info("Notificação enviada para User ID {$user->id} com " . count($alerts) . " alertas.");
                } catch (\Exception $e) {
                    $this->error("Falha ao notificar User ID {$user->id}: " . $e->getMessage());
                }
            }
        }

        $this->info('Verificação de prazos concluída.');
    }
}
