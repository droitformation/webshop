<?php namespace App\Droit\Bloc\Repo;

interface BlocInterface {

    public function getAll();
	public function find($id);
    public function findyByPosition(array $positions);
	public function findyByType($type);
	public function create(array $data);
	public function update(array $data);
	public function delete($id);

}
