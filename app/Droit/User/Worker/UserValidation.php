<?php namespace App\Droit\User\Worker;

use App\Droit\User\Entities\User;

class UserValidation
{
    protected $adresse;
    
    public $errors = [];

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function activate()
    {
        $this->hasOrdersOrInscriptions();

        if(!empty($this->errors)){
            throw new \App\Exceptions\AdresseRemoveException(implode(', ', $this->errors));
        }

        return true;
    }

    public function hasOrdersOrInscriptions()
    {
        if(!$this->user->orders->isEmpty()) {
            $this->errors[] = 'Le compte est lié à des commandes';
        }

        if(!$this->user->inscriptions->isEmpty()) {
            $this->errors[] = 'Le compte est lié à des inscriptions';
        }

        $this->user->adresses->map(function ($adresse, $key) {
            if(!$adresse->abos->isEmpty()) {
                $this->errors[] = 'Une des adresse est lié à des abonnements';
            }
        });

        return $this;
    }
}