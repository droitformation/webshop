<?php namespace App\Droit\User\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;


class User_duplicates extends Model {

	use SoftDeletes;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user_duplicates';

    protected $dates = ['deleted_at'];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['id','first_name','last_name', 'username','email', 'oldpassword'];

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

    public function getAdresseFacturationAttribute()
    {
        $this->load('adresses');

        if(isset($this->adresses))
        {
            $contact = $this->adresses->filter(function($adresse)
            {
                if ($adresse->type == 1)
                {
                    $adresse->load('canton','profession','specialisations','civilite');

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

    /*
     * Relations
     * */

    public function adresses()
    {
        return $this->hasMany('App\Droit\Adresse\Entities\Adresse','user_id', 'id');
    }


    public function orders()
    {
        return $this->hasMany('App\Droit\Shop\Order\Entities\Order','user_id', 'user_id');
    }

    public function inscriptions()
    {
        return $this->hasMany('App\Droit\Inscription\Entities\Inscription','user_id', 'user_id');
    }

    public function user()
    {
        return $this->hasMany('App\Droit\User\Entities\User','email', 'email');
    }


}
