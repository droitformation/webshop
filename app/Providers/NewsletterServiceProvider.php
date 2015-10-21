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
     * Register bindings in the container.
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

    }

    /**
     * Newsletter Content service
     */
    protected function registerMailjetService(){

        $this->app->bind('App\Droit\Newsletter\Worker\MailjetInterface', function()
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

        $this->app->bind('App\Droit\Newsletter\Repo\NewsletterInterface', function()
        {
            return new \App\Droit\Newsletter\Repo\NewsletterEloquent( new \App\Droit\Newsletter\Entities\Newsletter );
        });
    }

    /**
     * Newsletter service
     */
    protected function registerContentService(){

        $this->app->bind('App\Droit\Newsletter\Repo\NewsletterContentInterface', function()
        {
            return new \App\Droit\Newsletter\Repo\NewsletterContentEloquent( new \App\Droit\Newsletter\Entities\Newsletter_contents );
        });
    }

    /**
     * Newsletter Types service
     */
    protected function registerTypesService(){

        $this->app->bind('App\Droit\Newsletter\Repo\NewsletterTypesInterface', function()
        {
            return new \App\Droit\Newsletter\Repo\NewsletterTypesEloquent( new \App\Droit\Newsletter\Entities\Newsletter_types );
        });
    }


    /**
     * Newsletter Types service
     */
    protected function registerCampagneService(){

        $this->app->bind('App\Droit\Newsletter\Repo\NewsletterCampagneInterface', function()
        {
            return new \App\Droit\Newsletter\Repo\NewsletterCampagneEloquent( new \App\Droit\Newsletter\Entities\Newsletter_campagnes );
        });
    }

    /**
     * Newsletter Campagne worker
     */
    protected function registerCampagneWorkerService(){

        $this->app->bind('App\Droit\Newsletter\Worker\CampagneInterface', function()
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

        $this->app->bind('App\Droit\Newsletter\Repo\NewsletterUserInterface', function()
        {
            return new \App\Droit\Newsletter\Repo\NewsletterUserEloquent( new \App\Droit\Newsletter\Entities\Newsletter_users );
        });
    }

    /**
     * Newsletter user abo service
     */
    protected function registerSubscribeService(){

        $this->app->bind('App\Droit\Newsletter\Repo\NewsletterSubscriptionInterface', function()
        {
            return new \App\Droit\Newsletter\Repo\NewsletterSubscriptionEloquent( new \App\Droit\Newsletter\Entities\Newsletter_subscriptions );
        });
    }

}
