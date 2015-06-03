<?php namespace App\Droit\Shop\Attribute\Repo;

interface AttributeInterface {

    public function getAll();
	public function find($data);
	public function create(array $data);
	public function update(array $data);
	public function delete($id);

}
