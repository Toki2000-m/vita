<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Comandos Artisan personalizados.
     */
    protected $commands = [
        \App\Console\Commands\MigrarInventario::class,
        \App\Console\Commands\AuditarInventario::class,
    ];

    /**
     * Tareas programadas (si las necesitas).
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Carga todos los comandos desde app/Console/Commands.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
    
}
