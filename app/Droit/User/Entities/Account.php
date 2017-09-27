<?php namespace App\Droit\User\Entities;

class Account{

    public $user;
    protected $newsworker;

    public function __construct(\App\Droit\User\Entities\User $user){
        $this->user = $user;
        $this->newsworker = \App::make('newsworker');
    }

    public function subscriptions()
    {
        $emails = array_merge([$this->user->email], isset($this->user->adresses) ? $this->user->adresses->pluck('email')->all() : []);

        return $this->newsworker->hasSubscriptions(array_unique($emails))->groupBy('email')->map(function($subscription,$key){
            return $subscription->pluck('subscriptions')->flatten(1)->unique('list_id');
        });
    }

}