<?php
namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CategorieRequest;

use App\Droit\Categorie\Repo\CategorieInterface;
use App\Droit\Service\UploadInterface;

class CategorieController extends Controller {

    protected $categorie;
    protected $upload;

    public function __construct( CategorieInterface $categorie, UploadInterface $upload)
    {
        $this->categorie = $categorie;
        $this->upload    = $upload;
    }
	
    public function index(Request $request)
    {
        $categories = $this->categorie->getAll($request->input('site'));

		return ['categories' => $categories];
    }
	
    public function arrets(Request $request)
    {

        $categories = $this->categorie->allForSite($request->input('site'), $request->input('selected',null));

        $arrets = $categories->map(function ($categorie, $key) {
            return $categorie->arrets->map(function ($arret, $key) {
                return [
                    'id'         => $arret->id,
                    'title'      => $arret->reference.' '.$arret->pub_date->formatLocalized('%d %B %Y'),
                    'reference'  => $arret->reference,
                    'abstract'   => $arret->abstract,
                    'pub_text'   => $arret->pub_text,
                    'document'   => $arret->document ? asset('files/arrets/'.$arret->file) : null,
                    'categories' => !$arret->categories->isEmpty() ? $arret->categories : null,
                ];
            });
        })->flatten(1);

        return ['arrets' => $arrets];
    }

}