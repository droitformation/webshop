<?php namespace App\Droit\Abo\Repo;

interface AboInterface {

    public function getAll();
    public function getAllFrontend();
    public function find($data);
    public function findAboByProduct($id);
    public function create(array $data);
    public function update(array $data);
    public function delete($id);

}