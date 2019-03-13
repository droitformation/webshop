<?php namespace App\Droit\Statistique\Entites;

use App\Droit\Statistique\Entites\Chart;
use Illuminate\Support\Collection;

class AboChangeChart
{
    use Chart;

    protected $results;
    protected $model;
    protected $abo_id;
    protected $type;

    public $sets;

    public function __construct($results, $type =  'chart')
    {
        $this->results = $results;
        $this->type  = $type;
        $this->model = new \App\Droit\Abo\Entities\Abo_users();
    }

    public function setAbo($abo)
    {
        $this->abo_id = $abo;

        return $this;
    }

    public function prepare()
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

    public function eachPeriod($results)
    {
        $years = $results->groupBy(function ($item, $key) {
            return $item->created_at->format('Y');
        })->map(function ($collection, $year) {
            return $collection->mapToGroups(function ($item, $key) {
                $status = $item->deleted_at ? 'deleted' : 'created';
                return [$status => $item];
            })->map(function ($collection, $year) {
                return $collection->count();
            });
        })->sortKeys();

        $this->sets = $years->keys()->reduce(function ($data, $year) use ($years) {
            $data['years'][] = $year;

            $items = $this->getByYears($data['years']);
            $count = $years[$year];

            $data[$year]['count']   = $items->count();
            $data[$year]['created'] = $count->get('created');
            $data[$year]['deleted'] = $count->get('deleted') ? $count->get('deleted') : 0;

            return $data;
        }, []);

        return $this;
    }

    public function chartData()
    {
        $data['labels']   = $years->keys()->all(); // years 1 loop
        $data['datasets'] = collect($sets)->transpose()->map(function ($item, $key) {
            $keys = [0 => 'Total', 1 => 'Crées', 2 => 'Supprimées'];
            $key  = isset($keys[$key]) ? $keys[$key] : 0;

            return $this->set($item,$key);
        })->all();
    }

    public function getByYears($current)
    {
        return $this->model->where('abo_id','=',$this->abo_id)->whereIn(\DB::raw("year(created_at)"), $current)->withTrashed()->get();
    }

}