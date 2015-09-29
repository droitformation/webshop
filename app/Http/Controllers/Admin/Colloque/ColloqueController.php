<?php

namespace App\Http\Controllers\Admin\Colloque;

use Illuminate\Http\Request;
use App\Droit\Colloque\Repo\ColloqueInterface;
use App\Droit\Inscription\Repo\InscriptionInterface;
use App\Droit\Location\Repo\LocationInterface;
use App\Droit\Organisateur\Repo\OrganisateurInterface;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ColloqueController extends Controller
{
    protected $colloque;
    protected $inscription;
    protected $location;
    protected $organisateur;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ColloqueInterface $colloque,InscriptionInterface $inscription, LocationInterface $location, OrganisateurInterface $organisateur)
    {
        $this->colloque     = $colloque;
        $this->inscription  = $inscription;
        $this->location     = $location;
        $this->organisateur = $organisateur;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $colloques = $this->colloque->getAll();

        return view('backend.colloques.index')->with(['colloques' => $colloques]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $locations     = $this->location->getAll();
        $organisateurs = $this->organisateur->centres();

        return view('backend.colloques.create')->with(['locations' => $locations, 'organisateurs' => $organisateurs]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id,Request $request)
    {
        $colloque = $this->colloque->find($id);
        $colloque->load('location');

        return view('backend.colloques.show')->with(['colloque' => $colloque]);

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

    /**
     *
     * @param  int  $id
     * @return Response
     */
    public function location($id)
    {
        return $this->location->find($id);
    }
}
