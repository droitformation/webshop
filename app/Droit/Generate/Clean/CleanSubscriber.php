<?php namespace App\Droit\Generate\Clean;

/**
 * Utility for cleaning subscribers
 */

use App\Droit\Newsletter\Worker\ImportWorkerInterface;
use App\Droit\Newsletter\Repo\NewsletterUserInterface;

class CleanSubscriber
{
    protected $import;
    protected $newsletter_user;

    public $emails;
    public $users;

    public function __construct(ImportWorkerInterface $import, NewsletterUserInterface $newsletter_user)
    {
        $this->import = $import;
        $this->newsletter_user = $newsletter_user;
    }

    public function cleanSubscribersFor($newsletter_id)
    {
        if($this->emails->isEmpty()){ return; }

        $users = $this->newsletter_user->getListWithTrashed($this->emails);

        $users->each(function ($user, $key) use ($newsletter_id) {

            // if no subscriptions delete the user
            if($user->subscriptions->isEmpty()){
                $this->newsletter_user->delete($user->email);
            }

            // if no subscription attach the newsletter subscription
            if($user->subscriptions->isEmpty()){
                // if is trashed, restore
                if ($user->trashed()) { $user->restore(); $user->fresh(); }
                $user->subscriptions()->attach($newsletter_id);
                $user->load('subscriptions');
            }

            // if subscriptions and in the list, sync the newsletter subscription to the already existing
            elseif(!$user->subscriptions->isEmpty())
            {
                // if is trashed, restore
                if ($user->trashed()) { $user->restore(); $user->fresh(); }

                $subscriptions = $user->subscriptions->pluck('id')->all();
                $subscriptions = array_merge([$newsletter_id], $subscriptions);

                $user->subscriptions()->sync(array_unique($subscriptions));
                $user->load('subscriptions');
            }
        });
    }

    public function addSubscriberFor($newsletter_id)
    {
        $this->emails->each(function ($email, $key) use ($newsletter_id) {

            $user = $this->newsletter_user->findByEmail($email);

            if(!$user){
                $user = $this->newsletter_user->create([
                    'email'            => $email,
                    'activated_at'     => \Carbon\Carbon::now()->toDateTimeString(),
                    'activation_token' => md5($email.\Carbon\Carbon::now()->toDateTimeString())
                ]);
            }

            // if no subscription attach the newsletter subscription
            if($user->subscriptions->isEmpty()){
                // if is trashed, restore
                $user->subscriptions()->attach($newsletter_id);
                $user->load('subscriptions');
            }

            // if subscriptions and in the list, sync the newsletter subscription to the already existing
            elseif(!$user->subscriptions->isEmpty())
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

    public function chargeUsers($newsletter_id)
    {
        $users = $this->newsletter_user->getByNewsletter($newsletter_id);

        $this->users = $users->pluck('email')->unique();

        return $this;
    }

    public function missing()
    {
        return  array_diff($this->emails->toArray(),$this->users->toArray());
    }

    public function extra()
    {
        return array_diff($this->users->toArray(),$this->emails->toArray());
    }

    public function deleteExtra()
    {
        $extra = $this->extra();

        if(!empty($extra)){
            foreach ($extra as $user){
                $this->newsletter_user->delete($user);
            }
        }
    }
}