<?php namespace App\Droit\Statistique\Entites;

use App\Droit\Statistique\Entites\Chart;

class AboStatusChart
{
    use Chart;

    protected $results;
    protected $isNested;
    protected $isGrouped;

    public function __construct($results, $isNested = true, $isGrouped = true)
    {
        $this->results   = $results;
        $this->isNested  = $isNested;
        $this->isGrouped = $isGrouped;
    }

    public function chart()
    {
        return $this->results->map(function ($collection, $year) {
            // there is a other depth
            if($collection instanceof Collection) {
                return $collection->map(function ($coll, $month) {
                    return $this->eachPeriod($coll);
                });
            }

            return $this->eachPeriod($collection);
        });
    }

    public function eachPeriod()
    {
        if($this->isNested){

            $data = $this->results->mapWithKeys(function ($first, $year) {

                $datasets = $first['results']->map(function ($item, $key) use ($year) {
                    return ['label' => $key, 'data' => $item->count()];
                });

                return [$year => ['datasets' => $datasets->values()]];
            });

            if($this->isGrouped){

                $set['labels']   = $data->keys()->all();
                $set['datasets'] = $data->flatten(1)->map(function ($set, $year) {
                    return $set->map(function ($row, $year) {
                        return $this->set($row['data'],$row['label']);
                    })->all();
                })->all();

                echo '<pre>';
                print_r($set);
                echo '</pre>';
                exit();
            }

            return $data;
        }

        $data['labels'] = $this->results->keys()->mapWithKeys(function ($item, $key) {
            return [(string) $item => span_to_name($item,$this->isGrouped)];
        });

        $data['datasets'][] = $this->set($this->results->pluck('results')->all(),'Somme');

        return $data;
    }

}