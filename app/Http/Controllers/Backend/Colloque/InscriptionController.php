<?php

namespace App\Http\Controllers\Backend\Colloque;

use App\Droit\Colloque\Repo\ColloqueInterface;
use App\Droit\Inscription\Repo\InscriptionInterface;
use App\Droit\Inscription\Worker\InscriptionWorkerInterface;
use App\Droit\User\Repo\UserInterface;
use App\Droit\Inscription\Repo\GroupeInterface;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\InscriptionCreateRequest;
use App\Http\Requests\MakeInscriptionRequest;
use App\Http\Requests\SendAdminInscriptionRequest;
use App\Http\Controllers\Controller;

class InscriptionController extends Controller
{
    protected $inscription;
    protected $register;
    protected $colloque;
    protected $user;
    protected $generator;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ColloqueInterface $colloque, InscriptionInterface $inscription, UserInterface $user, InscriptionWorkerInterface $register, GroupeInterface $groupe)
    {
        $this->colloque    = $colloque;
        $this->inscription = $inscription;
        $this->register    = $register;
        $this->user        = $user;
        $this->groupe      = $groupe;

        $this->generator   = \App::make('App\Droit\Generate\Pdf\PdfGeneratorInterface');
        $this->helper      = new \App\Droit\Helper\Helper();

        view()->share('badges', config('badge'));

        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $inscriptions = $this->inscription->getAll(5);

        return view('backend.inscriptions.index')->with(['inscriptions' => $inscriptions]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function colloque($id, Request $request)
    {
        $colloque = $this->colloque->find($id);

        if($request->input('inscription_no',null)) {
            $inscriptions = $this->inscription->findByNumero($request->input('inscription_no',null),$id);
        }
        else {
            $inscriptions = $this->inscription->getByColloque($id,false,true);
        }

        // Filter to remove inscriptions without all infos
        $inscriptions_filter = $inscriptions->filter(function ($inscription, $key) {
            $display = new \App\Droit\Inscription\Entities\Display($inscription);
            return $display->isValid();
        });
  
        return view('backend.inscriptions.colloque')->with(['inscriptions' => $inscriptions, 'inscriptions_filter' => $inscriptions_filter, 'colloque' => $colloque, 'names' => config('columns.names')]);
    }

    /**
     * Display creation.
     *
     * @return Response
     */
    public function create($colloque_id = null)
    {
        $colloques = $this->colloque->getAll(true,false);
 
        return view('backend.inscriptions.create')->with(['colloques' => $colloques, 'colloque_id' => $colloque_id]);
    }

    /**
     * Display creation.
     *
     * @return Response
     */
    public function make(MakeInscriptionRequest $request)
    {
        $this->register->colloqueIsOk($request->input('colloque_id'));
        
        $colloques = $this->colloque->getAll();
        $colloque  = $this->colloque->find($request->input('colloque_id'));

        $user      = $this->user->find($request->input('user_id'));
        $type      = $request->input('type');

        $form = view('backend.inscriptions.register.'.$type)->with(['colloque' => $colloque, 'user' => $user, 'type' => $type]);

        return view('backend.inscriptions.make')->with(['colloques' => $colloques, 'user' => $user, 'colloque' => $colloque, 'form' => $form, 'type' => $type]);
    }

    /**
     * Display creation.
     *
     * @return Response
     */
    public function add($group_id)
    {
        $inscription = $this->inscription->getByGroupe($group_id);

        return view('backend.inscriptions.add')->with(['group_id' => $group_id, 'groupe' => $inscription->first()->groupe, 'colloque' => $inscription->first()->colloque]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(InscriptionCreateRequest $request)
    {
        $type     = $request->input('type');
        $colloque = $request->input('colloque_id');

        $this->register->colloqueIsOk($colloque);

        // if type simple
        if($type == 'simple') {
            $inscription = $this->register->register($request->all(), $colloque, true);
            $this->register->makeDocuments($inscription, true);
        }
        else {
            $group = $this->register->registerGroup($colloque, $request->all());
            $this->register->makeDocuments($group, true);
        }

        alert()->success('L\'inscription à bien été crée');

        return redirect('admin/inscription/colloque/'.$colloque);
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

        if(!$inscription){
            return redirect('admin/inscription/create');
        }

        return view('backend.inscriptions.show')->with(['inscription' => $inscription, 'colloque' => $inscription->colloque, 'user' => $inscription->inscrit]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function regenerate($id)
    {
        $inscription = $this->inscription->find($id);

        $model = $inscription->group_id ? $inscription->groupe : $inscription;

        // remake docs
        $this->register->makeDocuments($model, true);

        // remake attestation
        if($inscription->doc_attestation) {
            $this->generator->make('attestation', $inscription);
        }

        alert()->success('Les documents ont été mis à jour');

        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function generate(Request $request)
    {
        $inscription = $this->inscription->find($request->input('id'));

        $model = $inscription->group_id ? $inscription->groupe : $inscription;

        $this->register->makeDocuments($model, true);

        return ['link' => $model->doc_facture];
    }

    /**
     * Send inscription via admin
     *
     * @return Response
     */
    public function send(SendAdminInscriptionRequest $request)
    {
        $group_id = $request->input('group_id',null);
        $model    = $group_id ? 'groupe' : 'inscription';
        $model_id = $group_id ? $group_id : $request->input('id');

        // Find model inscription or group
        $item = $this->$model->find($model_id);

        if($item)
        {
            $this->register->sendEmail($item, $request->input('email'));

            alert()->success('Email envoyé');

            return redirect()->back();
        }

        alert()->warning('Aucune inscription trouvé, problème');

        return redirect()->back();

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
        // Update inscription
        $inscription = $this->inscription->update($request->all());

        $model = $inscription->group_id ? $inscription->groupe : $inscription;

        // Remake the documents
        $this->register->makeDocuments($model, true);

        alert()->success('L\'inscription a été mise à jour');

        return redirect()->back();
    }

    public function edit(Request $request)
    {
        $data = $request->all();

        if($data['model'] == 'group') {
            $group = $this->groupe->find($data['pk']);

            if(!$group->inscriptions->isEmpty())
            {
                $group->inscriptions->each(function ($inscription, $key) use ($data) {
                    $this->inscription->updateColumn(['id' => $inscription->id , $data['name'] => $data['value']]);
                });
            }
        }
        else {
            $inscription = $this->inscription->updateColumn(['id' => $data['pk'], $data['name'] => $data['value']]);
        }

        return response()->json(['OK' => 200, 'etat' => ($inscription->status == 'pending' ? 'En attente' : 'Payé'),'color' => ($inscription->status == 'pending' ? 'default' : 'success')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $inscription = $this->inscription->find($id);

        // Destroy documents or else if we register the same person again the docs are going to be wrong
        $this->register->destroyDocuments($inscription);

        // If it's a group inscription and we have deleted refresh the groupe invoice and bv
        if($inscription->group_id > 0) {
            $this->register->makeDocuments($inscription->groupe, true);
        }

        $this->inscription->delete($id);

        alert()->success('Désinscription effectué');

        return redirect()->back();
    }

    /**
     * Inscription partial via ajax
     * @return Response
     */
    public function inscription(Request $request){

        $colloque = $this->colloque->find($request->input('colloque_id'));
        $user     = $this->user->find($request->input('user_id'));

        // simple or multiple
        $type     = $request->input('type');

        echo view('backend.inscriptions.register.'.$type)->with(['colloque' => $colloque, 'user_id' => $request->input('user_id'), 'user' => $user, 'type' => $type])->__toString();
    }

    public function presence(Request $request)
    {
        $inscription = $this->inscription->find($request->input('id'));

        if($inscription)
        {
            $inscription->present = $request->input('presence',null) ? 1 : null ;
            $inscription->save();
        }

        echo 'ok';
    }

}
