<?php namespace App\Droit\Newsletter\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Newsletter_lists extends Model {

    use SoftDeletes;

	protected $fillable = ['title'];

    public function emails()
    {
        return $this->hasMany('App\Droit\Newsletter\Entities\Newsletter_emails', 'list_id', 'id');
    }

    public function specialisations()
    {
        return $this->belongsToMany('App\Droit\Specialisation\Entities\Specialisation', 'list_specialisations', 'list_id', 'specialisation_id');
    }
}