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
        if(!$type || $type == 'product'){
            return $this->results->map(function ($item, $key) {
                return $item->products->count();
            })->sum();
        }

        if($type == 'price'){
            return $this->results->map(function ($item, $key) {
                return $item->total_sum;
            })->sum();
        }
    }

    public function count()
    {
        return $this->results->count();
    }
}