<?php namespace App\Droit\Sondage\Repo;

use App\Droit\Sondage\Repo\ModeleInterface;
use App\Droit\Sondage\Entities\Modele as M;

class ModeleEloquent implements ModeleInterface
{
    protected $modele;

    public function __construct(M $modele)
    {
        $this->modele = $modele;
    }

    public function getAll()
    {
        return $this->modele->all();
    }

    public function find($id)
    {
        return $this->modele->find($id);
    }

    public function create(array $data)
    {
        $modele = $this->modele->create(array(
            'title'       => $data['title'],
            'description' => $data['description'] ?? null,
        ));

        if(!$modele) {
            return false;
        }

        return $modele;
    }

    public function update(array $data)
    {
        $modele = $this->modele->findOrFail($data['id']);

        if(!$modele) {
            return false;
        }

        $modele->fill($data);
        $modele->save();

        return $modele;
    }

    public function delete($id)
    {
        $modele = $this->modele->find($id);

        return $modele->delete();
    }
}