<?php
namespace App\Droit\Inscription\Worker;
use App\Droit\Inscription\Repo\InscriptionInterface;
use App\Droit\Colloque\Repo\ColloqueInterface;
use App\Droit\Option\Repo\OptionInterface;

class InscriptionWorker implements InscriptionWorkerInterface{

    /*
    * Helper class for misc functions
    **/
    protected $helper;

    protected $inscription;
    protected $colloque;
    protected $option;

    public $dispatch = [];

    public function __construct(InscriptionInterface $inscription, ColloqueInterface $colloque, OptionInterface $option)
    {
        $this->inscription = $inscription;
        $this->colloque    = $colloque;
        $this->option      = $option;

        $this->helper  = new \App\Droit\Helper\Helper();
    }

    public function register($data,$colloque_id, $simple = false)
    {
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

        // Update counter
        $this->colloque->increment($colloque_id);

        return $inscription;
    }



}