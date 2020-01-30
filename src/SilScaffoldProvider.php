<?php

namespace Sil\Scaffold;

use Illuminate\Support\ServiceProvider;

class SilScaffoldProvider extends ServiceProvider
{

    
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            \Sil\Scaffold\Commands\RunMigrations::class,
            \Sil\Scaffold\Commands\MakeModels::class,
            \Sil\Scaffold\Commands\AddField::class
        ]);   
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadViewsFrom(__DIR__.'/Views','silscaffold');

        $this->publishes([
            __DIR__.'/public' => public_path('vendor/silscaffold'),
        ],'silscaffold-assets');
        
    }
}
