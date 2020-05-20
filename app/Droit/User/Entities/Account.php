<?php namespace App\Droit\User\Entities;

class Account{

    public $user;
    protected $newsworker;
    protected $rabais;

    public function __construct($user){
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
            return $this->user->rabais->contains('title',$value->title);
        });
    }

    public function coupons($compte_id)
    {
        $used = $this->user->inscriptions->pluck('rabais_id');

        return $this->user->rabais->filter(function ($rabais, $key) use ($compte_id,$used) {
            if($rabais->type == 'colloque'){
                return $rabais->comptes->contains('id',$compte_id);
            }

            return true;
        })->reject(function ($rabais, $key) use ($used) {
            return $used->contains($rabais->id);
        });
    }

    public function used()
    {
        $used = $this->user->inscriptions->pluck('rabais_id');

        return $this->user->rabais->filter(function ($rabais, $key) use ($used) {
            return $used->contains($rabais->id);
        });
    }

    public function active()
    {
        $used = $this->user->inscriptions->pluck('rabais_id');

        return $this->user->rabais->reject(function ($rabais, $key) use ($used) {
            return $used->contains($rabais->id);
        });
    }

}