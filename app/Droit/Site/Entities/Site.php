<?php namespace App\Droit\Site\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Site extends Model{

    use SoftDeletes;

    protected $dates    = ['deleted_at'];
    protected $table    = 'sites';
    protected $fillable = ['nom','url','logo','slug'];

    public function menus()
    {
        return $this->hasMany('App\Droit\Menu\Entities\Menu');
    }

    public function pages()
    {
        return $this->hasMany('App\Droit\Page\Entities\Page');
    }
}