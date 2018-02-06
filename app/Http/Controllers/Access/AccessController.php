<?php

namespace App\Http\Controllers\Access;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Droit\Adresse\Repo\AdresseInterface;
use App\Droit\User\Repo\UserInterface;
use App\Droit\Specialisation\Repo\SpecialisationInterface;
use App\Http\Requests\CreateAdresse;
use App\Http\Requests\UpdateAdresse;

class AccessController extends Controller {

    protected $adresse;
    protected $user;
    protected $format;
    protected $specialisation;

    public function __construct(AdresseInterface $adresse, SpecialisationInterface $specialisation, UserInterface $user)
    {
        $this->adresse = $adresse;
        $this->user    = $user;
        $this->specialisation = $specialisation;
        $this->format  = new \App\Droit\Helper\Format();
    }

	public function index()
	{
        $user = \Auth::user();
        $specialisations = $user->access;

        $adresses = $this->adresse->getBySpecialisations($user->access->pluck('id')->all());

        return view('access.index')->with(['specialisations' => $specialisations, 'adresses' => $adresses]);
	}

    public function add(Request $request)
    {
        $user = $this->user->find($request->input('id'));

        $exist = $this->specialisation->search($request->input('title'));
        $find  = $exist ? $exist : $this->specialisation->create(['title' => $request->input('title')]);

        $user->access()->attach($find->id);

        return response()->json( 'ok', 200 );
	}

    public function remove(Request $request)
    {
        $user = $this->user->find($request->input('id'));
        $find = $this->specialisation->search($request->input('title'));

        $user->access()->detach($find->id);

        return response()->json( 'ok', 200 );
    }
}
