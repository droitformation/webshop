<?php

namespace App\Droit\Page\Entities;

use Baum\Node;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
* Page
*/
class Page extends Node {

    use SoftDeletes;

    /**
    * Table name.
    *
    * @var string
    */
    protected $table = 'pages';

    protected $dates    = ['deleted_at'];
    protected $fillable = ['title','content','rang','main','template','slug','parent_id','lft','rgt','depth','hidden','site_id'];

    protected $orderColumn = 'rang';

    /**
     * Scope a query to only include arrets for site
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSite($query,$site)
    {
        if ($site) $query->where('site_id','=',$site);
    }

    public function getLimitTextAttribute()
    {
        return $this->truncate($this->content,350);
    }

    public function truncate($s, $l, $e = '...', $isHTML = false){

        $i    = 0;
        $tags = [];

        if($isHTML)
        {
            preg_match_all('/<[^>]+>([^<]*)/', $s, $m, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
            foreach($m as $o)
            {
                if($o[0][1] - $i >= $l)
                    break;
                $t = substr(strtok($o[0][0], " \t\n\r\0\x0B>"), 1);
                if($t[0] != '/')
                    $tags[] = $t;
                elseif(end($tags) == substr($t, 1))
                    array_pop($tags);
                $i += $o[1][1] - $o[0][1];
            }
        }

        return substr($s, 0, $l = min(strlen($s),  $l + $i)) . (count($tags = array_reverse($tags)) ? '' : '') . (strlen($s) > $l ? $e : '');
    }

    public function blocs()
    {
        return $this->hasMany('App\Droit\Content\Entities\Content');
    }
}
