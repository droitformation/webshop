<?php namespace App\Droit\Arret\Repo;

interface GroupeInterface {

    public function getAll();
	public function find($id);
    public function findAll($data);
	public function create(array $data);
	public function update(array $data);
	public function restore($id);
	public function delete($id);

}
