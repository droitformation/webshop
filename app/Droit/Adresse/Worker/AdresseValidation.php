<?php namespace App\Droit\Adresse\Worker;

use App\Droit\Adresse\Entities\Adresse;

class AdresseValidation
{
    protected $adresse;
    
    public $errors = [];

    public function __construct(Adresse $adresse)
    {
        $this->adresse = $adresse;
    }

    public function activate()
    {
        $this->hasUser()->hasOrdersOrInscriptions();

        if(!empty($this->errors)){
            throw new \App\Exceptions\AdresseRemoveException(implode(', ', $this->errors));
        }

        return true;
    }

    public function hasUser()
    {
        if(isset($this->adresse->user)) {
            $this->errors[] = 'L\'adresse est rattaché à un compte utilisateur';
        }

        return $this;
    }

    public function hasOrdersOrInscriptions()
    {
        if(isset($this->adresse->user)) {
            if(!$this->adresse->user->orders->isEmpty()) {
                $this->errors[] = 'L\'adresse est lié à des commandes';
            }

            if(!$this->adresse->user->inscriptions->isEmpty()) {
                $this->errors[] = 'L\'adresse est lié à des inscriptions';
            }
        }

        if(!$this->adresse->orders->isEmpty()) {
            $this->errors[] = 'L\'adresse est lié à des commandes';
        }

        if(!$this->adresse->abos->isEmpty()) {
            $this->errors[] = 'L\'adresse est lié à des abonnements';
        }

        return $this;
    }
}