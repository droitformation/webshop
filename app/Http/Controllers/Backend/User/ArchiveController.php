<?php

namespace App\Http\Controllers\Backend\User;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Adresse\Repo\AdresseInterface;
use App\Droit\User\Repo\UserInterface;

use App\Droit\Adresse\Worker\AdresseWorkerInterface;
use App\Droit\Shop\Order\Repo\OrderInterface;
use App\Droit\Inscription\Repo\InscriptionInterface;

class ArchiveController extends Controller
{
    protected $order;
    protected $inscription;

    public function __construct(OrderInterface $order, InscriptionInterface $inscription)
    {
        $this->order   = $order;
        $this->inscription = $inscription;
    }

    public function index(Request $request)
    {
        //$orders = $this->order->getPeriod($request->input('year',date('Y')));
        $inscriptions = $this->inscription->getYear($request->input('year',date('Y')));
 
        return view('backend.archives.index')->with(['inscriptions' => $inscriptions, 'year' => $request->input('year',date('Y'))]);
    }
}
