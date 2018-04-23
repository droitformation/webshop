<?php namespace App\Droit\Newsletter\Entities;

use Illuminate\Database\Eloquent\Model;

class Newsletter_contents extends Model {

	protected $fillable = ['type_id','titre','contenu','image','lien','arret_id','categorie_id','product_id','colloque_id','newsletter_campagne_id','rang','groupe_id'];

    public $timestamps = false;

    public function getContentTitleAttribute()
    {
        if($this->titre || $this->contenu){
            return !empty(trim($this->titre)) ? $this->titre : str_limit(strip_tags($this->contenu),25);
        }
        elseif(isset($this->arret)){
            return $this->arret->reference;
        }
        elseif(isset($this->product)){
            return $this->product->title;
        }
        elseif(isset($this->colloque)){
            return $this->colloque->titre;
        }
        elseif(isset($this->groupe)){
            return $this->groupe->categorie->title;
        }
    }

    public function getTypeContentAttribute()
    {
        return in_array($this->type_id, [1,2,3,4,6,10]) ? 'content' : 'model';
    }

    public function getLinkOrUrlAttribute()
    {
        $file = config('newsletter.path.upload').$this->lien;

        if(\File::exists($file)){
            return config('newsletter.path.upload').$this->lien;
        }

        return $this->lien;
    }

    public function campagne(){

        return $this->belongsTo('App\Droit\Newsletter\Entities\Newsletter_campagnes');
    }

    public function newsletter(){

        return $this->belongsTo('App\Droit\Newsletter\Entities\Newsletter');
    }

    public function type(){

        return $this->belongsTo('App\Droit\Newsletter\Entities\Newsletter_types');
    }

    public function categorie(){

        return $this->belongsTo('App\Droit\Categorie\Entities\Categorie');
    }

    // Has to be defined in configuration 
    public function arret()
    {
        if(in_array(5,array_keys(config('newsletter.components'))))
        {
            return $this->hasOne(config('newsletter.models.arret'), 'id', 'arret_id');
        }
    }

    public function groupe()
    {
        if(in_array(7,array_keys(config('newsletter.components'))))
        {
            return $this->hasOne(config('newsletter.models.groupe'), 'id', 'groupe_id');
        }
    }

    public function product()
    {
        if(in_array(8,array_keys(config('newsletter.components'))))
        {
            return $this->hasOne(config('newsletter.models.product'), 'id', 'product_id');
        }
    }

    public function colloque()
    {
        if(in_array(9,array_keys(config('newsletter.components'))))
        {
            return $this->hasOne(config('newsletter.models.colloque'), 'id', 'colloque_id');
        }
    }
}
