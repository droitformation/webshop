<?php

namespace App\Http\Controllers\Backend\Newsletter;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Newsletter\Worker\ImportWorkerInterface;
use App\Droit\Newsletter\Repo\NewsletterListInterface;
use App\Droit\Newsletter\Repo\NewsletterEmailInterface;
use App\Droit\Service\UploadInterface;
use App\Http\Requests\EmailListRequest;
use App\Http\Requests\UpdateListRequest;
use App\Http\Requests\SendListRequest;

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

    /**
     * Send test campagne
     *
     * @return \Illuminate\Http\Response
     */
    public function send(SendListRequest $request)
    {
        $list = $this->list->find($request->input('list_id'));

        $this->import->send($request->input('campagne_id'),$list);

        alert()->success('Campagne envoyé à la liste!');

        return redirect('build/newsletter');
    }

    public function store(EmailListRequest $request)
    {
        $file = $this->upload->upload( $request->file('file') , 'files');

        if(!$file)
        {
            throw new \App\Exceptions\FileUploadException('Upload failed');
        }

        // path to xls
        $path = public_path('files/'.$file['name']);

        // Read uploded xls
        $results = $this->import->read($path);

        if(isset($results) && $results->isEmpty() || !array_has($results->toArray(), '0.email') )
        {
            alert()->danger('Le fichier est vide ou mal formaté');

            return redirect()->back();
        }
        
        $list = $this->list->create([
            'title' => $request->input('title'), 
            'emails' => $results->pluck('email')->unique()->all(), 
            'specialisations' => $request->input('specialisations')]);

        alert()->success('Fichier importé!');

        return redirect('build/listes');
    }

    public function update(UpdateListRequest $request)
    {
        $data = $request->except('file');
        
        if($request->file('file'))
        {
            $file = $this->upload->upload( $request->file('file') , 'files');

            if(!$file) {
                throw new \App\Exceptions\FileUploadException('Upload failed');
            }

            // path to xls
            $path = public_path('files/'.$file['name']);
            // Read uploded xls
            $results = $this->import->read($path);

            if(isset($results) && $results->isEmpty() || !array_has($results->toArray(), '0.email') ) {
                alert()->danger('Le fichier est vide ou mal formaté');
                return redirect()->back();
            }

            $data['emails'] = $results->pluck('email')->unique()->all();
        }

        $list = $this->list->update($data);

        alert()->success('Liste mise à jour');

        return redirect()->back();
    }

    public function export(Request $request)
    {
        $list = $this->list->find($request->input('list_id'));

        $emails = $list->emails->map(function ($item) {
            return [$item->email];
        });

        \Excel::create('Export_liste_'.$list->title, function ($excel) use ($emails) {
            $excel->sheet('Export', function ($sheet) use ($emails) {
                $sheet->setOrientation('portrait');

                $sheet->rows($emails->toArray());
            });

        })->export('xls');
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

        alert()->success('Liste supprimée');

        return redirect()->back();
    }
}
