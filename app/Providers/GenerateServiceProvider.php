<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class GenerateServiceProvider extends ServiceProvider
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
/*
        $this->app->singleton('App\Droit\Generate\Pdf\PdfGenerator', function()
        {
            return new \App\Droit\Shop\Order\Repo\OrderEloquent(new \App\Droit\Shop\Order\Entities\Order);
        });*/

    }
}
