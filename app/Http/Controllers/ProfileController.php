<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Droit\Adresse\Repo\AdresseInterface;
use App\Droit\User\Repo\UserInterface;
use App\Http\Requests\CreateAdresse;
use App\Http\Requests\UpdateAdresse;
use App\Http\Requests\UpdateUser;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    protected $adresse;
    protected $user;
    protected $format;

    public function __construct(AdresseInterface $adresse, UserInterface $user)
    {
        $this->middleware('auth');

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

        $user = $this->user->find(\Auth::user()->id);

        return view('frontend.pubdroit.profil.index')->with(compact('user'));
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

        return view('frontend.pubdroit.profil.orders')->with(compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function colloques()
    {
        $user = $this->user->find(\Auth::user()->id);

        return view('frontend.pubdroit.profil.colloques')->with(compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function colloque($id)
    {
        $user = $this->user->find(\Auth::user()->id);

        return view('frontend.pubdroit.profil.colloques')->with(compact('user'));
    }

    /**
     *
     * @return Response
     */
    public function inscription($id)
    {
        $user = $this->user->find(\Auth::user()->id);
        $inscription = $user->inscriptions->find($id);
        $inscription->load('user_options','colloque');
        $inscription->user_options->load('option');
        $inscription->colloque->load('location','centres','compte');

        return view('frontend.pubdroit.profil.inscription')->with(compact('user','id','inscription'));
    }

}
