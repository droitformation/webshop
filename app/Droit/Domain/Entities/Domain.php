<?php namespace App\Droit\Domain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Domain extends Model{

    use SoftDeletes;

    protected $table = 'domains';
    
    protected $dates = ['deleted_at'];

    protected $fillable = ['title','hidden'];
    /**
     * Set timestamps off
     */
    public $timestamps = false;

}