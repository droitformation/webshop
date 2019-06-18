<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Adresse\Repo\AdresseInterface;
use App\Droit\User\Repo\UserInterface;
use App\Http\Requests\CreateAdresse;
use App\Http\Requests\UpdateAdresse;

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
    public function show($id, Request $request)
    {
        $adresse = $this->adresse->find($id);

        if($request->ajax())
        {
            return response()->json($adresse);
        }

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

        alert()->success('Adresse supprimé');

        return redirect('/');
    }

    public function getAdresse($id)
    {
        $adresse = $this->adresse->find($id);

        return $adresse;

        die();
    }

    /*
     * Vue FactureAdresse methods
     * */
    public function getAdresseDetail($id)
    {
        $adresse = $this->adresse->find($id);

        echo view('frontend.pubdroit.partials.adresse-details')->with(['adresse' => $adresse])->render();
        die();
    }

    public function createOrUpdateFacturation(Request $request)
    {
        $adresse = $this->adresse->facturation($request->all());

        return response()->json($adresse);
        die();
    }
}
