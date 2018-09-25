<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Arret\Repo\ArretInterface;
use App\Droit\Categorie\Repo\CategorieInterface;
use App\Droit\Shop\Product\Repo\ProductInterface;
use App\Droit\Colloque\Repo\ColloqueInterface;

class NewsletterModelController extends Controller {

    protected $arret;
    protected $categorie;
    protected $product;
    protected $colloque;

    public function __construct( ArretInterface $arret, CategorieInterface $categorie, ProductInterface $product, ColloqueInterface $colloque)
    {
        $this->arret   = $arret;
        $this->categorie = $categorie;
        $this->product = $product;
        $this->colloque = $colloque;
    }

    public function index($model,$site_id = null)
    {
        $site_id = $model == 'product' || $model == 'colloque' ? null : $site_id;

        $models = $this->$model->getAll($site_id);
        $models = $models->map(function ($item, $key) {
            return [
                'id' => $item->id,
                'title' => $item->title
            ];
        });

        return response()->json( $models , 200 );
    }

    public function show($model,$id)
    {
        $single = $this->$model->find($id);

        $convert = new \App\Droit\Newsletter\Entities\ContentModel();
        return $convert->$model($single);

        return response()->json( $convert , 200 );
    }

    public function arrets($id){

        $categorie = $this->categorie->find($id);
        $references = $categorie->arrets->map(function ($item, $key) {
            return [
                'id' => $item->id,
                'reference' => $item->reference
            ];
        });

        return response()->json( $references, 200 );
    }
}