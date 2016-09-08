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
    protected $fillable = ['title','menu_title','content','rang','menu_id','template','slug','parent_id','lft','rgt','depth','hidden','site_id','url','isExternal'];

    protected $orderColumn = 'rang';

    /**
     * Scope a query to only include arrets for site
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSites($query,$site)
    {
        if ($site) $query->where('site_id','=',$site);
    }

    public function getLimitTextAttribute()
    {
        return $this->truncate($this->content,350);
    }

    public function getPageUrlAttribute()
    {
        $prefix   = $this->site->prefix ? $this->site->prefix : '/';

        $page_url = ($this->isExternal ? $this->url : $prefix.'/page/'.$this->slug);
        $page_url = ($this->template == 'index' ? $prefix : $page_url);

        $class    = \Request::is($page_url) ? 'active' : '';

        $linked   = ($this->isExternal ? 'target="_blank"' : 'class="'.$class.'"');

        return '<a '.$linked.' href="'.url($page_url).'">'.$this->menu_title.'</a>';
    }

    public function truncate($s, $l, $e = '...', $isHTML = false)
    {
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

    public function contents()
    {
        return $this->hasMany('App\Droit\Content\Entities\Content');
    }

    public function blocs()
    {
        return $this->belongsToMany('App\Droit\Bloc\Entities\Bloc','bloc_pages','page_id','bloc_id')->orderBy('blocs.rang','ASC');
    }

    public function menu()
    {
        return $this->belongsTo('App\Droit\Menu\Entities\Menu');
    }

    public function site()
    {
        return $this->belongsTo('\App\Droit\Site\Entities\Site');
    }

}
