<?php

namespace App\Console;

use App\Core\Services\DiariosService;
//use App\Enums\LogTypes;
use App\Models\AppConfig;
//use App\Models\AppLogs;
//use App\Models\Diario;
use Carbon\Carbon;
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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $cierre_almuerzo = Carbon::parse(AppConfig::getConfig()->hs_cierre_almuerzo);
        $cierre_cena = Carbon::parse(AppConfig::getConfig()->hs_cierre_cena);

        $schedule->call(function () {
        $service = new DiariosService();
        $service->procesarDiarios();

        })->twiceDaily($cierre_almuerzo->hour, $cierre_cena->hour);
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
