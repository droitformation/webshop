<?php 
namespace App\Droit\Option\Entities;

use Illuminate\Database\Eloquent\Model;

class OptionGroupe extends Model
{
    protected $table = 'colloque_option_groupes';

    public $timestamps = false;

    protected $fillable = ['text','colloque_id','option_id'];

    public function colloque()
    {
        return $this->belongsTo('App\Droit\Colloque\Entities\Colloque');
    }

    public function option()
    {
        return $this->belongsTo('App\Droit\Option\Entities\Option');
    }

}
