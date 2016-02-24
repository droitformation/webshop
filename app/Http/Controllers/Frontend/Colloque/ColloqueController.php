<?php

namespace App\Http\Controllers\Frontend\Colloque;

use Illuminate\Http\Request;
use App\Droit\Colloque\Repo\ColloqueInterface;
use App\Droit\Inscription\Repo\InscriptionInterface;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ColloqueController extends Controller
{
    protected $colloque;
    protected $inscription;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ColloqueInterface $colloque,InscriptionInterface $inscription)
    {
        $this->colloque    = $colloque;
        $this->inscription = $inscription;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $colloques = $this->colloque->getAll();

        if ($request->ajax())
        {
            $colloques = $colloques->toArray();

            return response()->json($colloques);
        }

        return view('frontend.pubdroit.colloque.index')->with(['colloques' => $colloques]);
    }

    public function archives()
    {
        $colloques  = $this->colloque->getAll(false,true);

        return view('frontend.pubdroit.colloque.archives')->with(['colloques' => $colloques]);
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

        if ($request->ajax())
        {
            return view('colloques.partials.details')->with(['colloque' => $colloque]);
        }

        return view('frontend.pubdroit.colloque.show')->with(['colloque' => $colloque]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function inscription($id)
    {
        $colloque = $this->colloque->find($id);
        $colloque->load('location','options','prices');

        return view('colloques.show')->with(['colloque' => $colloque]);
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
