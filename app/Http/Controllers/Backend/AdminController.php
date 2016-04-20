<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Droit\User\Repo\UserInterface;
use App\Droit\Adresse\Repo\AdresseInterface;
use App\Droit\Service\FileWorkerInterface;
use App\Droit\Inscription\Repo\InscriptionInterface;
use App\Droit\Shop\Order\Repo\OrderInterface;

class AdminController extends Controller {

    protected $user;
    protected $adresse;
    protected $file;
    protected $inscription;
    protected $order;

    public function __construct(UserInterface $user, AdresseInterface $adresse, FileWorkerInterface $file, InscriptionInterface $inscription, OrderInterface $order)
    {
        $this->user        = $user;
        $this->adresse     = $adresse;
        $this->file        = $file;
        $this->inscription = $inscription;
        $this->order       = $order;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }

	/**
	 * @return Response
	 */
	public function index()
	{
        $files        = $this->file->manager();
        $inscriptions = $this->inscription->getAll(5);
        $orders       = $this->order->getLast(5);

        return view('backend.index')->with(['files' => $files,'inscriptions' => $inscriptions, 'orders' => $orders]);
	}
}
