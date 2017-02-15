<?php namespace App\Droit\Newsletter\Worker;

use App\Droit\Newsletter\Repo\NewsletterUserInterface;

class SubscriptionWorker{
    
    protected $subscription;

    public function __construct(NewsletterUserInterface $subscription)
    {
        $this->subscription = $subscription;
    }

    public function subscribe($email,$newsletter_id)
    {
        $subscriber = $this->exist($email);

        // If not subscriber found make one
        if(!$subscriber){
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
}