<?php

namespace App\Http\Controllers\Api;

use App\Droit\Colloque\Repo\ColloqueInterface;
use App\Droit\Inscription\Repo\InscriptionInterface;
use App\Droit\Inscription\Worker\InscriptionWorkerInterface;
use App\Droit\User\Repo\UserInterface;
use App\Droit\Inscription\Repo\GroupeInterface;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class InscriptionController extends Controller
{
    protected $colloque;
    protected $inscription;
    protected $register;
    protected $user;
    protected $groupe;
    
    public function __construct(ColloqueInterface $colloque, InscriptionInterface $inscription, UserInterface $user, InscriptionWorkerInterface $register, GroupeInterface $groupe)
    {
        $this->colloque    = $colloque;
        $this->inscription = $inscription;
        $this->register    = $register;
        $this->user        = $user;
        $this->groupe      = $groupe;
    }

    /**
     * Generate the inscription invoice pdf
     *
     * @param  $request
     * @return array 
     */
    public function generate(Request $request)
    {
        $inscription = $this->inscription->find($request->input('id'));

        $model = $inscription->group_id ? $inscription->groupe : $inscription;

        $this->register->makeDocuments($model, true);

        return ['link' => $model->doc_facture];
    }

    /*
     * Edit via ajax x-editable in payed partial
     * */
    public function edit(Request $request)
    {
        $data = $request->all();

        // List inscription ids to update
        $list = $data['model'] == 'group' ? $this->groupe->find($data['pk'])->inscriptions->pluck('id') : collect([$data['pk']]);

        // update all of them
        $inscriptions = $list->map(function ($id, $key) use ($data) {
            return $this->inscription->updateColumn(['id' => $id , $data['name'] => $data['value']]);
        });

        return response()->json(['OK' => 200, 'etat' => $inscriptions->first()->status_name['status'] ,'color' => $inscriptions->first()->status_name['color']]);
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

    /**
     * Inscription presence
     * @return Response
     */
    public function presence(Request $request)
    {
        $this->inscription->updateColumn(['id' => $request->input('id') , 'present' => $request->input('presence') ? 1 : null]);

        echo 'ok';exit;
    }

}
