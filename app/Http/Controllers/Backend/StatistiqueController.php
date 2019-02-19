<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Inscription\Repo\InscriptionInterface;
use App\Droit\Shop\Order\Repo\OrderInterface;

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

    public function __construct(InscriptionInterface $inscription, OrderInterface $order,CategorieInterface $categories, ColloqueInterface $colloque, ShopAuthorInterface $authors,DomainInterface $domains)
    {
        $this->inscription = $inscription;
        $this->order       = $order;

        $this->categories = $categories;
        $this->authors    = $authors;
        $this->domains    = $domains;
        $this->colloque  = $colloque;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }

    public function index(Request $request)
    {
        $results = collect([]);

        if(!empty($request->all())){

            $data = collect(request()->all())->map(function ($item, $key) {
                return  is_array($item) ? array_filter($item) : $item;
            })->filter(function($value) {
                return null !== $value;
            })->toArray();

            // [sum] => sum-price
            $aggretate = explode('-',$data['sum']);
            $worker = new \App\Droit\Statistique\StatistiqueWorker();

            $results = $worker->setFilters($data['sort'])->setSort($data['period'])
                ->setAggregate(['model' => $data['model'], 'name' => $aggretate[0], 'type' => $aggretate[1]]) // product or price or title (title,count)
                ->makeQuery($data['model'])
                ->aggregate();

        }

        return view('backend.stats.index')->with(['results' => $results]);
        // ->with(['inscriptions' => $inscriptions, 'orders' => $orders, 'colloques' => $colloques])
    }

    public function models(Request $request)
    {
        $prepared = [];
        $filters = [
            'order' => ['authors','domains','categories'],
            'inscription' => ['colloque'],
            'abo' => ['products'],
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
