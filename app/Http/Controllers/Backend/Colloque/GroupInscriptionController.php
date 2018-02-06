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
        // Register a new inscription for group
        $this->register->register($request->all(), $request->input('colloque_id'));

        // Get the group
        $group = $this->groupe->find($request->input('group_id'));

        // Remake docs
        $this->register->makeDocuments($group, true);

        alert()->success('L\'inscription à bien été crée');

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

        alert()->success('Le groupe a été modifié');

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

        alert()->success('Suppression du groupe effectué');

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

        alert()->success('Groupe restauré');

        return redirect()->back();
    }

}
