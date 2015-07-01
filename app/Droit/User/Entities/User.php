<?php namespace App\Droit\User\Entities;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Laravel\Cashier\Billable;
use Laravel\Cashier\Contracts\Billable as BillableContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract, BillableContract {

	use Authenticatable, CanResetPassword, Billable, SoftDeletes;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

    protected $dates = ['trial_ends_at', 'subscription_ends_at','deleted_at'];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['first_name','last_name', 'email', 'password'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

    public function getAdresseLivraisonAttribute()
    {
        if(isset($this->adresses))
        {
            $livraison = $this->adresses->filter(function($adresse)
            {
                if ($adresse->livraison == 1) {
                    return true;
                }
            });

            return $livraison->first();
        }

        return [];
    }

    public function getNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function adresses()
    {
        return $this->hasMany('App\Droit\Adresse\Entities\Adresses','user_id', 'id');
    }

}
