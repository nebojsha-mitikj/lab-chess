<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Stringable;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\InsertError::class,
    ];

    protected function schedule(Schedule $schedule){

        // Database Backups.
        $schedule->command('backup:run --only-db --disable-notifications')->dailyAt('04:00')
        ->onSuccess(function(Stringable $output){
            echo "Success: " . $output;
        })->onFailure(function(Stringable $output){
            echo "Failure: " . $output;
        });


        /* Weekly Progress Report.
        $schedule->command('send:weekly-reports')->saturdays()->at('13:00')
            ->onSuccess(function(Stringable $output){
                echo "Success: " . $output;
            })->onFailure(function(Stringable $output){
                echo "Failure: " . $output;
            });
        */
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
