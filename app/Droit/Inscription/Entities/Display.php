<?php  namespace App\Droit\Inscription\Entities;

use Illuminate\Database\Eloquent\Model;

class Display
{
    public $inscription;
    public $valid = true;
    public $errors;
    public $type;
    public $inscrit = null;
    public $name = '';

    public function __construct(Model $inscription)
    {
        $this->inscription = $inscription;
    }

    public function isValid()
    {
        $this->getType()->getInscrit();
        
        if(!$this->valid){
            return false;
        }

        return true;
    }

    public function getModel()
    {
        if($this->type == 'group')
        {
            return $this->inscription->groupe;
        }

        return $this->inscription;
    }
    
    public function getType()
    {
        $this->type = (isset($this->inscription->group_id) ? 'group' : 'inscription');
        
        return $this;
    }

    public function getInscrit()
    {
        if($this->type == 'group' && isset($this->inscription->groupe))
        {
            $this->inscription->groupe->load('user','user.adresses');

            $this->inscrit = $this->inscription->groupe->user;
            $this->name = $this->getName($this->inscription->groupe->user);

            return $this;
        }

        if($this->type == 'inscription' && isset($this->inscription->user))
        {
            $this->inscription->user->load('adresses');
            $this->inscrit = $this->inscription->user;

            $this->name = $this->getName($this->inscription->user);

            return $this;
        }

        $this->valid    = false;
        $this->errors[] = 'Pas de model inscrit valide';
    }

    public function getName($inscrit)
    {
        if(!isset($inscrit->adresses) || $inscrit->adresses->isEmpty()){
            $this->valid = false;
            $this->errors[] = 'Pas de\'adresse pour l\'inscrit';
            
            return [];
        }

        $adresse = $inscrit->adresses->where('type',1)->first();

        return ['civilite' => $adresse->civilite_title, 'name' => $adresse->name ];
    }

    public function getParticiants()
    {
        $group = $this->getModel();
        
        return $group->inscriptions->pluck('participant');
    }
}