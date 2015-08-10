<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Droit\Pays\Repo\PaysInterface;
use App\Droit\Canton\Repo\CantonInterface;
use App\Droit\Profession\Repo\ProfessionInterface;
use App\Droit\Adresse\Repo\AdresseInterface;
use App\Droit\User\Repo\UserInterface;
use App\Http\Requests\CreateAdresse;
use App\Http\Requests\UpdateAdresse;
use App\Http\Requests\UpdateUser;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    protected $pays;
    protected $canton;
    protected $profession;
    protected $adresse;
    protected $user;
    protected $format;

    public function __construct(AdresseInterface $adresse, UserInterface $user, CantonInterface $canton, PaysInterface $pays, ProfessionInterface $profession)
    {
        $this->middleware('auth');

        $this->pays       = $pays;
        $this->canton     = $canton;
        $this->profession = $profession;

        $this->adresse = $adresse;
        $this->user    = $user;
        $this->format  = new \App\Droit\Helper\Format();
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $cantons     = $this->canton->getAll();
        $professions = $this->profession->getAll();
        $pays        = $this->pays->getAll();

        $user = $this->user->find(\Auth::user()->id);

        return view('users.index')->with(compact('user','pays','cantons','professions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(UpdateAdresse $request)
    {
        $this->adresse->update($request->all());

        return redirect('profil');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function orders()
    {
        $user = $this->user->find(\Auth::user()->id);

        return view('users.orders')->with(compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function colloques()
    {
        $user = $this->user->find(\Auth::user()->id);

        return view('users.colloques')->with(compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function colloque($id)
    {
        $user = $this->user->find(\Auth::user()->id);

        return view('users.colloques')->with(compact('user'));
    }

    /**
     *
     * @return Response
     */
    public function inscription($id)
    {
        $user = $this->user->find(\Auth::user()->id);

        return view('users.inscription')->with(compact('user','id'));
    }

}
