<?php

namespace App\Http\Controllers\Frontend\Colloque;

use Illuminate\Http\Request;
use App\Droit\Colloque\Repo\ColloqueInterface;
use App\Droit\Inscription\Repo\InscriptionInterface;
use App\Http\Requests;
use App\Http\Requests\InscriptionRequest;
use App\Http\Controllers\Controller;
use App\Events\InscriptionWasRegistered;

class InscriptionController extends Controller
{
    protected $inscription;
    protected $colloque;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ColloqueInterface $colloque, InscriptionInterface $inscription)
    {
        $this->colloque    = $colloque;
        $this->inscription = $inscription;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($id)
    {
        $inscriptions = $this->inscription->getByColloque($id);

        return view('inscriptions.index')->with(['inscriptions' => $inscriptions]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(InscriptionRequest $request)
    {
        $colloque = $this->colloque->find($request->input('colloque_id'));
        $inscription_no  = $this->colloque->getNewNoInscription($colloque->id);

        // Prepare data
        $data        = $request->all() + ['inscription_no' => $inscription_no];
        $inscription = $this->inscription->create($data);

        // Update counter
        $this->colloque->increment($colloque->id);

        event(new InscriptionWasRegistered($inscription));

        return redirect('colloque')->with(array('status' => 'success', 'message' => 'Nous avons bien pris en compte votre inscription, vous recevrez prochainement une confirmation par email.' ));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $inscription = $this->inscription->find($id);

        return view('inscriptions.show')->with(['inscription' => $inscription]);
    }

}
