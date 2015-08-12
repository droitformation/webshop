<?php

namespace App\Http\Controllers\Frontend\Colloque;

use Illuminate\Http\Request;
use App\Droit\Colloque\Repo\ColloqueInterface;
use App\Droit\Inscription\Repo\InscriptionInterface;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Events\InscriptionWasRegistered;

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

        return view('colloques.index')->with(['colloques' => $colloques]);
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

        if ($request->ajax())
        {
            return view('colloques.partials.details')->with(['colloque' => $colloque]);
        }
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
     * Registration for user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function registration(Request $request)
    {
        $colloque = $this->colloque->find($request->input('colloque_id'));
        $counter  = $this->colloque->getNewNoInscription($colloque->id);

        // Prepare data
        $data        = $request->all() + ['inscription_no' => $counter];
        $inscription = $this->inscription->create($data);

        // Update counter
        $colloque->counter = $counter;
        $colloque->save();

        event(new InscriptionWasRegistered($inscription));

        return redirect('colloque')->with(array('status' => 'success', 'message' => 'Nous avons bien pris en compte votre inscription, vous recevrez prochainement une confirmation par email.' ));
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
