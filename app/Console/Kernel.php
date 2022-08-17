<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\AssignedTask',
        'App\Console\Commands\AssignTimeOffType',
        'App\Console\Commands\PTOTransaction',
        'App\Console\Commands\PTONotification',
        'App\Console\Commands\UpdateBenefitStatus',
        'App\Console\Commands\AssignManagerToEmployee',
        'App\Console\Commands\UpdateEmployeePaySchedule',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('pto:cron')
            ->Daily();
        $schedule->command('pto:notify')
            ->dailyAt('08:00');
        $schedule->command('send:plan-expiration-email')
            ->dailyAt('08:00');
        $schedule->command('employee:clockout')
            ->dailyAt('23:58');
        $schedule->command('assign:manager')
            ->dailyAt('08:00');
        $schedule->command('paySchedule:update')
            ->dailyAt('08:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
