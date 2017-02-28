<?php

namespace App\Droit\Inscription\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inscription extends Model
{
    use SoftDeletes;

    protected $table = 'colloque_inscriptions';

    protected $dates = ['deleted_at','payed_at','send_at'];

    protected $fillable = ['colloque_id', 'user_id', 'group_id', 'inscription_no', 'price_id', 'payed_at', 'send_at', 'status','admin','present'];

    public function getRappelListAttribute()
    {
        return $this->rappels->map(function ($item, $key) {
            return ['id' => $item->id ,'date' => 'Rappel '.$item->created_at->format('d/m/Y'), 'doc_rappel' => $item->doc_rappel];
        });
    }
    
    public function getPriceCentsAttribute()
    {
        $money = new \App\Droit\Shop\Product\Entities\Money;
        $price = $this->price->price / 100;

        return $money->format($price);
    }

    public function getAnnexeAttribute()
    {
        if(in_array('bon',$this->colloque->annexe))
        {
            return ['bon' => 'bon de participation à présenter à l\'entrée'];
        }

        return null;
    }

    public function getQrcodeAttribute()
    {
        $occurrence   = isset($this->occurrences) && !$this->occurrences->isEmpty() ? 'occurrence/' : '';
        $url          = url('presence/'.$occurrence.$this->id.'/'.config('services.qrcode.key'));

        return base64_encode(\QrCode::format('png')->margin(3)->size(115)->encoding('UTF-8')->generate($url));
    }

    public function getStatusNameAttribute()
    {
        switch ($this->status) {
            case 'pending':
                $status = [ 'status' => 'En attente', 'color' => 'default' ];
                break;
            case 'payed':
                $status = [ 'status' => 'Payé', 'color' => 'success' ];
                break;
            case 'free':
                $status = [ 'status' => 'Gratuit', 'color' => 'info' ];
                break;
        }

        return $status;
    }

    /* *
     * Get only bon doc
     * */
    public function getDocBonAttribute()
    {
        $this->load('groupe','user','groupe.user');
        $path = config('documents.colloque.bon');

        if(isset($this->groupe) && !empty($this->groupe))
        {
            $file = $path.'bon'.'_'.$this->colloque->id.'-'.$this->groupe->id.'-'.$this->participant->id.'.pdf';
        }
        else
        {
            $file = $path.'bon'.'_'.$this->colloque->id.'-'.$this->user->id.'.pdf';
        }

        return ($this->annexe && \File::exists(public_path($file)) ? $file : null);
    }

    /* *
  * Get only facture doc
  * */
    public function getDocFactureAttribute()
    {
        $path = config('documents.colloque.facture');

        if(!$this->user){
            return null;
        }
        
        $file = $path.'facture'.'_'.$this->colloque->id.'-'.$this->user->id.'.pdf';

        return ($this->annexe && \File::exists(public_path($file)) ? $file : null);
    }

    /* *
     * Get only attestation doc
     * */
    public function getDocAttestationAttribute()
    {
        $path = config('documents.colloque.attestation');

        if(!$this->inscrit)
        {
            return null;
        }

        $file = $path.'attestation'.'_'.$this->colloque_id.'-'.$this->inscrit->id.'.pdf';

        return (\File::exists(public_path($file)) ? $file : null);
    }

    /* *
     * Used for admin list of documents ['link']
     * Used for attachements for sending via email ['file']
     * */
    public function getDocumentsAttribute()
    {
        $this->load('colloque');

        if(!empty($this->colloque->annexe) && $this->inscrit)
        {
            foreach($this->colloque->annexe as $id => $annexe)
            {
                $name = $annexe.'_'.$this->colloque->id.'-'.$this->inscrit->id.'.pdf';
                $path = config('documents.colloque.'.$annexe).$name;
                $file = public_path($path);

                if (\File::exists($file))
                {
                    $docs[$annexe]['file'] = $file;
                    $docs[$annexe]['link'] = $path;
                    $docs[$annexe]['name'] = $name;
                }
            }
        }

        return isset($docs) ? $docs : [];
    }

    public function getInscritAttribute()
    {
        if(isset($this->groupe)) {
            return $this->groupe->user;
        }

        if(isset($this->user)) {
            return $this->user;
        }

        return null;
    }

    public function getDetenteurAttribute()
    {
        $adresse = $this->inscrit->adresses->where('type',1)->first();

        return ['id' => $this->inscrit->id, 'civilite' => $adresse->civilite_title, 'name' => $adresse->name, 'email' => $adresse->email ];
    }

    public function getAdresseInscritAttribute()
    {
        return $this->inscrit->adresses->where('type',1)->first();
    }

    public function getIsFreeAttribute()
    {
        if(isset($this->price) && $this->price->price > 0)
        {
            return false;
        }

        return true;
    }

    public function getListRappelAttribute()
    {
        return $this->group_id > 0 ? $this->group_rappels : $this->rappels;
    }

    public function getNameInscriptionAttribute()
    {
        if($this->group_id > 0)
        {
            return ucwords($this->participant->name);
        }
        elseif(isset($this->user))
        {
            return ucwords($this->user->adresse_contact->name);
        }

        return $this->user_id.' | '.$this->inscription_no;
    }

    public function getAdresseFacturationAttribute()
    {
        if($this->group_id)
        {
            $this->groupe->load('user','user.adresses');

            return $this->groupe->user->adresse_contact;
        }

        if($this->user)
        {
            return $this->user->adresse_contact;
        }

        return null;
    }

    /**
     * Scope a query to only include simple inscription
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSimple($query)
    {
        return $query->where('group_id', NULL);
    }

    /**
     * Scope a query to only include inscriptions for occurrences
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOccurrence($query, $occurrences)
    {
        if(!empty($occurrences)){
            return $query->whereHas('occurrences', function($q) use ($occurrences){
                $q->whereIn('colloque_occurrences.id', $occurrences);
            });
        }
    }

    /**
     * Scope a query to only include multiple inscription
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMultiple($query)
    {
        return $query->where('user_id', NULL);
    }

    public function price()
    {
        return $this->belongsTo('App\Droit\Price\Entities\Price');
    }

    public function colloque()
    {
        return $this->belongsTo('App\Droit\Colloque\Entities\Colloque');
    }

    public function user()
    {
        return $this->belongsTo('App\Droit\User\Entities\User');
    }

    public function duplicate()
    {
        return $this->belongsTo('App\Droit\Adresse\Entities\Adresse', 'duplicate_id', 'user_id');
    }

    public function groupe()
    {
        return $this->belongsTo('App\Droit\Inscription\Entities\Groupe', 'group_id', 'id')->withTrashed();
    }

    public function participant()
    {
        return $this->hasOne('App\Droit\Inscription\Entities\Participant');
    }

    public function user_options()
    {
        return $this->hasMany('App\Droit\Option\Entities\OptionUser')->orderBy('id');
    }

    public function options()
    {
        return $this->belongsToMany('App\Droit\Option\Entities\Option' , 'colloque_option_users', 'inscription_id', 'option_id');
    }

    public function occurrences()
    {
        return $this->belongsToMany('App\Droit\Occurrence\Entities\Occurrence' , 'colloque_occurrence_users', 'inscription_id', 'occurrence_id')->withPivot('present');
    }

    public function rappels()
    {
        return $this->hasMany('App\Droit\Inscription\Entities\Rappel');
    }

    public function group_rappels()
    {
        return $this->hasMany(
            'App\Droit\Inscription\Entities\Rappel','group_id', 'group_id'
        );
    }
}
