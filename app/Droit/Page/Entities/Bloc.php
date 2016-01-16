<?php

namespace App\Droit\Page\Entities;

use Illuminate\Database\Eloquent\Model;

class Bloc extends Model {

    protected $table   = 'blocs';

    protected $fillable = ['title','content','rang','image','type','page_id','lien','style'];

}
