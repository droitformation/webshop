<?php namespace App\Droit\Statistique;

use App\Droit\Statistique\Entites\OrderAggregate;

class StatistiqueWorker
{
    protected $order;
    protected $inscription;
    protected $abo;

    protected $filters = [];
    protected $sort = [];
    protected $aggregate = [];

    public $results = null;

    public function __construct()
    {
        $this->inscription = new \App\Droit\Inscription\Entities\Inscription();
        $this->order       = new \App\Droit\Shop\Order\Entities\Order();
        $this->abo         = new \App\Droit\Abo\Entities\Abo();
    }

    /*
     * Year
     * Period
     * */
    public function setFilters($filters)
    {
        $this->filters = $filters;

        return $this;
    }

    /*
     * Authors
     * Domains
     * Categories
     * */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /*
     * Sum
     * Median
     * */
    public function setAggregate($aggregate)
    {
        $this->aggregate = $aggregate;

        return $this;
    }

    public function makeQuery($model)
    {
        $q = $this->$model;

        if($this->sort){
            $q = $q->period($this->sort);
        }

        if(!empty($this->filters)){
            foreach($this->filters as $relation => $values){
                $q = $q->$relation($values);
            }
        }

        $this->results = $q->get();

        return $this;
    }

    public function aggregate()
    {
        if($this->aggregate){
            if($this->results->isEmpty()) return collect([]);

            // by price => orders, by products, by title, sum
            if($this->aggregate['model'] == 'order' || $this->aggregate['model'] == 'inscription'){
                $aggregate = new OrderAggregate($this->results);

                $func = $this->aggregate['name'];
                $type = $this->aggregate['type'];

                return $aggregate->$func($type);
            }
        }

        return $this->results;
    }
}