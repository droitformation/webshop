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
        $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');
        $socialite->extend(
            'droithub',
            function ($app) use ($socialite) {
                $config = $app['config']['services.droithub'];
                return $socialite->buildProvider(\App\Services\DroitHubProvider::class, $config);
            }
        );

        view()->composer([
            'backend.partials.sites',
            'backend.menus.*',
            'backend.pages.*',
            'backend.bloc.*',
            'backend.arrets.*',
            'backend.analyses.*',
            'backend.domains.*'
        ], 'App\Http\ViewComposers\SiteComposer');

        view()->composer([
            'backend.colloques.show',
            'backend.colloques.partials.occurrences',
        ], 'App\Http\ViewComposers\ColloqueComposer');

        view()->composer(
            [
                'backend.users.adresse',
                'frontend.pubdroit.profil.account',
                'backend.export.user',
                'frontend.pubdroit.checkout.billing',
                'backend.orders.partials.adresse',
                'backend.inscriptions.colloque',
                'backend.inscriptions.desinscription',
                'backend.adresses.*',
                'auth.register'
            ], 'App\Http\ViewComposers\UserAttributeComposer');

        view()->composer(['frontend.pubdroit.partials.label','backend.export.user'], 'App\Http\ViewComposers\LabelComposer');
        view()->composer([
            'frontend.pubdroit.partials.menu',
            'frontend.pubdroit.layouts.master',
            'frontend.pubdroit.subscribe',
            'frontend.pubdroit.unsubscribe'
        ], 'App\Http\ViewComposers\PubdroitComposer');
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

        $this->registerSiteService();
        $this->registerAboService();
        $this->registerAboUserService();
        $this->registerAboFactureService();
        $this->registerAboRappelService();

        $this->registerAboWorkerService();

        $this->registerUserService();
        $this->registerDuplicateService();
        $this->registerDuplicateWorkerService();
        $this->registerAdresseService();

        $this->registerPaysService();
        $this->registerCantonService();
        $this->registerProfessionService();
        $this->registerCiviliteService();
        $this->registerCategorieService();
        $this->registerAuthorService();
        $this->registerDomainService();
        $this->registerSpecialisationService();
        $this->registerMemberService();
        $this->registerUploadService();
        $this->registerReminderService();
        $this->registerReminderWorkerService();

        $this->registerFileWorkerService();
        $this->registerPageService();
        $this->registerMenuService();

        $this->registerFaqQuestionService();
        $this->registerFaqCategorieService();

	}

    /**
     * Categorie
     */
    protected function registerCategorieService(){

        $this->app->singleton('App\Droit\Categorie\Repo\CategorieInterface', function()
        {
            return new \App\Droit\Categorie\Repo\CategorieEloquent(
                new \App\Droit\Categorie\Entities\Categorie
            );
        });
    }

    /**
     * Site
     */
    protected function registerSiteService(){

        $this->app->singleton('App\Droit\Site\Repo\SiteInterface', function()
        {
            return new \App\Droit\Site\Repo\SiteEloquent(new \App\Droit\Site\Entities\Site);
        });
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

    /**
     * User
     */
    protected function registerUserService(){

        $this->app->singleton('App\Droit\User\Repo\UserInterface', function()
        {
            return new \App\Droit\User\Repo\UserEloquent(new \App\Droit\User\Entities\User);
        });
    }

    /**
     * Duplicate
     */
    protected function registerDuplicateService(){

        $this->app->singleton('App\Droit\User\Repo\DuplicateInterface', function()
        {
            return new \App\Droit\User\Repo\DuplicateEloquent(new \App\Droit\User\Entities\User_duplicates());
        });
    }

    /**
     * Duplicate worker
     */
    protected function registerDuplicateWorkerService(){

        $this->app->singleton('App\Droit\User\Worker\DuplicateWorkerInterface', function()
        {
            return new \App\Droit\User\Worker\DuplicateWorker();
        });
    }

    /**
     * Adresse
     */
    protected function registerAdresseService(){

        $this->app->singleton('App\Droit\Adresse\Repo\AdresseInterface', function()
        {
            return new \App\Droit\Adresse\Repo\AdresseEloquent(new \App\Droit\Adresse\Entities\Adresse);
        });
    }

    /**
     * Author
     */
    protected function registerAuthorService(){

        $this->app->singleton('App\Droit\Author\Repo\AuthorInterface', function()
        {
            return new \App\Droit\Author\Repo\AuthorEloquent(new \App\Droit\Author\Entities\Author);
        });
    }


    /**
     * Domain
     */
    protected function registerDomainService(){

        $this->app->singleton('App\Droit\Domain\Repo\DomainInterface', function()
        {
            return new \App\Droit\Domain\Repo\DomainEloquent(new \App\Droit\Domain\Entities\Domain);
        });
    }

    /**
     * Pays
     */
    protected function registerPaysService(){

        $this->app->singleton('App\Droit\Pays\Repo\PaysInterface', function()
        {
            return new \App\Droit\Pays\Repo\PaysEloquent(new \App\Droit\Pays\Entities\Pays);
        });
    }

    /**
     * Canton
     */
    protected function registerCantonService(){

        $this->app->singleton('App\Droit\Canton\Repo\CantonInterface', function()
        {
            return new \App\Droit\Canton\Repo\CantonEloquent(new \App\Droit\Canton\Entities\Canton);
        });
    }

    /**
     * Civilite
     */
    protected function registerCiviliteService(){

        $this->app->singleton('App\Droit\Civilite\Repo\CiviliteInterface', function()
        {
            return new \App\Droit\Civilite\Repo\CiviliteEloquent(new \App\Droit\Civilite\Entities\Civilite);
        });
    }

    /**
     * Profession
     */
    protected function registerProfessionService(){

        $this->app->singleton('App\Droit\Profession\Repo\ProfessionInterface', function()
        {
            return new \App\Droit\Profession\Repo\ProfessionEloquent(new \App\Droit\Profession\Entities\Profession);
        });
    }

    /**
     * Specialisation
     */
    protected function registerSpecialisationService(){

        $this->app->singleton('App\Droit\Specialisation\Repo\SpecialisationInterface', function()
        {
            return new \App\Droit\Specialisation\Repo\SpecialisationEloquent(new \App\Droit\Specialisation\Entities\Specialisation);
        });
    }

    /**
     * Member
     */
    protected function registerMemberService(){

        $this->app->singleton('App\Droit\Member\Repo\MemberInterface', function()
        {
            return new \App\Droit\Member\Repo\MemberEloquent(new \App\Droit\Member\Entities\Member);
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

    /*
    * FileWorker
    */
    protected function registerFileWorkerService(){

        $this->app->bind('App\Droit\Service\FileWorkerInterface', function()
        {
            return new \App\Droit\Service\FileWorker();
        });
    }

    /**
     * Reminder
     */
    protected function registerReminderService(){

        $this->app->singleton('App\Droit\Reminder\Repo\ReminderInterface', function()
        {
            return new \App\Droit\Reminder\Repo\ReminderEloquent(new \App\Droit\Reminder\Entities\Reminder);
        });
    }

    /*
    * Reminder Worker
    */
    protected function registerReminderWorkerService(){

        $this->app->bind('App\Droit\Reminder\Worker\ReminderWorkerInterface', function()
        {
            return new \App\Droit\Reminder\Worker\ReminderWorker(
                \App::make('App\Droit\Reminder\Repo\ReminderInterface')
            );
        });
    }

    /**
     * Page
     */
    protected function registerPageService(){

        $this->app->singleton('App\Droit\Page\Repo\PageInterface', function()
        {
            return new \App\Droit\Page\Repo\PageEloquent(new \App\Droit\Page\Entities\Page);
        });
    }

    /**
     * Menu
     */
    protected function registerMenuService(){

        $this->app->singleton('App\Droit\Menu\Repo\MenuInterface', function()
        {
            return new \App\Droit\Menu\Repo\MenuEloquent(new \App\Droit\Menu\Entities\Menu);
        });
    }

    /**
     * Question
     */
    protected function registerFaqQuestionService(){

        $this->app->singleton('App\Droit\Faq\Repo\FaqQuestionInterface', function()
        {
            return new \App\Droit\Faq\Repo\FaqQuestionEloquent(new \App\Droit\Faq\Entities\Faq_question);
        });
    }

    /**
     * Categorie
     */
    protected function registerFaqCategorieService(){

        $this->app->singleton('App\Droit\Faq\Repo\FaqCategorieInterface', function()
        {
            return new \App\Droit\Faq\Repo\FaqCategorieEloquent(new \App\Droit\Faq\Entities\Faq_categorie);
        });
    }
}
