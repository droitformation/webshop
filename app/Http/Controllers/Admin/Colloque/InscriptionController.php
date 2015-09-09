<?php

namespace App\Http\Controllers\Admin\Colloque;

use Illuminate\Http\Request;
use App\Droit\Colloque\Repo\ColloqueInterface;
use App\Droit\Inscription\Repo\InscriptionInterface;
use App\Droit\Inscription\Worker\InscriptionWorker;
use App\Droit\User\Repo\UserInterface;
use App\Http\Requests;
use App\Http\Requests\InscriptionRequest;
use App\Http\Controllers\Controller;
use App\Events\InscriptionWasCreated;
use App\Events\GroupeInscriptionWasRegistered;

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
    public function __construct(ColloqueInterface $colloque, InscriptionInterface $inscription, UserInterface $user, InscriptionWorker $register)
    {
        $this->colloque    = $colloque;
        $this->inscription = $inscription;
        $this->register    = $register;
        $this->user        = $user;
        $this->generator   = new \App\Droit\Generate\Pdf\PdfGenerator();
        $this->helper      = new \App\Droit\Helper\Helper();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $colloques = $this->colloque->getAll();

        return view('backend.inscriptions.index')->with(['colloques' => $colloques]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function colloque($id)
    {
        $colloque     = $this->colloque->find($id);

        $inscriptions    = $this->inscription->getByColloque($id);
        $inscriptions    = $this->helper->groupInscriptionCollection($inscriptions);
        $desinscriptions = $this->inscription->getByColloqueTrashed($id);

        return view('backend.inscriptions.index')->with(['inscriptions' => $inscriptions, 'colloque' => $colloque, 'desinscriptions' => $desinscriptions]);
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
        $groupe      = $inscription->first()->load('groupe','colloque');

        return view('backend.inscriptions.add')->with(['group_id' => $group_id, 'groupe' => $groupe->groupe, 'colloque' => $groupe->colloque]);
    }

    public function push(Request $request)
    {

        // Get all infos for inscription/participant
        $participant  = $request->input('participant');
        $price_id     = $request->input('price_id');
        $options      = $request->input('options');
        $groupes      = $request->input('groupes');
        $group_id     = $request->input('group_id');

        $colloque    = $this->colloque->find($request->input('colloque_id'));

        $data = [
            'group_id'    => $group_id,
            'colloque_id' => $colloque->id,
            'participant' => $participant,
            'price_id'    => $price_id
        ];

        // choosen options for participants
        if(isset($options))
        {
            $data['options'] = $options;
        }

        // choosen groupe of options for participants
        if(isset($groupes))
        {
            $data['groupes'] = $groupes;
        }

        // Register a new inscription
        $inscription = $this->register->register($data,$colloque->id);

        // Update counter for no inscription
        $colloque->counter = $colloque->counter + 1;
        $colloque->save();

        $groupe      = new \App\Droit\Inscription\Entities\Groupe();
        $group_user  = $groupe->find($group_id);

        event(new GroupeInscriptionWasRegistered($group_user));

        return redirect('admin/inscription/colloque/'.$colloque->id)->with(array('status' => 'success', 'message' => 'L\'inscription à bien été crée' ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(InscriptionRequest $request)
    {
        $type     = $request->input('type');
        $colloque = $this->colloque->find($request->input('colloque_id'));

        // if type simple
        if($type == 'simple')
        {
            $data        = $request->all();
            $inscription = $this->register->register($data,$colloque->id);
            $colloque->counter = $colloque->counter + 1;
            $colloque->save();

            event(new InscriptionWasCreated($inscription));
        }
        else
        {
            // Create a new group holder
            $groupe = new \App\Droit\Inscription\Entities\Groupe();

            $group_user   = $groupe->create(['colloque_id' => $colloque->id , 'user_id' => $request->input('user_id')]);

            // Get all infos for inscriptions/participants
            $participants = $request->input('participant');
            $prices       = $request->input('price_id');
            $options      = $request->input('options');
            $groupes      = $request->input('groupes');

            foreach($participants as $index => $participant)
            {
                $data = [
                    'group_id'    => $group_user->id,
                    'colloque_id' => $colloque->id,
                    'participant' => $participant,
                    'price_id'    => $prices[$index]
                ];

                // choosen options for participants
                if(isset($options[$index]))
                {
                    $data['options'] = $options[$index];
                }

                // choosen groupe of options for participants
                if(isset($groupes[$index]))
                {
                    $data['groupes'] = $groupes[$index];
                }

                // Register a new inscription
                $inscriptions[] = $this->register->register($data,$colloque->id);

                // Update counter for no inscription
                $colloque->counter = $colloque->counter + 1;
                $colloque->save();
            }

            event(new GroupeInscriptionWasRegistered($group_user));
        }

        return redirect('admin/inscription/colloque/'.$colloque->id)->with(array('status' => 'success', 'message' => 'L\'inscription à bien été crée' ));
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
        $inscription->load('colloque','user_options','groupe','participant');

        if(isset($inscription->user_options))
        {
            $inscription->user_options->load('option');
        }

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

        if($inscription->group_id > 0)
        {
            event(new GroupeInscriptionWasRegistered($inscription->load('groupe')->groupe));
        }
        else
        {
            event(new InscriptionWasCreated($inscription));
        }

        return redirect('admin/inscription/'.$inscription->colloque_id)->with(array('status' => 'success', 'message' => 'Les documents ont été mis à jour' ));
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
        $inscription = $this->inscription->update($request->all());

        $annexes     = $inscription->colloque->annexe;

        $this->generator->setInscription($inscription)->generate($annexes);

        return redirect('admin/inscription/'.$inscription->id)->with(array('status' => 'success', 'message' => 'L\'inscription a été mise à jour' ));
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

        return redirect('admin/inscription/colloque/'.$inscription->colloque_id)->with(array('status' => 'success', 'message' => 'Désinscription effectué' ));
    }

    /**
     * Inscription partial
     * @return Response
     */
    public function inscription(Request $request){

        $colloque = $this->colloque->find($request->input('colloque_id'));
        $user     = $this->user->find($request->input('user_id'));
        $type     = $request->input('type');

        echo view('backend.inscriptions.partials.'.$type)->with(['colloque' => $colloque, 'user_id' => $request->input('user_id'), 'user' => $user, 'type' => $type]);
    }

}
