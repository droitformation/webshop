<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubscribeRequest;

use App\Droit\Newsletter\Repo\NewsletterUserInterface;
use App\Droit\Newsletter\Worker\MailjetInterface;
use App\Droit\Site\Repo\SiteInterface;
use App\Droit\User\Repo\UserInterface;
use App\Droit\Adresse\Repo\AdresseInterface;

class SubscribeController extends Controller
{
    protected $subscription;
    protected $site;
    protected $worker;
    protected $user;
    protected $adresse;

    public function __construct(MailjetInterface $worker, NewsletterUserInterface $subscription,  SiteInterface $site, UserInterface $user, AdresseInterface $adresse)
    {
        $this->worker        = $worker;
        $this->subscription  = $subscription;
        $this->site          = $site;
        $this->user          = $user;
        $this->adresse       = $adresse;
    }

    /**
     * Activate newsletter abo
     * GET //activation
     *
     * @return Response
     */
    public function activation($token)
    {
        // Activate the email on the website
        $user = $this->subscription->activate($token);

        if(!$user)
        {
            return redirect('/')->with(['status' => 'danger', 'jeton' => true ,'message' => 'Le jeton ne correspond pas ou à expiré']);
        }

        //Subscribe to mailjet
        $this->worker->subscribeEmailToList( $user->email );

        return redirect('/')->with(['status' => 'success', 'message' => 'Vous êtes maintenant abonné à la newsletter en droit du travail']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function subscribe(SubscribeRequest $request)
    {
        $email = $this->subscription->findSubscription($request->newsletter_id,$request->email);

        if($email)
        {
            $messages = ['status' => 'warning', 'message' => 'Cet email existe déjà'];

            if($email->activated_at == NULL)
            {
                $messages['resend'] = true;
            }

            return redirect()->back()->with($messages);
        }
        
        $data = ['email' => $request->input('email'), 'activation_token' => md5($request->email.\Carbon\Carbon::now())];
        $user = $this->findUserExist($email);
        $data = $user ? $data + $user : $data;

        // Subscribe user with activation token to website list and sync newsletter abos
        $suscribe = $this->subscription->create($data);

        $suscribe->subscriptions()->attach($request->newsletter_id);

        $site = $this->site->find($request->site_id);

        \Mail::send('emails.confirmation', ['token' => $suscribe->activation_token, 'site' => $site], function($message) use ($suscribe)
        {
            $message->to($suscribe->email, $suscribe->email)->subject('Inscription!');
        });

        return redirect('/')
            ->with([
                'status'  => 'success',
                'message' => '<strong>Merci pour votre inscription!</strong><br/>Veuillez confirmer votre adresse email en cliquant le lien qui vous a été envoyé par email'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function unsubscribe(SubscribeRequest $request)
    {
        // find the abo
        $abonne = $this->subscription->findByEmail( $request->email );

        // Sync the abos to newsletter we have
        $abonne->subscriptions()->detach($request->newsletter_id);

        if(!$this->worker->removeContact($abonne->email))
        {
            throw new \App\Exceptions\SubscribeUserException('Erreur synchronisation email vers mailjet');
        }

        $this->subscription->delete($abonne->email);

        return redirect()->back()->with(array('status' => 'success', 'message' => '<strong>Vous avez été désinscrit</strong>'));
    }

    public function findUserExist($email)
    {
        $user    = $this->user->findByEmail($email);
        $adresse = $this->adresse->findByEmail($email);

        if($user){
            return ['user_id' => $user->id];
        }

        if($adresse){
            return ['adresse_id' => $adresse->id];
        }

        return null;

    }
}
