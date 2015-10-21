<?php namespace App\Droit\Author\Repo;

use App\Droit\Author\Repo\AuthorInterface;
use App\Droit\Author\Entities\Author as M;

class AuthorEloquent implements AuthorInterface{

    protected $author;

    public function __construct(M $author)
    {
        $this->author = $author;
    }

    public function getAll(){

        return $this->author->with(['analyses'])->orderBy('last_name', 'asc')->get();
    }

    public function find($id){

        return $this->author->with(['analyses'])->findOrFail($id);
    }

    public function create(array $data){

        $author = $this->author->create(array(
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'occupation' => $data['occupation'],
            'bio'        => $data['bio'],
            'photo'      => (isset($data['photo']) ? $data['photo'] : null),
            'rang'       => (isset($data['rang']) ? $data['rang'] : 0),
        ));

        if( ! $author )
        {
            return false;
        }

        return $author;

    }

    public function update(array $data){

        $author = $this->author->findOrFail($data['id']);

        if( ! $author )
        {
            return false;
        }

        $author->fill($data);

        if(isset($data['photo']) && !empty($data['photo'])){
            $author->photo  = $data['photo'];
        }

        if(isset($data['rang']) && !empty($data['rang'])){
            $author->rang  = $data['rang'];
        }

        $author->save();

        return $author;
    }

    public function delete($id){

        $author = $this->author->find($id);

        return $author->delete();
    }

}
