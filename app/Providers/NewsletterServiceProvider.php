<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class NewsletterServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
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
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        // Custom Facade for CampagneWorker
        $this->app->singleton('newsworker', function ($app) {
            return $app->make('App\Droit\Newsletter\Worker\CampagneInterface');
        });

        $this->registerMailgunNewService();

        $this->registerMailjetService();
        $this->registerMailjetNewService();
        $this->registerNewsletterService();
        $this->registerContentService();
        $this->registerTypesService();
        $this->registerCampagneService();
        $this->registerCampagneWorkerService();
        $this->registerInscriptionService();
        $this->registerSubscribeService();
        $this->registerSubscribeWorkerService();
        $this->registerImportService();
        $this->registerListService();
        $this->registerEmailService();
        $this->registerClipboardService();
        $this->registerTrackingService();
    }


    protected function registerMailgunNewService(){

        $this->app->bind('App\Droit\Newsletter\Worker\MailgunInterface', function()
        {
            if (\App::environment('testing')) {
                $mailgun = \Mockery::mock('\Mailgun\Mailgun');
            }
            else{
                $mailgun = new \Mailgun\Mailgun(config('mailgun.api_key'));
            }

            return new \App\Droit\Newsletter\Worker\MailgunService($mailgun);
        });
    }

    /**
     * Newsletter Content service
     */
    protected function registerMailjetService(){

        $this->app->bind('App\Droit\Newsletter\Worker\MailjetInterface', function()
        {
            return new \App\Droit\Newsletter\Worker\MailjetWorker(
                new \App\Droit\Newsletter\Service\Mailjet(
                    config('newsletter.mailjet.api'),config('newsletter.mailjet.secret')
                )
            );
        });
    }

    /**
     * Newsletter Content service
     */
    protected function registerMailjetNewService(){

        $this->app->bind('App\Droit\Newsletter\Worker\MailjetServiceInterface', function()
        {
            return new \App\Droit\Newsletter\Worker\MailjetService(
                new \Mailjet\Client(config('newsletter.mailjet.api'),config('newsletter.mailjet.secret')),
                new \Mailjet\Resources()
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
                \App::make('App\Droit\Newsletter\Repo\NewsletterInterface'),
                \App::make('App\Droit\Newsletter\Repo\NewsletterUserInterface')
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
            return new \App\Droit\Newsletter\Repo\NewsletterSubscriptionEloquent(
                new \App\Droit\Newsletter\Entities\Newsletter_subscriptions
            );
        });
    }

    /**
     * Newsletter subscriber service
     */
    protected function registerSubscribeWorkerService(){

        $this->app->bind('App\Droit\Newsletter\Worker\SubscriptionWorkerInterface', function()
        {
            return new \App\Droit\Newsletter\Worker\SubscriptionWorker(
                \App::make('App\Droit\Newsletter\Repo\NewsletterInterface'),
                \App::make('App\Droit\Newsletter\Repo\NewsletterUserInterface'),
                \App::make('App\Droit\Newsletter\Worker\MailjetServiceInterface')
            );
        });
    }

    /**
     * Newsletter Import worker
     */
    protected function registerImportService(){

        $this->app->singleton('App\Droit\Newsletter\Worker\ImportWorkerInterface', function()
        {
            return new \App\Droit\Newsletter\Worker\ImportWorker(
                \App::make('App\Droit\Newsletter\Worker\MailjetServiceInterface'),
                \App::make('App\Droit\Newsletter\Repo\NewsletterUserInterface'),
                \App::make('App\Droit\Newsletter\Repo\NewsletterInterface'),
                \App::make('Maatwebsite\Excel\Excel'),
                \App::make('App\Droit\Newsletter\Repo\NewsletterCampagneInterface'),
                \App::make('App\Droit\Newsletter\Worker\CampagneInterface'),
                \App::make('App\Droit\Service\UploadInterface')
            );
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
     * Newsletter Email
     */
    protected function registerEmailService(){

        $this->app->singleton('App\Droit\Newsletter\Repo\NewsletterEmailInterface', function()
        {
            return new \App\Droit\Newsletter\Repo\NewsletterEmailEloquent( new \App\Droit\Newsletter\Entities\Newsletter_emails() );
        });
    }

    /**
     * Newsletter clipboard
     */
    protected function registerClipboardService(){

        $this->app->singleton('App\Droit\Newsletter\Repo\NewsletterClipboardInterface', function()
        {
            return new \App\Droit\Newsletter\Repo\NewsletterClipboardEloquent( new \App\Droit\Newsletter\Entities\Newsletter_clipboards() );
        });
    }

    /**
     * Newsletter Tracking
     */
    protected function registerTrackingService(){

        $this->app->singleton('App\Droit\Newsletter\Repo\NewsletterTrackingInterface', function()
        {
            return new \App\Droit\Newsletter\Repo\NewsletterTrackingEloquent(
                new \App\Droit\Newsletter\Entities\Newsletter_tracking(),
                new \App\Droit\Newsletter\Entities\Newsletter_sent()
            );
        });
    }
}