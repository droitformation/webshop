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

        return $sondage->reponses->map(function ($item, $key) {
            return $item->items;
        })->flatten()
            ->groupBy('avis_id')
            ->mapWithKeys(function ($item, $key) {
                if($item->first()->avis->type == 'radio' || $item->first()->avis->type == 'checkbox'){
                    $reponses = $item->groupBy('reponse')->map(function ($av, $key) use ($item) {
                        return $av->count();
                    });
                }
                else{
                    $reponses = $item->first()->avis->type == 'chapitre' ? null : $item->pluck('reponse');
                }

                $title = $item->first()->avis->type == 'chapitre' ? ['chapitre' => $item->first()->avis->question] : ['title' => $item->first()->avis->question];

                return [$item->first()->avis_id => $title +  ['reponses' => $reponses, 'type' => $item->first()->avis->type]];
            });

    }
}