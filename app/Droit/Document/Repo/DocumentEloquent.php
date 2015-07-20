<?php namespace App\Droit\Document\Repo;

use App\Droit\Document\Repo\DocumentInterface;
use App\Droit\Document\Entities\Document as M;

class DocumentEloquent implements DocumentInterface{

    protected $document;

    public function __construct(M $document)
    {
        $this->document = $document;
    }

    public function getAll(){

        return $this->document->all();
    }

    public function find($id){

        return $this->document->find($id);
    }

    public function create(array $data){

        $document = $this->document->create(array(
            'name'    => $data['name'],
            'adresse' => $data['adresse'],
            'url'     => $data['name'],
            'map'     => $data['map']
        ));

        if( ! $document )
        {
            return false;
        }

        return $document;

    }

    public function update(array $data){

        $document = $this->document->findOrFail($data['id']);

        if( ! $document )
        {
            return false;
        }

        $document->fill($data);

        $document->save();

        return $document;
    }

    public function delete($id){

        $document = $this->document->find($id);

        return $document->delete();

    }
}
