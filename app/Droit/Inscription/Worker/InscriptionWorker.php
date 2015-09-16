<?php
namespace App\Droit\Inscription\Worker;

class InscriptionWorker{

    protected $inscription;
    protected $colloque;
    protected $option;

    public function __construct()
    {
        $this->inscription = \App::make('App\Droit\Inscription\Repo\InscriptionInterface');
        $this->colloque    = \App::make('App\Droit\Colloque\Repo\ColloqueInterface');
        $this->option      = \App::make('App\Droit\Option\Repo\OptionInterface');
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

    public function dispatch($inscriptions, $option_id = null)
    {
        $dispatch = [];
        // $option = $this->option->find($option_id);

        if(!$inscriptions->isEmpty())
        {
            foreach($inscriptions as $inscription)
            {
                if(!$inscription->options->isEmpty())
                {
                    foreach($inscription->options as $option)
                    {
                        if($option->type == 'choix')
                        {
                            $option->load('groupe');

                            foreach($option->groupe as $groupe)
                            {
                                $dispatch[$option->type][$option->id][$groupe->id] = $inscription->toArray();
                            }
                        }
                        else
                        {
                            $dispatch[$option->type][$option->id][] = $inscription->toArray();
                        }
                    }
                }
            }
        }

        return $dispatch;

    }

}