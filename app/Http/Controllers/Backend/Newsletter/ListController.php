<?php

namespace App\Http\Controllers\Backend\Newsletter;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Newsletter\Worker\ImportWorkerInterface;
use App\Droit\Newsletter\Repo\NewsletterListInterface;
use App\Droit\Newsletter\Repo\NewsletterEmailInterface;
use App\Droit\Newsletter\Repo\NewsletterCampagneInterface;
use App\Droit\Newsletter\Repo\NewsletterTrackingInterface;

use App\Droit\Service\UploadInterface;
use App\Http\Requests\EmailListRequest;
use App\Http\Requests\UpdateListRequest;
use App\Http\Requests\SendListRequest;

class ListController extends Controller
{
    protected $list;
    protected $import;
    protected $campagne;
    protected $emails;
    protected $upload;
    protected $tracking;

    public function __construct( 
        NewsletterListInterface $list, 
        NewsletterEmailInterface $emails,
        NewsletterCampagneInterface $campagne,
        ImportWorkerInterface $import, 
        UploadInterface $upload ,
        NewsletterTrackingInterface $tracking
    )
    {
        $this->list     = $list;
        $this->emails   = $emails;
        $this->campagne = $campagne;
        $this->import   = $import;
        $this->upload   = $upload;
        $this->tracking = $tracking;

        setlocale(LC_ALL, 'fr_FR.UTF-8');

        view()->share('isNewsletter',true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lists = $this->list->getAll();

        return view('backend.newsletter.lists.index')->with(['lists' => $lists]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lists = $this->list->getAll();
        $list  = $this->list->find($id);

        return view('backend.newsletter.lists.show')->with(['lists' => $lists, 'list' => $list]);
    }

    public function store(EmailListRequest $request)
    {
        $emails = $this->import->setFile($request->file('file'))->uploadAndRead();
        
        $list = $this->list->create([
            'title' => $request->input('title'),
            'emails' => $emails->flatten()->all(),
            'specialisations' => $request->input('specialisations')
        ]);

        flash('Fichier importé!')->success();

        return redirect()->back();
    }

    public function update(UpdateListRequest $request)
    {
        $data = $request->except('file');

        if($request->file('file'))
        {
            $emails = $this->import->setFile($request->file('file'))->uploadAndRead();
            $data['emails'] = $emails->flatten()->all();
        }

        $this->list->update($data);

        flash('Liste mise à jour')->success();

        return redirect()->back();
    }

    public function destroy($id)
    {
        $this->list->delete($id);

        flash('Liste supprimée')->success();

        return redirect()->back();
    }

    /**
     * Send test campagne
     *
     * @return \Illuminate\Http\Response
     */
    public function send(SendListRequest $request)
    {
        $list = $this->list->find($request->input('list_id'));

        if(!$list) {
            flash('Les emails de la liste n\'ont pas pu être récupérés')->error();
            return redirect('build/newsletter');
        }
        
        $results = $this->import->send($request->input('campagne_id'),$list);

        // add mailgun tracking
        $this->tracking->logSent([
            'campagne_id'    => $request->input('campagne_id'),
            'send_at'        => \Carbon\Carbon::now()->toDateTimeString(),
            'list_id'        => $request->input('list_id'),
        ]);

        // Update campagne status
        $this->campagne->update([
            'id'         => $request->input('campagne_id'),
            'status'     => 'envoyé',
            'updated_at' => date('Y-m-d G:i:s'),
            'send_at'    => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        flash('Campagne envoyé à la liste! Contrôler l\'envoi via le tracking (après quelques minutes) ou sur le service externe mailgun.')->success();

        return redirect('build/newsletter');
    }

    public function confirmation($id)
    {
        $campagne = $this->campagne->find($id);
        $listes   = $this->list->getAll();

        return view('backend.newsletter.lists.confirmation')->with(['campagne' => $campagne, 'listes' => $listes]);
    }

    public function export(Request $request)
    {
        $list = $this->list->find($request->input('list_id'));

        return \Excel::download(new \App\Exports\ListExport($list), 'Export_liste_'.$list->title.'.xlsx');
    }
}
