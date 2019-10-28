<?php

namespace App\Http\Controllers\Backend\Sondage;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Sondage\Repo\AvisInterface;
use App\Droit\Sondage\Repo\ReponseInterface;
use App\Droit\Sondage\Repo\SondageInterface;
use App\Droit\Sondage\Worker\ReponseWorker;

class ReponseController extends Controller
{
    protected $avis;
    protected $reponse;
    protected $sondage;
    protected $worker;

    public function __construct(AvisInterface $avis, ReponseInterface $reponse, SondageInterface $sondage, ReponseWorker $worker)
    {
        $this->avis    = $avis;
        $this->reponse = $reponse;
        $this->sondage = $sondage;
        $this->worker  = $worker;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id, Request $request)
    {
        $sondage = $this->sondage->find($id);

        $isTest = $request->input('isTest',false);
        $sort   = $request->input('sort',null) ? $request->input('sort') : 'avis_id';
        
        $reponses = !$isTest ? $sondage->reponses_no_test : $sondage->reponses;
        $reponses = $sort == 'reponse_id' ? $this->worker->sortByPerson($reponses) : $this->worker->sortByQuestion($sondage);

        return view('backend.reponses.show')->with(['sondage' => $sondage, 'reponses' => $reponses, 'sort' => $sort, 'isTest' => $isTest]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->avis->delete($id);

        flash('La question a été supprimé')->success();

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function download($id, Request $request)
    {
        $sondage  = $this->sondage->find($id);
        $sort     = $request->input('sort',null) ? $request->input('sort') : 'avis_id';
        $reponses = $sort == 'reponse_id' ? $this->worker->sortByPerson($sondage->reponses) : $this->worker->sortByQuestion($sondage);

        return \PDF::loadView('templates.sondage.index', ['sondage' => $sondage, 'reponses' => $reponses, 'sort' => $sort])
            ->setPaper('a4')
            ->download('Sondage_reponse_' . $sondage->id . '.pdf');
    }
}
