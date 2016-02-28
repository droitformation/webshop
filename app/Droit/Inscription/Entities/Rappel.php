<?php

namespace App\Droit\Inscription\Entities;

use Illuminate\Database\Eloquent\Model;

class Rappel extends Model
{
    protected $table = 'colloque_inscription_rappels';

    protected $fillable = ['user_id', 'group_id', 'inscription_id'];

    public function getDocRappelAttribute()
    {
        $this->load('inscription');

        $user = $this->group_id ? $this->group_id : $this->user_id;

        $path = config('documents.colloque.rappel');
        $file = 'rappel_'.$this->id.'_'.$this->inscription->colloque_id.'-'.$user.'.pdf';

        if (\File::exists(public_path($path.$file)))
        {
            return $path.$file;
        }

        return null;
    }

    public function inscription()
    {
        return $this->belongsTo('App\Droit\Inscription\Entities\Inscription');
    }
}
