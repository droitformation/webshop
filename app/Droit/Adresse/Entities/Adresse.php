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
        return $this->first_name.' '.$this->last_name;
    }

    public function getCpTrimAttribute()
    {
        $wordlist = array("CP", "case", "postale","Case","Postale","cp","Cp","Postfach","postfach", "C. P." , "PF" , "PO Box");

        $cp = str_replace($wordlist, "",  $this->cp);
        $cp = trim($cp);

        return  (!empty($cp) ? 'CP '.$cp : '');
    }
 	
	public function user()
    {
        return $this->belongsTo('App\Droit\User\Entities\User','user_id');
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
}
