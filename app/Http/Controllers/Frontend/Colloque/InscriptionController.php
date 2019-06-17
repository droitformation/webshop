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
        echo '<pre>';
        print_r($request->all());
        echo '</pre>';
        exit();
        $inscription = $this->register->register($request->all(), $request->input('colloque_id'), true);

        \Log::info('IP:'.\Request::ip().' time: '.\Carbon\Carbon::now());

        event(new InscriptionWasRegistered($inscription));
        
        $request->session()->flash('InscriptionConfirmation', 'Ok');

        return redirect('pubdroit');
    }
}
