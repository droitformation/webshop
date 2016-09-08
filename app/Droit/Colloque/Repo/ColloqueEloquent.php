<?php namespace App\Droit\Colloque\Repo;

use App\Droit\Colloque\Repo\ColloqueInterface;
use App\Droit\Colloque\Entities\Colloque as M;

class ColloqueEloquent implements ColloqueInterface{

    protected $colloque;

    public function __construct(M $colloque)
    {
        $this->colloque = $colloque;
    }

    public function getAll($active = false, $archives = false)
    {
        return $this->colloque->active($active)->archives($archives)->orderBy('start_at','DESC')->get();
    }

    public function search($term)
    {
        return $this->colloque->where('titre', 'like', '%'.$term.'%')->orWhere('sujet', 'like', '%'.$term.'%')->orWhere('soustitre', 'like', '%'.$term.'%')->get();
    }

    public function getCurrent($registration = false, $finished = false, $visible = true)
    {
        return $this->colloque->visible($visible)->registration($registration)->finished($finished)->orderBy('start_at','DESC')->get();
    }

    public function getByYear($year)
    {
        return $this->colloque->whereRaw('YEAR(`start_at`) = ?', [$year])->orderBy('start_at','DESC')->get();
    }

    public function getYears()
    {
        return $this->colloque->select('start_at')->archives(true)->orderBy('start_at','DESC')->get();
    }

    public function find($id){

        return $this->colloque->with(['documents','location'])->findOrFail($id);
    }

    public function increment($colloque_id)
    {
        $colloque = $this->colloque->find($colloque_id);
        $colloque->counter = $colloque->counter + 1;
        $colloque->save();

        return $colloque->counter;
    }

    public function getNewNoInscription($colloque_id){

        $counter = $this->increment($colloque_id);

        return $colloque_id.'-'.date('Y') .'/'.$counter;
    }

    public function create(array $data){

        $colloque = $this->colloque->create(array(
            'titre'           => $data['titre'],
            'soustitre'       => $data['soustitre'],
            'sujet'           => $data['sujet'],
            'remarques'       => $data['remarques'],
            'organisateur'    => $data['organisateur'],
            'location_id'     => $data['location_id'],
            'adresse_id'      => (isset($data['adresse_id']) ? $data['adresse_id'] : 1),
            'start_at'        => $data['start_at'],
            'end_at'          => (isset($data['end_at']) && !empty($data['end_at']) ? $data['end_at'] : null),
            'registration_at' => $data['registration_at'],
            'active_at'       => (isset($data['active_at']) ? $data['active_at'] : null),
            'created_at'      => \Carbon\Carbon::now(),
            'updated_at'      => \Carbon\Carbon::now()
        ));

        if( ! $colloque )
        {
            return false;
        }

        // centres
        if(isset($data['centres']))
        {
            $colloque->centres()->attach($data['centres']);
        }

        // illustration
        if(isset($data['illustration']))
        {
            $colloque->documents()->create( ['colloque_id' => $colloque->id ,'display' => 1,'type' => 'illustration','titre' => 'Illustration','path' => $data['illustration'] ]);
        }

        return $colloque;
    }

    public function update(array $data){

        $colloque = $this->colloque->findOrFail($data['id']);

        if( ! $colloque )
        {
            return false;
        }

        $colloque->fill($data);

        $colloque->end_at    = (isset($data['end_at']) &&  !empty($data['end_at']) ? $data['end_at'] : null);
        $colloque->active_at = (isset($data['active_at']) &&  !empty($data['active_at']) ? $data['active_at'] : null);

        $colloque->save();

        // centres
        if(isset($data['centres']))
        {
            $colloque->centres()->sync($data['centres']);
        }

        return $colloque;
    }

    public function delete($id){

        $colloque = $this->colloque->find($id);

        return $colloque->delete();

    }
}
