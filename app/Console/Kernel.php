<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [];

    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('stock:alert')->daily();
        $schedule->command('activitylog:clean')->daily();
        if (demo()) {
            $schedule->command('data:reset')->twiceDaily(1, 13)->withoutOverlapping(5);
        }
    }
}
