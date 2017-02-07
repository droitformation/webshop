<?php namespace App\Droit\Newsletter\Entities;

use Illuminate\Database\Eloquent\Model;

class Newsletter_lists extends Model {

	protected $fillable = ['title'];

    public function emails()
    {
        return $this->hasMany('App\Droit\Newsletter\Entities\Newsletter_emails', 'list_id', 'id');
    }
}