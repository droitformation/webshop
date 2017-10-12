<?php

namespace App\Http\Controllers\Backend\Newsletter;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\SendTestRequest;
use App\Droit\Newsletter\Repo\NewsletterCampagneInterface;
use App\Droit\Newsletter\Worker\CampagneInterface;
use App\Droit\Newsletter\Repo\NewsletterUserInterface;
use App\Droit\Newsletter\Worker\MailjetServiceInterface;
use App\Droit\Newsletter\Worker\MailgunInterface;

use App\Jobs\SendCampagne;

class SendController extends Controller
{
    protected $campagne;
    protected $worker;
    protected $mailjet;
    protected $mailgun;
    protected $subscriber;

    public function __construct(NewsletterCampagneInterface $campagne, CampagneInterface $worker, MailjetServiceInterface $mailjet, MailgunInterface $mailgun, NewsletterUserInterface $subscriber)
    {
        $this->campagne = $campagne;
        $this->worker   = $worker;
        $this->mailjet  = $mailjet;
        $this->mailgun  = $mailgun;
        $this->subscriber = $subscriber;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
        view()->share('isNewsletter',true);
    }
    
    /**
     * Send campagne
     *
     * @return \Illuminate\Http\Response
     */
    public function campagne(Request $request)
    {
        // Get campagne
        $campagne = $this->campagne->find($request->input('id'));
        $date     = $request->input('date',null);

        //set or update html
        $html   = $this->worker->html($campagne->id);
        $toSend = $date ? \Carbon\Carbon::parse($date) : \Carbon\Carbon::now()->addMinutes(15);

        $emails = $this->subscriber->getByNewsletter($campagne->newsletter_id);

        $job = (new SendCampagne($campagne,$html,$emails->toArray()))->delay($toSend);

        $job_id = $this->dispatch($job);

        // Update campagne status
        $this->campagne->update([
            'id'         => $campagne->id,
            'job_id'     => $job_id,
            'status'     => 'envoyé',
            'updated_at' => date('Y-m-d G:i:s'),
            'send_at'    => $toSend
        ]);

        alert()->success('Campagne envoyé!');

        return redirect('build/newsletter');
    }
    
    /**
     * Send test campagne
     *
     * @return \Illuminate\Http\Response
     */
    public function test(SendTestRequest $request)
    {
        $campagne = $this->campagne->find($request->input('id'));

        // GET html
        $html = $this->worker->html($campagne->id);

        $this->mailgun->setSender($campagne->newsletter->from_email,$campagne->newsletter->from_name)
            ->setRecipients([$request->input('email')])
            ->setHtml($html);

        $this->mailgun->sendTransactional('TEST | '.$campagne->sujet);

        // If we want to send via ajax just add a send_type "ajax
        $ajax = $request->input('send_type', 'normal');

        if($ajax == 'ajax') { echo 'ok'; exit; }

        alert()->success('Email de test envoyé!');

        return redirect('build/campagne/'.$campagne->id);
    }

    /**
     * Send test campagne
     *
     * @return \Illuminate\Http\Response
     */
    public function forward(SendTestRequest $request)
    {
        $campagne = $this->campagne->find($request->input('id'));
        $sujet    = ($campagne->status == 'brouillon' ? 'TEST | '.$campagne->sujet : $campagne->sujet );

        // GET html
        $html  = $this->worker->html($campagne->id);
        $email = $request->input('email');
        
        \Mail::send([], [], function ($message) use ($html,$email,$sujet) {
            $message->to($email)->subject($sujet)->setBody($html, 'text/html');
        });

        // If we want to send via ajax just add a send_type "ajax
        $ajax = $request->input('send_type', 'normal');

        if($ajax == 'ajax') {
            echo 'ok'; exit;
        }

        alert()->success('Email de test envoyé!');

        return redirect('build/campagne/'.$campagne->id);
    }
}
