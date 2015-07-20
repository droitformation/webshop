<?php namespace App\Droit\Document\Repo;

interface DocumentInterface {

    public function getAll();
    public function find($data);
    public function create(array $data);
    public function update(array $data);
    public function delete($id);

}