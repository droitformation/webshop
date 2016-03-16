<?php

namespace App\Http\Controllers\Backend\Newsletter;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Service\UploadInterface;
use App\Droit\Newsletter\Repo\NewsletterInterface;
use App\Droit\Newsletter\Worker\ImportWorkerInterface;

class ImportController extends Controller
{
    protected $newsletter;
    protected $upload;
    protected $worker;

    public function __construct( UploadInterface $upload, NewsletterInterface $newsletter, ImportWorkerInterface $worker)
    {
        $this->newsletter = $newsletter;
        $this->upload     = $upload;
        $this->worker     = $worker;
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

    public function store(Request $request)
    {
        $file = $this->upload->upload( $request->file('file') , 'files' );
        $list  = $request->input('newsletter_id',null);

        if($file)
        {
            // path to xls
            $path = public_path('files/'.$file['name']);

            // Read uploded xls
            $results = $this->worker->read($path);

            // If the upload is not formatted correctly redirect back
            if(isset($results) && $results->isEmpty() || !array_has($results->toArray(), '0.email') )
            {
                return redirect()->back()->with(['status' => 'danger', 'message' => 'Le fichier est vide ou mal formaté']);
            }

            // Subscribe the new emails
            $this->worker->subscribe($results,$list);

            // Store imported file as csv for mailjet sync
            $this->worker->store($path);

            // Mailjet sync
            $this->worker->sync($file,$list);

            return redirect()->back()->with(['status' => 'success', 'message' => 'Import terminé']);
        }
    }

}
