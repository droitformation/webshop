<?php namespace App\Droit\Shop\Stock\Repo;

use App\Droit\Shop\Stock\Repo\StockInterface;
use App\Droit\Shop\Stock\Entities\Stock as M;

class StockEloquent implements StockInterface{

    protected $stock;

    public function __construct(M $stock)
    {
        $this->stock = $stock;
    }

    public function getAll()
    {
        return $this->stock->with(['product'])->get();
    }

    public function find($id)
    {
        return $this->stock->with(['product'])->find($id);
    }

    public function create(array $data)
    {
        $stock = $this->stock->create(array(
            'product_id'  => $data['product_id'],
            'amount'      => $data['amount'],
            'motif'       => $data['motif'],
            'operator'    => $data['operator'],
            'created_at'  => date('Y-m-d G:i:s'),
            'updated_at'  => date('Y-m-d G:i:s')
        ));

        if( ! $stock )
        {
            return false;
        }

        return $stock;
    }

    public function update(array $data){

        $stock = $this->stock->findOrFail($data['id']);

        if( ! $stock )
        {
            return false;
        }

        $stock->fill($data);

        $stock->save();
        
        return $stock;
    }

    public function delete($id)
    {
        return $this->stock->find($id)->delete();
    }

}
