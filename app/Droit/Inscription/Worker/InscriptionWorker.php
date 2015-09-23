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

    public function dispatch($inscriptions, $options)
    {
        foreach($inscriptions as $inscription)
        {
            foreach($options as $option_id => $option)
            {
                $groupe_choix = $inscription->user_options->groupBy('option_id');

                if(isset($groupe_choix) && isset($groupe_choix[$option_id]) && $this->helper->is_multi($option))
                {

                        foreach ($option as $groupe_id => $groupe)
                        {
                            $current = $groupe_choix[$option_id];

                            if($current->contains('groupe_id', $groupe_id))
                            {
                                $dispatch[$option_id][$groupe_id][] = $inscription;
                            }
                        }





                }
                elseif(!$this->helper->is_multi($option))
                {
                    if(isset($groupe_choix) && isset($groupe_choix[$option_id]))
                    {
                        $dispatch[$option_id][] = $inscription;
                    }
                    else
                    {
                        $dispatch[0][] = $inscription;
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