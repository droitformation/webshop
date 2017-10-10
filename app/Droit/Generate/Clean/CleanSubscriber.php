<?php namespace App\Droit\Generate\Clean;

/**
 * Utility for cleaning subscribers
 */

use App\Droit\Newsletter\Worker\ImportWorkerInterface;
use App\Droit\Newsletter\Repo\NewsletterUserInterface;

class CleanSubscriber
{
    protected $import;
    protected $emails;
    protected $newsletter_user;

    public function __construct(ImportWorkerInterface $import, NewsletterUserInterface $newsletter_user)
    {
        $this->import = $import;
        $this->newsletter_user = $newsletter_user;
    }

    public function cleanSubscribersFor($newsletter_id)
    {
        $users = $this->newsletter_user->getAll();

        if($users->isEmpty()){ return; }

        $users->each(function ($user, $key) use ($newsletter_id) {

            // if not subscriptions and not in the list, delete the subscription
            if($user->subscriptions->isEmpty() && !$this->emails->contains($user->email)){
                $this->newsletter_user->delete($user->email);
            }

            // if not subscriptions and in the list, attach the newsletter subscription
            elseif($user->subscriptions->isEmpty() && $this->emails->contains($user->email)){
                $user->subscriptions()->attach($newsletter_id);
                $user->load('subscriptions');
            }

            // if subscriptions and in the list, sync the newsletter subscription to the already existing
            elseif(!$user->subscriptions->isEmpty() && $this->emails->contains($user->email))
            {
                $subscriptions = $user->subscriptions->pluck('id')->all();
                $subscriptions = array_merge([$newsletter_id], $subscriptions);

                $user->subscriptions()->sync(array_unique($subscriptions));
                $user->load('subscriptions');
            }
        });
    }

    public function chargeEmailsFrom($file)
    {
        $emails = $this->import->read($file);

        $this->emails = $emails->pluck('email');

        return $this;
    }
    
}