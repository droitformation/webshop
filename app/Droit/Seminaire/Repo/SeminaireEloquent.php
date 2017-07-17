<?php namespace App\Droit\Seminaire\Repo;

use App\Droit\Seminaire\Repo\SeminaireInterface;
use App\Droit\Seminaire\Entities\Seminaire as M;

class SeminaireEloquent implements SeminaireInterface{

    protected $seminaire;

    public function __construct(M $seminaire)
    {
        $this->seminaire = $seminaire;
    }

    public function getAll(){

        return $this->seminaire->with(['subjects','product','colloques'])->orderBy('year','DESC')->get();
    }

    public function find($id){

        return $this->seminaire->with(['subjects','product','colloques'])->find($id);
    }

    public function create(array $data){

        $seminaire = $this->seminaire->create(array(
            'title'      => $data['title'],
            'year'       => $data['year'],
            'image'      => isset($data['image']) ? $data['image'] : null,
            'product_id' => isset($data['product_id']) ? $data['product_id'] : null,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now()
        ));

        if( ! $seminaire )
        {
            return false;
        }

        // colloques
        if(isset($data['colloque_id']))
        {
            $seminaire->colloques()->attach($data['colloque_id']);
        }

        return $seminaire;
    }

    public function update(array $data){

        $seminaire = $this->seminaire->findOrFail($data['id']);

        if( ! $seminaire )
        {
            return false;
        }

        $seminaire->fill($data);
        $seminaire->save();

        // colloques
        if(isset($data['colloque_id']))
        {
            $seminaire->colloques()->sync($data['colloque_id']);
        }

        return $seminaire;
    }

    public function delete($id){

        $seminaire = $this->seminaire->find($id);

        return $seminaire->delete();

    }
}
