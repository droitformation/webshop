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

        return $this->abo->with(['products','abonnements','abonnements.realuser','abonnements.realuser.primary_adresse','abonnements.tiers_user'])->get();
    }

    public function getAllFrontend()
    {
        return $this->abo->with(['products'])->whereHas('products', function($query){
            $query->whereHas('attributs', function($q){
                $q->where('title', '=', 'Référence');
            });
            $query->whereHas('attributs', function($q){
                $q->where('title', '=', 'Édition');
            });

            $query->where('hidden','=', 0);
        })->get();
    }

    public function find($id){
        return $this->abo->with(['abonnements','abonnements.user','abonnements.realuser.primary_adresse','abonnements.tiers_user.primary_adresse'])->find($id);
    }

    public function findAboByProduct($id)
    {
        $abos = $this->abo->with(['abonnements','products'])->whereHas('products', function ($query) use ($id)
        {
            $query->where('product_id', $id);
        })->get();

        return !$abos->isEmpty() ? $abos->first() : null;
    }

    public function create(array $data){

        $abo = $this->abo->create(array(
            'title'    => $data['title'],
            'logo'     => (isset($data['logo']) ? $data['logo']: null),
            'name'     => (isset($data['name']) ? $data['name']: null),
            'compte'   => (isset($data['compte']) ? $data['compte']: null),
            'adresse'  => (isset($data['adresse']) ? $data['adresse']: null),
            'remarque'  => (isset($data['remarque']) ? $data['remarque']: null),
            'price'    => $data['price'] * 100,
            'shipping' => isset($data['shipping']) ? $data['shipping'] * 100 : null,
            'plan'     => $data['plan']
        ));

        if( ! $abo )
        {
            return false;
        }

        // products
        if(isset($data['products_id'])) {
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

        if(isset($data['price']))
        {
            $abo->price = $data['price'] * 100;
        }

        if(isset($data['shipping']))
        {
            $abo->shipping = $data['shipping'] * 100;
        }

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
