<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        Commands\GenerateDailyBill::class,
        Commands\GenerateMonthlyBill::class,

    ];
    protected function schedule(Schedule $schedule): void
    {
        // Example: schedule a command

        //Daily Bill Generator
        $schedule->command('GenerateDailyBill')->daily();

        //Monthly Bill Generator
        $schedule->command('GenerateMonthlyBill')->monthly();

    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
