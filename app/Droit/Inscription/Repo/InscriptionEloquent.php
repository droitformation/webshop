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

    public function getByColloque($id)
    {
        return $this->inscription->where('colloque_id','=',$id)->with(['price','colloque','user'])->get();
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

    public function find($id){

        return $this->inscription->with(['price','colloque','user'])->find($id);
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

        if(isset($data['options']))
        {
            foreach($data['options'] as $option)
            {
                $inscription->options()->attach($option, ['user_id' => $inscription->user_id, 'inscription_id' => $inscription->id]);
            }
        }

        if(isset($data['groupes']))
        {
            foreach($data['groupes'] as $option_id => $groupe_id)
            {
                $inscription->options()->attach($option_id, ['user_id' => $inscription->user_id, 'groupe_id' => $groupe_id, 'inscription_id' => $inscription->id]);
            }
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

        return $inscription;
    }

    public function delete($id){

        $inscription = $this->inscription->find($id);

        return $inscription->delete();

    }
}
