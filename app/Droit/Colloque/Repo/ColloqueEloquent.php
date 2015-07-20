<?php namespace App\Droit\Colloque\Repo;

use App\Droit\Colloque\Repo\ColloqueInterface;
use App\Droit\Colloque\Entities\Colloque as M;

class ColloqueEloquent implements ColloqueInterface{

    protected $colloque;

    public function __construct(M $colloque)
    {
        $this->colloque = $colloque;
    }

    public function getAll(){

        return $this->colloque->with(['location','organisateur','centres','compte','prices','documents'])->get();
    }

    public function find($id){

        return $this->colloque->with(['location','organisateur','centres','compte','prices','documents','options'])->find($id);
    }

    public function create(array $data){

        $colloque = $this->colloque->create(array(
            'titre'           => $data['titre'],
            'soustitre'       => $data['soustitre'],
            'sujet'           => $data['sujet'],
            'remarques'       => $data['remarques'],
            'start_at'        => $data['start_at'],
            'end_at'          => $data['end_at'],
            'registration_at' => $data['registration_at'],
            'active_at'       => $data['active_at'],
            'organisateur_id' => $data['organisateur_id'],
            'location_id'     => $data['location_id'],
            'compte_id'       => $data['compte_id'],
            'visible'         => $data['visible'],
            'bon'             => $data['bon'],
            'facture'         => $data['facture'],
            'created_at'      => \Carbon\Carbon::now(),
            'updated_at'      => \Carbon\Carbon::now()
        ));

        if( ! $colloque )
        {
            return false;
        }

        return $colloque;

    }

    public function update(array $data){

        $colloque = $this->colloque->findOrFail($data['id']);

        if( ! $colloque )
        {
            return false;
        }

        $colloque->fill($data);

        $colloque->save();

        return $colloque;
    }

    public function delete($id){

        $colloque = $this->colloque->find($id);

        return $colloque->delete();

    }
}
