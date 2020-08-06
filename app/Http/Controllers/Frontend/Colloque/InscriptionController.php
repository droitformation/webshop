<?php

namespace App\Http\Controllers\Frontend\Colloque;

use Illuminate\Http\Request;
use App\Droit\Colloque\Repo\ColloqueInterface;
use App\Droit\Inscription\Repo\InscriptionInterface;
use App\Droit\Inscription\Worker\InscriptionWorkerInterface;
use App\Http\Requests;
use App\Http\Requests\InscriptionRequest;
use App\Http\Controllers\Controller;
use App\Events\InscriptionWasRegistered;

class InscriptionController extends Controller
{
    protected $inscription;
    protected $colloque;
    protected $register;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ColloqueInterface $colloque, InscriptionInterface $inscription, InscriptionWorkerInterface $register)
    {
        $this->colloque    = $colloque;
        $this->inscription = $inscription;
        $this->register    = $register;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(InscriptionRequest $request)
    {
        // Prepare data to register
        $register     = new \App\Droit\Inscription\Entities\Register($request->all());
        $inscriptions = $register->prepare();

        $inscriptions = $inscriptions->map(function ($data) use ($request) {
            // Register each inscription
            session()->put('reference_no', $request->input('reference_no',null));
            session()->put('transaction_no', $request->input('transaction_no',null));

            $inscription = $this->register->register($data,true);

            if($request->input('test')){
               \Log::info(json_encode($inscription->toArray()));
            }

            event(new InscriptionWasRegistered($inscription));

            return $inscription;
        });

        $request->session()->flash('InscriptionConfirmation', 'Ok');

        return redirect('pubdroit');
    }
}
