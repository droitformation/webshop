<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubscribeRequest;

use App\Droit\Newsletter\Repo\NewsletterUserInterface;
use App\Droit\Newsletter\Worker\MailjetServiceInterface;
use App\Droit\Newsletter\Repo\NewsletterInterface;
use App\Droit\Newsletter\Worker\SubscriptionWorker;
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
        SubscriptionWorker $subscribeworker,
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
        $user = $this->subscription->activate($token);

        if(!$user)
        {
            alert()->danger('Le jeton ne correspond pas ou à expiré');

            return redirect('/');
        }

        $newsletter = $this->newsletter->find($newsletter_id);

        if(!$newsletter)
        {
            alert()->danger('Cette newsletter n\'existe pas');

            return redirect('/');
        }

        //Subscribe to mailjet
        $this->worker->setList($newsletter->list_id);
        $result = $this->worker->subscribeEmailToList($user->email);

        if(!$result){

            alert()->danger('Problème');

            return redirect('/');
        }

        alert()->success('Vous êtes maintenant abonné à la newsletter');

        return redirect('/');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function subscribe(SubscribeRequest $request)
    {
        $site = $this->site->find($request->input('site_id'));
        $subscribe = $this->subscribeworker->subscribe($request->input('email'), $request->input('newsletter_id'));

        if(!$subscribe) {
            $request->session()->flash('alreadySubscribed', 'Already');
        }
        else {
            
            \Mail::send('emails.confirmation', ['site' => $site, 'token' => $subscribe->activation_token, 'newsletter_id' => $request->input('newsletter_id')], function($message) use ($subscribe) {
                $message->to($subscribe->email, $subscribe->email)->subject('Confirmation d\'inscription à la newsletter');
            });

            $request->session()->flash('confirmationSent', 'Confirmation envoyé');
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
        // find the abo
        $abonne = $this->subscription->findByEmail( $request->input('email') );

        // Sync the abos to newsletter we have
        $abonne->subscriptions()->detach($request->input('newsletter_id'));

        $newsletter = $this->newsletter->find($request->input('newsletter_id'));

        if(!$newsletter)
        {
            alert()->danger('Cette newsletter n\'existe pas');

            return redirect('/');
        }

        //Subscribe to mailjet
        $this->worker->setList($newsletter->list_id);
        
        if(!$this->worker->removeContact($abonne->email))
        {
            throw new \App\Exceptions\SubscribeUserException('Erreur synchronisation email vers mailjet');
        }

        // Delete person only if no subscription left
        if($abonne->subscriptions->isEmpty())
        {
            $this->subscription->delete($abonne->email);
        }

        $back = $request->input('return_path', '/');

        alert()->success('<strong>Vous avez été désinscrit</strong>');

        return redirect($back);
    }

    /**
     * Resend activation link email
     * POST /inscription/resend/email
     *
     * @return Response
     */
/*    public function resend(Request $request)
    {
        $subscribe = $this->subscription->findByEmail($request->input('email'));

        \Mail::send('emails.newsletter.confirmation', ['token' => $subscribe->activation_token, 'newsletter_id' => $request->input('newsletter_id')], function($message) use ($subscribe)
        {
            $message->to($subscribe->email, $subscribe->email)->subject('Inscription!');
        });

        alert()->success('<strong>Lien d\'activation envoyé</strong>');

        return redirect('/');
    }*/
}
