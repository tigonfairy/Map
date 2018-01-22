<?php

namespace App\Console;

use App\Console\Commands\AddAdmin;
use App\Console\Commands\ConvertDate;
use App\Console\Commands\Hin;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\GetGeoJson;
use App\Console\Commands\AddProvinceJson;
use App\Console\Commands\UpdateCacheView;
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        AddAdmin::class,
        GetGeoJson::class,
        AddProvinceJson::class,
        Hin::class,
        UpdateCacheView::class,
        ConvertDate::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//         $schedule->command('craw:hin')
//                  ->withoutOverlapping()->dailyAt('11:00');
        $schedule->command('update-cache-view')
            ->everyFiveMinutes()->withoutOverlapping()->appendOutputTo(storage_path('cron/updateCacheView.log'));
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
