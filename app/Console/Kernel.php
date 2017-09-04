<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Http\Controllers\CrontabController;

use DB;
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//        $schedule->command('inspire')->everyMinute();

        $schedule->call(join('@', [CrontabController::class, 'v_fUpdateLeaveRequestReport']))->dailyAt('1:00');
        $schedule->call(join('@', [CrontabController::class, 'v_fUpdateUsersStaff']))->dailyAt('1:15');

//        $schedule->command('log:demo')->everyMinute();
    }
}
