<?php namespace App\Droit\Newsletter\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Newsletter_users extends Model {

    use SoftDeletes;

    protected $dates    = ['activated_at','deleted_at'];
	protected $fillable = ['email','activation_token','activated_at'];

    public function getActivatedAttribute()
    {
        return $this->activated_at && $this->activated_at->timestamp < 0 ? null : $this->activated_at;
    }

    public function scopeNewsletter($query,$newsletter_id)
    {
        if ($newsletter_id){
            return $query->whereHas('subscriptions', function ($query) use ($newsletter_id) {
                $query->where('newsletter_id','=',$newsletter_id);
            });
        }
    }

    public function subscriptions()
    {
        $database = $this->getConnection()->getDatabaseName();
        return $this->belongsToMany('App\Droit\Newsletter\Entities\Newsletter', $database.'.newsletter_subscriptions', 'user_id', 'newsletter_id');
    }
}