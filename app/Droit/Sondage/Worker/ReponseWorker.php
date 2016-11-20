<?php
namespace App\Droit\Sondage\Worker;

use App\Droit\Sondage\Repo\ReponseInterface;

class ReponseWorker
{
    protected $reponse;

    public function __construct(ReponseInterface $reponse)
    {
        $this->reponse = $reponse;
    }

    public function make($data, $reponses){
        
        $reponse = $this->reponse->create($data);

        $reponses = array_filter($reponses['reponses']);
        
        if(!empty($reponses))
        {
            foreach ($reponses as $avis => $answer)
            {
                $reponse->items()->create([
                    'reponse_id' => $reponse->id,
                    'avis_id'    => $avis,
                    'reponse'    => $answer,
                ]);
            }
        }
     
        return $reponse;
    }
}