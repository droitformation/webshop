<?php namespace App\Hub\Tax\Repo;

use App\Hub\Shop\Tax\Repo\TaxInterface;
use App\Hub\Shop\Tax\Entities as M;

class TaxEloquent implements TaxInterface{

    protected $tax;

    public function __construct(M $tax)
    {
        $this->tax = $tax;
    }

    public function getAll(){

        return $this->tax->all();
    }

    public function find($id){

        return $this->tax->find($id);
    }

    public function create(array $data){

        $tax = $this->tax->create(array(
            'title' => $data['title'],
            'value' => $data['value'],
            'price' => $data['price'],
            'type' => $data['type']
        ));

        if( ! $tax )
        {
            return false;
        }

        return $tax;

    }

    public function update(array $data){

        $tax = $this->tax->findOrFail($data['id']);

        if( ! $tax )
        {
            return false;
        }

        $tax->fill($data);

        $tax->save();

        return $tax;
    }

    public function delete($id){

        $tax = $this->tax->find($id);

        return $tax->delete();

    }

}
