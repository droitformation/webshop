<?php namespace App\Droit\Abo\Repo;

use App\Droit\Abo\Repo\AboInterface;
use App\Droit\Abo\Entities\Abo as M;

class AboEloquent implements AboInterface{

    protected $abo;

    public function __construct(M $abo)
    {
        $this->abo = $abo;
    }

    public function getAll(){

        return $this->abo->with(['products'])->get();
    }

    public function find($id){

        return $this->abo->with(['abonnements','resilie','products'])->find($id);
    }

    public function findAboByProduct($id)
    {
        $abos = $this->abo->whereHas('products', function ($query) use ($id)
        {
            $query->where('product_id', $id);
        })->get();

        return !$abos->isEmpty() ? $abos->first() : null;
    }

    public function create(array $data){

        $abo = $this->abo->create(array(
            'title' => $data['title'],
            'logo'  => (isset($data['logo']) ? $data['logo']: null),
            'name'  => (isset($data['name']) ? $data['name']: null),
            'plan'  => $data['plan']
        ));

        if( ! $abo )
        {
            return false;
        }

        // products
        if(isset($data['products_id']))
        {
            $abo->products()->attach($data['products_id']);
        }

        return $abo;
    }

    public function update(array $data){

        $abo = $this->abo->findOrFail($data['id']);

        if( ! $abo )
        {
            return false;
        }

        $abo->fill($data);

        $abo->save();

        // products
        if(isset($data['products_id']))
        {
            $abo->products()->sync($data['products_id']);
        }

        return $abo;
    }

    public function delete($id){

        $abo = $this->abo->find($id);

        return $abo->delete();

    }
}
