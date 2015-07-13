<?php namespace App\Droit\Shop\Cart\Repo;

interface CartInterface {

    public function getAll();
	public function find($data);
	public function create(array $data);
	public function update(array $data);
	public function delete($id);
}
