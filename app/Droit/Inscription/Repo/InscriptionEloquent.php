<?php namespace App\Droit\Inscription\Repo;

use App\Droit\Inscription\Repo\InscriptionInterface;
use App\Droit\Inscription\Entities\Inscription as M;

class InscriptionEloquent implements InscriptionInterface{

    protected $inscription;

    public function __construct(M $inscription)
    {
        $this->inscription = $inscription;
    }

    public function getAll(){

        return $this->inscription->with(['price','colloque','user'])->get();
    }

    public function getByColloque($id,$type = false)
    {
        $inscription = $this->inscription->where('colloque_id','=',$id)->with(['price','colloque','user']);

        if($type)
        {
            $inscription->$type;
        }

        return $inscription->groupBy('id')->get();
    }

    public function getByColloqueTrashed($id)
    {
        return $this->inscription->where('colloque_id','=',$id)->with(['price','colloque','user'])->onlyTrashed()->groupBy('id')->get();
    }

    public function getByUser($colloque_id,$user_id)
    {
        $inscription = $this->inscription->where('colloque_id','=',$colloque_id)->where('user_id','=',$user_id)->get();

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

        return $this->inscription->with(['price','colloque','user','groupe'])->find($id);
    }

    public function restore($id)
    {
        return $this->inscription->withTrashed()->find($id)->restore();
    }

    public function hasPayed($user_id)
    {
        $days  = \Config::get('inscription.days');

        $today = \Carbon\Carbon::now()->subDays($days);

        $notpayed = $this->inscription->whereNull('payed_at')->where('created_at','<=',$today)
            ->where(function ($query) use ($user_id)
            {
                $query->whereHas('groupe', function ($query) use ($user_id){
                    $query->where('user_id','=',$user_id);
                })->orWhere('user_id','=',$user_id);
            })->get();

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

        if( ! $inscription )
        {
            return false;
        }

        $inscription->fill($data);
        $inscription->save();

        $inscription->options()->detach();

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
