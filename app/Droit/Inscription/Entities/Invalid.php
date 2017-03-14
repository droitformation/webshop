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
                        $this->invalid['group_account'] = ['message' => 'Compte utilisateur du groupe supprimé', 'id' => $user->first()->id];
                    }
                    else{
                        $this->invalid['group_user'] = ['message' => 'Aucun utilisateur pour le groupe', 'id' => $this->inscription->groupe->id];
                    }
                }
                else{
                    $this->user = $this->inscription->groupe->user;
                }
            }
            else{

                $group = $this->inscription->groupe()->withTrashed()->get();

                if(!$group->isEmpty()){
                    $group = $group->first();
                    $this->invalid['group_deleted'] = ['message' => 'Groupe supprimé', 'id' => $group->id];
                }
                else{
                    $this->invalid['group_missing'] = ['message' => 'Aucun groupe', 'id' => null];
                }
            }
        }
        else
        {
            $user = $this->inscription->user()->withTrashed()->get();
            
            if(!$user->isEmpty()) {
                $this->user = $user->first();
                $this->invalid['user'] = ['message' => 'Compte supprimé', 'id' => $user->first()->id];
            }
            else{
                $this->invalid['user_missing'] = ['message' => 'Aucun utilisateur', 'id' => null];
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
                $this->invalid['adresse_missing'] = ['message' => 'Aucunw adresse', 'id' => null];
            }
        }

        return $this;
    }

    public function restoreUrl($type)
    {
        $paths = [
            'group_account' => 'user',
            'group_user'    => 'user',
            'group_deleted' => 'group',
            'user'          => 'user'
        ];

        if(isset($paths[$type])){
            $id   = $this->invalid[$type]['id'];
            $path = isset($paths[$type]) ? $paths[$type] : 'user';

            return url('admin/'.$path.'/restore/'.$id);
        }

        return null;
    }
}