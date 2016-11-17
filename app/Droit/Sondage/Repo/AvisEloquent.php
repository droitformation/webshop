<?php namespace App\Droit\Sondage\Repo;

use App\Droit\Sondage\Repo\AvisInterface;
use App\Droit\Sondage\Entities\Avis as M;

class AvisEloquent implements AvisInterface{

    protected $avis;

    public function __construct(M $avis)
    {
        $this->avis = $avis;
    }

    public function getAll()
    {
        return $this->avis->all();
    }

    public function find($id)
    {
        return $this->avis->find($id);
    }

    public function create(array $data)
    {
        $avis = $this->avis->create(array(
            'type'     => $data['type'],
            'question' => $data['question'],
            'choices'  => isset($data['choices']) ? $data['choices'] : null
        ));

        if(!$avis)
        {
            return false;
        }

        return $avis;
    }

    public function update(array $data)
    {
        $avis = $this->avis->findOrFail($data['id']);

        if(!$avis)
        {
            return false;
        }

        $avis->fill($data);
        $avis->save();

        return $avis;
    }

    public function delete($id)
    {
        $avis = $this->avis->find($id);

        return $avis->delete();
    }
}
