<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Inscription\Repo\InscriptionInterface;
use App\Droit\Shop\Order\Repo\OrderInterface;

use App\Droit\Abo\Repo\AboInterface;
use App\Droit\Shop\Categorie\Repo\CategorieInterface;
use App\Droit\Colloque\Repo\ColloqueInterface;
use App\Droit\Shop\Author\Repo\AuthorInterface as ShopAuthorInterface;
use App\Droit\Domain\Repo\DomainInterface;

class StatistiqueController extends Controller
{
    protected $inscription;
    protected $order;

    protected $categories;
    protected $authors;
    protected $domains;
    protected $colloque;
    protected $abo;

    public function __construct(
        InscriptionInterface $inscription,
        OrderInterface $order,
        CategorieInterface $categories,
        ColloqueInterface $colloque,
        ShopAuthorInterface $authors,
        DomainInterface $domains,
        AboInterface $abo
    )
    {
        $this->inscription = $inscription;
        $this->order       = $order;

        $this->categories = $categories;
        $this->authors    = $authors;
        $this->domains    = $domains;
        $this->colloque  = $colloque;
        $this->abo  = $abo;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }

    public function index(Request $request)
    {
        $results = collect([]);

        if(!empty($request->all())){

            $worker = new \App\Droit\Statistique\StatistiqueWorker();

            $aggretate = explode('-',$request->input('sum')); // [sum] => sum-price

            $results = $worker->setFilters($request->input('filters',[]))
                ->setPeriod($request->input('period',[]))
                ->setAggregate(['model' => $request->input('model'), 'name' => $aggretate[0], 'type' => $aggretate[1]]) // product or price or title (title,count)
                ->makeQuery($request->input('model'))
                ->group($request->input('group',null))
                ->doAggregate();

            $datapoints = $worker->chart($results);

          /*  echo '<pre>';
            print_r(collect($datapoints['datasets'])->pluck('label'));
            echo '</pre>';
            exit();*/
        }

        return view('backend.stats.index')->with([
            'results' => $results,
            'search'  => $request->except('_token'),
            'datapoints' => isset($datapoints) ? $datapoints : []
        ]);
    }

    public function models(Request $request)
    {
        $prepared = [];
        $filters = [
            'order' => ['authors','domains','categories'],
            'inscription' => ['colloque'],
            'abonnement' => ['abo'],
        ];

        $models = $filters[$request->input('model')];

        foreach ($models as $filter){
            $models = $this->$filter->getAll();

            $prepared[$filter] = $models->map(function ($item, $key) {
                return ['id' => $item->id, 'title' => $item->title];
            });
        }

        return response()->json($prepared);
    }
}
