<?php namespace App\Droit\Adresse\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Adresse extends Model {

	use SoftDeletes;

    protected $table = 'adresses';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'user_id', 'civilite_id' ,'first_name','last_name', 'email', 'company', 'profession_id', 'telephone','mobile',
        'fax', 'adresse', 'cp', 'complement','npa', 'ville', 'canton_id','pays_id', 'type', 'livraison'
    ];

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

    public function getTypeTitleAttribute()
    {
        $this->load('typeadresse');

        return  (isset($this->typeadresse->type) ? $this->typeadresse->type : '');
    }

    public function getCiviliteTitleAttribute()
    {
        $this->load('civilite');

        return  (isset($this->civilite->title) ? $this->civilite->title : '');
    }

    public function getCantonTitleAttribute()
    {
        $this->load('canton');

        return  (isset($this->canton->title) ? $this->canton->title : '');
    }

    public function getProfessionTitleAttribute()
    {
        $this->load('profession');

        return  (isset($this->profession->title) ? $this->profession->title : '');
    }

    public function getPaysTitleAttribute()
    {
        $this->load('pays');

        return (isset($this->pays->title) ? $this->pays->title : '');
    }

    public function getNameAttribute()
    {
        if(empty($this->first_name) && empty($this->last_name))
        {
            return $this->company;
        }

        return $this->first_name.' '.$this->last_name;
    }

    public function getNameSlugAttribute()
    {
        if(empty(trim($this->first_name)) && empty(trim($this->last_name)))
        {
            return str_slug($this->company);
        }

        return str_slug($this->last_name);
    }

    public function getMainAdresseAttribute()
    {
        return $this->name.', '.$this->adresse.', '.$this->npa.' '.$this->ville;
    }

    public function getInvoiceNameAttribute()
    {
        $name = [];

        if(!empty($this->civilite_title)){
            $name[] = $this->civilite_title;
        }
        
        if(!empty($this->first_name) && !empty($this->last_name)){
            $name[] = $this->first_name.' '.$this->last_name;
        }

        if(empty($this->first_name) && !empty($this->last_name)){
            $name[] = $this->last_name;
        }

        if(!empty($this->first_name) && empty($this->last_name)){
            $name[] = $this->first_name;
        }

        if(!empty($this->company)){
            $name[] = $this->company;
        }

        $name = array_unique($name);

        return $name;
    }

    public function getCpTrimAttribute()
    {
        $wordlist = array("CP", "case", "postale","Case","Postale","cp","Cp","Postfach","postfach", "C. P." , "PF" , "PO Box");

        $cp = str_replace($wordlist, "",  $this->cp);
        $cp = trim($cp);

        return  (!empty($cp) ? 'CP '.$cp : '');
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

    public function scopeSearchCanton($query, $cantons)
    {
        if ($cantons) $query->whereIn('canton_id', $cantons);
    }

    public function scopeSearchPays($query, $pays)
    {
        if ($pays) $query->where('pays_id', '=' ,$pays);
    }

    public function scopeSearchProfession($query, $professions)
    {
        if ($professions) $query->whereIn('profession_id', $professions);
    }

    public function scopeSearchMemberEach($query, $members)
    {
        if ($members) $query->whereHas('members', function ($query) use ($members)
        {
            $query->whereIn('member_id', $members);
        });
    }

    public function scopeSearchSpecialisationEach($query, $specialisations)
    {
        if ($specialisations) $query->whereHas('specialisations', function ($query) use ($specialisations)
        {
            $query->whereIn('specialisation_id', $specialisations);
        });
    }

    public function scopeSearchMember($query, $members)
    {
        if ($members)
        {
            foreach($members as $member)
            {
                $query->whereHas('members', function($query) use ($member){
                    $query->where('member_id', '=' ,$member);
                });
            }
        }
    }

    public function scopeSearchSpecialisation($query, $specialisations)
    {
        if ($specialisations)
        {
            foreach($specialisations as $specialisation)
            {
                $query->whereHas('specialisations', function($query) use ($specialisation){
                    $query->where('specialisation_id', '=' ,$specialisation);
                });
            }
        }
    }

    /*
     * Relations
     * */

	public function user()
    {
        return $this->belongsTo('App\Droit\User\Entities\User','user_id');
    }

    public function trashed_user()
    {
        return $this->belongsTo('App\Droit\User\Entities\User','user_id')->withTrashed();
    }

    public function specialisations()
    {
        return $this->belongsToMany('App\Droit\Specialisation\Entities\Specialisation','adresse_specialisations','adresse_id','specialisation_id');
    }

    public function members()
    {
        return $this->belongsToMany('App\Droit\Member\Entities\Member','adresse_members','adresse_id','member_id');
    }

    public function pays()
    {
        return $this->belongsTo('App\Droit\Pays\Entities\Pays','pays_id');
    }

    public function profession()
    {
        return $this->belongsTo('App\Droit\Profession\Entities\Profession','profession_id');
    }

    public function canton()
    {
        return $this->belongsTo('App\Droit\Canton\Entities\Canton','canton_id');
    }

    public function civilite()
    {
        return $this->belongsTo('App\Droit\Civilite\Entities\Civilite','civilite_id');
    }

    public function typeadresse()
    {
        return $this->belongsTo('App\Droit\Adresse\Entities\Adresse_types','type');
    }

    public function scopePrincipaleAddresse($query,$user_id)
    {
        return $query->where('user_id', '=', $user_id)->where('type', '=', 1);
    }

    public function orders()
    {
        return $this->hasMany('App\Droit\Shop\Order\Entities\Order','adresse_id', 'id');
    }

    public function abos()
    {
        return $this->hasMany('App\Droit\Abo\Entities\Abo_users','adresse_id', 'id');
    }
}
