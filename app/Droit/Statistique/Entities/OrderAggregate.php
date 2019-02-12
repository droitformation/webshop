<?php namespace App\Droit\Statistique\Entites;

class OrderAggregate
{
    protected $results;

    public function __construct($results)
    {
        $this->results = $results;
    }

    public function sum($type = null)
    {
        if(!$type || ( $type && $type == 'product')){

            return $this->results->map(function ($item, $key) {
                return $item->products->count();
            })->sum();
        }

        if($type == 'price'){
            return $this->results->map(function ($item, $key) {
                if($item instanceof \App\Droit\Shop\Order\Entities\Order){
                    return $item->total_sum;
                }
                if($item instanceof \App\Droit\Inscription\Entities\Inscription){
                    return $item->price_cents;
                }
            })->sum();
        }

        if($type == 'title'){
            return $this->results->pluck('products')->flatten()->groupBy(function ($product, $key) {
                return $product->id;
            })->map(function ($item, $key) {
                return $item->pluck('title');
            })->map(function ($item, $key) {
                return ['count' => $item->count(), 'title' => $item->first()];
            });
        }

        // type of price, full or free
    }

    public function count()
    {
        return $this->results->count();
    }
}