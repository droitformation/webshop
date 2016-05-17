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

        $this->app->singleton('App\Droit\Generate\Excel\ExcelInscriptionInterface', function()
        {
            return new \App\Droit\Generate\Excel\ExcelInscription();
        });

        $this->app->singleton('App\Droit\Generate\Excel\ExcelOrderInterface', function()
        {
            return new \App\Droit\Generate\Excel\ExcelOrder();
        });

        $this->app->singleton('App\Droit\Generate\Pdf\PdfBadgeInterface', function()
        {
            return new \App\Droit\Generate\Pdf\PdfBadge();
        });

        $this->app->singleton('App\Droit\Generate\Pdf\QrcodeInterface', function()
        {
            return new \App\Droit\Generate\Pdf\Qrcode();
        });
    }
}
