<?php  namespace App\Droit\Inscription\Entities;

use Illuminate\Database\Eloquent\Model;

class Display
{
    public $inscription;
    public $valid = true;
    public $errors;
    public $type;
    public $inscrit = null;
    public $detenteur = '';
    public $adresse   = '';

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

            $this->inscrit   = $this->inscription->groupe->user;
            $this->detenteur = $this->getDetenteur($this->inscription->groupe->user);

            return $this;
        }

        if($this->type == 'inscription' && isset($this->inscription->user))
        {
            $this->inscription->user->load('adresses');
            $this->inscrit = $this->inscription->user;

            $this->detenteur = $this->getDetenteur($this->inscription->user);

            return $this;
        }

        $this->valid    = false;
        $this->errors[] = 'Pas de model inscrit valide';
    }

    public function getDetenteur($inscrit)
    {
        if(!isset($inscrit->adresses) || $inscrit->adresses->isEmpty()){
            $this->valid = false;
            $this->errors[] = 'Pas de\'adresse pour l\'inscrit';
            
            return [];
        }

        $this->adresse = $inscrit->adresses->where('type',1)->first();

        return ['id' => $inscrit->id, 'civilite' => $this->adresse->civilite_title, 'name' => $this->adresse->name, 'email' => $this->adresse->email ];
    }

    public function getParticiants()
    {
        $group = $this->getModel();

        return $group->inscriptions->filter(function ($inscription, $key) {
            return $inscription->group_id;
        })->map(function ($item, $key) {
            return $item->participant;
        });
    }
}