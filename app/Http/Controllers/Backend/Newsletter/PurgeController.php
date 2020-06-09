<?php

namespace App\Http\Controllers\Backend\Newsletter;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Newsletter\Repo\NewsletterInterface;
use App\Droit\Newsletter\Worker\MailjetServiceInterface;
use App\Droit\Newsletter\Worker\ImportWorkerInterface;

use App\Droit\Service\UploadInterface;

class PurgeController extends Controller
{
    protected $newsletter;
    protected $mailjet;
    protected $import;
    protected $upload;

    public function __construct(NewsletterInterface $newsletter, MailjetServiceInterface $mailjet, ImportWorkerInterface $import, UploadInterface $upload)
    {
        $this->newsletter = $newsletter;
        $this->mailjet    = $mailjet;
        $this->import     = $import;
        $this->upload     = $upload;

        view()->share('isNewsletter',true);
    }

    public function index()
    {
        $extern = config('newsletter.lists');

        $newsletters = $this->newsletter->findMultiple($extern);

        return view('backend.newsletter.lists.purge')->with(['newsletters' => $newsletters]);
    }

    /*
     * Unsubscribe in bulk from multiple lists
     * */
    public function store(Request $request)
    {
        $emails = $this->import->setFile($request->file('file'))->uploadAndRead();
        $lists  = $request->input('newsletter_id');

        $Contacts = $emails->flatten()->map(function ($email) {
            return ['Email' => $email];
        });

        $ContactsLists = collect($request->input('list_id'))->map(function ($id) {
            return ['Action' => "remove", 'ListID' => $id];
        });

    }
}
