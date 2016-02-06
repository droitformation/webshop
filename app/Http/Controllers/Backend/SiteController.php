<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Site\Repo\SiteInterface;
use App\Droit\Service\UploadInterface;
use App\Droit\Inscription\Repo\InscriptionInterface;
use App\Droit\Shop\Order\Repo\OrderInterface;

class SiteController extends Controller
{
    protected $upload;
    protected $site;
    protected $inscription;
    protected $order;

    public function __construct( UploadInterface $upload, SiteInterface  $site, InscriptionInterface $inscription, OrderInterface $order)
    {
        $this->upload      = $upload;
        $this->site        = $site;
        $this->inscription = $inscription;
        $this->order       = $order;
    }

    /**
     * @return Response
     */
    public function show($id)
    {
        $site         = $this->site->find($id);
        $inscriptions = $this->inscription->getAll(5);
        $orders       = $this->order->getLast(5);

        return view('backend.sites.show')->with(['site' => $site, 'inscriptions' => $inscriptions, 'orders' => $orders]);
    }
}
