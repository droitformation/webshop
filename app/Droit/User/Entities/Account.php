<?php namespace App\Droit\User\Entities;

class Account{

    public $user;
    protected $newsworker;
    protected $rabais;

    public function __construct(\App\Droit\User\Entities\User $user){
        $this->user = $user;
        $this->newsworker = \App::make('newsworker');
        $this->rabais =  \App::make('App\Droit\Inscription\Repo\RabaisInterface');
    }

    public function subscriptions()
    {
        $emails = array_merge([$this->user->email], isset($this->user->adresses) ? $this->user->adresses->pluck('email')->all() : []);

        return $this->newsworker->hasSubscriptions(array_unique($emails))->groupBy('email')->map(function($subscription,$key){
            return $subscription->pluck('subscriptions')->flatten(1)->unique('list_id');
        });
    }

    public function rabais()
    {
        $rabais = $this->rabais->getAll();

        return $rabais->reject(function ($value, $key) {
            return $this->user->used_rabais->contains('title',$value->title) || $this->user->rabais->contains('title',$value->title);
        });
    }

}