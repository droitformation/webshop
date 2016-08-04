<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AboServiceProvider extends ServiceProvider
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
        $this->registerAboService();
        $this->registerAboUserService();
        $this->registerAboFactureService();
        $this->registerAboRappelService();
        $this->registerAboWorkerService();
    }

    /**
     * Abo
     */
    protected function registerAboService(){

        $this->app->singleton('App\Droit\Abo\Repo\AboInterface', function()
        {
            return new \App\Droit\Abo\Repo\AboEloquent(new \App\Droit\Abo\Entities\Abo);
        });
    }

    /**
     * Abo user
     */
    protected function registerAboUserService(){

        $this->app->singleton('App\Droit\Abo\Repo\AboUserInterface', function()
        {
            return new \App\Droit\Abo\Repo\AboUserEloquent(new \App\Droit\Abo\Entities\Abo_users, new \App\Droit\Abo\Entities\Abo_factures, new \App\Droit\Abo\Entities\Abo_rappels);
        });
    }

    /**
     * Abo rappel
     */
    protected function registerAboRappelService(){

        $this->app->singleton('App\Droit\Abo\Repo\AboRappelInterface', function()
        {
            return new \App\Droit\Abo\Repo\AboRappelEloquent(new \App\Droit\Abo\Entities\Abo_rappels());
        });
    }


    /**
     * Abo facture
     */
    protected function registerAboFactureService(){

        $this->app->singleton('App\Droit\Abo\Repo\AboFactureInterface', function()
        {
            return new \App\Droit\Abo\Repo\AboFactureEloquent(new \App\Droit\Abo\Entities\Abo_factures);
        });
    }


    /**
     * Abo worker
     */
    protected function registerAboWorkerService(){

        $this->app->singleton('App\Droit\Abo\Worker\AboWorkerInterface', function()
        {
            return new \App\Droit\Abo\Worker\AboWorker(
                \App::make('App\Droit\Abo\Repo\AboFactureInterface'),
                \App::make('App\Droit\Abo\Repo\AboRappelInterface'),
                \App::make('App\Droit\Generate\Pdf\PdfGeneratorInterface'),
                \App::make('App\Droit\Abo\Repo\AboUserInterface')
            );
        });
    }

}
