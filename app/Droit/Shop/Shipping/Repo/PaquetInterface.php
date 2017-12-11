<?php namespace App\Droit\Shop\Shipping\Repo;

interface PaquetInterface {

    public function getAll();
	public function find($data);
	public function create(array $data);
	public function update(array $data);
	public function delete($id);
}
