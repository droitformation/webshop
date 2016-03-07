<?php
namespace App\Droit\Inscription\Worker;
use App\Droit\Inscription\Repo\InscriptionInterface;
use App\Droit\Colloque\Repo\ColloqueInterface;
use App\Droit\Option\Repo\OptionInterface;
use App\Droit\Inscription\Repo\GroupeInterface;
use Illuminate\Support\Collection;

class InscriptionWorker implements InscriptionWorkerInterface{

    /*
    * Helper class for misc functions
    **/
    protected $helper;

    protected $inscription;
    protected $colloque;
    protected $option;
    protected $group;

    public $dispatch = [];

    public function __construct(InscriptionInterface $inscription, ColloqueInterface $colloque, OptionInterface $option, GroupeInterface $group)
    {
        $this->inscription = $inscription;
        $this->colloque    = $colloque;
        $this->option      = $option;
        $this->group       = $group;

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

    public function registerGroup($colloque, $request)
    {
        // create new group
        $group = $this->group->create(['colloque_id' => $colloque , 'user_id' => $request->input('user_id')]);

        // Get all infos for inscriptions/participants
        $participants = $request->input('participant');
        $prices       = $request->input('price_id');
        $options      = $request->input('options');
        $groupes      = $request->input('groupes');

        // Make inscription for each participant
        foreach($participants as $index => $participant)
        {
            $data = [
                'group_id'    => $group->id,
                'colloque_id' => $colloque,
                'participant' => $participant,
                'price_id'    => $prices[$index]
            ];

            // choosen options for participants
            if(isset($options[$index]))
            {
                $data['options'] = $options[$index];
            }

            // choosen groupe of options for participants
            if(isset($groupes[$index]))
            {
                $data['groupes'] = $groupes[$index];
            }

            // Register a new inscription
            $this->register($data,$colloque);

        }

        return $group;
    }

}