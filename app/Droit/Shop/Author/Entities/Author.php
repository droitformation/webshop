<?php namespace App\Droit\Shop\Author\Entities;

use Illuminate\Database\Eloquent\Model;

class Author extends Model {

    protected $table = 'shop_authors';

	protected $fillable = ['first_name','last_name','occupation','bio','photo'];

    public $timestamps  = false;

    public function getNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function getAuthorPhotoAttribute()
    {
        return (!empty($this->photo) ? $this->photo : 'avatar.jpg');
    }

    public function products()
    {
        return $this->belongsToMany('\App\Droit\Shop\Product\Entities\Product', 'shop_product_authors', 'author_id', 'product_id');
    }

}

