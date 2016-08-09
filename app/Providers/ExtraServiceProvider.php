<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ExtraServiceProvider extends ServiceProvider
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
        $this->registerFaqQuestionService();
        $this->registerFaqCategorieService();
        $this->registerFaqWorkerService();

        $this->registerCalculetteTauxService();
        $this->registerCalculetteIpcService();
        $this->registerCalculetteWorkerService();

        $this->registerSeminaireService();
        $this->registerSubjectService();
        $this->registerSeminaireWorkerService();
    }

    /**
     * Calculette Taux
     */
    protected function registerCalculetteTauxService()
    {
        $this->app->singleton('App\Droit\Calculette\Repo\CalculetteTauxInterface', function()
        {
            return new \App\Droit\Calculette\Repo\CalculetteTauxEloquent(new \App\Droit\Calculette\Entities\Calculette_taux());
        });
    }

    /**
     * Calculette IPC
     */
    protected function registerCalculetteIpcService()
    {
        $this->app->singleton('App\Droit\Calculette\Repo\CalculetteIpcInterface', function()
        {
            return new \App\Droit\Calculette\Repo\CalculetteIpcEloquent(new \App\Droit\Calculette\Entities\Calculette_ipc());
        });
    }

    /**
     * Calculette Worker
     */
    protected function registerCalculetteWorkerService()
    {
        $this->app->singleton('App\Droit\Calculette\Worker\CalculetteWorkerInterface', function()
        {
            return new \App\Droit\Calculette\Worker\CalculetteWorker(
                new \App\Droit\Calculette\Entities\Calculette_taux(),
                new \App\Droit\Calculette\Entities\Calculette_ipc()
            );
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


    /**
     * Faq Worker
     */
    protected function registerFaqWorkerService()
    {
        $this->app->singleton('App\Droit\Faq\Worker\FaqWorkerInterface', function()
        {
            return new \App\Droit\Faq\Worker\FaqWorker(
                \App::make('App\Droit\Faq\Repo\FaqQuestionInterface'),
                \App::make('App\Droit\Faq\Repo\FaqCategorieInterface')
            );
        });
    }

    /**
     * Seminaire
     */
    protected function registerSeminaireService(){

        $this->app->singleton('App\Droit\Seminaire\Repo\SeminaireInterface', function()
        {
            return new \App\Droit\Seminaire\Repo\SeminaireEloquent(new \App\Droit\Seminaire\Entities\Seminaire);
        });
    }

    /**
     * Subject
     */
    protected function registerSubjectService(){

        $this->app->singleton('App\Droit\Seminaire\Repo\SubjectInterface', function()
        {
            return new \App\Droit\Seminaire\Repo\SubjectEloquent(new \App\Droit\Seminaire\Entities\Subject);
        });
    }
    
    protected function registerSeminaireWorkerService()
    {
        $this->app->singleton('App\Droit\Seminaire\Worker\SeminaireWorkerInterface', function()
        {
            return new \App\Droit\Seminaire\Worker\SeminaireWorker(
                \App::make('App\Droit\Seminaire\Repo\SeminaireInterface'),
                \App::make('App\Droit\Seminaire\Repo\SubjectInterface')
            );
        });
    }
}
