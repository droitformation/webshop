<?php

namespace App\Http\Controllers\Backend\Colloque;

use Illuminate\Http\Request;
use App\Droit\Colloque\Repo\ColloqueInterface;
use App\Droit\Document\Worker\DocumentWorker;
use App\Droit\Inscription\Repo\InscriptionInterface;
use App\Droit\Location\Repo\LocationInterface;
use App\Droit\Organisateur\Repo\OrganisateurInterface;
use App\Droit\Compte\Repo\CompteInterface;
use App\Droit\Price\Repo\PriceInterface;
use App\Droit\Option\Repo\OptionInterface;
use App\Droit\Option\Repo\GroupOptionInterface;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ColloqueController extends Controller
{
    protected $colloque;
    protected $compte;
    protected $document;
    protected $inscription;
    protected $location;
    protected $organisateur;
    protected $price;
    protected $option;
    protected $group;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        ColloqueInterface $colloque,
        CompteInterface $compte,
        InscriptionInterface $inscription,
        LocationInterface $location,
        OrganisateurInterface $organisateur,
        DocumentWorker $document,
        PriceInterface $price,
        OptionInterface $option,
        GroupOptionInterface $group
    )
    {
        $this->colloque     = $colloque;
        $this->compte       = $compte;
        $this->document     = $document;
        $this->inscription  = $inscription;
        $this->location     = $location;
        $this->organisateur = $organisateur;
        $this->price        = $price;
        $this->option       = $option;
        $this->group        = $group;
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
        $colloque = $this->colloque->create($request->all());

        return redirect('admin/colloque/'.$colloque->id)->with(array('status' => 'success', 'message' => 'Le colloque a été crée'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id,Request $request)
    {
        $colloque      = $this->colloque->find($id);
        $locations     = $this->location->getAll();
        $comptes       = $this->compte->getAll();
        $organisateurs = $this->organisateur->centres();

        return view('backend.colloques.show')->with(['colloque' => $colloque, 'comptes' => $comptes, 'locations' => $locations, 'organisateurs' => $organisateurs]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $colloque     = $this->colloque->update($request->all());
        $illustration = $request->input('illustration',null);

        if($illustration && !empty($illustration))
        {
            $this->document->updateColloqueDoc($id, ['illustration' => $illustration]);
        }

        return redirect('admin/colloque/'.$colloque->id)->with(array('status' => 'success', 'message' => 'Le colloque a été mis à jour' ));
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

    /**
     *
     * @param  int  $id
     * @return Response
     */
    public function adresse($id)
    {
        return $this->organisateur->find($id);
    }

    /**
     *
     * @param  int  $id
     * @return Response
     */
    public function generate($id,$doc)
    {
        $colloque = $this->colloque->find($id);
        $user     = \Auth::user();

        $inscription = factory(\App\Droit\Inscription\Entities\Inscription::class)->make([
            'colloque_id' => $colloque->id,
            'user_id'     => $user->id,
            'price_id'    => $colloque->prices->first()->id
        ]);

        $generator = \App::make('App\Droit\Generate\Pdf\PdfGeneratorInterface');
        $generator->stream = true;

        $doc = $doc.'Event';
        return $generator->setInscription($inscription)->$doc();
    }

    /**
     *
     * @param  int  $id
     * @return Response
     */
    public function addprice(Request $request)
    {
        parse_str($request->input('data'), $data);
        $price = $this->price->create($data);

        $colloque = $this->colloque->find($data['colloque_id']);

        return view('backend.colloques.partials.prices')->with(['type' => $price->type, 'title' => 'Prix '.$price->type, 'colloque' => $colloque]);
    }

    /**
     * @return Response
     */
    public function editprice(Request $request)
    {
        $price = $this->price->update([ 'id' => $request->input('pk'), $request->input('name') =>  $request->input('value')]);

        if($price)
        {
            return response('OK', 200);
        }

        return response('OK', 200)->with(['status' => 'error','msg' => 'problème']);
    }

    /**
     *
     * @param  int  $id
     * @return Response
     */
    public function removeprice(Request $request)
    {
        $price    = $this->price->find($request->input('id'));
        $oldprice = $price;
        $this->price->delete($price->id);
        $colloque = $this->colloque->find($oldprice->colloque_id);

        return view('backend.colloques.partials.prices')->with(['type' => $oldprice->type, 'title' => 'Prix '.$oldprice->type, 'colloque' => $colloque]);
    }

    /**
     *
     * @param  int  $id
     * @return Response
     */
    public function addoption(Request $request)
    {
        parse_str($request->input('data'), $data);

        // Create option
        $option = $this->option->create($data);

        // is it's a multichoice
        if(isset($data['group']))
        {
            $choices = explode(PHP_EOL, $data['group']);
            if(!empty($choices))
            {
                foreach($choices as $choice)
                {
                    $this->group->create([
                        'text'        => $choice,
                        'colloque_id' => $data['colloque_id'],
                        'option_id'   => $option->id
                    ]);
                }
            }
        }

        $colloque = $this->colloque->find($data['colloque_id']);

        return view('backend.colloques.partials.options')->with(['colloque' => $colloque]);
    }

    /**
     * @return Response
     */
    public function editoption(Request $request)
    {
         $model = $request->input('model');

         $item  = $this->$model->update([ 'id' => $request->input('pk'), $request->input('name') => $request->input('value')]);

         if($item)
         {
             return response('OK', 200);
         }

         return response('OK', 200)->with(['status' => 'error','msg' => 'problème']);
    }
    
    /**
     *
     * @param  int  $id
     * @return Response
     */
    public function removeoption(Request $request)
    {
        $option    = $this->option->find($request->input('id'));
        $oldoption = $option;
        $this->option->delete($option->id);
        $colloque = $this->colloque->find($oldoption->colloque_id);

        return view('backend.colloques.partials.options')->with(['colloque' => $colloque]);
    }

}
