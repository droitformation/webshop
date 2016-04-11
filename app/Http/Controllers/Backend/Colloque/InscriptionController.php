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
    public function colloque($id)
    {
        $colloque        = $this->colloque->find($id);
        $inscriptions    = $this->inscription->getByColloque($id,false,true);

        return view('backend.inscriptions.colloque')->with(['inscriptions' => $inscriptions, 'colloque' => $colloque, 'names' => config('columns.names')]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function desinscription($id)
    {
        $colloque        = $this->colloque->find($id);
        $desinscriptions = $this->inscription->getByColloqueTrashed($id);

        return view('backend.inscriptions.desinscription')->with(['colloque' => $colloque, 'desinscriptions' => $desinscriptions]);
    }

    /**
     * Display creation.
     *
     * @return Response
     */
    public function create($colloque_id = null)
    {
        $colloques = $this->colloque->getAll();

        return view('backend.inscriptions.create')->with(['colloques' => $colloques, 'colloque_id' => $colloque_id]);
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
     * Display groupe edit.
     *
     * @return Response
     */
    public function groupe($group_id)
    {
        $groupe = $this->groupe->find($group_id);

        return view('backend.inscriptions.groupe')->with(['groupe' => $groupe]);
    }

    public function push(Request $request)
    {
        // Register a new inscription for group
        $this->register->register($request->all(), $request->input('colloque_id'));

        // Get the group
        $group = $this->groupe->find($request->input('group_id'));

        // Remake docs
        $this->register->makeDocuments($group, true);

        return redirect()->back()->with(array('status' => 'success', 'message' => 'L\'inscription à bien été crée' ));
    }

    public function change(Request $request)
    {
        // Update user for group and remake docs
        $groupe = $this->groupe->update([
            'id'      => $request->input('group_id'),
            'user_id' => $request->input('user_id')
        ]);

        $this->register->makeDocuments($groupe, true);

        return redirect('admin/inscription/colloque/'.$groupe->colloque_id)->with(['status' => 'success', 'message' => 'Le groupe a été modifié']);
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

        // if type simple
        if($type == 'simple')
        {
            $inscription = $this->register->register($request->all(), $colloque, true);

            $this->register->makeDocuments($inscription, true);
        }
        else
        {
            $group = $this->register->registerGroup($colloque, $request);

            $this->register->makeDocuments($group, true);
        }

        return redirect('admin/inscription/colloque/'.$colloque)->with(['status' => 'success', 'message' => 'L\'inscription à bien été crée']);
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

        return view('backend.inscriptions.show')->with(['inscription' => $inscription, 'colloque' => $inscription->colloque, 'user' => $inscription->inscrit]);
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

        $model = $inscription->group_id ? $inscription->groupe : $inscription;

        // remake docs
        $this->register->makeDocuments($model, true);

        // remake attestation
        if($inscription->doc_attestation)
        {
            $this->generator->make('attestation', $inscription);
        }

        return redirect()->back()->with(['status' => 'success', 'message' => 'Les documents ont été mis à jour']);
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
            $this->register->sendEmail($item, $request->input('email',null));

            return redirect()->back()->with(['status' => 'success', 'message' => 'Email envoyé']);
        }

        return redirect()->back()->with(['status' => 'warning', 'message' => 'Aucune inscription trouvé, problème']);

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

        return redirect()->back()->with(['status' => 'success', 'message' => 'L\'inscription a été mise à jour']);
    }

    public function edit(Request $request)
    {
        $model = $request->input('model');

        if($model == 'group')
        {
            $group = $this->groupe->find($request->input('pk'));

            if(!$group->inscriptions->isEmpty())
            {
                foreach($group->inscriptions as $inscription)
                {
                    $inscription = $this->inscription->update(['id' => $inscription->id , $request->input('name') => $request->input('value')]);
                }
            }
        }
        else
        {
            $inscription = $this->inscription->update(['id' => $request->input('pk'), $request->input('name') => $request->input('value')]);
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

        $this->inscription->delete($id);

        // If it's a group inscription and we have deleted refresh the groupe invoice and bv
        if($inscription->group_id > 0)
        {
            $this->register->makeDocuments($inscription->groupe, true);
        }

        return redirect('admin/inscription/colloque/'.$inscription->colloque_id)->with(['status' => 'success', 'message' => 'Désinscription effectué']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroygroup($id)
    {
        $group = $this->groupe->find($id);

        // Delete all inscriptions for group
        $group->inscriptions()->delete();

        // Delete the group
        $this->groupe->delete($id);

        return redirect()->back()->with(['status' => 'success', 'message' => 'Suppression du groupe effectué']);
    }

    /**
     * Restore the inscription
     *
     * @param  int  $id
     * @return Response
     */
    public function restore($id)
    {
        $this->inscription->restore($id);

        $inscription = $this->inscription->find($id);

        // Remake the documents
        $model = ($inscription->group_id ? $inscription->groupe : $inscription);

        $this->register->makeDocuments($model, true);

        // If the inscription was in a group, restore the group
        if($inscription->group_id > 0 && $inscription->groupe)
        {
            $this->groupe->restore($inscription->group_id);
        }

        return redirect()->back()->with(['status' => 'success', 'message' => 'L\'inscription a été restauré']);
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

        echo view('backend.inscriptions.partials.'.$type)->with(['colloque' => $colloque, 'user_id' => $request->input('user_id'), 'user' => $user, 'type' => $type])->__toString();
    }

}
