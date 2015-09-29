<?php namespace App\Droit\Organisateur\Repo;

interface OrganisateurInterface {

    public function getAll();
    public function find($data);
    public function centres();
    public function create(array $data);
    public function update(array $data);
    public function delete($id);
}