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
        $colloques = $this->colloque->getCurrent(true);

        if ($request->ajax())
        {
            $colloques = $colloques->toArray();

            return response()->json($colloques);
        }

        return view('frontend.pubdroit.colloque.index')->with(['colloques' => $colloques]);
    }

    public function archives()
    {
        $colloques  = $this->colloque->getArchive();

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

        if(!$colloque->visible) {
            return redirect('pubdroit/colloque');
        }

        $pending    = \Auth::check() && \Auth::user()->cant_register ? 'pending' : false;
        $registered = \Auth::check() && \Auth::user()->inscriptions->contains('colloque_id',$id) ? 'registered' : false;

        if ($request->ajax()) {
            return view('colloques.partials.details')->with(['colloque' => $colloque]);
        }

        return view('frontend.pubdroit.colloque.show')->with(['colloque' => $colloque, 'registered' => $registered, 'pending' => $pending, 'user' => \Auth::user()]);
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

    public function documents($id)
    {
        $colloque = $this->colloque->find($id);

        return view('frontend.pubdroit.colloque.partials.download')->with(['colloque' => $colloque]);
    }
}
