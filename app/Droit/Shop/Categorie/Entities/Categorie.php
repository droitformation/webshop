<?php namespace App\Droit\Shop\Categorie\Entities;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model {

    protected $table = 'shop_categories';

	protected $fillable = ['title','site_id','sorting'];

    public function site()
    {
        return $this->belongsTo('\App\Droit\Site\Entities\Site');
    }
}