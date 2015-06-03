<?php namespace App\Hub\Shop\Tax\Repo;

interface TaxInterface {

    public function getAll();
	public function find($data);
	public function create(array $data);
	public function update(array $data);
	public function delete($id);
}
