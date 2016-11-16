<?php namespace App\Droit\Sondage\Repo;

use App\Droit\Sondage\Repo\ReponseInterface;
use App\Droit\Sondage\Entities\Reponse as M;

class ReponseEloquent implements ReponseInterface{

    protected $reponse;

    public function __construct(M $reponse)
    {
        $this->reponse = $reponse;
    }

    public function getAll()
    {
        return $this->reponse->all();
    }

    public function find($id)
    {
        return $this->reponse->find($id);
    }

    public function create(array $data)
    {
        $reponse = $this->reponse->create(array(
            'sondage_id' => $data['sondage_id'],
            'user_id'    => $data['user_id'],
            'email'      => $data['email'],
            'sent_at'    => $data['sent_at']
        ));

        if(!$reponse)
        {
            return false;
        }

        return $reponse;
    }

    public function update(array $data)
    {
        $reponse = $this->reponse->findOrFail($data['id']);

        if(!$reponse)
        {
            return false;
        }

        $reponse->fill($data);
        $reponse->save();

        return $reponse;
    }

    public function delete($id)
    {
        $reponse = $this->reponse->find($id);

        return $reponse->delete();
    }
}
