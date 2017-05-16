<?php namespace App\Droit\Adresse\Worker;

use App\Droit\Adresse\Repo\AdresseInterface;
use App\Droit\User\Repo\UserInterface;
use App\Droit\Adresse\Worker\AdresseWorkerInterface;

class AdresseWorker implements AdresseWorkerInterface{

    public $action = null;
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
        $this->fromadresses = array_flatten($fromadresses); //isset($fromadresses[0]) ? $fromadresses[0] : $fromadresses;

        return $this;
    }

    public function removeSession()
    {
        session()->forget('deleted');
        session()->forget('transvase');

        return $this;
    }

    public function getAdresses()
    {
        return $this->adresse->getMultiple($this->fromadresses);
    }

    /*
     * Set from adresses
     * Set types
     * Reassign
     * */
    public function reassignFor($recipient, $compare = true){

        $adresses = $this->adresse->getMultiple($this->fromadresses);

        // mergr all adresse and users, only for compare
        if($compare){
            $adresses = $this->getList($adresses, $type = 'adresse');
            $accounts = $this->getList($adresses, $type = 'user');
            $adresses   = $adresses->merge($accounts)->reject(function ($value, $key) {
                return !$value;
            });
        }

        $adresses->map(function ($model, $key) use ($recipient) {
            // Re assign types for model
            $this->reassign($model, $recipient);

            if($this->action){
                $this->action($model, $recipient);
                $this->reasignMembership($model,$recipient);
            }
        });
    }

    public function action($model, $recipient)
    {
        $user = $model instanceof \App\Droit\User\Entities\User ? $model : null;

        $type = $user ? 'user' : 'adresse';

        if($this->action == 'delete' || ($this->action == 'attachdelete' && $user) ){
            $this->$type->delete($model->id);
            session()->push('deleted.'.$type, $model->id);
        }

        if(in_array($this->action,['attach','attachdelete']) && !$user){
            $type = (!$recipient->adresses->isEmpty() && $recipient->adresses->count() >= 1) ? 2 : 1;;
            $this->adresse->update(['id' => $model->id, 'user_id' => $recipient->id, 'type' => $type]);
        }
    }

    public function reassign($model, $recipient)
    {
        if(!empty($this->types)){
            foreach($this->types as $type){
                if(isset($model->$type) && !$model->$type->isEmpty()) {
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
                    }
                }
            }
        }
    }

    public function getList($adresses, $type = 'adresse')
    {
        return $adresses->map(function ($adresse, $key) use ($type) {
            $user = isset($adresse->user) ? $adresse->user : null;

            if($type == 'adresse'){
                $user = isset($adresse->user) ? $adresse->user : null;
                return  $user ? $user->adresses : collect([$adresse]);
            }

            return $user ? $user : null;

        })->flatten(1)->reject(function ($value, $key) {
            return !$value;
        });
    }

    /* If deleted or attach action
     * list all specialisations/members from all adresses
     * Reassign to recipient contact adresse
    */
    public function reasignMembership($model, $recipient)
    {
        if($this->action != 'rien')
        {
            $list  = $model instanceof \App\Droit\User\Entities\User ? $model->adresses : collect([$model]);

            if($recipient instanceof \App\Droit\User\Entities\User){

                $specs = $list->pluck('specialisations')->flatten(1)->pluck('id')->all();
                $mems  = $list->pluck('members')->flatten(1)->pluck('id')->all();

                $list->each(function ($item, $key){
                    $item->specialisations()->detach();
                    $item->members()->detach();
                });

                $this->adresse->setSpecialisation($recipient->adresse_contact->id,$specs);
                $this->adresse->setMember($recipient->adresse_contact->id,$mems);
            }
        }
    }

    public function prepareTerms($terms, $type)
    {
        $terms = array_filter($terms);

        return !empty($terms) ? collect($terms)->transpose()->map(function ($term) {
            return ['column' => trim($term[1]),'value' => trim($term[0])];
        })->filter(function ($term, $key) use ($type) {
            return !empty($term['value']) && $this->authorized($term['column'],$type) ? $term : false;
        })->toArray() : [];
    }

    public function authorized($column,$type)
    {
        $authorized = ['user' => ['first_name', 'last_name', 'email'], 'adresse' => ['first_name', 'last_name', 'email','company','adresse','npa', 'ville']];

        return in_array($column, $authorized[$type]);
    }
}