<?php

namespace App\Http\Controllers\Backend\Sondage;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\SondageRequest;
use App\Http\Controllers\Controller;
use App\Jobs\SendSondage;
use App\Droit\Sondage\Repo\SondageInterface;
use App\Droit\Sondage\Repo\AvisInterface;
use App\Droit\Colloque\Repo\ColloqueInterface;
use App\Droit\Sondage\Repo\ReponseInterface;
use App\Droit\Newsletter\Repo\NewsletterListInterface;

class SondageController extends Controller
{
    protected $sondage;
    protected $reponse;
    protected $avis;
    protected $colloque;
    protected $list;

    public function __construct(SondageInterface $sondage, AvisInterface $avis, ColloqueInterface $colloque, ReponseInterface $reponse, NewsletterListInterface $list)
    {
        $this->sondage  = $sondage;
        $this->avis     = $avis;
        $this->colloque = $colloque;
        $this->reponse  = $reponse;
        $this->list     = $list;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $sondages  = $this->sondage->getAll();

        return view('backend.sondages.index')->with(['sondages' => $sondages]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $colloques = $this->colloque->getAllAdmin(true,false);
        
        return view('backend.sondages.create')->with(['colloques' => $colloques]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(SondageRequest $request)
    {
        $sondage = $this->sondage->create($request->all());

        if($sondage->colloque_id){
            $worker = new \App\Droit\Sondage\Worker\SondageWorker();
            $worker->createList($sondage->colloque_id);
        }

        alert()->success('Le sondage a été crée');

        return redirect('admin/sondage/'.$sondage->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $emails    = [];
        $sondage   = $this->sondage->find($id);
        $avis      = $this->avis->getAll();
        $colloques = $this->colloque->getAll(false,false);

        if($sondage->colloque_id){
            $worker = new \App\Droit\Sondage\Worker\SondageWorker();
            $emails = $worker->getEmails($sondage->colloque_id);
        }
        
        return view('backend.sondages.show')->with(['sondage' => $sondage, 'avis' => $avis, 'colloques' => $colloques, 'emails' => $emails]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, SondageRequest $request)
    {
        $sondage = $this->sondage->update($request->all());

        if($sondage->colloque_id){
            $worker = new \App\Droit\Sondage\Worker\SondageWorker();
            $worker->updateList($sondage->colloque_id);
        }

        alert()->success('Le sondage a été mis à jour');

        return redirect('admin/sondage/'.$sondage->id);
    }

    public function updateList(Request $request)
    {
        $worker = new \App\Droit\Sondage\Worker\SondageWorker();
        $worker->updateList($request->input('colloque_id'));

        alert()->success('La liste pour le sondage a été mis à jour');

        return redirect()->back();
    }

    public function createList(Request $request)
    {
        $worker = new \App\Droit\Sondage\Worker\SondageWorker();
        $worker->createList($request->input('colloque_id'));

        alert()->success('La liste pour le sondage a été crée');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->sondage->delete($id);

        alert()->success('Le sondage a été supprimé');

        return redirect('admin/sondage');
    }

    public function sorting(Request $request)
    {
        $data = $request->all();

        $sondage = $this->sondage->updateSorting($request->input('id'), $data['question_rang']);

        echo 'ok';die();
    }

    public function confirmation($id)
    {
        $sondage = $this->sondage->find($id);
        $listes  = $this->list->getForColloques();

        return view('backend.sondages.confirmation')->with(['sondage' => $sondage, 'listes' => $listes]);
    }

    public function send(Request $request)
    {
        $sondage = $this->sondage->find($request->input('sondage_id'));
        
        // Test if there are questions in sondage
        if($sondage->avis->isEmpty()){
            throw new \App\Exceptions\MissingException('Aucune question dans ce sondage!');
        }
        
        if($request->input('list_id',null)){
            $list    = $this->list->find($request->input('list_id'));
            $emails  = $list->emails->pluck('email');

            if(!empty($emails)){
                foreach ($emails as $email) {
                    $this->dispatch(new SendSondage($sondage, ['email' => $email]));
                }
            }
        }
        else{
            $this->dispatch(new SendSondage($sondage, $request->except(['_token','sondage_id'])));
        }

        alert()->success('Le sondage a été envoyé');

        return redirect('admin/sondage');
    }
}
