<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Documento;
use App\Models\Faturamento;
use App\Notifications\DailyAlertNotification;

use App\Models\Licitacao;

class SendDailyNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-daily-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia notificações diárias por email sobre documentos, faturas e licitações.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando envio de notificações diárias...');

        $users = User::whereNotNull('empresa_id')->get();

        foreach ($users as $user) {
            $alerts = [];
            $empresaId = $user->empresa_id;

            // 1. Documentos Vencendo (Próximos 15 dias) ou Vencidos (Recentes)
            // Nota: EmpresaScope não se aplica no console (sem auth), então filtramos manualmente.
            $docs = Documento::where('empresa_id', $empresaId)
                ->where('validade', '<=', now()->addDays(15))
                ->where('validade', '>=', now()->subDays(30)) // Limite para não spammar documentos muito antigos
                ->get();
            
            foreach ($docs as $doc) {
                $days = (int) now()->diffInDays($doc->validade, false);
                
                if ($days < 0) {
                    $status = 'VENCIDO';
                    $msg = "Venceu em {$doc->validade->format('d/m/Y')} (há " . abs($days) . " dias)";
                } elseif ($days == 0) {
                    $status = 'VENCE HOJE';
                    $msg = "Vence hoje: {$doc->validade->format('d/m/Y')}";
                } else {
                    $status = 'Vencendo';
                    $msg = "Vence em {$days} dias ({$doc->validade->format('d/m/Y')})";
                }
                
                $alerts[] = [
                    'titulo' => "Documento {$status}",
                    'mensagem' => "{$doc->nome} - {$msg}",
                    'url' => route('documentos.index'),
                ];
            }

            // 2. Financeiro Inadimplente (Atrasado)
            $faturas = Faturamento::whereHas('proposta', fn($q) => $q->where('empresa_id', $empresaId))
                ->where('status', 'pendente')
                ->where('data_vencimento', '<', now())
                ->get();

            foreach ($faturas as $fatura) {
                $alerts[] = [
                    'titulo' => 'Fatura em Atraso',
                    'mensagem' => "NF {$fatura->numero_nf} - R$ " . number_format($fatura->valor, 2, ',', '.') . " (Venceu em {$fatura->data_vencimento->format('d/m/Y')})",
                    'url' => route('financeiro.index'),
                ];
            }

            // 3. Licitações do Radar (Abrindo Hoje ou Amanhã)
            // Filtramos por empresa_id manualmente se a tabela tiver, mas Licitacao tem empresa_id agora.
            $licitacoes = Licitacao::where('empresa_id', $empresaId)
                ->whereBetween('data_abertura', [now()->format('Y-m-d'), now()->addDays(1)->format('Y-m-d')])
                ->get();

            foreach ($licitacoes as $licitacao) {
                $quando = $licitacao->data_abertura->isToday() ? 'HOJE' : 'AMANHÃ';
                $alerts[] = [
                    'titulo' => "Licitação {$quando}",
                    'mensagem' => "{$licitacao->orgao} - " . \Illuminate\Support\Str::limit($licitacao->objeto, 40) . " ({$licitacao->data_abertura->format('d/m/Y')})",
                    'url' => route('radar.detalhes', $licitacao->id),
                ];
            }

            if (count($alerts) > 0) {
                $this->info("Enviando " . count($alerts) . " alertas para {$user->email}");
                try {
                    $user->notify(new DailyAlertNotification($alerts));
                } catch (\Exception $e) {
                    $this->error("Erro ao enviar para {$user->email}: " . $e->getMessage());
                }
            }
        }
        
        $this->info('Processo finalizado.');
    }
}
