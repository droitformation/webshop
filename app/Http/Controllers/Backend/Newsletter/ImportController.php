<?php

namespace App\Http\Controllers\Backend\Newsletter;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmailImportRequest;
use App\Droit\Newsletter\Repo\NewsletterInterface;
use App\Droit\Newsletter\Worker\ImportWorkerInterface;

class ImportController extends Controller
{
    protected $newsletter;
    protected $worker;

    public function __construct( NewsletterInterface $newsletter, ImportWorkerInterface $worker )
    {
        $this->newsletter = $newsletter;
        $this->worker     = $worker;

        view()->share('isNewsletter',true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $newsletters = $this->newsletter->getAll();

        return view('backend.newsletter.import')->with(['newsletters' => $newsletters]);
    }

    public function store(EmailImportRequest $request)
    {
        $data = $request->all();
        $file = $request->file('file');

        $this->worker->import($data,$file);

        alert()->success('Fichier importé!');

        return redirect('build/import');
    }
}
