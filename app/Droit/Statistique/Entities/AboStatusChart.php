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
        if($this->isNested){

            $data = $this->results->mapWithKeys(function ($first, $year) {

                $datasets = $first['results']->map(function ($item, $key) use ($year) {
                    return ['label' => $key, 'data' => $item->count()];
                });

                $labels[] = $year;

                return [
                    $year => ['datasets' => $datasets->values()]
                ];

            })/*->reduce(function ($carry, $item) {
                $data['datasets']   = $carry['datasets'];
                $data['labels']     = array_values(array_unique(array_merge($item['labels'],$carry['labels'])));
                $data['datasets'][] = $this->set($item['data'],$item['label']);

                return $data;

            }, ['labels' => [], 'datasets' => []])*/;

            if($this->isGrouped){

                $nbr = groupedPeriod($this->isGrouped);

                $set['labels']   = $data->keys()->all();
                $set['datasets'] = $data->flatten(1)->map(function ($set, $year) {
                    return $set->map(function ($row, $year) {
                        return $this->set($row['data'],$row['label']);
                    });
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