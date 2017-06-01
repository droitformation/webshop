<?php namespace App\Droit\Newsletter\Worker;

use App\Droit\Newsletter\Repo\NewsletterInterface;
use App\Droit\Newsletter\Repo\NewsletterUserInterface;
use App\Droit\Newsletter\Worker\MailjetServiceInterface;

class SubscriptionWorker{
    
    protected $newsletter;
    protected $subscription;
    protected $mailjet;

    public function __construct(NewsletterInterface $newsletter, NewsletterUserInterface $subscription, MailjetServiceInterface $mailjet)
    {
        $this->newsletter = $newsletter;
        $this->subscription = $subscription;
        $this->mailjet = $mailjet;
    }

    public function subscribe($email,$newsletter_id)
    {
        $subscriber = $this->exist($email);

        // If not subscriber found make one
        if(!$subscriber || !$subscriber->activation_token){
            $subscriber = $this->make($email,$newsletter_id);
        }

        // If not activated return found subscriber to resend activation link
        if(!$subscriber->activated_at) {
            return $subscriber;
        }

        // If the subscriber is already subscribed
        if($subscriber->subscriptions->contains('id',$newsletter_id)) {
            return false;
        }

        return $subscriber;
    }

    public function make($email,$newsletter_id)
    {
        return $this->subscription->create([
            'email' => $email,
            'activation_token' => md5($email.\Carbon\Carbon::now()),
            'newsletter_id' => $newsletter_id
        ]);
    }

    public function exist($email)
    {
        return $this->subscription->findByEmail($email);
    }

    public function unsubscribe($email,$newsletter_id)
    {
        $subscriber = $this->exist($email);
        $subscriber->subscriptions()->detach($newsletter_id);

        $newsletter = $this->newsletter->find($newsletter_id);

        if($newsletter){
            $this->mailjet->setList($newsletter->list_id);
            // Remove subscriber from list mailjet
            if(!$this->mailjet->removeContact($email)) {
                throw new \App\Exceptions\DeleteUserException('Erreur avec la suppression de l\'abonnés sur mailjet');
            }
        }

        $subscriber->load('subscriptions');

        if($subscriber->subscriptions->isEmpty()){
            $this->subscription->delete($subscriber->email);
        }
    }
}