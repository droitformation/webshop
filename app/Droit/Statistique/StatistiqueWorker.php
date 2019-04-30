<?php namespace App\Droit\Statistique;

use App\Droit\Statistique\Entites\OrderAggregate;
use Illuminate\Support\Collection;

class StatistiqueWorker
{
    protected $order;
    protected $inscription;
    protected $abonnement;

    protected $model;

    public $filters = [];
    public $period = [];
    public $aggregate = [];

    public $results = null;
    public $isGrouped = null;
    public $isNested = null;

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

    public function spanYears()
    {
        $start = \Carbon\Carbon::parse($this->period['start'])->year;
        $end   = \Carbon\Carbon::parse($this->period['end'])->year;

        return $start == $end ? false : true;
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
        $this->model = $model;

        $q = $this->$model;

        if($this->period && !empty($this->period)){
            $q = $q->period($this->period);
        }

        if(!empty($this->filters)){
            foreach($this->filters as $relation => $values){
                $relation = $relation == 'abo' ? 'main' : $relation;
                $q = $q->$relation($values);
            }
        }

        $this->results = $q->get();

        return $this;
    }

    public function group($when)
    {
        if($when){
            $this->isGrouped = $when;

            $year  = function ($item) {return $item->created_at->format('Y');};
            $month = function ($item) {return $item->created_at->format('m');};
            $week  = function ($item) {return $item->created_at->format('W');};
            $day   = function ($item) {return $item->created_at->format('md');};

            $grouping = ['month' => [$year,$month], 'year' => [$year], 'day' => [$year,$day], 'week' => [$year,$week]];
            $group    = isset($grouping[$when]) ? $grouping[$when] : [$year,$month];

            $this->results = $this->results->groupBy($group, $preserveKeys = true);
        }

        return $this;
    }

    public function doAggregate()
    {
        if($this->aggregate){

            if($this->results->isEmpty()) return collect([]);

            if($this->model == 'abonnement' && $this->aggregate['type'] == 'change'){
                return $this->results;
            }

            // by price => orders, by products, by title, sum
            if(!$this->isGrouped){ return $this->aggregateCollection($this->results); }

            // first collection is year wee keep it
            return $this->results->map(function ($collection, $year) {

                if($collection->first() instanceof Collection) { // there is a other depth
                    $this->isNested = true;
                    return $collection->map(function ($coll, $year) {
                        return $this->makeAggregate($coll);
                    });
                }

                return $this->makeAggregate($collection);
            });

        }

        return $this->results;
    }

    public function isSumProduct()
    {
        return ($this->aggregate['model'] == 'order' && $this->aggregate['name'] == 'sum' && $this->aggregate['type'] == 'product') ? true : false;
    }

    public function makeAggregate($collection)
    {
        return [
            //'collection' => $this->isSumProduct() ? (new OrderAggregate($collection))->titles() : $collection,
            'results' => $this->aggregateCollection($collection),
        ];
    }

    public function aggregateCollection($collection)
    {
        $aggregate = new OrderAggregate($collection);

        $func = $this->aggregate['name'];
        $type = $this->aggregate['type'];

        return $aggregate->$func($type);
    }

    public function chart($results)
    {
        if($this->model == 'abonnement' && $this->aggregate['type'] == 'change'){
            $chart = new \App\Droit\Statistique\Entites\AboChangeChart($results);

            return $chart->setAbo($this->filters['abo'])->chart();
        }

        if($this->model == 'abonnement' && $this->aggregate['type'] == 'status'){
            $chart = new \App\Droit\Statistique\Entites\AboStatusChart($results);

            return $chart->chart();
        }

        if($this->model == 'order' || $this->model == 'inscription'){
            $chart = new \App\Droit\Statistique\Entites\OrderChart($results,$this->isNested,$this->isGrouped);

            return $chart->chart();
        }
    }
}