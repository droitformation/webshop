<?php namespace App\Droit\Price\Repo;

use App\Droit\Price\Repo\PriceInterface;
use App\Droit\Price\Entities\Price as M;

class PriceEloquent implements PriceInterface{

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
            'colloque_id' => $data['colloque_id'],
            'price'       => $data['price'] * 100,
            'type'        => $data['type'],
            'description' => $data['description'],
            'main'        => $data['main'] ?? null,
            'rang'        => $data['rang'] ?? 1,
            'remarque'    => isset($data['remarque']) ? $data['remarque'] : null,
            'end_at'      => isset($data['end_at']) && !empty($data['end_at']) ? $data['end_at'] : null
        ));

        if( ! $price ) {
            return false;
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

        $price->end_at = isset($data['end_at']) && !empty($data['end_at']) && $data['end_at'] != '0000-00-00' ? $data['end_at'] : null;

        $price->save();

        return $price;
    }

    public function delete($id){

        $price = $this->price->find($id);

        return $price->delete();
    }
}
