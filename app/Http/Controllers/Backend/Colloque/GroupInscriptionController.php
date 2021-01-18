<?php

namespace App\Http\Controllers\Backend\Colloque;

use App\Droit\Inscription\Repo\GroupeInterface;
use App\Droit\Inscription\Worker\InscriptionWorkerInterface;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class GroupInscriptionController extends Controller
{
    protected $groupe;
    protected $register;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(GroupeInterface $groupe, InscriptionWorkerInterface $register)
    {
        $this->groupe   = $groupe;
        $this->register = $register;
    }

    /**
     * Display groupe edit.
     *
     * @return Response
     */
    public function show($id)
    {
        $groupe = $this->groupe->find($id);

        return view('backend.inscriptions.groupe')->with(['groupe' => $groupe]);
    }

    public function store(Request $request)
    {
        $register = new \App\Droit\Inscription\Entities\Register($request->all());
        $inscriptions = $register->addParticipant();

        $inscriptions = $inscriptions->map(function ($data){
            return $this->register->register($data, true);
        });

        // Get the group
        $group = $this->groupe->find($request->input('group_id'));

        // Remake docs
        $this->register->makeDocuments($group, true);

        flash('L\'inscription à bien été crée')->success();

        return redirect()->back();
    }

    public function update(Request $request)
    {
        // Update user for group and remake docs
        $groupe = $this->groupe->update([
            'id'      => $request->input('group_id'),
            'user_id' => $request->input('user_id')
        ]);

        $this->register->makeDocuments($groupe, true);

        flash('Le groupe a été modifié')->success();

        return redirect('admin/inscription/colloque/'.$groupe->colloque_id);
    }

    public function destroy($id)
    {
        $group = $this->groupe->find($id);

        // Delete all inscriptions for group
        $inscriptions =  $group->inscriptions;
        $inscriptions->map(function ($item, $key) {
            return $item->participant()->delete();
        });

        $group->inscriptions()->delete();
        $group->rappels()->delete();

        // Delete the group
        $this->groupe->delete($id);

        flash('Suppression du groupe effectué')->success();

        return redirect()->back();
    }

    /**
     * Restore the user
     *
     * @param  int  $id
     * @return Response
     */
    public function restore($id)
    {
        $this->groupe->restore($id);

        flash('Groupe restauré')->success();

        return redirect()->back();
    }

    public function references(Request $request, $id)
    {
        $group = $this->groupe->find($id);

        // Attach references if any
        $reference = \App\Droit\Transaction\Reference::update($group, $request->only(['reference_no','transaction_no']));

        $this->register->makeDocuments($group, true);

        flash('Références modifiés')->success();

        return redirect()->back();
    }

    public function participant(Request $request)
    {
        echo '<pre>';
        print_r($request->all());
        echo '</pre>';
        exit;
    }
}
