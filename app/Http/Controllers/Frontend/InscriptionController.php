<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubscribeRequest;

use App\Droit\Newsletter\Repo\NewsletterUserInterface;
use App\Droit\Newsletter\Worker\MailjetServiceInterface;
use App\Droit\Newsletter\Repo\NewsletterInterface;
use App\Droit\Newsletter\Worker\SubscriptionWorkerInterface;
use App\Droit\Site\Repo\SiteInterface;


class InscriptionController extends Controller
{
    protected $subscription;
    protected $worker;
    protected $newsletter;
    protected $subscribeworker;
    protected $site;

    public function __construct(
        MailjetServiceInterface $worker,
        NewsletterUserInterface $subscription,
        NewsletterInterface $newsletter,
        SubscriptionWorkerInterface $subscribeworker,
        SiteInterface $site
    )
    {
        $this->worker        = $worker;
        $this->subscription  = $subscription;
        $this->newsletter    = $newsletter;
        $this->subscribeworker  = $subscribeworker;
        $this->site           = $site;
    }

    /**
     * Activate newsletter abo
     * GET //activation
     *
     * @return Response
     */
    public function activation($token,$newsletter_id)
    {
        // Activate the email on the website
        $subscriber = $this->subscription->activate($token);
        $newsletter = $this->newsletter->find($newsletter_id);

        if(!$newsletter) { abort(404); }
        
        if(!$subscriber){
            alert()->success('Le jeton ne correspond pas ou a expiré');
            return redirect($newsletter->site->url);
        }

        //Subscribe to mailjet
        $this->subscribeworker->subscribe($subscriber,[$newsletter_id]);

        alert()->success('Vous êtes maintenant abonné à la newsletter');
        return redirect($newsletter->site->url);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function subscribe(SubscribeRequest $request)
    {
        $site       = $this->site->find($request->input('site_id'));
        $subscribe  = $this->subscribeworker->activate($request->input('email'), $request->input('newsletter_id'));
        $newsletter = $this->newsletter->find($request->input('newsletter_id'));

        if(!$subscribe) {
            alert()->danger('<strong>Vous êtes déjà inscrit à cettte newsletter</strong>');
        }
        else {

            \Mail::send('emails.confirmation', ['site' => $site, 'token' => $subscribe->activation_token, 'newsletter_id' => $request->input('newsletter_id')], function($message) use ($subscribe,$newsletter) {
                $message->from($newsletter->from_email, $newsletter->from_name);
                $message->to($subscribe->email, $subscribe->email)->subject('Confirmation d\'inscription à la newsletter');
            });

            alert()->success('<strong>Veuillez confirmer votre adresse email en cliquant le lien qui vous a été envoyé par email</strong>');
        }

        if(isset($site)) {
            return redirect($site->slug);
        }

        return redirect($request->input('return_path', '/'));
    }

    /**
     * Remove the specified resource from storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function unsubscribe(SubscribeRequest $request)
    {
        // find the abo and newsletter
        $subscriber = $this->subscription->findByEmail( $request->input('email') );
        $newsletter = $this->newsletter->find($request->input('newsletter_id'));

        if(!$newsletter || !$subscriber) {
            $msg = !$newsletter ? 'Cette newsletter n\'existe pas': 'L\'abonnée n\'existe pas';
            alert()->danger($msg);
            return redirect($request->input('return_path', '/').'/unsubscribe');
        }

        $this->subscribeworker->unsubscribe($subscriber,[$newsletter->id]);

        alert()->success('<strong>Vous avez été désinscrit</strong>');

        return redirect($request->input('return_path', '/'));
    }
}
