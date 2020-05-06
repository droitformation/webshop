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

        return $this->rabais->with(['colloques'])->get();
    }

    public function notUsed($user_id){

        return $this->rabais->whereHas('colloques', function ($query) use ($id) {
            $query->where('colloque_id', '=', $id);
        })->get();
    }

    public function byColloque($id){
        return $this->rabais->whereHas('colloques', function ($query) use ($id) {
            $query->where('colloque_id', '=', $id);
        })->orDoesntHave('colloques')->get();
    }

    public function find($id){

        return $this->rabais->with(['colloques'])->find($id);
    }

    public function search($term){

        return $this->rabais->where('title','LIKE',$term)->first();
    }

    public function create(array $data){

        $rabais = $this->rabais->create(array(
            'value'     => $data['value'],
            'title'     => $data['title'],
            'type'      => $data['type'],
            'expire_at' => $data['expire_at'] ?? null,
        ));

        if( ! $rabais ) {
            return false;
        }

        if(isset($data['colloque_id'])){
            $rabais->colloques()->attach($data['colloque_id']);
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

        $rabais->colloques()->detach();

        if(isset($data['colloque_id'])){
            $rabais->colloques()->attach($data['colloque_id']);
        }

        return $rabais;
    }

    public function delete($id){

        $rabais = $this->rabais->find($id);

        return $rabais->delete();
    }
}
