<?php

namespace App\Http\Controllers\Backend\Newsletter;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Newsletter\Repo\NewsletterInterface;
use App\Droit\Service\UploadInterface;
use App\Droit\Newsletter\Worker\MailjetInterface;
use App\Droit\Newsletter\Repo\NewsletterUserInterface;

class ImportController extends Controller
{
    protected $newsletter;
    protected $upload;
    protected $mailjet;
    protected $subscriber;

    public function __construct( UploadInterface $upload, MailjetInterface $mailjet, NewsletterUserInterface $subscriber, NewsletterInterface $newsletter)
    {
        $this->newsletter = $newsletter;
        $this->upload     = $upload;
        $this->mailjet    = $mailjet;
        $this->subscriber = $subscriber;
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
        $files = $this->upload->upload( $request->file('file') , 'files' );
        $list  = $request->input('newsletter_id');

        if($files)
        {
            // path to csv
            $path = public_path('files/'.$files['name']);

            $result = \Excel::load($path, function($reader) {
                $reader->ignoreEmpty();
                $reader->setSeparator('\r\n');
            })->get();

            foreach($result as $email)
            {
                $subscriber = $this->subscriber->findByEmail($email->email);

                if(!$subscriber)
                {
                    $subscriber = $this->subscriber->create([
                        'email'         => $email->email,
                        'activated_at'  => \Carbon\Carbon::now(),
                        'newsletter_id' => $list
                    ]);
                }

                $relation = $subscriber->subscriptions()->lists('newsletter_id');
                $contains = $relation->contains($list);

                if(!$contains)
                {
                    $subscriber->subscriptions()->attach($list);
                }
            }

            // Convert to csv
            \Excel::load($path)->store('csv', public_path('files/import'));

            // Import csv to mailjet
            $newsletter =  $this->newsletter->find($list);
            $this->mailjet->setList($newsletter->list_id); // testing list

            $filename = preg_replace('/\\.[^.\\s]{3,4}$/', '', $files['name']);

            $dataID   = $this->mailjet->uploadCSVContactslistData(file_get_contents(public_path('files/import/'.$filename.'.csv')));
            $response = $this->mailjet->importCSVContactslistData($dataID->ID);

            return redirect()->back()->with(array('status' => 'success', 'message' => 'Import terminé' ));
        }
    }

}
