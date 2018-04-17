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

        // Filter for occurrences
        $inscriptions = !$colloque->occurrences->isEmpty() ? $inscriptions->reject(function ($inscription, $key) {
            return isset($inscription->occurrence_done) && $inscription->occurrence_done->isEmpty();
        }) : $inscriptions;

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

        $rappels_bv = 'files/colloques/rappel/rappels_colloque_'.$colloque->id.'.pdf';
        $rappels_bv = \File::exists(public_path($rappels_bv)) ? $rappels_bv : null;

        return view('backend.inscriptions.rappels.index')->with(['inscriptions' => $inscriptions,'colloque' => $colloque, 'archives' => $archives, 'rappels_bv' => $rappels_bv]);
    }

    public function confirmation($id)
    {
        $colloque     = $this->colloque->find($id);
        $inscriptions = $this->inscription->getRappels($id);

        // Filter for occurrences
        $inscriptions = !$colloque->occurrences->isEmpty() ? $inscriptions->reject(function ($inscription, $key) {
            return $inscription->occurrence_done->isEmpty();
        }) : $inscriptions;

        return view('backend.inscriptions.rappels.confirmation')->with(['inscriptions' => $inscriptions,'colloque' => $colloque]);
    }

    public function make(Request $request)
    {
        $inscriptions = $this->inscription->getRappels($request->input('colloque_id'));

        $this->worker->make($inscriptions, $request->input('more',false));

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

    public function toprint($id)
    {
        $colloque     = $this->colloque->find($id);
        $inscriptions = $this->inscription->getRappels($id);

        // Filter for occurrences
        $inscriptions = !$colloque->occurrences->isEmpty() ? $inscriptions->reject(function ($inscription, $key) {
            return isset($inscription->occurrence_done) && $inscription->occurrence_done->isEmpty();
        }) : $inscriptions;

        $file = $this->worker->generateWithBv($inscriptions);

        return response()->download($file);
    }

    public function send(Request $request)
    {
        $inscriptions = $this->inscription->getMultiple($request->input('inscriptions'));

        if(!$inscriptions->isEmpty()){

            $this->worker->make($inscriptions);

            foreach ($inscriptions as $inscription){
                // Send the rappels via email
                $job = (new SendRappelEmail($inscription))->delay(\Carbon\Carbon::now()->addMinutes(1));
                $this->dispatch($job);
            }
        }

        alert()->success('Rappels envoyés');

        return redirect('admin/inscription/rappels/'.$request->input('colloque_id'));exit;
    }

    public function generate(Request $request)
    {
        $inscription = $this->inscription->find($request->input('id'));

        $print = $request->input('print',false);

        if($inscription->group_id) {
            $this->worker->generateMultiple($inscription->groupe, $print);
            $list = $inscription->groupe->rappel_list;
        }
        else{
            $this->worker->generateSimple($inscription, $print);
            $list = $inscription->rappel_list;
        }

        return ['rappels' => $list];
    }

    public function show($id)
    {
        $generator = \App::make('App\Droit\Generate\Pdf\PdfGeneratorInterface');

        $rappel = $this->rappel->find($id);

        if($rappel->group_id > 0) {
            $model = $rappel->groupe;
        }
        else{
            $model = $rappel->inscription;
        }

        $generator->stream = true;
        $generator->setPrint(true);

        return $generator->make('facture', $model, $rappel);
    }


}
