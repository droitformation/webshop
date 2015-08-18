<?php

namespace App\Http\Controllers\Admin\Colloque;

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
    protected $generator;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ColloqueInterface $colloque, InscriptionInterface $inscription)
    {
        $this->colloque    = $colloque;
        $this->inscription = $inscription;
        $this->generator   = new \App\Droit\Generate\Pdf\PdfGenerator();

    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $inscriptions = $this->inscription->getAll();

        return view('backend.inscriptions.index')->with(['inscriptions' => $inscriptions]);
    }

    /**
     * Display creation.
     *
     * @return Response
     */
    public function create()
    {
        $colloque = $this->colloque->find(71);

        return view('backend.inscriptions.create')->with(['colloque' => $colloque]);
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
        $counter  = $this->colloque->getNewNoInscription($colloque->id);

        // Prepare data
        $data        = $request->all() + ['inscription_no' => $counter];
        $inscription = $this->inscription->create($data);

        // Update counter
        $colloque->counter = $counter;
        $colloque->save();

        event(new InscriptionWasRegistered($inscription));

        return redirect('admin/inscription')->with(array('status' => 'success',
            'message' => 'Nous avons bien pris en compte votre inscription, vous recevrez prochainement une confirmation par email.' ));
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

        return view('backend.inscriptions.show')->with(['inscription' => $inscription]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function generate($id)
    {
        $inscription = $this->inscription->find($id);
        $annexes     = $inscription->colloque->annexe;

        $this->generator->setInscription($inscription)->generate($annexes);

        return redirect('admin/inscription/'.$id)->with(array('status' => 'success', 'message' => 'Les documents ont été mis à jour' ));
    }

    /**
     * Send inscription via admin
     *
     * @param  int  $id
     * @return Response
     */
    public function send($id)
    {
        $inscription = $this->inscription->find($id);

        event(new InscriptionWasRegistered($inscription));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
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
