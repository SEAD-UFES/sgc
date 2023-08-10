<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('backup:clean')->daily()->at('01:00');
        $schedule->command('backup:run')->daily()->at('01:30')
            ->onFailure(static function () {
                Log::error('Backup failed');
            })
            ->onSuccess(static function () {
                Log::info('Backup successful');
            });
        $schedule->command('sgc:send-institutional-login-confirmation-required-event')->daily()->at('06:00')
            ->onFailure(static function () {
                Log::error('Institutional Login event dispatch failed');
            })
            ->onSuccess(static function () {
                Log::info('Institutional Login event dispatch successful');
            });
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
