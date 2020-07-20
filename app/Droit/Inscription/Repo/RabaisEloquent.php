<?php namespace App\Droit\Inscription\Repo;

use App\Droit\Inscription\Repo\RabaisInterface;
use App\Droit\Inscription\Entities\Rabais as M;

class RabaisEloquent implements RabaisInterface{

    protected $rabais;

    public function __construct(M $rabais)
    {
        $this->rabais = $rabais;
    }

    public function getAll(){

        return $this->rabais->with(['comptes'])->get();
    }

    public function notUsed($id){

        return $this->rabais->whereHas('comptes', function ($query) use ($id) {
            $query->where('compte_id', '=', $id);
        })->get();
    }

    public function byCompte($id){
        return $this->rabais->whereHas('comptes', function ($query) use ($id) {
            $query->where('compte_id', '=', $id);
        })->orDoesntHave('comptes')->get();
    }

    public function find($id){

        return $this->rabais->with(['comptes'])->find($id);
    }

    public function search($term){

        return $this->rabais->where('title','LIKE', $term.'%')->first();
    }

    public function create(array $data){

        $rabais = $this->rabais->create(array(
            'value'     => $data['value'],
            'title'     => $data['title'],
            'type'      => $data['type'],
            'description' => $data['description'] ?? null,
            'expire_at' => $data['expire_at'] ?? null,
        ));

        if( ! $rabais ) {
            return false;
        }

        if(isset($data['compte_id'])){
            $rabais->comptes()->attach($data['compte_id']);
        }

        return $rabais;

    }

    public function update(array $data){

        $rabais = $this->rabais->findOrFail($data['id']);

        if( ! $rabais ) {
            return false;
        }

        $rabais->fill($data);
        $rabais->save();

        $rabais->comptes()->detach();

        if(isset($data['compte_id'])){
            $rabais->comptes()->attach($data['compte_id']);
        }

        return $rabais;
    }

    public function delete($id){

        $rabais = $this->rabais->find($id);

        return $rabais->delete();
    }
}
