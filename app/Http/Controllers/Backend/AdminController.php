<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Droit\Colloque\Repo\ColloqueInterface;
use App\Droit\Inscription\Repo\InscriptionInterface;
use App\Droit\Shop\Order\Repo\OrderInterface;

class AdminController extends Controller {

    protected $colloque;
    protected $inscription;
    protected $order;

    public function __construct(ColloqueInterface $colloque, InscriptionInterface $inscription, OrderInterface $order)
    {
        $this->colloque    = $colloque;
        $this->inscription = $inscription;
        $this->order       = $order;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }

	/**
	 * @return Response
	 */
	public function index()
	{
        $inscriptions = $this->inscription->getAll(3);
        $orders       = $this->order->getLast(3);
        $colloques    = $this->colloque->getAll(true);

        return view('backend.index')->with(['inscriptions' => $inscriptions, 'orders' => $orders, 'colloques' => $colloques]);
	}

    public function test()
    {
        return view('backend.test');
    }
}
