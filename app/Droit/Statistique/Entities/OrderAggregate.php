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
        if($type == 'product'){
            return $this->results->map(function ($item, $key) {
                return $item->products->count();
            })->sum();
        }

        if($type == 'price'){
            return $this->results->map(function ($item, $key) {
                if($item instanceof \App\Droit\Shop\Order\Entities\Order){
                    return $item->total_with_shipping;
                }
                if($item instanceof \App\Droit\Inscription\Entities\Inscription || $item instanceof \App\Droit\Abo\Entities\Abo_users){
                    return $item->price_cents;
                }
            })->sum();
        }

        if($type == 'status'){
            return $this->results->groupBy(function ($product, $key) {
                return $product->status;
            })->map(function ($item, $key) {
                return $item->pluck('numero');
            });
        }

        if($type == 'title'){
            return $this->titles();
        }

        return $this->results->count(); // type of price, full or free
    }

    public function count()
    {
        return $this->results->count();
    }

    public function titles()
    {
        return $this->results->pluck('products')->flatten()->groupBy(function ($product, $key) {
            return $product->id;
        })->map(function ($item, $key) {
            return $item->pluck('title');
        })->map(function ($item, $key) {
            return ['count' => $item->count(), 'title' => $item->first()];
        });
    }
}