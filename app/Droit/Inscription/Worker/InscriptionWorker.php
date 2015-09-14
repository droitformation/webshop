<?php
namespace App\Droit\Inscription\Worker;

class InscriptionWorker{

    protected $inscription;
    protected $colloque;

    public function __construct()
    {
        $this->inscription = \App::make('App\Droit\Inscription\Repo\InscriptionInterface');
        $this->colloque    = \App::make('App\Droit\Colloque\Repo\ColloqueInterface');
    }

    public function register($data,$colloque_id){

        $inscription_no = $this->colloque->getNewNoInscription($colloque_id);

        // Prepare data
        $data        = $data + ['inscription_no' => $inscription_no];
        $inscription = $this->inscription->create($data);

        return $inscription;

    }

}