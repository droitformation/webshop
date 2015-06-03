<?php namespace  App\Droit\Categorie\Entities;

use Illuminate\Database\Eloquent\Model;

class Parent_categories extends Model{

    protected $table = 'shop_parent_categories';

    protected $fillable = array('categorie_id', 'parent_id', 'sorting');

    /**
     * Set timestamps off
     */
    public $timestamps = false;

}