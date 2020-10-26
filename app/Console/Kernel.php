<?php

namespace App\Console;

use App\Core\Services\DiariosService;
use App\Enums\LogTypes;
use App\Models\AppLogs;
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


        $schedule->call(function () {
            $service = new DiariosService();
            try{
                $item = $service->generarProximoDiario();
                AppLogs::add("Nuevo diario creado: ". $item->horario_comida);
            }catch (\Exception $e){
                AppLogs::addError("Se ha producido un error al crear el próximo diario.",$e);
            }

        })->twiceDaily(3,14);
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
