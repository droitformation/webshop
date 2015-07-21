<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ColloqueServiceProvider extends ServiceProvider {

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

        $this->registerColloqueService();
        $this->registerLocationService();
        $this->registerOrganisateurService();
        $this->registerCompteService();
        $this->registerInscriptionService();

	}

    /**
     * Colloque
     */
    protected function registerColloqueService(){

        $this->app->singleton('App\Droit\Colloque\Repo\ColloqueInterface', function()
        {
            return new \App\Droit\Colloque\Repo\ColloqueEloquent(new \App\Droit\Colloque\Entities\Colloque);
        });
    }

    /**
     * Inscription
     */
    protected function registerInscriptionService(){

        $this->app->singleton('App\Droit\Inscription\Repo\InscriptionInterface', function()
        {
            return new \App\Droit\Inscription\Repo\InscriptionEloquent(new \App\Droit\Inscription\Entities\Inscription);
        });
    }

    /**
     * Location
     */
    protected function registerLocationService(){

        $this->app->singleton('App\Droit\Location\Repo\LocationInterface', function()
        {
            return new \App\Droit\Location\Repo\LocationEloquent(new \App\Droit\Location\Entities\Location);
        });
    }

    /**
     * Organisateur
     */
    protected function registerOrganisateurService(){

        $this->app->singleton('App\Droit\Organisateur\Repo\OrganisateurInterface', function()
        {
            return new \App\Droit\Organisateur\Repo\OrganisateurEloquent(new \App\Droit\Organisateur\Entities\Organisateur);
        });
    }

    /**
     * Compte
     */
    protected function registerCompteService(){

        $this->app->singleton('App\Droit\Compte\Repo\CompteInterface', function()
        {
            return new \App\Droit\Compte\Repo\CompteEloquent(new \App\Droit\Compte\Entities\Compte);
        });
    }

    /**
     * Price
     */
    protected function registerPriceService(){

        $this->app->singleton('App\Droit\Price\Repo\PriceInterface', function()
        {
            return new \App\Droit\Price\Repo\PriceEloquent(new \App\Droit\Price\Entities\Price);
        });
    }

    /**
     * Document
     */
    protected function registerDocumentService(){

        $this->app->singleton('App\Droit\Document\Repo\DocumentInterface', function()
        {
            return new \App\Droit\Document\Repo\DocumentEloquent(new \App\Droit\Document\Entities\Document);
        });
    }

    /**
     * Option
     */
    protected function registerOptionService(){

        $this->app->singleton('App\Droit\Option\Repo\OptionInterface', function()
        {
            return new \App\Droit\Option\Repo\OptionEloquent(new \App\Droit\Option\Entities\Option);
        });
    }

}
