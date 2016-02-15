<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Site\Repo\SiteInterface;
use App\Droit\Service\UploadInterface;
use App\Droit\Inscription\Repo\InscriptionInterface;
use App\Droit\Shop\Order\Repo\OrderInterface;

use App\Droit\Arret\Repo\ArretInterface;
use App\Droit\Categorie\Repo\CategorieInterface;
use App\Droit\Analyse\Repo\AnalyseInterface;

class SiteController extends Controller
{
    protected $upload;
    protected $site;
    protected $inscription;
    protected $order;
    protected $arret;
    protected $categorie;
    protected $analyse;

    public function __construct(
        UploadInterface $upload,
        SiteInterface  $site,
        InscriptionInterface $inscription,
        OrderInterface $order,
        ArretInterface $arret,
        CategorieInterface $categorie,
        AnalyseInterface $analyse
    )
    {
        $this->upload      = $upload;
        $this->site        = $site;
        $this->inscription = $inscription;
        $this->order       = $order;
        $this->arret       = $arret;
        $this->categorie   = $categorie;
        $this->analyse     = $analyse;
    }

    /**
     * @return Response
     */
    public function show($id)
    {
        $site         = $this->site->find($id);
        $inscriptions = $this->inscription->getAll(5);
        $orders       = $this->order->getLast(5);
        $arrets       = $this->arret->getLast(5,$id);
        $analyses     = $this->analyse->getLast(5,$id);

        return view('backend.sites.show')->with(['site' => $site, 'inscriptions' => $inscriptions, 'orders' => $orders, 'arrets' => $arrets, 'analyses' => $analyses]);
    }
}
