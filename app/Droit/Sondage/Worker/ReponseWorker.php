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
        $reponses = $sondage->reponses->map(function ($item, $key) {
            return $item->items;
        })->flatten();

        return $sondage->avis->mapWithKeys(function ($item, $key) use ($sondage, $reponses) {

            $rep = $reponses->where('avis_id', $item->id);

            if($item->type == 'radio' || $item->type == 'checkbox'){
                $answers = $rep->groupBy('reponse')->map(function ($av, $key) use ($item) {
                    return $av->count();
                });
            }
            else{
                $answers = $item->type == 'chapitre' ? '' : $rep->pluck('reponse');
            }

            return [
                $item->id => [
                    'title'    => $item->question,
                    'reponses' => $item->type != 'chapitre' ?  $answers : null,
                    'type'     => $item->type,
                ]
            ];
        });

    }
}