<?php
namespace App\Droit\Inscription\Worker;

class InscriptionWorker{

    /*
    * Helper class for misc functions
    **/
    protected $helper;

    protected $inscription;
    protected $colloque;
    protected $option;

    public $dispatch = [];

    public function __construct()
    {
        $this->inscription = \App::make('App\Droit\Inscription\Repo\InscriptionInterface');
        $this->colloque    = \App::make('App\Droit\Colloque\Repo\ColloqueInterface');
        $this->option      = \App::make('App\Droit\Option\Repo\OptionInterface');

        $this->helper  = new \App\Droit\Helper\Helper();
    }

    public function register($data,$colloque_id, $simple = false){

        if($simple)
        {
            $already = $this->inscription->getByUser($colloque_id,$data['user_id']);

            if($already)
            {
                throw new \App\Exceptions\RegisterException('Register failed');
            }
        }

        $inscription_no = $this->colloque->getNewNoInscription($colloque_id);

        // Prepare data
        $data        = $data + ['inscription_no' => $inscription_no];
        $inscription = $this->inscription->create($data);

        return $inscription;
    }

}