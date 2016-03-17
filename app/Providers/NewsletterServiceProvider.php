<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class NewsletterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['validator']->extend('emailconfirmed', function ($attribute, $value, $parameters)
        {
            $email = \DB::table('newsletter_users')->where('email','=',$value)->first();

            if($email)
            {
                return (!$email->activated_at ? false  : true);
            }

            return false;
        });
    }

    /**
     * Register singletonings in the container.
     *
     * @return void
     */
    public function register()
    {

        $this->registerMailjetService();
        $this->registerNewsletterService();
        $this->registerContentService();
        $this->registerTypesService();
        $this->registerCampagneService();
        $this->registerCampagneWorkerService();
        $this->registerInscriptionService();
        $this->registerSubscribeService();
        $this->registerListService();
        $this->registerEmailService();
        $this->registerImportService();
    }

    /**
     * Newsletter Content service
     */
    protected function registerMailjetService(){

        $this->app->singleton('App\Droit\Newsletter\Worker\MailjetInterface', function()
        {
            return new \App\Droit\Newsletter\Worker\MailjetWorker(
                new \App\Droit\Newsletter\Service\Mailjet(
                    config('services.mailjet.api'),config('services.mailjet.secret')
                )
            );
        });
    }

    /**
     * Newsletter Content service
     */
    protected function registerNewsletterService(){

        $this->app->singleton('App\Droit\Newsletter\Repo\NewsletterInterface', function()
        {
            return new \App\Droit\Newsletter\Repo\NewsletterEloquent( new \App\Droit\Newsletter\Entities\Newsletter );
        });
    }

    /**
     * Newsletter service
     */
    protected function registerContentService(){

        $this->app->singleton('App\Droit\Newsletter\Repo\NewsletterContentInterface', function()
        {
            return new \App\Droit\Newsletter\Repo\NewsletterContentEloquent( new \App\Droit\Newsletter\Entities\Newsletter_contents );
        });
    }

    /**
     * Newsletter Types service
     */
    protected function registerTypesService(){

        $this->app->singleton('App\Droit\Newsletter\Repo\NewsletterTypesInterface', function()
        {
            return new \App\Droit\Newsletter\Repo\NewsletterTypesEloquent( new \App\Droit\Newsletter\Entities\Newsletter_types );
        });
    }


    /**
     * Newsletter Types service
     */
    protected function registerCampagneService(){

        $this->app->singleton('App\Droit\Newsletter\Repo\NewsletterCampagneInterface', function()
        {
            return new \App\Droit\Newsletter\Repo\NewsletterCampagneEloquent( new \App\Droit\Newsletter\Entities\Newsletter_campagnes );
        });
    }

    /**
     * Newsletter Campagne worker
     */
    protected function registerCampagneWorkerService(){

        $this->app->singleton('App\Droit\Newsletter\Worker\CampagneInterface', function()
        {
            return new \App\Droit\Newsletter\Worker\CampagneWorker(
                \App::make('App\Droit\Newsletter\Repo\NewsletterContentInterface'),
                \App::make('App\Droit\Newsletter\Repo\NewsletterCampagneInterface'),
                \App::make('App\Droit\Arret\Repo\ArretInterface'),
                \App::make('App\Droit\Categorie\Repo\CategorieInterface'),
                \App::make('App\Droit\Arret\Repo\GroupeInterface')
            );
        });
    }

    /**
     * Newsletter user abo service
     */
    protected function registerInscriptionService(){

        $this->app->singleton('App\Droit\Newsletter\Repo\NewsletterUserInterface', function()
        {
            return new \App\Droit\Newsletter\Repo\NewsletterUserEloquent( new \App\Droit\Newsletter\Entities\Newsletter_users );
        });
    }

    /**
     * Newsletter user abo service
     */
    protected function registerSubscribeService(){

        $this->app->singleton('App\Droit\Newsletter\Repo\NewsletterSubscriptionInterface', function()
        {
            return new \App\Droit\Newsletter\Repo\NewsletterSubscriptionEloquent( new \App\Droit\Newsletter\Entities\Newsletter_subscriptions );
        });
    }

    /**
     * Newsletter list
     */
    protected function registerListService(){

        $this->app->singleton('App\Droit\Newsletter\Repo\NewsletterListInterface', function()
        {
            return new \App\Droit\Newsletter\Repo\NewsletterListEloquent( new \App\Droit\Newsletter\Entities\Newsletter_lists() );
        });
    }

    /**
     * Newsletter list emails
     */
    protected function registerEmailService(){

        $this->app->singleton('App\Droit\Newsletter\Repo\NewsletterEmailInterface', function()
        {
            return new \App\Droit\Newsletter\Repo\NewsletterEmailEloquent( new \App\Droit\Newsletter\Entities\Newsletter_emails() );
        });
    }

    /**
     * Newsletter Import worker
     */
    protected function registerImportService(){

        $this->app->singleton('App\Droit\Newsletter\Worker\ImportWorkerInterface', function()
        {
            return new \App\Droit\Newsletter\Worker\ImportWorker(
                \App::make('App\Droit\Newsletter\Worker\MailjetInterface'),
                \App::make('App\Droit\Newsletter\Repo\NewsletterUserInterface'),
                \App::make('App\Droit\Newsletter\Repo\NewsletterInterface'),
                \App::make('Maatwebsite\Excel\Excel'),
                \App::make('App\Droit\Newsletter\Repo\NewsletterCampagneInterface'),
                \App::make('App\Droit\Newsletter\Worker\CampagneInterface'),
                \App::make('App\Droit\Service\UploadInterface')
            );
        });
    }

}
