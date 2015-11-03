<?php

namespace App\Droit\Member\Entities;

use Illuminate\Database\Eloquent\Model;

class Member extends Model{

    protected $table = 'members';

    protected $fillable = ['title'];

    /**
     * Set timestamps off
     */
    public $timestamps = false;


}