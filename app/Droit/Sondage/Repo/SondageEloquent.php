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
            'marketing'   => isset($data['marketing']) && !empty($data['marketing']) ? 1 : null,
            'colloque_id' => isset($data['colloque_id']) && !empty($data['colloque_id']) ? $data['colloque_id'] : null,
            'title'       => isset($data['title']) && !empty($data['title']) ? $data['title'] : null,
            'description' => isset($data['description']) && !empty($data['description']) ? $data['description'] : null,
            'valid_at'    => $data['valid_at']
        ));

        if(!$sondage) {
            return false;
        }

        return $sondage;
    }

    public function update(array $data)
    {
        $sondage = $this->sondage->findOrFail($data['id']);

        if(!$sondage) {
            return false;
        }

        $sondage->marketing   = isset($data['marketing']) && !empty($data['marketing']) ? 1 : null;
        $sondage->colloque_id = isset($data['colloque_id']) && !empty($data['colloque_id']) ? $data['colloque_id'] : null;
        $sondage->title       = isset($data['title']) && !empty($data['title']) ? $data['title'] : null;
        $sondage->description = isset($data['description']) && !empty($data['description']) ? $data['description'] : null;
        $sondage->valid_at    = $data['valid_at'];

        $sondage->save();

        return $sondage;
    }

    public function updateSorting($id, array $data)
    {
        if(!empty($data))
        {
            $sondage = $this->find($id);

            if( ! $sondage ) {
                return false;
            }
            
            $sorting = $this->sorting($data);
            
            $sondage->avis()->sync($sorting);

            return true;
        }
    }

    public function sorting($data)
    {
        return collect(array_filter($data))->map(function($item,$key) {
            return ['key' => $key, 'id' => $item];
        })->mapWithKeys(function($item,$key) {
            return [$item['id'] => ['rang' => $item['key']]];
        })->toArray();
    }

    public function delete($id)
    {
        $sondage = $this->sondage->find($id);

        return $sondage->delete();
    }
}
