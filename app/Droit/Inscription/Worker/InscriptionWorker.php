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

    public function dispatch($inscriptions, $type = null)
    {
        $dispatch = [];
        $options  = $inscriptions->first()->colloque->options;

        $choix = $options->filter(function ($item) {
            return $item->type == 'choix';
        });

        $checkbox = $options->filter(function ($item) {
            return $item->type == 'checkbox';
        });

        if(!$inscriptions->isEmpty())
        {
            foreach($inscriptions as $inscription)
            {
                if(!$options->isEmpty())
                {
                    foreach($options as $option)
                    {
                        $option->load('groupe');
                        $groupe_choix = $inscription->user_options->groupBy('option_id');

                        if(isset($groupe_choix) && isset($groupe_choix[$option->id]))
                        {
                            $current = $groupe_choix[$option->id];

                            if($option->type == 'choix' && !$option->groupe->isEmpty())
                            {
                                foreach($option->groupe as $groupe)
                                {
                                    if($current->contains('groupe_id', $groupe->id))
                                    {
                                        $dispatch['choix'][$option->id][$groupe->id][] = $inscription;
                                    }
                                }
                            }

                            if($option->type == 'checkbox')
                            {
                                $dispatch['checkbox'][$option->id][] = $inscription;
                            }
                        }
                        else
                        {
                            $dispatch['empty'][] = $inscription;
                        }
                    }
                }

            }
        }

        return $dispatch;

    }

    public function checkbox($inscription){

        $dispatch = [];

        foreach($inscription->options as $option)
        {
            $dispatch['checkbox'][$option->id][] = $inscription;
        }

        return $dispatch;
    }

}