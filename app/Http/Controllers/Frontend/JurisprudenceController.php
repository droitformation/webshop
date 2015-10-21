<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Arret\Repo\ArretInterface;
use App\Droit\Arret\Worker\JurisprudenceWorker;
use App\Droit\Categorie\Repo\CategorieInterface;
use App\Droit\Newsletter\Repo\NewsletterInterface;

class JurisprudenceController extends Controller
{
    protected $arret;
    protected $categorie;
    protected $jurisprudence;
    protected $newsletter;

    public function __construct(ArretInterface $arret, CategorieInterface $categorie, JurisprudenceWorker $jurisprudence, NewsletterInterface $newsletter)
    {
        $this->arret         = $arret;
        $this->categorie     = $categorie;
        $this->jurisprudence = $jurisprudence;
        $this->newsletter    = $newsletter;

        $newsletters = $this->newsletter->getAll();
        view()->share('newsletters', $newsletters);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $arrets     = $this->jurisprudence->preparedArrets();
        $analyses   = $this->jurisprudence->preparedAnalyses();
        $annees     = $this->jurisprudence->preparedAnnees();

        $categories =  $this->categorie->getAll();

        return view('frontend.jurisprudence')->with(array('arrets' => $arrets, 'analyses' => $analyses, 'annees' => $annees, 'categories' => $categories ));
    }

}
