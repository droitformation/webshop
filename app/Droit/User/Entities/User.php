<?PHP namespace App\Droit\User\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Cashier\Billable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {

	use Billable, SoftDeletes;

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
                if ($adresse->livraison == 1)
                {
                    return true;
                }
                elseif ($adresse->type == 1)
                {
                    return true;
                }
            });

            return $livraison->first();
        }

        return [];
    }

    public function getAdresseContactAttribute()
    {
        if(isset($this->adresses))
        {
            $contact = $this->adresses->filter(function($adresse)
            {
                if ($adresse->type == 1)
                {
                    return true;
                }
            });

            return $contact->first();
        }

        return false;
    }

    public function getAdresseFacturationAttribute()
    {
        if(isset($this->adresses))
        {
            $contact = $this->adresses->filter(function($adresse)
            {
                if ($adresse->type == 1)
                {
                    return true;
                }
            });

            return $contact->first();
        }

        return false;
    }

    public function getNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function getRoleAdminAttribute()
    {
        $roles = $this->roles->lists('id')->all();

        if(in_array(1,$roles))
        {
            return true;
        }

        return false;
    }

    /*
     * Search scopes
     * */

    public function scopeSearchFirstName($query, $first_name)
    {
        if ($first_name) $query->where('first_name', 'like' ,'%'.$first_name.'%');
    }

    public function scopeSearchLastName($query, $last_name)
    {
        if ($last_name) $query->where('last_name', 'like' ,'%'.$last_name.'%');
    }

    public function scopeSearchEmail($query, $email)
    {
        if ($email) $query->where('email', 'like' ,'%'.$email.'%');
    }

    /*
     * Relations
     * */

    public function adresses()
    {
        return $this->hasMany('App\Droit\Adresse\Entities\Adresse','user_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany('App\Droit\Shop\Order\Entities\Order','user_id', 'id');
    }

    public function inscriptions()
    {
        return $this->hasMany('App\Droit\Inscription\Entities\Inscription','user_id', 'id');
    }

    public function inscription_groupes()
    {
        return $this->hasMany('App\Droit\Inscription\Entities\Groupe');
    }

    public function roles()
    {
        return $this->belongsToMany('App\Droit\User\Entities\Role', 'user_roles', 'user_id', 'role_id');
    }

    public function subscriptions()
    {
        return $this->hasMany('App\Droit\Newsletter\Entities\Newsletter_subscriptions', 'user_id', 'id');
    }
}
