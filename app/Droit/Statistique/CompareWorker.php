<?php
/**
 * Created by PhpStorm.
 * User: cindyleschaud
 * Date: 2019-04-24
 * Time: 13:42
 */

namespace App\Droit\Statistique;


class CompareWorker
{
    protected $order;
    protected $inscription;
    protected $abonnement;

    public $intervalle;
    public $model;
    public $results;
    public $isGrouped;

    public function __construct()
    {
        $this->inscription = new \App\Droit\Inscription\Entities\Inscription();
        $this->order       = new \App\Droit\Shop\Order\Entities\Order();
        $this->abonnement  = new \App\Droit\Abo\Entities\Abo_users();
    }

    /*
     * intervalle [dates] => 04/04/2019 - 04/28/2019
     * */
    public function setIntervalle($intervalles)
    {
        $periode = explode('-',$intervalles);

        $intervalle['start'] = \Carbon\Carbon::createFromFormat('d/m/Y',trim($periode[0]))->toDateString();
        $intervalle['end'] = \Carbon\Carbon::createFromFormat('d/m/Y',trim($periode[1]))->toDateString();

        $this->intervalle = $intervalle;

        return $this;
    }

    public function makeQuery($model)
    {
        $this->model = $model;

        $q = $this->$model;

        if($this->intervalle && !empty($this->intervalle)){
            $q = $q->period($this->intervalle);
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
}