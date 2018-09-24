<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Arret\Repo\ArretInterface;
use App\Droit\Analyse\Repo\AnalyseInterface;

class ArretController extends Controller {

    protected $arret;
    protected $analyse;

    public function __construct( ArretInterface $arret, AnalyseInterface $analyse)
    {
        $this->arret   = $arret;
        $this->analyse     = $analyse;

        setlocale(LC_ALL, 'fr_FR');
    }

    public function index(Request $request)
    {
        $results = $this->arret->allForSite(
            $request->input('site'), [
                'categories' => $request->input('categories',[]),
                'years'      => $request->input('years',[]),
                'display'    => $request->input('display') > 0 ? true : null
            ]
        );

        $arrets = $results->map(function ($arret, $key) {

            if(!$arret->analyses->isEmpty()) {
                $analyses = $arret->analyses->map(function ($analyse, $key) {
                    return [
                        'id'         => $analyse->id,
                        'date'       => utf8_encode($analyse->pub_date->formatLocalized('%d %B %Y')),
                        'auteurs'    => $analyse->authors->implode('name', '; '),
                        'abstract'   => $analyse->abstract,
                        'document'   => $analyse->document ? secure_asset('files/analyses/'.$analyse->file) : null,
                    ];
                });
            }

            return [
                'id'         => $arret->id,
                'title'      => $arret->reference.', '.utf8_encode($arret->pub_date->formatLocalized('%d %B %Y')),
                'reference'  => $arret->reference,
                'abstract'   => $arret->abstract,
                'pub_text'   => $arret->pub_text,
                'document'   => $arret->document ? secure_asset('files/arrets/'.$arret->file) : null,
                'categories' => !$arret->categories->isEmpty() ? $arret->categories : null,
                'analyses'   => isset($analyses) ? $analyses : null,
            ];
        });


        return response()->json([
            'arrets'   => $arrets,
        ]);

    }

    /**
     * Return response arrets
     *
     * @return response
     */
    public function arrets($site = null)
    {
        $arrets = $this->arret->getAll($site);
        $arrets = $arrets->pluck('title','id');

        return response()->json( $arrets , 200 );
    }
}