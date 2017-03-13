<?php  namespace App\Droit\Inscription\Entities;

use Illuminate\Database\Eloquent\Model;

class Invalid
{
    public $inscription;
    public $user    = null;
    public $adresse = null;
    public $invalid = [];

    public function __construct(Model $inscription)
    {
        $this->inscription = $inscription;
    }

    public function trashedUser()
    {
        if($this->inscription->group_id > 0)
        {
            if(isset($this->inscription->groupe))
            {
                if(!isset($this->inscription->groupe->user))
                {
                    $user = $this->inscription->groupe->user()->withTrashed()->get();

                    if(!$user->isEmpty()) {
                        $this->user = $user->first();
                        $this->invalid[] = 'Compte utilisateur ID '.$user->first()->id.' du groupe ID  supprimé';
                    }
                    else{
                        $this->invalid[] = 'Aucun utilisateur pour le groupe ID '.$groupe->id;
                    }
                }
                else{
                    $this->user = $this->inscription->groupe->user;
                }
            }
            else{
                $this->invalid[] = 'Aucun groupe';
            }
        }
        else
        {
            $user = $this->inscription->user()->withTrashed()->get();
            
            if(!$user->isEmpty()) {
                $this->user = $user->first();
                $this->invalid[] = 'Compte ID '.$user->first()->id.' supprimé';
            }
            else{
                $this->invalid[] = 'Aucun utilisateur';
            }
        }
        
        return $this;
    }

    public function getAdresse()
    {
        if($this->user) {
            $adresses = $this->user->adresses()->withTrashed()->where('type','=',1)->get();

            if(!$adresses->isEmpty()){
                $this->adresse = $adresses->first()->invoice_name;
            }
            else{
                $this->invalid[] = 'Aucune adresse';
            }
        }

        return $this;
    }
}