<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Laravel\Dusk\DuskServiceProvider;

class AppServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
        view()->composer([
            'backend.partials.sites',
            'backend.menus.*',
            'backend.pages.*',
            'backend.bloc.*',
            'backend.arrets.*',
            'backend.analyses.*',
            'backend.calculette.*',
            'backend.categories.*',
            'backend.seminaires.*',
            'newsletter::Backend.*',
        ], 'App\Http\ViewComposers\SiteComposer');

        view()->composer(['emails.*',], 'App\Http\ViewComposers\EmailComposer');
        view()->composer(['backend.colloques.*'], 'App\Http\ViewComposers\ColloqueComposer');

        view()->composer([
            'backend.users.*',
            'frontend.pubdroit.profil.account',
            'backend.export.user',
            'frontend.pubdroit.checkout.billing',
            'backend.orders.partials.adresse',
            'backend.inscriptions.colloque',
            'backend.inscriptions.desinscription',
            'backend.adresses.*',
            'auth.register'
        ], 'App\Http\ViewComposers\UserAttributeComposer');

        view()->composer([
            'frontend.pubdroit.partials.label',
            'backend.export.user',
            'backend.products.*',
            'frontend.bail.*',
        ], 'App\Http\ViewComposers\LabelComposer');

        view()->composer([
            'frontend.pubdroit.partials.menu',
            'frontend.pubdroit.layouts.master',
            'frontend.pubdroit.subscribe',
            'frontend.pubdroit.unsubscribe'
        ], 'App\Http\ViewComposers\PubdroitComposer');

        view()->composer(['frontend.bail.*','backend.seminaires.*'], 'App\Http\ViewComposers\BailComposer');
        view()->composer(['frontend.matrimonial.*'], 'App\Http\ViewComposers\MatrimonialComposer');

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
     /*   if ($this->app->environment('local', 'testing')) {
            $this->app->register(DuskServiceProvider::class);
        }*/

        $this->registerSiteService();
        
        $this->registerDuplicateService();
        $this->registerDuplicateWorkerService();

        $this->registerUploadService();
        $this->registerReminderService();
        $this->registerReminderWorkerService();

        $this->registerFileWorkerService();
        $this->registerPageService();
        $this->registerContentService();
        $this->registerBlocService();
        $this->registerMenuService();

        $this->registerEmailService();
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
     * Bloc
     */
    protected function registerBlocService(){

        $this->app->singleton('App\Droit\Bloc\Repo\BlocInterface', function()
        {
            return new \App\Droit\Bloc\Repo\BlocEloquent(new \App\Droit\Bloc\Entities\Bloc);
        });
    }

    /**
     * Content
     */
    protected function registerContentService(){

        $this->app->singleton('App\Droit\Content\Repo\ContentInterface', function()
        {
            return new \App\Droit\Content\Repo\ContentEloquent(new \App\Droit\Content\Entities\Content);
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
     * Email
     */
    protected function registerEmailService(){

        $this->app->singleton('App\Droit\Email\Repo\EmailInterface', function()
        {
            return new \App\Droit\Email\Repo\EmailEloquent(new \App\Droit\Email\Entities\Email);
        });
    }
}
