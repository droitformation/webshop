<?php

namespace App\Http\Controllers\Backend\Newsletter;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Newsletter\Worker\ImportWorkerInterface;
use App\Droit\Newsletter\Repo\NewsletterListInterface;
use App\Droit\Newsletter\Repo\NewsletterEmailInterface;
use App\Droit\Service\UploadInterface;
use App\Http\Requests\ImportRequest;

class ListController extends Controller
{
    protected $list;
    protected $import;
    protected $emails;
    protected $upload;

    public function __construct( NewsletterListInterface $list, NewsletterEmailInterface $emails, ImportWorkerInterface $import, UploadInterface $upload )
    {
        $this->list     = $list;
        $this->emails   = $emails;
        $this->import   = $import;
        $this->upload   = $upload;

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
        $campagne_id = $request->input('campagne_id');
        $list        = $this->list->find($list_id);

        $this->import->send($campagne_id,$list);

        return redirect('admin/campagne/'.$campagne_id)->with( ['status' => 'success' , 'message' => 'Campagne envoyé à la liste!'] );
    }

    public function store(ImportRequest $request)
    {
        $data = $request->all();
        $file = $request->file('file');

        $file    = $this->upload->upload( $file , 'files');

        if(!$file)
        {
            throw new \App\Exceptions\FileUploadException('Upload failed');
        }

        // path to xls
        $path = public_path('files/'.$file['name']);

        // Read uploded xls
        $results = $this->import->read($path);
        $emails  = $results->lists('emails')->all();
        $list    = $this->list->create(['title' => $request->input('title')]);

        foreach($emails as $email)
        {
            $list->emails()->save(new \App\Droit\Newsletter\Entities\Newsletter_emails(['list_id' => $list->id, 'email' => $email]));
        }

        return redirect()->back()->with(['status' => 'success', 'message' => 'Fichier importé!']);
    }


    /**
     * Remove the specified resource from storage.
     * DELETE /list
     *
     * @return Response
     */
    public function destroy($id)
    {
        $this->list->delete($id);

        return redirect()->back()->with(['status' => 'success', 'message' => 'Liste supprimée']);
    }
}
