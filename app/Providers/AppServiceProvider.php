<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register any application services.
	 *
	 * This service provider is a great spot to register your various container
	 * bindings with the application. As you can see, we are registering our
	 * "Registrar" implementation here. You can add your own bindings too!
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind('Illuminate\Contracts\Auth\Registrar','App\Services\Registrar');

        $this->registerCategorieService();
        $this->registerAuthorService();
        $this->registerDomainService();
        $this->registerUploadService();

	}

    /**
     * Categorie
     */
    protected function registerCategorieService(){

        $this->app->bindShared('App\Droit\Categorie\Repo\CategorieInterface', function()
        {
            return new \App\Droit\Categorie\Repo\CategorieEloquent(
                new \App\Droit\Categorie\Entities\Categorie,
                new \App\Droit\Categorie\Entities\Parent_categories
            );
        });
    }

    /**
     * Author
     */
    protected function registerAuthorService(){

        $this->app->bindShared('App\Droit\Author\Repo\AuthorInterface', function()
        {
            return new \App\Droit\Author\Repo\AuthorEloquent(new \App\Droit\Author\Entities\Author);
        });
    }


    /**
     * Domain
     */
    protected function registerDomainService(){

        $this->app->bindShared('App\Droit\Domain\Repo\DomainInterface', function()
        {
            return new \App\Droit\Domain\Repo\DomainEloquent(new \App\Droit\Domain\Entities\Domain);
        });
    }

    /*
   * Upload
   */
    protected function registerUploadService(){

        $this->app->bind('App\Droit\Service\UploadInterface', function()
        {
            return new \App\Droit\Service\UploadWorker();
        });
    }

}
