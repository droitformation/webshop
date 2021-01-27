<?php

namespace App\Http\Controllers\Backend\Newsletter;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Newsletter\Repo\NewsletterInterface;
use App\Droit\Newsletter\Worker\MailjetServiceInterface;
use App\Droit\Newsletter\Worker\ImportWorkerInterface;
use App\Droit\Newsletter\Worker\SubscriptionWorkerInterface;

class PurgeController extends Controller
{
    protected $newsletter;
    protected $mailjet;
    protected $import;
    protected $worker;

    public function __construct(NewsletterInterface $newsletter, MailjetServiceInterface $mailjet, ImportWorkerInterface $import, SubscriptionWorkerInterface $worker)
    {
        $this->newsletter = $newsletter;
        $this->mailjet    = $mailjet;
        $this->import     = $import;
        $this->worker     = $worker;

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

        $ids = $request->input('newsletter_id');

        $results = $emails->flatten()->map(function ($email) use ($ids) {

            $subscription = $this->worker->exist($email);

            if($subscription){
                $this->worker->unsubscribe($subscription,$ids);
            }
        });

        flash('Emails désincrits')->success();

        return redirect()->back();
    }

    public function invalid(Request $request)
    {
        $purger = \App::make('\App\Droit\Newsletter\Service\Purger');

        $emails = $this->import->setFile($request->file('file'))->uploadAndRead();

        $results = $emails->flatten()->map(function ($email) use ($purger) {

            $results  = $purger->verify($email);
            $response = $purger->status($results);

            return ['email' => $email, 'status' => $response];
        });

        return \Excel::download(new \App\Exports\StatusEmailExport($results), 'status_email' . date('dmy').'.xlsx');

    }
}
