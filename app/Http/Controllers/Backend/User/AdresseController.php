<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Adresse\Repo\AdresseInterface;
use App\Droit\User\Repo\UserInterface;
use App\Http\Requests\CreateAdresse;
use App\Http\Requests\UpdateAdresse;
use Illuminate\Http\Request;

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
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $adresses = $this->adresse->getAll();

        return view('adresse.index')->with([ 'adresses' => $adresses ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('adresse.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(CreateAdresse $request)
    {
        $adresse = $this->adresse->create($request->all());

        return redirect('adresse/'.$adresse->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $adresse = $this->adresse->find($id);

        return view('adresse.show')->with(array( 'adresse' => $adresse ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id,UpdateAdresse $request)
    {
        $adresse = $this->adresse->update($request->all());

        return redirect('adresse/'.$id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function ajaxUpdate($id,Request $request)
    {

        $data    = $this->format->convertSerializedData($request->all());
        $adresse = $this->adresse->update($data);
        $user    = $this->user->find($data['user_id']);

        echo view('shop.partials.user-livraison', ['user' => $user]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->adresse->delete($id);

        return redirect('/')->with(array('status' => 'success', 'message' => 'Adresse supprimé' ));
    }

}
