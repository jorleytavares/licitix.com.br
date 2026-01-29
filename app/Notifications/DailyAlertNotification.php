<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DailyAlertNotification extends Notification
{
    use Queueable;

    public $alerts;

    /**
     * Create a new notification instance.
     */
    public function __construct($alerts)
    {
        $this->alerts = $alerts;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Resumo Diário - Licitix')
            ->greeting('Olá, ' . $notifiable->name)
            ->line('Aqui está o seu resumo diário de alertas:');

        foreach ($this->alerts as $alert) {
            $titulo = $alert['titulo'] ?? 'Alerta';
            $mensagem = $alert['mensagem'] ?? '';
            $url = $alert['url'] ?? route('painel');

            // Usa Markdown para criar links no texto
            $mail->line("- **{$titulo}**: {$mensagem} ([Ver]({$url}))");
        }
        
        $mail->action('Acessar Painel', route('painel'));
        $mail->line('Obrigado por usar o Licitix!');
        
        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
