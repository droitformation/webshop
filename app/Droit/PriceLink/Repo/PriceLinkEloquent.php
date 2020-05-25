<?php namespace App\Droit\PriceLink\Repo;

use App\Droit\PriceLink\Repo\PriceLinkInterface;
use App\Droit\PriceLink\Entities\PriceLink as M;

class PriceLinkEloquent implements PriceLinkInterface{

    protected $price;

    public function __construct(M $price)
    {
        $this->price = $price;
    }

    public function getAll(){

        return $this->price->all();
    }

    public function find($id){

        return $this->price->find($id);
    }

    public function create(array $data){

        $price = $this->price->create(array(
            'price'       => $data['price'] * 100,
            'type'        => $data['type'],
            'description' => $data['description'] ?? '',
            'rang'        => $data['rang'] ?? 1,
            'remarque'    => isset($data['remarque']) ? $data['remarque'] : null,
        ));

        if( ! $price ) {
            return false;
        }

        if(isset($data['colloques'])) {
            $price->colloques()->attach($data['colloques']);
        }

        return $price;
    }

    public function update(array $data){

        $price = $this->price->findOrFail($data['id']);

        if( ! $price ) {
            return false;
        }

        $price->fill($data);

        if(isset($data['price'])) {
            $price->price = $data['price'] * 100;
        }

        if(isset($data['colloques'])) {
            $price->colloques()->sync($data['colloques']);
        }

        $price->save();

        return $price;
    }

    public function delete($id){

        $price = $this->price->find($id);

        return $price->delete();
    }
}
