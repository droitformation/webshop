<?php namespace App\Droit\Newsletter\Entities;

use Illuminate\Database\Eloquent\Model;

class Newsletter_contents extends Model {

	protected $fillable = ['type_id','titre','contenu','image','lien','arret_id','categorie_id','newsletter_campagne_id','rang','groupe_id'];

    public $timestamps = false;

    public function campagne(){

        return $this->belongsTo('App\Droit\Newsletter\Entities\Newsletter_campagnes');
    }

    public function newsletter(){

        return $this->belongsTo('App\Droit\Newsletter\Entities\Newsletter');
    }

    public function type(){

        return $this->belongsTo('App\Droit\Newsletter\Entities\Newsletter_types');
    }

    public function arrets(){

        return $this->hasMany('App\Droit\Arret\Entities\Arret', 'id', 'arret_id');
    }

}
