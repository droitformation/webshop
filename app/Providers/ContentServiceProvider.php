<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ContentServiceProvider extends ServiceProvider
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
        $this->registerAuthorService();
        $this->registerDomainService();
    }

    /**
     * Author
     */
    protected function registerAuthorService(){

        $this->app->singleton('App\Droit\Author\Repo\AuthorInterface', function()
        {
            return new \App\Droit\Author\Repo\AuthorEloquent( new \App\Droit\Author\Entities\Author );
        });
    }

    /**
     * Author
     */
    protected function registerDomainService(){

        $this->app->singleton('App\Droit\Domain\Repo\DomainInterface', function()
        {
            return new \App\Droit\Domain\Repo\DomainEloquent( new \App\Droit\Domain\Entities\Domain );
        });
    }
}
