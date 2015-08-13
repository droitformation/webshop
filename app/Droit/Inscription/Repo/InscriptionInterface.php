<?php namespace App\Droit\Inscription\Repo;

interface InscriptionInterface {

    public function getAll();
    public function getByColloque($id);
    public function getByUser($colloque_id,$user_id);
    public function find($data);
    public function create(array $data);
    public function update(array $data);
    public function delete($id);

}