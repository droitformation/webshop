<?php namespace App\Droit\Shop\Author\Repo;

interface AuthorInterface {

    public function getAll();
    public function find($data);
    public function search($term);
    public function create(array $data);
    public function update(array $data);
    public function delete($id);

}
