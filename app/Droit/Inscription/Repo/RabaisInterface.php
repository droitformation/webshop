<?php namespace App\Droit\Inscription\Repo;

interface RabaisInterface {
    public function getAll();
    public function find($id);
    public function search($term);
    public function create(array $data);
    public function update(array $data);
    public function delete($id);
}