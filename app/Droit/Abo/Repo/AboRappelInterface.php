<?php namespace App\Droit\Abo\Repo;

interface AboRappelInterface {

    public function getAll();
    public function find($data);
    public function findByFacture($facture_id);
    public function create(array $data);
    public function update(array $data);
    public function delete($id);

}