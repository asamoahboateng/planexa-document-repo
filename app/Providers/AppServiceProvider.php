<?php

namespace App\Providers;

use App\Console\Commands\Migrate\MeetingMigrateCommand;
use App\Console\Commands\Migrate\MeetingVideoMigrateCommand;
use App\Console\Commands\Update\CleanLocationAddressCommand;
use App\Console\Commands\Update\UpdateLocationCommand;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        $this->loadRoutesFrom(__DIR__.'/../../routes/backend.php');
        $this->commands([
            UpdateLocationCommand::class,
            CleanLocationAddressCommand::class,
            MeetingMigrateCommand::class,
            MeetingVideoMigrateCommand::class
        ]);
//        if($this->app->runningInConsole()){
//            $this->commands([
//                UpdateLocationCommand::class,
//                CleanLocationAddressCommand::class,
//                MeetingMigrateCommand::class,
//                MeetingVideoMigrateCommand::class
//            ]);
//        };
    }
}
