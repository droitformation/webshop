<?php namespace App\Droit\Shop\Attribute\Repo;

use App\Droit\Shop\Attribute\Repo\AttributeInterface;
use App\Droit\Shop\Attribute\Entities\Attribute as M;

class AttributeEloquent implements AttributeInterface{

    protected $attribute;

    public function __construct(M $attribute)
    {
        $this->attribute = $attribute;
    }

    public function getAll($reminder = false)
    {
        return $this->attribute->reminder($reminder)->with(['attributs'])->get();
    }

    public function find($id){

        return $this->attribute->find($id);
    }

    public function create(array $data){

        $attribute = $this->attribute->create(array(
            'title'    => $data['title'],
            'reminder' => (isset($data['reminder']) && !empty($data['reminder']) ? 1 : null),
            'text'     => (isset($data['text']) ? $data['text'] : null)
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

        $attribute->fill($data);

        $attribute->save();

        return $attribute;
    }

    public function delete($id){

        return $this->attribute->delete($id);

    }

}
