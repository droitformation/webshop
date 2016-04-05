<?php namespace App\Droit\Colloque\Repo;

interface ColloqueInterface {

    public function getAll($active = false, $archives = false);
    public function getCurrent($registration = false, $finished = false, $visible = true);
    public function getByYear($year);
    public function search($term);
    public function getYears();
    public function find($data);
    public function getNewNoInscription($colloque_id);
    public function increment($colloque_id);
    public function create(array $data);
    public function update(array $data);
    public function delete($id);

}