<?php

namespace App\Http\Controllers\Backend\Colloque;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Inscription\Repo\RappelInterface;
use App\Droit\Inscription\Worker\RappelWorkerInterface;
use App\Droit\Colloque\Repo\ColloqueInterface;
use App\Droit\Inscription\Repo\InscriptionInterface;
use App\Droit\Inscription\Repo\GroupeInterface;
use App\Jobs\SendRappelEmail;
use App\Jobs\MakeRappelInscription;
use App\Jobs\NotifyJobFinished;

class RappelController extends Controller
{
    protected $inscription;
    protected $colloque;
    protected $group;
    protected $rappel;
    protected $worker;
    protected $user;

    public function __construct(
        InscriptionInterface $inscription,
        RappelInterface $rappel,
        RappelWorkerInterface $worker,
        ColloqueInterface $colloque,
        GroupeInterface $group
    )
    {
        $this->inscription = $inscription;
        $this->colloque    = $colloque;
        $this->group       = $group;
        $this->rappel      = $rappel;
        $this->worker      = $worker;
    }

    /**
     * Rappels list
     * By colloque: colloque_id, type (simple or multiple), paginate
     * @param  $id
     * @return Response
     */
    public function rappels(Request $request, $id)
    {
        $colloque     = $this->colloque->find($id);
        $inscriptions = $this->inscription->getRappels($id);

        if($request->ajax()) {
            $rappel = $inscriptions->map(function ($item, $key) {
                return ['id' => $item->id, 'name' => $item->inscrit->name, 'inscription_no' => $item->inscription_no];
            });

            return response()->json($rappel);
        }

        $files = \File::glob('files/colloques/rappel/archives/pdfrappel_'.$colloque->id.'-*.pdf');

        $archives = !empty($files) ? collect($files)->map(function ($file, $key) use ($id) {
            $get = new \App\Droit\Inscription\Entities\Archive($file,$id);
            return $get->archives();
        })->reject(function ($value, $key) {
            return empty($value);
        }) : collect([]);

        return view('backend.inscriptions.rappels.index')->with(['inscriptions' => $inscriptions,'colloque' => $colloque, 'archives' => $archives]);
    }

    public function make(Request $request)
    {
        $inscriptions = $this->inscription->getRappels($request->input('colloque_id'));

        $this->worker->make($inscriptions, $request->input('more',false));

            // Make sur we have created all the rappels in pdf
   /*         $job = (new MakeRappelInscription($inscriptions->pluck('id')->all()));
            $this->dispatch($job);

            $job = (new NotifyJobFinished('Les rappels pour le colloque ont été crées.'));
            $this->dispatch($job);
        }*/

        alert()->success('Rappels crées.');

        return redirect()->back();
    }

    public function store(Request $request)
    {
        $inscription = $this->inscription->find($request->input('id'));

        if($inscription->group_id)
        {
            $this->worker->generateMultiple($inscription->groupe);
        }
        else {
            $this->worker->generateSimple($inscription);
        }

        alert()->success('Le rappel a été crée');

        return redirect()->back();
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $this->rappel->delete($id);

        if($request->ajax())
        {
            $inscription = $this->inscription->find($request->input('item'));

            $list = $inscription->group_id ? $inscription->groupe->rappel_list : $inscription->rappel_list;
            return ['rappels' => $list];
        }
        
        return redirect()->back('Rappel supprimé');

    }

    public function send(Request $request)
    {
        // Make sur we have created all the rappels in pdf
        //$job = (new MakeRappelInscription($request->input('inscriptions')));
        //$this->dispatch($job);

        $inscriptions = $this->inscription->getMultiple($request->input('inscriptions'));

        $this->worker->make($inscriptions);

        // Send the rappels via email
        $job = (new SendRappelEmail($request->input('inscriptions')))->delay(\Carbon\Carbon::now()->addMinutes(1));
        $this->dispatch($job);

        alert()->success('Rappels envoyés');

        return redirect()->back();
    }

    public function generate(Request $request)
    {
        $inscription = $this->inscription->find($request->input('id'));

        if($inscription->group_id) {
            $this->worker->generateMultiple($inscription->groupe);
            $list = $inscription->groupe->rappel_list;
        }
        else{
            $this->worker->generateSimple($inscription);
            $list = $inscription->rappel_list;
        }

        return ['rappels' => $list];
    }
}
