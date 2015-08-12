<?php namespace App\Droit\Colloque\Repo;

interface ColloqueInterface {

    public function getAll();
    public function find($data);
    public function getNewNoInscription($colloque_id);
    public function create(array $data);
    public function update(array $data);
    public function delete($id);

}