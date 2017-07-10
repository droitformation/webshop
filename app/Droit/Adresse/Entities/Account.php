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
     * The model type
     * @param  string
     */
    public $type = null;

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
    public $withTrashed = true;

    public function __construct(Model $model = null)
    {
        if($model){
            $this->dispatch($model);
        }
    }

    protected function dispatch($model){

        $this->type  = $model instanceof \App\Droit\User\Entities\User ? 'user' : 'adresse';
        $this->model = $model;

        if($this->type == 'user'){

            $this->user_deleted = $this->model->trashed();

            if(!$this->model->adresses->isEmpty()){
                $this->adresses     = $this->model->adresses;
                $this->name         = $this->main_adresse->name;
                $this->main_adresse = $this->adresses->where('type',1)->first();
                $this->main_adresse_deleted = $this->main_adresse->trashed();
            }
            else{
                $adresses = $this->model->adresses()->withTrashed()->get();

                if(!$adresses->isEmpty()) {
                    $this->adresses = $adresses;

                    if($adresses->count() > 1) {
                        // If more than 2 test if one of them is not trashed
                        $main = $this->adresses->where('type',1)->first(function ($value, $key) {
                            return !$value->trashed();
                        });

                        if(!$main){
                            // Get the first one type 1
                            $main = $this->adresses->where('type',1)->first();
                        }
                    }
                    else{
                        // Get the first one type 1
                        $main = $this->adresses->where('type',1)->first();
                    }

                    $this->main_adresse = $main;
                    $this->name         = $this->main_adresse->name;
                    $this->main_adresse_deleted = $this->main_adresse->trashed();
                }
            }

        }

        if($this->type == 'adresse'){
            $this->main_adresse = $this->model;
            $this->name         = $this->main_adresse->name;

            $this->main_adresse_deleted = $this->main_adresse->trashed();
        }

    }
}