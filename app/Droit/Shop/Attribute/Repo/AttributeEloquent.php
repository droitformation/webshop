<?php namespace App\Droit\Shop\Attribute\Repo;

use App\Droit\Shop\Attribute\Repo\AttributeInterface;
use App\Droit\Shop\Attribute\Entities\Attribute as M;

class AttributeEloquent implements AttributeInterface{

    protected $attribute;

    public function __construct(M $attribute)
    {
        $this->attribute = $attribute;
    }

    public function getAll(){

        return $this->attribute->with(['attributs'])->get();
    }

    public function find($id){

        return $this->attribute->find($id);
    }

    public function create(array $data){

        $attribute = $this->attribute->create(array(
            'title' => $data['title']
        ));

        if( ! $attribute )
        {
            return false;
        }

        return $attribute;

    }

    public function update(array $data){

        $attribute = $this->attribute->findOrFail($data['id']);

        if( ! $attribute )
        {
            return false;
        }

        $attribute->title = $data['title'];

        $attribute->save();

        return $attribute;
    }

    public function delete($id){

        return $this->attribute->delete($id);

    }

}
