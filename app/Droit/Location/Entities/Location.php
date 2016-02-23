<?php namespace App\Droit\Location\Entities;

use Illuminate\Database\Eloquent\Model;

class Location extends Model{

    protected $table = 'locations';

    protected $fillable = ['name','adresse','url','map'];

    public $timestamps = false;

    public function getLocationMapAttribute()
    {

        if($this->map)
        {
            return 'files/colloques/cartes/'.$this->map;
        }

        return false;
    }

}