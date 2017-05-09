<?php  namespace App\Droit\Inscription\Entities;

class Archive
{
    public $colloque_id;
    public $inscription = null;
    public $user        = null;
    public $file;
    public $invalid  = false;

    protected $repo_user;

    public function __construct($file,$colloque_id)
    {
        $this->file = $file;
        $this->colloque_id = $colloque_id;

        $this->repo_user = \App::make('App\Droit\User\Repo\UserInterface');
    }

    public function archives()
    {
        $this->getUser()->getInscription()->isInvalid();

        if(!$this->invalid){
            return ['user' => $this->user, 'inscription' => $this->inscription ,'file' => $this->file];
        }

        return null;
    }

    public function getUser()
    {
        $pieces  = explode('-',$this->file);
        $user_id = str_replace('.pdf','',end($pieces));
        
        $this->user = $this->repo_user->findWithTrashed($user_id);
        
        return $this;
    }

    public function getInscription()
    {
        if($this->user){
            $inscription = $this->user->inscriptions->where('colloque_id',$this->colloque_id);
            $inscription = !$inscription->isEmpty() ? $inscription->first() : null;
            $this->inscription = $inscription && $inscription->price_cents > 0 ? $inscription : null;
        }
        
        return $this;
    }

    public function isInvalid()
    {
        $this->invalid = (!isset($this->user) || $this->user->trashed()) || !$this->inscription ? true : false;

        return $this;
    }
}