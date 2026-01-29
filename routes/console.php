<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Agendamento de notificações diárias
Schedule::command('app:send-daily-notifications')->dailyAt('08:00');

// Agendamento de busca diária de oportunidades (Radar)
// Executa 4 vezes ao dia: 06:00, 12:00, 18:00, 22:00
Schedule::command('radar:buscar-diarias')->at('06:00');
Schedule::command('radar:buscar-diarias')->at('12:00');
Schedule::command('radar:buscar-diarias')->at('18:00');
Schedule::command('radar:buscar-diarias')->at('22:00');
