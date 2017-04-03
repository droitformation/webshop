<?php namespace App\Droit\Adresse\Worker;

use App\Droit\Adresse\Repo\AdresseInterface;
use App\Droit\User\Repo\UserInterface;
use App\Droit\Adresse\Worker\AdresseWorkerInterface;

class AdresseWorker implements AdresseWorkerInterface{

    protected $action;
    protected $items;

    protected $adresse;
    protected $user;

    protected $recipient;
    protected $fromadresses;

    public function __construct(AdresseInterface $adresse, UserInterface $user)
    {
        $this->adresse = $adresse;
        $this->user    = $user;
    }

    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    public function setItems($items)
    {
        $this->items = $items;

        return $this;
    }

    public function setFromAdresses($fromadresses)
    {
        $this->fromadresses = $fromadresses;

        return $this;
    }

    public function fetchUser($adresse_id)
    {
        $recipient = $this->adresse->find($adresse_id);

        // we have a user
        if(!$recipient && isset($recipient->user)){
            throw new \App\Exceptions\UserNotExistException('Cet utilisateur n\'existe pas');
        }

        $this->recipient = $recipient->user;

        return $this;
    }

    public function getAdresses($ids)
    {
        return $this->adresse->getMultiple($ids);
    }

    public function transvase()
    {
        // get user recipient

        // if attach
            // attach fromadresses to recipient
        // if delete
            // delete fromadresses
        // if items
            // get all items from fromadresses and attach to user
        
    }

    public function prepareTerms($terms, $type)
    {
        $terms = array_filter($terms);

        return !empty($terms) ? collect($terms)->transpose()->map(function ($term) {
            return ['value' => $term[0], 'column' => $term[1]];
        })->filter(function ($terms, $key) use ($type) {
            return !empty($terms['value']) && $this->authorized($terms['column'],$type) ? true : false;
        })->toArray() : [];
    }

    public function authorized($column,$type)
    {
        $authorized = ['user' => ['first_name', 'last_name', 'email'], 'adresse' => ['first_name', 'last_name', 'email','company','adresse','npa', 'ville']];

        return in_array($column, $authorized[$type]);
    }
}