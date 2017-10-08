<?php

namespace App\Http\Controllers\Backend\Newsletter;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\SendTestRequest;
use App\Droit\Newsletter\Repo\NewsletterCampagneInterface;
use App\Droit\Newsletter\Worker\CampagneInterface;
use App\Droit\Newsletter\Worker\SendgridInterface;

class SendController extends Controller
{
    protected $campagne;
    protected $worker;
    protected $mailjet;

    public function __construct(NewsletterCampagneInterface $campagne, CampagneInterface $worker, SendgridInterface $mailjet)
    {
        $this->campagne = $campagne;
        $this->worker   = $worker;
        $this->mailjet  = $mailjet;

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
        $html = $this->worker->html($campagne->id);

        $this->mailjet->setList($campagne->newsletter->list_id); // list id

        $site = isset($campagne->newsletter->site) ? $campagne->newsletter->preview.'/'.$campagne->newsletter->site->slug : 'pubdroit';

        // Sync html content to api service and send to newsletter list!
        $response = $this->mailjet->setHtml($html,$campagne->api_campagne_id, [
            'subject' => $campagne->sujet,
            'custom_unsubscribe_url' => url($site.'/unsubscribe'),
            'suppression_group_id' => null,
        ]);

        if(!$response) {
            throw new \App\Exceptions\CampagneUpdateException('Problème avec la préparation du contenu');
        }

        /*
         *  Send at specified date or delay for 15 minutes before sending just in case
         */
        $toSend = $date ? \Carbon\Carbon::parse($date)->timestamp : \Carbon\Carbon::now()->addMinutes(15)->timestamp;

        $this->mailjet->sendCampagne($campagne->api_campagne_id, $toSend);

        // Update campagne status
        $this->campagne->update(['id' => $campagne->id, 'status' => 'envoyé', 'updated_at' => date('Y-m-d G:i:s'), 'send_at' => $toSend]);

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
        $this->mailjet->setList($campagne->newsletter->list_id); // list id

        $html     = $this->worker->html($campagne->id);

        $site = isset($campagne->newsletter->site) ? $campagne->newsletter->preview.'/'.$campagne->newsletter->site->slug : 'pubdroit';

        $response = $this->mailjet->setHtml($html,$campagne->api_campagne_id, [
            'subject' => $campagne->sujet,
            'custom_unsubscribe_url' => url($site.'/unsubscribe'),
            'suppression_group_id' => null,
        ]);

        if(!$response) {
            throw new \App\Exceptions\CampagneUpdateException('Problème avec la préparation du contenu');
        }

        $result = $this->mailjet->sendTest($campagne->api_campagne_id, $request->input('email'), $campagne->sujet);

        if(!$result) {
            throw new \App\Exceptions\TestSendException('Problème avec le test');
        }

        // If we want to send via ajax just add a send_type "ajax
        $ajax = $request->input('send_type', 'normal');

        if($ajax == 'ajax') {
            echo 'ok'; exit;
        }

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
