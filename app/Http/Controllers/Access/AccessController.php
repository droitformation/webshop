<?php

namespace App\Http\Controllers\Access;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Droit\Adresse\Repo\AdresseInterface;
use App\Droit\User\Repo\UserInterface;
use App\Http\Requests\CreateAdresse;
use App\Http\Requests\UpdateAdresse;

class AccessController extends Controller {

    protected $adresse;
    protected $user;
    protected $format;

    public function __construct(AdresseInterface $adresse, UserInterface $user)
    {
        $this->adresse = $adresse;
        $this->user    = $user;
        $this->format  = new \App\Droit\Helper\Format();
    }

	public function index()
	{
        $user = \Auth::user();
        $specialisations = $user->access;

        $adresses = $this->adresse->getBySpecialisations($user->access->pluck('id')->all());

        return view('access.index')->with(['specialisations' => $specialisations, 'adresses' => $adresses]);
	}
}
