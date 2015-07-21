<?php
namespace App\Droit\Option\Entities;

use Illuminate\Database\Eloquent\Model;

class OptionUser extends Model
{
    protected $table = 'colloque_option_users';

    protected $fillable = array('user_id','option_id','inscription_id','groupe_id','reponse');

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\Droit\User\Entities\User');
    }

    public function colloque()
    {
        return $this->belongsTo('App\Droit\Colloque\Entities\Colloque');
    }

    public function option()
    {
        return $this->belongsTo('App\Droit\Option\Entities\Option');
    }

}
