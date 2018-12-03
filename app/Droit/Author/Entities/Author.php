<?php namespace App\Droit\Author\Entities;

use Illuminate\Database\Eloquent\Model;

class Author extends Model {

	protected $fillable = ['first_name','last_name','occupation','bio','photo','rang','site_id'];

    public $timestamps  = false;

    public function getNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function getNameTitleAttribute()
    {
        return $this->first_name.' '.$this->last_name.', '.$this->occupation;
    }

    public function getTitleAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function getAuthorPhotoAttribute()
    {
        return (!empty($this->photo) ? $this->photo : 'avatar.jpg');
    }

    public function analyses()
    {
        return $this->belongsToMany('\App\Droit\Analyse\Entities\Analyse', 'analyse_authors', 'author_id', 'analyse_id');
    }

    public function products()
    {
        return $this->belongsToMany('\App\Droit\Shop\Product\Entities\Product', 'shop_product_authors', 'author_id', 'product_id');
    }

    public function sites()
    {
        return $this->belongsToMany('\App\Droit\Site\Entities\Site', 'authors_sites', 'author_id', 'site_id');
    }
}

