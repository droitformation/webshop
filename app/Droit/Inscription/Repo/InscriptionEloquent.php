<?php namespace App\Droit\Inscription\Repo;

use App\Droit\Inscription\Repo\InscriptionInterface;
use App\Droit\Inscription\Entities\Inscription as M;
use Carbon\Carbon;

class InscriptionEloquent implements InscriptionInterface{

    protected $inscription;

    public function __construct(M $inscription)
    {
        $this->inscription = $inscription;
    }

    public function getAll($nbr = null){

        return $this->inscription->with(['price','colloque','user','duplicate'])->take($nbr)->groupBy('group_id')->orderBy('created_at','DESC')->get();
    }

    public function getByColloque($id, $type = false, $paginate = false)
    {
        $inscription = $this->inscription->where('colloque_id','=',$id)->with(['price','colloque','duplicate','rappels','user_options.option_groupe','user.adresses' => function($query)
        {
            $query->where('adresses.type','=',1);

        },'participant','groupe']);

        if($type)
        {
            $inscription->$type;
        }

        if($paginate)
        {
            return $inscription->groupBy('group_id')->paginate(20);
        }

        return $inscription->groupBy('id')->get();
    }

    public function getByColloqueTrashed($id)
    {
        return $this->inscription->where('colloque_id','=',$id)->with(['price','colloque','user','participant','groupe','duplicate'])->onlyTrashed()->groupBy('id')->get();
    }

    public function getRappels($id)
    {
        return $this->inscription->where('colloque_id','=',$id)->whereNull('group_id')->whereNull('payed_at')->with(['price','colloque','user','participant','groupe','duplicate'])->paginate(20);
    }

    public function getByUser($colloque_id,$user_id)
    {
        $inscription = $this->inscription->with('rappels')->where('colloque_id','=',$colloque_id)->where('user_id','=',$user_id)->get();

        if(!$inscription->isEmpty())
        {
            return $inscription->first();
        }

        return false;
    }

    public function getByGroupe($groupe_id)
    {
       return $this->inscription->where('group_id','=',$groupe_id)->get();
    }

    public function find($id){

        return $this->inscription->with(['price','colloque','user','groupe','rappels'])->find($id);
    }

    public function restore($id)
    {
        return $this->inscription->withTrashed()->find($id)->restore();
    }

    public function hasPayed($user_id)
    {
        $days  = \Registry::get('inscription.days');

        $today = Carbon::now()->subDays($days);

        $notpayed = $this->inscription->where('status','!=','free')->whereNull('payed_at')
            ->where(function ($query) use ($user_id)
            {
                $query->whereHas('groupe', function ($query) use ($user_id){
                    $query->where('user_id','=',$user_id);
                })->orWhere('user_id','=',$user_id);
            });

        if($days > 0)
        {
            $notpayed->where('created_at','<=',$today);
        }

        $notpayed = $notpayed->get();

        return ($notpayed->isEmpty() ? true : false );
    }

    public function create(array $data){

        $inscription = $this->inscription->create(array(
            'colloque_id'     => $data['colloque_id'],
            'user_id'         => (isset($data['user_id']) ? $data['user_id'] : null),
            'group_id'        => (isset($data['group_id']) ? $data['group_id'] : null),
            'inscription_no'  => $data['inscription_no'],
            'price_id'        => $data['price_id'],
            'created_at'      => \Carbon\Carbon::now(),
            'updated_at'      => \Carbon\Carbon::now()
        ));

        if( ! $inscription )
        {
            return false;
        }

        // Options
        if(isset($data['options']))
        {
            foreach($data['options'] as $option)
            {
                $inscription->options()->attach($option, ['inscription_id' => $inscription->id]);
            }
        }

        // Options groupes
        if(isset($data['groupes']))
        {
            foreach($data['groupes'] as $option_id => $groupe_id)
            {
                $inscription->options()->attach($option_id, ['groupe_id' => $groupe_id, 'inscription_id' => $inscription->id]);
            }
        }

        if(isset($data['participant']) && !empty($data['participant']))
        {
            $participant = new \App\Droit\Inscription\Entities\Participant();

            $participant->create(['name' => $data['participant'], 'inscription_id' => $inscription->id ]);
        }

        return $inscription;

    }

    public function update(array $data){

        $inscription = $this->inscription->findOrFail($data['id']);

        if(! $inscription)
        {
            return false;
        }

        $inscription->fill($data);

        if(isset($data['payed_at']) && !empty($data['payed_at']))
        {
            $valid = (Carbon::createFromFormat('Y-d-m', $data['payed_at']) !== false);

            $inscription->status = !$valid || null ? 'pending' : 'payed';
            $inscription->payed_at = $data['payed_at'];
        }

        if(empty($data['payed_at']))
        {
            $inscription->status   = 'pending';
            $inscription->payed_at = null;
        }

        $inscription->save();

        // Options
        // Remove all and re-attach if any
        $inscription->options()->detach();

        if(isset($data['options']))
        {
            foreach($data['options'] as $option)
            {
                $inscription->options()->attach($option, ['inscription_id' => $inscription->id]);
            }
        }

        // Options groupes
        if(isset($data['groupes']))
        {
            foreach($data['groupes'] as $option_id => $groupe_id)
            {
                $inscription->options()->detach($option_id);

                $inscription->options()->attach($option_id, ['groupe_id' => $groupe_id, 'inscription_id' => $inscription->id]);
            }
        }

        if(isset($data['participant']) && !empty($data['participant']))
        {
            $participant = new \App\Droit\Inscription\Entities\Participant();
            $participant->where('inscription_id','=',$inscription->id )->delete();
            $participant->create(['name' => $data['participant'], 'inscription_id' => $inscription->id ]);
        }

        return $inscription;
    }

    public function delete($id){

        $inscription = $this->inscription->find($id);

        return $inscription->delete();
    }
}
