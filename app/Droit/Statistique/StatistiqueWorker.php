<?php namespace App\Droit\Statistique;

use App\Droit\Statistique\Entites\OrderAggregate;
use Illuminate\Support\Collection;

class StatistiqueWorker
{
    protected $order;
    protected $inscription;
    protected $abonnement;

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
        $this->isGrouped = $when;

        $year = function ($item) {
            return $item->created_at->format('Y');
        };

        $month = function ($item) {
            return $item->created_at->format('m');
        };

        $week = function ($item) {
            return $item->created_at->format('W');
        };

        $day = function ($item) {
            return $item->created_at->format('m-d');
        };

        $grouping = ['month' => [$year,$month], 'year' => [$year], 'day' => [$year,$day], 'week' => [$year,$week]];
        $group    = isset($grouping[$when]) ? $grouping[$when] : [$year,$month];

        $this->results = $this->results->groupBy($group, $preserveKeys = true);

        return $this;
    }

    public function aggregate()
    {
        if($this->aggregate){

            if($this->results->isEmpty()) return collect([]);

            // by price => orders, by products, by title, sum
            if($this->isGrouped){
                // first collection is year wee keep it
                return $this->results->map(function ($collection, $year) {

                    // there is a other depth
                    if($collection->first() instanceof Collection) {
                        $this->isNested = true;
                        return $collection->map(function ($coll, $year) {
                            return $this->makeAggregate($coll);
                        });
                    }

                    return $this->makeAggregate($collection);
                });
            }

            return $this->aggregateCollection($this->results);
        }

        return $this->results;
    }

    public function chart($results,$search = null)
    {
        if($this->isNested){
            // multiple charts for years
            $data = $results->mapWithKeys(function ($first, $year) use ($search) {
                return [
                    $year => [
                        'year'   => $year,
                        'labels' => $first->keys()->all(),
                        'data'   => $first->map(function ($item,$key) {
                            return $item['results'];
                        })->all(),
                    ]
                ];
            })->reduce(function ($carry, $item) {
                $data['datasets']   = $carry['datasets'];
                $data['labels']     = array_values(array_unique(array_merge($item['labels'],$carry['labels'])));
                $data['datasets'][] = $this->set($item['data'],$item['year']);

                return $data;

            }, ['labels' => [], 'datasets' => []]);

            if($this->isGrouped){

                $nbr = $this->isGrouped == 'week' ? 52 : 12;

                $data['labels'] = fillMissing(1,$nbr, array_combine($data['labels'], $data['labels']));
                $data['labels'] = collect($data['labels'])->mapWithKeys(function ($item, $key) {
                    return [$key => span_to_name($key,$this->isGrouped)];
                })->all();
            }

            return $data;
        }

        $data['labels'] = $results->keys()->mapWithKeys(function ($item, $key) {
            return [(string) $item => span_to_name($item,$this->isGrouped)];
        });

        $data['datasets'][] = $this->set($results->pluck('results')->all(),'Somme');

        return $data;
/*
        if(is_int($first['results'])){
            return [$year => $first['results']];
        }*/
    }

    public function set($data,$label = null)
    {
        $color = rand(0,255).', '.rand(0,255).', '.rand(0,255);
        //$data = array_replace(array_fill_keys(range(0, 11), 0), $data);

        return [
            'label' => isset($label) ? $label : '',
            'data'  => array_map('intval', $data),
            'backgroundColor' => 'rgba('.$color.', 0.2)',
            'borderColor' => 'rgba('.$color.',1)',
            'borderWidth' => 1
        ];
    }

    public function isSumProduct()
    {
        return ($this->aggregate['model'] == 'order' && $this->aggregate['name'] == 'sum' && $this->aggregate['type'] == 'product') ? true : false;
    }

    public function makeAggregate($collection)
    {
        return [
            //'collection' => $this->isSumProduct() ? (new OrderAggregate($collection))->titles() : $collection,
            'results'    => $this->aggregateCollection($collection),
        ];
    }

    public function aggregateCollection($collection)
    {
        $aggregate = new OrderAggregate($collection);

        $func = $this->aggregate['name'];
        $type = $this->aggregate['type'];

        return $aggregate->$func($type);
    }
}