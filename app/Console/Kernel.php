<?php

namespace App\Console;

use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule($schedule): void
    {
        $settings = \Illuminate\Support\Facades\DB::table('tribe_settings')->first();
        $frequency = $settings->backup_frequency ?? 'monthly';

        if ($frequency !== 'none') {
            $task = $schedule->command('backup:run')->onOneServer();

            switch ($frequency) {
                case 'daily':
                    $task->dailyAt('02:00');
                    break;
                case 'weekly':
                    $task->weeklyOn(1, '02:00');
                    break;
                case 'monthly':
                    $task->monthlyOn(1, '02:00');
                    break;
                case 'yearly':
                    $task->yearlyOn(1, 1, '02:00');
                    break;
            }

            $schedule->command('backup:clean')->dailyAt('01:00')->onOneServer();
        }
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
