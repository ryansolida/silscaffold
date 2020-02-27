<?php

namespace Sil\Scaffold;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

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
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->app['router']->aliasMiddleware('sil-scaffold-middleware', \Sil\Scaffold\SilScaffoldMiddleware::class);
        
        $cur_auth_guards = config('auth.guards');
        $cur_auth_guards['scaffold_user'] = [
            'driver'=>'session',
            'provider'=>'scaffold_users'
        ];
        config(['auth.guards'=>$cur_auth_guards]);
        

        $cur_auth_providers = config('auth.providers');
        $cur_auth_providers['scaffold_users'] = [
            'driver'=>'eloquent',
            'model'=>\Sil\Scaffold\SilScaffoldUser::class
        ];
        config(['auth.providers'=>$cur_auth_providers]);
        
        $this->publishes([
            __DIR__.'/public' => public_path('vendor/silscaffold'),
        ],'silscaffold-assets');

        $this->publishes([
            //__DIR__.'/resources'=>resource_path('vendor/silscaffold'),
        ],'silscaffold-assets');
        
    }
}
