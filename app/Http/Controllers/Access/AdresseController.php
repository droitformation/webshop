<?php

namespace App\Http\Controllers\Access;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Adresse\Repo\AdresseInterface;
use App\Droit\User\Repo\UserInterface;
use App\Http\Requests\CreateAdresse;

class AdresseController extends Controller {

    protected $adresse;
    protected $user;
    protected $format;

    public function __construct(AdresseInterface $adresse, UserInterface $user)
    {
        $this->adresse = $adresse;
        $this->user    = $user;
        $this->format  = new \App\Droit\Helper\Format();
    }

    /**
     * Create new adresse, if we passe an user_id make the adresse for the user
     *
     * @return Response
     */
    public function create()
    {
        return view('access.adresses.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(CreateAdresse $request)
    {
        $adresse = $this->adresse->create($request->all());

        $adresse->specialisations()->attach(\Auth::user()->access->pluck('id')->all());

        flash('Adresse crée')->success();

        return redirect('access/adresse/'.$adresse->id);
    }

    public function update($id,CreateAdresse $request)
    {
        $adresse = $this->adresse->update($request->all());

        flash('Adresse mise à jour')->success();

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id,Request $request)
    {
        $adresse = $this->adresse->find($id);

        return view('access.adresses.show')->with(['adresse' => $adresse , 'term' => session()->get('term')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id, Request $request)
    {
        $adresse = $this->adresse->find($id);

        // Validate deletion, if no user or user with no orders or inscriptions delete the adresse
        $validator = new \App\Droit\Adresse\Worker\AdresseValidation($adresse);
        $validator->activate();

        $this->adresse->delete($id);

        flash('Adresse supprimée')->success();

        return redirect('access/adresses');
    }

}
