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

        $this->registerUserService();
        $this->registerAdresseService();

        $this->registerPaysService();
        $this->registerCiviliteService();
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
     * User
     */
    protected function registerUserService(){

        $this->app->bindShared('App\Droit\User\Repo\UserInterface', function()
        {
            return new \App\Droit\User\Repo\UserEloquent(new \App\Droit\User\Entities\User);
        });
    }

    /**
     * Adresse
     */
    protected function registerAdresseService(){

        $this->app->bindShared('App\Droit\Adresse\Repo\AdresseInterface', function()
        {
            return new \App\Droit\Adresse\Repo\AdresseEloquent(new \App\Droit\Adresse\Entities\Adresses);
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

    /**
     * Pays
     */
    protected function registerPaysService(){

        $this->app->bindShared('App\Droit\Pays\Repo\PaysInterface', function()
        {
            return new \App\Droit\Pays\Repo\PaysEloquent(new \App\Droit\Pays\Entities\Pays);
        });
    }

    /**
     * Civilite
     */
    protected function registerCiviliteService(){

        $this->app->bindShared('App\Droit\Civilite\Repo\CiviliteInterface', function()
        {
            return new \App\Droit\Civilite\Repo\CiviliteEloquent(new \App\Droit\Civilite\Entities\Civilite);
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
