<?php

namespace App\Http\Controllers\Frontend\Colloque;

use Illuminate\Http\Request;
use App\Droit\Colloque\Repo\ColloqueInterface;
use App\Droit\Inscription\Repo\InscriptionInterface;
use App\Droit\PriceLink\Repo\PriceLinkInterface;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ColloqueController extends Controller
{
    protected $colloque;
    protected $inscription;
    protected $price_link;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ColloqueInterface $colloque,InscriptionInterface $inscription, PriceLinkInterface $price_link)
    {
        $this->colloque    = $colloque;
        $this->inscription = $inscription;
        $this->price_link = $price_link;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $colloques = $this->colloque->getCurrent(true);

        if ($request->ajax()) {
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

        if(isset($colloque->url) && !empty($colloque->url)) {
            return redirect($colloque->url);
        }

        $pending    = \Auth::check() && \Auth::user()->cant_register ? 'pending' : false;
        $registered = \Auth::check() && \Auth::user()->inscriptions->contains('colloque_id',$id) ? 'registered' : false;

        if ($request->ajax()) {
            return view('colloques.partials.details')->with(['colloque' => $colloque]);
        }

        return view('frontend.pubdroit.colloque.show')->with([
            'colloque' => $colloque,
            'registered' => $registered,
            'pending' => $pending,
            'user' => \Auth::user(),
            'account' => new \App\Droit\User\Entities\Account(\Auth::user())
        ]);
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

    public function colloqueoptions(Request $request)
    {
        $current    = $this->colloque->find($request->input('colloque_id'));
        $price_link = $this->price_link->find($request->input('price_link_id'));

        return $price_link->colloques->filter(function ($colloque) use ($current) {
            return $colloque->id != $current->id;
        })->reduce(function ($carry, $colloque) {
            $html = view('frontend.pubdroit.colloque.wizard.option')->with(['colloque' => $colloque])->render();
            return $carry .= $html;
        }, '');
    }

    public function documents($id)
    {
        $colloque = $this->colloque->find($id);

        return view('frontend.pubdroit.colloque.partials.download')->with(['colloque' => $colloque]);
    }

    public function resume(Request $request)
    {
        $colloque = $this->colloque->find($request->input('colloque_id'));

        $preview = new \App\Droit\Transaction\Preview($colloque, $request->except('_token'));

        return $preview->getHtml();
    }
}
