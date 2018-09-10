<?php namespace App\Droit\Sondage\Repo;

interface AvisInterface {

    public function getAll($withhidden = null);
    public function find($data);
    public function create(array $data);
    public function update(array $data);
    public function delete($id);

}