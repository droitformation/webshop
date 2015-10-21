<?php namespace App\Droit\Arret\Repo;

interface ArretInterface {

    public function getAll();
    public function getAllActives($include = []);
    public function getPaginate($nbr);
    public function getLatest($include = []);
	public function find($data);
    public function findyByImage($file);
	public function create(array $data);
	public function update(array $data);
	public function delete($id);

}
