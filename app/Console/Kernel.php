<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Http\Controllers\Helper;


class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
		$schedule->call(function () {
			\Log::info('Compliance reminder scheduler running');
			Helper::insertComplianceReminderNotifications();
		})
		->dailyAt('00:10')   // runs once daily after midnight
		//->everyMinute() // TEMP FOR TESTING
		->name('compliance-reminder-notification')
		->withoutOverlapping()
		->onOneServer();
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
