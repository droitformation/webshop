<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Inscription\Repo\InscriptionInterface;
use App\Droit\Shop\Order\Repo\OrderInterface;

class StatistiqueController extends Controller
{
    protected $inscription;
    protected $order;

    public function __construct(InscriptionInterface $inscription, OrderInterface $order)
    {
        $this->inscription = $inscription;
        $this->order       = $order;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }

    public function index(Request $request)
    {

        return view('backend.stats.index');
        // ->with(['inscriptions' => $inscriptions, 'orders' => $orders, 'colloques' => $colloques])
    }
}
