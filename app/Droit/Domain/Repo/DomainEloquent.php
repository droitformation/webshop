<?php namespace App\Droit\Domain\Repo;

use App\Droit\Domain\Repo\DomainInterface;
use App\Droit\Domain\Entities\Domain as M;

class DomainEloquent implements DomainInterface{

    protected $domain;

    public function __construct(M $domain)
    {
        $this->domain = $domain;
    }

    public function getAll(){

        return $this->domain->all();
    }

    public function search($term)
    {
        return $this->domain->where('title', 'like', '%'.$term.'%')->get();
    }

    public function find($id){

        return $this->domain->find($id);
    }

    public function create(array $data){

        $domain = $this->domain->create(array(
            'title' => $data['title']
        ));

        if( ! $domain )
        {
            return false;
        }

        return $domain;

    }

    public function update(array $data){

        $domain = $this->domain->findOrFail($data['id']);

        if( ! $domain )
        {
            return false;
        }

        $domain->domain = $data['domain'];

        $domain->save();

        return $domain;
    }

    public function delete($id){

        $domain = $this->domain->find($id);

        return $domain->delete();

    }
}
