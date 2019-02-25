<?php namespace App\Droit\Statistique;

use App\Droit\Statistique\Entites\OrderAggregate;

class StatistiqueWorker
{
    protected $order;
    protected $inscription;
    protected $abonnement;

    protected $filters = [];
    protected $period = [];
    protected $aggregate = [];

    public $results = null;
    public $isGrouped = null;

    public function __construct()
    {
        $this->inscription = new \App\Droit\Inscription\Entities\Inscription();
        $this->order       = new \App\Droit\Shop\Order\Entities\Order();
        $this->abonnement  = new \App\Droit\Abo\Entities\Abo_users();
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
    public function setPeriod($period)
    {
        $this->period = $period;

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

        if($this->period){
            $q = $q->period($this->period);
        }

        if(!empty($this->filters)){
            foreach($this->filters as $relation => $values){
                $q = $q->$relation($values);
            }
        }

        $this->results = $q->get();

        return $this;
    }

    public function group($when)
    {
        $this->isGrouped = true;

        $this->results = $this->results->mapToGroups(function ($item, $key) use ($when) {

            $grouping = ['month' => 'Y-m', 'year' => 'Y', 'day' => 'Y-m-d', 'week' => 'W Y'];
            $group = isset($grouping[$when]) ? $grouping[$when] : 'm';

            return [$item->created_at->format($group) => $item];
        });

        return $this;
    }

    public function aggregate()
    {
        if($this->aggregate){
            if($this->results->isEmpty()) return collect([]);

            // by price => orders, by products, by title, sum
            if($this->aggregate['model'] == 'order' || $this->aggregate['model'] == 'inscription' || $this->aggregate['model'] == 'abonnement'){

                if($this->isGrouped){
                    return $this->results->map(function ($collection, $key) {

                        if($this->isSumProduct()){
                            $aggregate = new OrderAggregate($collection);
                            $results   = $aggregate->titles();
                        }
                        else{
                            $results = $collection;
                        }

                        return [
                            //'collection' => $results,
                            'results'    => $this->makeAggregate($collection),
                            'aggregate'  => $this->aggregate
                        ];
                    });
                }

                return $this->makeAggregate($this->results);
            }
        }

        return $this->results;
    }

    public function chart($results)
    {
        return $results->mapWithKeys(function ($item, $key) {
            return [$key => (int) $item['results']];
        });
    }

    public function isSumProduct()
    {
        return ($this->aggregate['model'] == 'order' && $this->aggregate['name'] == 'sum' && $this->aggregate['type'] == 'product') ? true : false;
    }

    public function makeAggregate($collection)
    {
        $aggregate = new OrderAggregate($collection);

        $func = $this->aggregate['name'];
        $type = $this->aggregate['type'];

        return $aggregate->$func($type);
    }
}