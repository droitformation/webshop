<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Arret\Repo\ArretInterface;

class ArretController extends Controller {

    protected $arret;

    public function __construct( ArretInterface $arret)
    {
        $this->arret     = $arret;

        setlocale(LC_ALL, 'fr_FR');
    }

    public function index(Request $request)
    {
        $arrets = $this->arret->allForSite($request->input('site'), $request->input('selected',null));
        
        $arrets = $arrets->map(function ($arret, $key) {
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

        return ['arrets' => $arrets];
    }
    
}