<?php
namespace App\Droit\Option\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OptionUser extends Model
{

    use SoftDeletes;

    protected $table = 'colloque_option_users';

    protected $fillable = array('user_id','option_id','inscription_id','groupe_id','reponse');

    protected $dates = ['deleted_at'];

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

    public function option_groupe()
    {
        return $this->belongsTo('App\Droit\Option\Entities\OptionGroupe','groupe_id','id');
    }

}
