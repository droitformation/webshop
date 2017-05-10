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
        $sondage   = $this->sondage->find($id);
        $avis      = $this->avis->getAll();
        $colloques = $this->colloque->getAll(false,false);
        
        return view('backend.sondages.show')->with(['sondage' => $sondage, 'avis' => $avis, 'colloques' => $colloques]);
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

        alert()->success('Le sondage a été mis à jour');

        return redirect('admin/sondage/'.$sondage->id);
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
        $listes  = $this->list->getAll();

        return view('backend.sondages.confirmation')->with(['sondage' => $sondage, 'listes' => $listes]);
    }

    public function send(Request $request)
    {
        $sondage = $this->sondage->find($request->input('sondage_id'));
        $list    = $this->list->find($request->input('list_id'));

        $emails = $list->emails->pluck('email');
        
        if(!empty($emails)){
            foreach ($emails as $email) {
                $this->dispatch(new SendSondage($sondage, ['email' => $email]));
            }
        }

        alert()->success('Le sondage a été envoyé');

        return redirect('admin/sondage');
    }
}
