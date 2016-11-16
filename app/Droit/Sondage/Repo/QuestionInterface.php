<?php namespace App\Droit\Sondage\Repo;

interface QuestionInterface {

    public function getAll();
    public function find($data);
    public function create(array $data);
    public function update(array $data);
    public function delete($id);

}