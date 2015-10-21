<?php namespace App\Droit\Newsletter\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Newsletter extends Model {

	protected $fillable = ['titre','from_name','from_email','return_email','unsuscribe','preview','list_id','color','logos','header'];

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function getBanniereLogosAttribute()
    {
        return 'newsletter/'.$this->logos;
    }

    public function getBanniereHeaderAttribute()
    {
        return 'newsletter/'.$this->header;
    }

    public function campagnes()
    {
        return $this->hasMany('\App\Droit\Newsletter\Entities\Newsletter_campagnes');
    }
}