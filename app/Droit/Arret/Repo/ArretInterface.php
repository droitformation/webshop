<?php namespace App\Droit\Arret\Repo;

interface ArretInterface {

    public function getAll($site = null);
    public function annees($site);
    public function getAllActives($include = [], $site = null);
    public function getPaginate($nbr);
    public function getLatest($include = []);
	public function find($data);
    public function findyByImage($file);
	public function create(array $data);
	public function update(array $data);
	public function delete($id);

}
