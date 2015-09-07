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

    public function getCiviliteTitleAttribute()
    {
        $this->load('civilite');

        return  $this->civilite->title;
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

        return  'CP '.$cp;
    }
 	
	public function user()
    {
        return $this->belongsTo('App\Droit\User\Entities\User','user_id');
    }

    public function pays()
    {
        return $this->belongsTo('App\Droit\Pays\Entities\Pays','pays_id');
    }

    public function civilite()
    {
        return $this->belongsTo('App\Droit\Civilite\Entities\Civilite','civilite_id');
    }

    public function scopePrincipaleAddresse($query,$user_id)
    {
        return $query->where('user_id', '=', $user_id)->where('type', '=', 1);
    }
}
