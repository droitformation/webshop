<?php namespace App\Droit\Adresse\Entities;

use Illuminate\Database\Eloquent\Model;

class Account
{
   /**
    * The model passed, user or adresse
    * @param  Model
    */
    public $model = null;

    /**
     * Is the user deleted
     * @param  bool
     */
    public $user_deleted = false;

    /**
     * Is the main account adresse type 1 deleted
     * @param  bool
     */
    public $main_adresse_deleted = false;


    /**
     * The name
     * @param  string
     */
    public $name = 'Admin';

    /**
     * All adresses of user if any
     * @param  \Illuminate\Support\Collection
     */
    public $adresses = null;

    /**
     * The main adresse type 1
     * @param  string
     */
    public $main_adresse = null;

    /**
     * Do we want trashed models
     * @param  bool
     */
    public $withTrashed = false;

    public function __construct(Model $model)
    {
        $this->dispatch($model);
    }

    protected function dispatch($model){

        $this->model = $model instanceof \App\Droit\User\Entities\User ? 'user' : 'adresse';

        if($this->model == 'user'){

            // Only activ adresses
            if(!$this->withTrashed){

                $this->user_deleted = $this->model->trashed();

                if(!$this->model->adresses->isEmpty()){
                    $this->adresses     = $this->model->adresses;
                    $this->main_adresse = $this->adresses->where('type',1)->first();
                    $this->name         = $this->main_adresse->name;
                }
            }

            $adresses = $this->model->adresses()->withTrashed()->get();

            if(!$adresses->isEmpty())
            {
                $this->adresses     = $adresses;
                $this->main_adresse = $this->adresses->where('type',1)->first();
                $this->name         = $this->main_adresse->name;

                $this->main_adresse_deleted = $this->main_adresse->trashed();
            }
        }

        if($this->model == 'adresse'){
            $this->main_adresse = $this->model->first();
            $this->name         = $this->main_adresse->name;
            
            $this->main_adresse_deleted = $this->main_adresse->trashed();
        }

    }
}