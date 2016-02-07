<?php namespace App\Droit\User\Repo;

interface DuplicateInterface {

    public function getAll();
    public function find($data);
    public function search($term);
    public function create(array $data);
    public function update(array $data);
    public function delete($id);

}
