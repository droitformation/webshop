<?php namespace  App\Droit\Shop\Categorie\Repo;

interface CategorieInterface {

    public function getAll();
    public function getParents();
    public function search($term);
    public function find($data);
    public function create(array $data);
    public function update(array $data);
    public function delete($id);

}
