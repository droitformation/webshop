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

        return view('users.index')->with(['user' => $user]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
