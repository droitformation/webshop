<?php namespace App\Droit\Adresse\Worker;

use App\Droit\Adresse\Repo\AdresseInterface;
use App\Droit\User\Repo\UserInterface;
use App\Droit\Adresse\Worker\AdresseWorkerInterface;

class AdresseWorker implements AdresseWorkerInterface{

    public $action;
    public $types = ['orders','inscriptions','abos'];

    protected $adresse;
    protected $user;

    public $recipient;
    public $fromadresses = [];

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

    public function setTypes($types)
    {
        $this->types = $types;

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

    public function getAdresses()
    {
        return $this->adresse->getMultiple($this->fromadresses);
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

    /*
     * Set from adresses
     * Set types
     * Reassign
     * */
    public function reassignFor($recipient){

        $adresses = $this->adresse->getMultiple($this->fromadresses);

        $adresses->map(function ($adresse, $key) use ($recipient) {

            // Re assign types for adresse
            $this->reassign($adresse, $recipient);

            // Re assign types for eventual user
            $adresseuser = isset($adresse->user) ? $adresse->user : null;

            if(in_array($this->action,['delete','attachdelete'])){
                $this->adresse->delete($adresse->id);
            }

            if(in_array($this->action,['attach','attachdelete'])){
                $type = (!$recipient->adresses->isEmpty() && $recipient->adresses->count() >= 1) ? 2 : 1;;
                $this->adresse->update(['id' => $adresse->id, 'user_id' => $recipient->id, 'type' => $type]);
            }


            
            if($this->action == 'attachdelete' && $adresseuser){
                $this->adresse->delete($adresseuser->id);
            }
        });
    }

    public function reassign($model, $recipient)
    {
        foreach($this->types as $type){
            if(!$model->$type->isEmpty()) {
                foreach($model->$type as $item) {

                    if($type == 'orders'){
                        $item->adresse_id = null;
                        $item->user_id = ($recipient instanceof \App\Droit\User\Entities\User ? $recipient->id : $recipient->user_id);
                    }

                    if($type == 'abos'){
                        $item->adresse_id = ($recipient instanceof \App\Droit\User\Entities\User ? $recipient->adresse_contact->id : $recipient->id);
                    }

                    if($type == 'inscriptions'){
                        $item->user_id = ($recipient instanceof \App\Droit\User\Entities\User ? $recipient->id : $recipient->user_id);
                    }

                    $item->save();
                    echo '<pre>';
                    print_r($item);
                    echo '</pre>';exit();
                }
            }
        }
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