<?php namespace App\Droit\Sondage\Repo;

use App\Droit\Sondage\Repo\SondageInterface;
use App\Droit\Sondage\Entities\Sondage as M;

class SondageEloquent implements SondageInterface{

    protected $sondage;

    public function __construct(M $sondage)
    {
        $this->sondage = $sondage;
    }

    public function getAll()
    {
        return $this->sondage->with(['colloque','avis'])->get();
    }

    public function find($id)
    {
        return $this->sondage->find($id);
    }

    public function create(array $data)
    {
        $sondage = $this->sondage->create(array(
            'colloque_id' => $data['colloque_id'],
            'valid_at'    => $data['valid_at']
        ));

        if(!$sondage)
        {
            return false;
        }

        return $sondage;
    }

    public function update(array $data)
    {
        $sondage = $this->sondage->findOrFail($data['id']);

        if(!$sondage)
        {
            return false;
        }

        $sondage->fill($data);
        $sondage->save();

        return $sondage;
    }

    public function updateSorting($id, array $data)
    {
        if(!empty($data))
        {
            $sorting = collect($data)->map(function($item,$key) {
                return ['key' => $key, 'id' => $item];
            })->mapWithKeys(function($item) {
                return [['key' => $item['id'], 'rang' => $item['key']]];
            })->keyBy('key')->map(function ($item, $key) {
                unset($item['key']);
                return $item;
            })->toArray();
            
            $sondage = $this->find($id);

            if( ! $sondage )
            {
                return false;
            }

            $sondage->avis()->sync($sorting);

            return true;
        }
    }


    public function delete($id)
    {
        $sondage = $this->sondage->find($id);

        return $sondage->delete();
    }
}
