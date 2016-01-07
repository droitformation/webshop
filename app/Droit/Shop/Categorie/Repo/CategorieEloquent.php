<?php namespace  App\Droit\Shop\Categorie\Repo;

use  App\Droit\Shop\Categorie\Repo\CategorieInterface;
use  App\Droit\Shop\Categorie\Entities\Categorie as M;

class CategorieEloquent implements CategorieInterface{

    protected $categorie;

    public function __construct(M $categorie)
    {
        $this->categorie = $categorie;
    }

    public function getAll(){

        return $this->categorie->orderBy('title', 'ASC')->get();
    }

    public function find($id){

        return $this->categorie->findOrFail($id);
    }

    public function create(array $data){

        $categorie = $this->categorie->create(array(
            'title'     => $data['title'],
            'rang'      => (isset($data['rang']) ? $data['rang'] : 0),
            'image'     => (isset($data['image']) ? $data['image'] : null),
            'parent_id' => (isset($data['parent_id']) ? $data['parent_id'] : null)
        ));

        if( ! $categorie )
        {
            return false;
        }

        return $categorie;

    }

    public function update(array $data){

        $categorie = $this->categorie->findOrFail($data['id']);

        if( ! $categorie )
        {
            return false;
        }

        $categorie->fill($data);

        $categorie->save();

        return $categorie;
    }

    public function delete($id){

        $categorie = $this->categorie->find($id);

        return $categorie->delete();
    }

}
