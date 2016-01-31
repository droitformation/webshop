<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CalculetteServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton('App\Droit\Calculette\Repo\CalculetteTauxInterface', function()
        {
            return new \App\Droit\Calculette\Repo\CalculetteTauxEloquent(new \App\Droit\Calculette\Entities\Calculette_taux());
        });

        $this->app->singleton('App\Droit\Calculette\Repo\CalculetteIpcInterface', function()
        {
            return new \App\Droit\Calculette\Repo\CalculetteIpcEloquent(new \App\Droit\Calculette\Entities\Calculette_ipc());
        });

        $this->app->singleton('App\Droit\Calculette\Worker\CalculetteWorkerInterface', function()
        {
            return new \App\Droit\Calculette\Worker\CalculetteWorker(
                new \App\Droit\Calculette\Entities\Calculette_taux(),
                new \App\Droit\Calculette\Entities\Calculette_ipc()
            );
        });

    }
}
