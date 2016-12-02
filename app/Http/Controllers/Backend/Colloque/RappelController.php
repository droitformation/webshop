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

class RappelController extends Controller
{
    protected $inscription;
    protected $colloque;
    protected $group;
    protected $rappel;
    protected $worker;

    public function __construct(InscriptionInterface $inscription, RappelInterface $rappel, RappelWorkerInterface $worker, ColloqueInterface $colloque, GroupeInterface $group)
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

        if($request->ajax())
        {
            $rappel = $inscriptions->map(function ($item, $key) {
                return ['id' => $item->id, 'name' => $item->inscrit->name, 'inscription_no' => $item->inscription_no];
            });

            return response()->json($rappel);
        }

        return view('backend.colloques.rappels.index')->with(['inscriptions' => $inscriptions,'colloque' => $colloque]);
    }

    public function make($id)
    {
        $inscriptions = $this->inscription->getRappels($id);

        if(!$inscriptions->isEmpty())
        {
            foreach($inscriptions as $inscription)
            {
                // Simple rappels
                if($inscription->group_id)
                {
                    $this->worker->generateMultiple($inscription->groupe);
                }
                else   // Multiple rappels
                {
                    $this->worker->generateSimple($inscription);
                }
            }
        }

        alert()->success('Les rappels ont été crées');

        return redirect()->back();
    }

    public function store(Request $request)
    {
        $inscription = $this->inscription->find($request->input('id'));

        if($inscription->group_id)
        {
            $this->worker->generateMultiple($inscription->groupe);
        }
        else
        {
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
    public function destroy($id)
    {
        $this->rappel->delete($id);

        alert()->success('Rappel supprimé');

        return redirect()->back();
    }

    public function send(Request $request)
    {
        // Make sur we have created all the rappels in pdf
        $job = (new MakeRappelInscription($request->input('inscriptions')));
        $this->dispatch($job);
        
        // Send the rappels via email
        $job = (new SendRappelEmail($request->input('inscriptions')))->delay(\Carbon\Carbon::now()->addMinutes(1));
        $this->dispatch($job);

        alert()->success('Rappels envoyés');

        return redirect()->back();
    }
}
