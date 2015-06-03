<?php namespace App\Droit\Author\Repo;

use App\Droit\Author\Repo\AuthorInterface;
use App\Droit\Author\Author as M;

class AuthorEloquent implements AuthorInterface{

    protected $author;

    public function __construct(M $author)
    {
        $this->author = $author;
    }

    public function getAll(){

        return $this->author->all();
    }

    public function find($id){

        return $this->author->find($id);
    }

    public function create(array $data){

        $author = $this->author->create(array(
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name']
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

        $author->first_name = $data['first_name'];
        $author->last_name  = $data['last_name'];

        $author->save();

        return $author;
    }

    public function delete($id){

        $author = $this->author->find($id);

        return $author->delete($id);

    }

}
