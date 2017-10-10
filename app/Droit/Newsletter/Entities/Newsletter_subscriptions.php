<?php namespace App\Droit\Newsletter\Entities;

use Illuminate\Database\Eloquent\Model;

class Newsletter_subscriptions extends Model {

    public $timestamps = false;

	protected $fillable = ['user_id','newsletter_id'];

    public function newsletter()
    {
        return $this->hasOne('App\Droit\Newsletter\Entities\Newsletter');
    }

    public function user()
    {
        return $this->hasOne('App\Droit\Newsletter\Entities\Newsletter_users','id','user_id')->whereNotNull('activated_at');
    }

    public function subscriptions()
    {
        return $this->belongsToMany('App\Droit\Newsletter\Entities\Newsletter', 'newsletter_subscriptions', 'user_id', 'newsletter_id');
    }
}