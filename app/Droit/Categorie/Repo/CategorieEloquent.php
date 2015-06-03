<?php namespace App\Droit\Categorie\Repo;

use App\Droit\Categorie\Repo\CategorieInterface;
use App\Droit\Categorie\Entities\Categorie as M;
use App\Droit\Categorie\Entities\Parent_categories as P;

class CategorieEloquent implements CategorieInterface{

    protected $categorie;
    protected $parent;
    protected $helper;

    public function __construct(M $categorie, P $parent)
    {
        $this->categorie = $categorie;
        $this->parent    = $parent;
        $this->helper    = new \App\Hub\Helper\Helper();
    }

    public function getAll(){

        return $this->categorie->with(array('parent'))->get();
    }

    public function getParent(){

        $parents = $this->getAll();

        if(!$parents->isEmpty())
        {
            foreach($parents as $parent){
                $allcats[$parent->parent->parent_id][] = $parent->id;
                $catsort[$parent->parent->parent_id][$parent->id] =  $parent->sorting;
            }

            $first_level = $allcats[2];

            foreach($first_level as $level)
            {
                if(isset($allcats[$level]))
                {
                    $data[$level] = $allcats[$level];
                }
                else{
                    $data[$level] = $level;
                }
            }

            // Sorting
            foreach($catsort as $cat => $sorting)
            {
                if(isset($data[$cat]))
                {
                    krsort($sorting);
                    $sorting = array_keys($sorting);
                    $sorted  = $this->helper->sortArrayByArray($data[$cat],$sorting);
                    $data[$cat] = $sorted;
                }
            }

           krsort($data);

        }

        return $data;

    }

    public function find($id){

        return $this->categorie->where('id','=',$id)->with(array('parent'))->get();
    }

    public function create(array $data){

        $categorie = $this->categorie->create(array(
            'title'   => $data['title'],
            'sorting' => $data['sorting'],
            'hidden'  => $data['hidden']
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
