<?php

namespace App\Http\Controllers\Backend\Colloque;

use App\Events\InscriptionWasRegistered;
use App\Http\Controllers\Controller;

use App\Droit\Colloque\Repo\ColloqueInterface;
use App\Droit\Inscription\Repo\InscriptionInterface;
use App\Droit\Inscription\Worker\InscriptionWorkerInterface;
use App\Droit\Inscription\Repo\RabaisInterface;
use App\Droit\User\Repo\UserInterface;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\InscriptionCreateRequest;
use App\Http\Requests\MakeInscriptionRequest;

class InscriptionController extends Controller
{
    protected $inscription;
    protected $register;
    protected $colloque;
    protected $rabais;
    protected $user;
    protected $generator;
    
    public function __construct(ColloqueInterface $colloque, RabaisInterface $rabais, InscriptionInterface $inscription, UserInterface $user, InscriptionWorkerInterface $register)
    {
        $this->colloque    = $colloque;
        $this->inscription = $inscription;
        $this->rabais      = $rabais;
        $this->register    = $register;
        $this->user        = $user;

        $this->generator   = \App::make('App\Droit\Generate\Pdf\PdfGeneratorInterface');

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
     * @param  int  $id
     * @param  Request  $request
     * @return Response
     */
    public function colloque($id, Request $request)
    {
        $colloque   = $this->colloque->find($id);
        $filters    = array_filter($request->only(['search','status']));
        $pagination = !empty($filters) ? false : true;

        $inscriptions = $this->inscription->getColloqe($id ,$pagination, $filters);
        
        // Filter to remove inscriptions without all infos
        list($inscriptions_filter, $invalid) = $inscriptions->partition(function ($inscription) {
            $display = new \App\Droit\Inscription\Entities\Display($inscription);
            return $display->isValid();
        });
  
        return view('backend.inscriptions.colloque')
            ->with([
                'inscriptions' => $inscriptions,
                'inscriptions_filter' => $inscriptions_filter,
                'invalid'  => $invalid,
                'colloque' => $colloque,
                'current'  => isset($filters['status']) ? $filters['status'] : '',
                'names' => config('columns.names')
            ]);
    }

    /**
     * Display creation form
     * @param  int  $colloque_id
     * @return Response
     */
    public function create($colloque_id = null)
    {
        $colloques = $this->colloque->getAllAdmin(true,false);
 
        return view('backend.inscriptions.create')->with(['colloques' => $colloques, 'colloque_id' => $colloque_id]);
    }

    /**
     * Display creation.
     * @param  MakeInscriptionRequest  $request
     * @return Response
     */
    public function make(MakeInscriptionRequest $request)
    {
        $colloques = $this->colloque->getAll();
        $colloque  = $this->colloque->find($request->input('colloque_id'));
        $rabais    = $this->rabais->byCompte($colloque->compte_id);

        $validator = new \App\Droit\Colloque\Worker\ColloqueValidation($colloque);
        $validator->activate();

        $user      = $this->user->find($request->input('user_id'));
        $type      = $request->input('type');

        $form = view('backend.inscriptions.register.'.$type)->with(['colloque' => $colloque, 'rabais' => $rabais, 'user' => $user, 'type' => $type]);

        return view('backend.inscriptions.make')->with(['colloques' => $colloques, 'user' => $user, 'colloque' => $colloque, 'form' => $form, 'type' => $type]);
    }

    /**
     * Display creation.
     * @param  int  $group_id
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
    //public function store(InscriptionCreateRequest $request){
    public function store(Request $request){

        $register = new \App\Droit\Inscription\Entities\Register($request->all());
        $inscriptions = $register->prepare();

        $inscriptions = $inscriptions->map(function ($data) use ($request) {
            // Register each inscription
            session()->put('reference_no', $request->input('reference_no',null));
            session()->put('transaction_no', $request->input('transaction_no',null));

            $inscription = $this->register->register($data,$request->input('type') == 'simple' ? true : null);
            $reference   = \App\Droit\Transaction\Reference::make($inscription);

            $this->register->makeDocuments($inscription,true);

            return $inscription;
        });

        flash('L\'inscription à bien été crée')->success();

        return redirect('admin/inscription/colloque/'.$request->input('colloque_id'));
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

        // Make sur we redirect to the create page if there is no inscription
        if(!$inscription){
            return redirect('admin/inscription/create');
        }

        return view('backend.inscriptions.show')->with(['inscription' => $inscription, 'colloque' => $inscription->colloque, 'user' => $inscription->inscrit]);
    }

    /**
     * Update the inscription
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        // Update inscription
        $inscription = $this->inscription->update(converPriceUpdate($request->all()));

        // Attach references if any
        $reference = \App\Droit\Transaction\Reference::update($inscription, $request->only(['reference_no','transaction_no']));

        $model = $inscription->group_id ? $inscription->groupe : $inscription;
        $model = $model->fresh();

        // Remake the documents
        $this->register->makeDocuments($model, true);

        flash('L\'inscription a été mise à jour')->success();

        return redirect()->back();
    }

    /**
     * Remove the inscription
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $inscription = $this->inscription->find($id);

        // Destroy documents or else if we register the same person again the docs are going to be wrong
        $this->register->unsubscribe($inscription);

        flash('Désinscription effectué')->success();

        return redirect()->back();
    }

    /**
     * Remake documents for inscription
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

        flash('Les documents ont été mis à jour')->success();

        return redirect()->back();
    }

    public function display($id)
    {
        $generator = \App::make('App\Droit\Generate\Pdf\PdfGeneratorInterface');

        $inscription = $this->inscription->find($id);

        if($inscription){
            $generator->stream = true;
            return $generator->make('facture', $inscription);
        }
    }
}
