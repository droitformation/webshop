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

    public function make($data, $reponses)
    {
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

    public function sortByPerson($reponses)
    {
        return $reponses->map(function ($item, $key) {
            return $item->items->load('response');
        })->flatten()->groupBy(function ($item, $key) {
            return $item->reponse_id;
        });
    }

    public function sortByQuestion($sondage)
    {
        return $sondage->avis->mapWithKeys(function ($item, $key) {

            if($item->type == 'radio' || $item->type == 'checkbox'){
                $reponses = $item->responses->groupBy('reponse')->mapWithKeys(function ($reponses,$key) {
                    return [$key => $reponses->count()];
                });
            }
            else{
                $reponses = $item->type == 'chapitre' ? null : $item->responses->pluck('reponse');
            }

            $title = $item->type == 'chapitre' ? ['chapitre' => $item->question] : ['title' => $item->question];

            return [
                $item->id => $title + ['reponses' => $reponses, 'type' => $item->type]
            ];
        });
    }
}