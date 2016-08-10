<?php namespace App\Droit\Abo\Repo;

interface AboUserInterface {

    public function getAll();
    public function find($data);
    public function max($abo_id);
    public function findByAdresse($id, $abo_id);
    public function create(array $data);
    public function update(array $data);
    public function restore($id);
    public function delete($id);
}