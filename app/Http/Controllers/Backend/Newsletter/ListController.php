<?php

namespace App\Http\Controllers\Backend\Newsletter;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Newsletter\Worker\ImportWorkerInterface;
use App\Droit\Newsletter\Repo\NewsletterListInterface;
use App\Droit\Newsletter\Repo\NewsletterEmailInterface;

class ListController extends Controller
{
    protected $list;
    protected $import;
    protected $emails;

    public function __construct( NewsletterListInterface $list, NewsletterEmailInterface $emails, ImportWorkerInterface $import)
    {
        $this->list     = $list;
        $this->emails   = $emails;
        $this->import   = $import;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lists = $this->list->getAll();

        return view('backend.newsletter.lists.import')->with(['lists' => $lists]);
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

        return view('backend.newsletter.lists.emails')->with(['lists' => $lists, 'list' => $list]);
    }

    /**
     * Send test campagne
     *
     * @return \Illuminate\Http\Response
     */
    public function send(Request $request)
    {
        $list_id     = $request->input('list_id');
        $list        = $this->list->find($list_id);
        $campagne_id = $request->input('campagne_id');

        $this->import->send($campagne_id,$list);

        return redirect('admin/campagne/'.$campagne_id)->with( ['status' => 'success' , 'message' => 'Campagne envoyé à la liste!'] );
    }

}
