<?php namespace App\Droit\Shop\Categorie\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categorie extends Model {

    use SoftDeletes;

    protected $table = 'shop_categories';

    protected $dates = ['deleted_at'];

	protected $fillable = ['title','image','rang','parent_id'];

    public function site()
    {
        return $this->belongsTo('\App\Droit\Site\Entities\Site');
    }
}