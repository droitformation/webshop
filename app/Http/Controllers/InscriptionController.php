<?php

namespace App\Http\Controllers;

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
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

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
    public function show($id)
    {
        $inscription = $this->inscription->find($id);

        return view('inscriptions.show')->with(['inscription' => $inscription]);
    }


    /**
     * Registration for user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function registration(InscriptionRequest $request)
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function generate($id)
    {
        $generator = new \App\Droit\Generate\Pdf\PdfGenerator();

        $inscription = $this->inscription->find($id);
        $annexes     = $inscription->colloque->annexe;

        // Generate annexes if any
        if(!empty($annexes))
        {
            foreach($annexes as $annexe)
            {
                $doc = $annexe.'Event';
                $generator->$doc($inscription);
            }
        }

        return redirect('profil/inscription/'.$id)->with(array('status' => 'success', 'message' => 'Les documents ont été mis à jour' ));
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
