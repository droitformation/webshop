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
            // multiple charts for years


            $data = $this->results->mapWithKeys(function ($first, $year) {
                return [
                    $year => [
                        'year'   => $year,
                        'data'   => $first['results']->all(),
                    ]
                ];
            });

            /*->reduce(function ($carry, $item) {
                $data['datasets']   = $carry['datasets'];
                $data['labels']     = array_values(array_unique(array_merge($item['labels'],$carry['labels'])));
                $data['datasets'][] = $this->set($item['data'],$item['year']);

                return $data;

            }, ['labels' => [], 'datasets' => []])*/

            echo '<pre>';
            print_r($data);
            echo '</pre>';
            exit();

            if($this->isGrouped){

                $nbr = groupedPeriod($this->isGrouped);

                $data['labels'] = fillMissing(1,$nbr, array_combine($data['labels'], $data['labels']));
                $data['labels'] = collect($data['labels'])->mapWithKeys(function ($item, $key) {
                    return [$key => span_to_name($key,$this->isGrouped)];
                })->values()->all();

                $data['datasets'] = collect($data['datasets'])->map(function ($set, $key) use ($nbr) {
                    $set['data'] = array_values(fillMissing(1,$nbr, $set['data']));
                    return $set;
                })->all();
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