<?php namespace App\Droit\Newsletter\Entities;

use Illuminate\Database\Eloquent\Model;

class Newsletter_users extends Model {

    protected $dates    = ['activated_at'];
	protected $fillable = ['email','user_id','adresse_id','activation_token','activated_at'];

    public function subscriptions()
    {
        return $this->belongsToMany('App\Droit\Newsletter\Entities\Newsletter', 'newsletter_subscriptions', 'user_id', 'newsletter_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Droit\User\Entities\User');
    }

    public function adresse()
    {
        return $this->belongsTo('App\Droit\Adresse\Entities\Adresse');
    }
}