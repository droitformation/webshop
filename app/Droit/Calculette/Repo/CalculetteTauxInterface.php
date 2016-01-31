<?php namespace App\Droit\Calculette\Repo;

interface CalculetteTauxInterface {

    public function getAll();
    public function find($data);
    public function create(array $data);
    public function update(array $data);
    public function delete($id);

}
