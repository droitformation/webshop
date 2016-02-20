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
	protected $fillable = ['id','first_name','last_name', 'username','email', 'oldpassword','old_id'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

    public function getAdressePrincipaleAttribute()
    {
        $this->load('originaladresse');

        if(isset($this->originaladresse))
        {
            $principale = $this->originaladresse->filter(function($adresse)
            {
                if ($adresse->type == 1) {
                    return true;
                }
            });

            return $principale->first();
        }

        return null;
    }

    public function getNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    /*
     * Relations
     * */

    public function adresse()
    {
        return $this->hasOne('App\Droit\Adresse\Entities\Adresse','duplicate_id', 'id');
    }

    public function originaladresse()
    {
        return $this->hasMany('App\Droit\Adresse\Entities\Adresse','user_id', 'user_id')->withTrashed();
    }

    public function orders()
    {
        return $this->hasMany('App\Droit\Shop\Order\Entities\Order','user_id', 'old_id');
    }

    public function inscriptions()
    {
        return $this->hasMany('App\Droit\Inscription\Entities\Inscription','user_id', 'old_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Droit\User\Entities\User','user_id', 'id')->withTrashed();
    }

}
