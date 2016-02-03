<?php namespace  App\Droit\Faq\Repo;

use  App\Droit\Faq\Repo\FaqCategorieInterface;
use  App\Droit\Faq\Entities\Faq_categorie as M;

class FaqCategorieEloquent implements FaqCategorieInterface{

    protected $categorie;

    public function __construct(M $categorie)
    {
        $this->categorie = $categorie;
    }

    public function getAll($site = null)
    {
        return $this->categorie->sites($site)->with(['questions'])->orderBy('rang', 'ASC')->get();
    }

    public function find($id){

        return $this->categorie->find($id);
    }

    public function create(array $data){

        $categorie = $this->categorie->create(array(
            'title'      => $data['title'],
            'site_id'    => (isset($data['site_id']) ? $data['site_id'] : null),
            'rang'       => (isset($data['rang']) && $data['rang'] > 1 ? $data['rang'] : 0),
            'created_at' => date('Y-m-d G:i:s'),
            'updated_at' => date('Y-m-d G:i:s')
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

        $categorie->updated_at = date('Y-m-d G:i:s');
        $categorie->save();

        return $categorie;
    }

    public function delete($id){

        $categorie = $this->categorie->find($id);

        return $categorie->delete();
    }

}
