<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
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
        $this->registerUserService();
        $this->registerRoleService();
        $this->registerAdresseService();
        $this->registerAdresseWorkerService();
        $this->registerAccountWorkerService();
        $this->registerCiviliteService();
        $this->registerCantonService();
        $this->registerPaysService();
        $this->registerProfessionService();
        $this->registerSpecialisationService();
        $this->registerMemberService();
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
     * Role
     */
    protected function registerRoleService(){

        $this->app->singleton('App\Droit\User\Repo\RoleInterface', function()
        {
            return new \App\Droit\User\Repo\RoleEloquent(new \App\Droit\User\Entities\Role);
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
     * Adresse worker
     */
    protected function registerAdresseWorkerService(){

        $this->app->singleton('App\Droit\Adresse\Worker\AdresseWorkerInterface', function()
        {
            return new \App\Droit\Adresse\Worker\AdresseWorker(
                \App::make('App\Droit\Adresse\Repo\AdresseInterface'),
                \App::make('App\Droit\User\Repo\UserInterface')
            );
        });
    }

    /**
     * Account Worker
     */
    protected function registerAccountWorkerService(){

        $this->app->singleton('App\Droit\User\Worker\AccountWorkerInterface', function()
        {
            return new \App\Droit\User\Worker\AccountWorker(
                \App::make('App\Droit\Adresse\Repo\AdresseInterface'),
                \App::make('App\Droit\User\Repo\UserInterface')
            );
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

}
