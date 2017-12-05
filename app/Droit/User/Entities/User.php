<?PHP namespace App\Droit\User\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword as ResetPasswordNotification;

class User extends Authenticatable {

	use  SoftDeletes, Notifiable;

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
	protected $fillable = ['first_name','last_name', 'company','email', 'password'];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Set the user's first name.
     *
     * @param  string  $value
     * @return void
     */
    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = trim($value);
    }

    /**
     * Set the user's first name.
     *
     * @param  string  $value
     * @return void
     */
    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = trim($value);
    }

    public function setCompanyAttribute($value)
    {
        $this->attributes['company'] = trim($value);
    }

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
                elseif ($adresse->type == 1) {
                    return true;
                }
            });

            return $livraison->first();
        }

        return null;
    }

    public function getAdresseContactAttribute()
    {
        if(isset($this->adresses))
        {
            return $this->adresses->first(function ($adresse, $key) {
                return $adresse->type == 1;
            });
        }

        return false;
    }

    public function getAdresseFacturationAttribute()
    {
        if(isset($this->adresses))
        {
            return $this->adresses->first(function ($adresse, $key) {
                return $adresse->type == 1;
            });
        }

        return false;
    }

    public function getAdresseSpecialisationsAttribute()
    {
        if(isset($this->adresses))
        {
            $contact = $this->adresses->first(function ($adresse, $key) {
                return $adresse->type == 1;
            })->specialisations->reduce(function ($carry, $item) {
                return $carry.'<li>'.$item->title.'</li>';
            }, '');

            return $contact;
        }

        return '';
    }

    public function getAdresseMembresAttribute()
    {
        if(isset($this->adresses))
        {
            $contact = $this->adresses->first(function ($adresse, $key) {
                return $adresse->type == 1;
            })->members->reduce(function ($carry, $item) {
                return $carry.'<li>'.$item->title.'</li>';
            }, '');

            return $contact;
        }

        return '';
    }

    public function getNameAttribute()
    {
        if(!empty($this->first_name) && !empty($this->last_name)){
            return $this->first_name.' '.$this->last_name;
        }

        if(!empty($this->company)){
            return $this->company;
        }

        return trim($this->first_name.' '.$this->last_name);
    }

    public function getRoleAdminAttribute()
    {
        return $this->roles->contains('id',1);
    }

    public function getInscriptionParticipationsAttribute()
    {
        return $this->participations->reject(function ($participation, $key) {
            return $participation->inscription->inscrit->id == $this->id;
        });
    }

    public function getAllRolesAttribute()
    {
        return $this->roles->pluck('id')->all();
    }

    public function getCantRegisterAttribute()
    {
        $restrict_colloque = \Registry::get('inscription.restrict');

        $inscription_pending = $this->inscription_pending->mapWithKeys_v2(function ($item, $key) {
            return [$item->colloque_id => $item->rappels->pluck('id')];
        })->filter(function ($value, $key) {
            return !$value->isEmpty();
        });

        // If we restrict and we have rappels we cant register
        return $restrict_colloque && ($inscription_pending->count() > 1) ? true : false;
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

    public function scopeCanton($query, $name)
    {
        if (isset($name['canton_id'])) $query->whereHas('adresses', function ($query) use ($name)
        {
            $query->whereIn('canton_id', $name['canton_id']);
        });
    }

    /*
     * Relations
     * */

    public function adresses()
    {
        return $this->hasMany('App\Droit\Adresse\Entities\Adresse','user_id', 'id');
    }

    public function primary_adresse()
    {
        return $this->hasMany('App\Droit\Adresse\Entities\Adresse','user_id', 'id')->where('type', '=', 1);
    }

    public function adresses_and_trashed()
    {
        return $this->hasMany('App\Droit\Adresse\Entities\Adresse','user_id', 'id')->orderBy('deleted_at','ASC')->orderBy('id','ASC')->withTrashed();
    }

    public function orders()
    {
        return $this->hasMany('App\Droit\Shop\Order\Entities\Order','user_id', 'id');
    }

    public function inscriptions()
    {
        return $this->hasMany('App\Droit\Inscription\Entities\Inscription','user_id', 'id');
    }

    public function inscription_pending()
    {
        return $this->hasMany('App\Droit\Inscription\Entities\Inscription','user_id', 'id')->whereNull('payed_at');
    }

    public function inscription_groupes()
    {
        return $this->hasMany('App\Droit\Inscription\Entities\Groupe');
    }

    public function participations()
    {
        return $this->hasMany('App\Droit\Inscription\Entities\Participant','email', 'email');
    }

    public function roles()
    {
        return $this->belongsToMany('App\Droit\User\Entities\Role', 'user_roles', 'user_id', 'role_id');
    }

    public function access()
    {
        return $this->belongsToMany('App\Droit\Specialisation\Entities\Specialisation', 'specialisations_access', 'user_id', 'specialisation_id');
    }

    public function subscriptions()
    {
        return $this->hasMany('App\Droit\Newsletter\Entities\Newsletter_subscriptions', 'user_id', 'id');
    }

    public function email_subscriptions()
    {
        return $this->hasMany('App\Droit\Newsletter\Entities\Newsletter_users', 'email', 'email');
    }

    public function abos()
    {
        return $this->hasMany('App\Droit\Abo\Entities\Abo_users','user_id', 'id');
    }
}
