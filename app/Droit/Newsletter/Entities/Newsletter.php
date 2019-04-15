<?php namespace App\Droit\Newsletter\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Newsletter extends Model {

	protected $fillable = [
	    'titre','from_name','from_email','return_email','unsuscribe','preview','site_id','list_id','color','logos','header','soutien',
        'pdf','comment','classe','comment_title','hide_title','display','second_color'];

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function getBanniereLogosAttribute()
    {
        $logos = public_path('newsletter/'.$this->logos);

        return \File::exists($logos) ? 'newsletter/'.$this->logos : null;
    }

    public function getBanniereHeaderAttribute()
    {
        $header = public_path('newsletter/'.$this->header);

        return \File::exists($header) ? 'newsletter/'.$this->header : null;
    }

    public function getLogoSoutienAttribute()
    {
        $soutien = public_path('newsletter/'.$this->soutien);

        return $this->soutien && \File::exists($soutien) ? 'newsletter/'.$this->soutien : null;
    }

    public function campagnes()
    {
        return $this->hasMany('\App\Droit\Newsletter\Entities\Newsletter_campagnes')->orderBy('updated_at','DESC');
    }

    public function campagnes_visibles()
    {
        return $this->hasMany('\App\Droit\Newsletter\Entities\Newsletter_campagnes')
            ->where('status','=','envoyé')
            ->where(function ($query) {
                $query->whereNull('hidden')
                    ->orWhere('hidden','=','0');
            })
            ->where(function ($query) {
                $query->whereDate('send_at', '<=', \Carbon\Carbon::now());
            })
            ->orderBy('created_at','DESC');
    }

    public function draft()
    {
        return $this->hasMany('\App\Droit\Newsletter\Entities\Newsletter_campagnes')->where('status','=','brouillon')->orderBy('updated_at','DESC');
    }

    public function pending()
    {
        return $this->hasMany('\App\Droit\Newsletter\Entities\Newsletter_campagnes')
            ->where('status','=','envoyé')
            ->where('send_at', '>', \Carbon\Carbon::now())
            ->orderBy('updated_at','DESC');
    }

    public function sent()
    {
        return $this->hasMany('\App\Droit\Newsletter\Entities\Newsletter_campagnes')
            ->where('status','=','envoyé')
            ->where(function ($query) {
                $query->whereDate('send_at', '<=', \Carbon\Carbon::now())->orWhereNull('send_at');
            })
            ->orderBy('created_at','DESC');
    }
    
    public function site()
    {
        if(config('newsletter.multi'))
        {
            return $this->belongsTo(config('newsletter.models.site'));
        }
    }

    public function subscriptions()
    {
        $database = $this->getConnection()->getDatabaseName();
        return $this->belongsToMany('App\Droit\Newsletter\Entities\Newsletter_users', $database.'.newsletter_subscriptions', 'newsletter_id','user_id');
    }

    public function active_subscriptions()
    {
        return $this->belongsToMany('App\Droit\Newsletter\Entities\Newsletter_users', 'newsletter_subscriptions', 'newsletter_id','user_id')
            ->whereNotNull('newsletter_users.activated_at');
    }
}