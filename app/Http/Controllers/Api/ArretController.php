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
                'display'    => $request->input('display')
            ]
        );
        
        $arrets = $results->map(function ($arret, $key) {

            if(!$arret->analyses->isEmpty()) {
                $analyses = $arret->analyses->map(function ($analyse, $key) {
                    return [
                        'id'         => $analyse->id,
                        'date'       => $analyse->pub_date->formatLocalized('%d %B %Y'),
                        'auteurs'    => $analyse->authors->implode('name', ', '),
                        'abstract'   => $analyse->abstract,
                        'document'   => $analyse->document ? asset('files/analyses/'.$analyse->file) : null,
                    ];
                });
            }
            
            return [
                'id'         => $arret->id,
                'title'      => $arret->reference.' '.$arret->pub_date->formatLocalized('%d %B %Y'),
                'reference'  => $arret->reference,
                'abstract'   => $arret->abstract,
                'pub_text'   => $arret->pub_text,
                'document'   => $arret->document ? asset('files/arrets/'.$arret->file) : null,
                'categories' => !$arret->categories->isEmpty() ? $arret->categories : null,
                'analyses' => isset($analyses) ? $analyses : null,
            ];
        });

       /* $analyses = $this->analyse->allForSite(
            $request->input('site'),
            $request->input('years',null)
        );

        $analyses = $analyses->map(function ($analyse, $key) {

            if(!$analyse->arrets->isEmpty()) {
                $references = $analyse->arrets->map(function ($item, $key) {
                    return [
                        'id'         => $item->id,
                        'title'      => $item->reference.' '.$item->pub_date->formatLocalized('%d %B %Y'),
                        'reference'  => $item->reference,
                        'abstract'   => $item->abstract,
                        'pub_text'   => $item->pub_text,
                        'document'   => $item->document ? asset('files/arrets/'.$item->file) : null,
                    ];
                });
            }

            return [
                'id'         => $analyse->id,
                'date'       => $analyse->pub_date->formatLocalized('%d %B %Y'),
                'references' => isset($references) && !$references->isEmpty() ? $references : null,
                'auteurs'    => $analyse->authors->implode('name', ', '),
                'abstract'   => $analyse->abstract,
                'document'   => $analyse->document ? asset('files/analyses/'.$analyse->file) : null,
            ];
        });*/

        return [
            'arrets'   => $arrets,
            //'analyses' => $analyses,
            'pagination' => [
                'total'    => $results->total(),
                'per_page' => $results->perPage(),
                'current_page' => $results->currentPage(),
                'last_page'    => $results->lastPage(),
                'from' => $results->firstItem(),
                'to'   => $results->lastItem()
            ],
        ];
    }
    
}