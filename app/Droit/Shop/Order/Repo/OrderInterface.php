<?php namespace App\Droit\Shop\Order\Repo;

interface OrderInterface {

    public function getAll();
	public function find($data);
    public function maxOrder($year);
	public function create(array $data);
	public function update(array $data);
	public function delete($id);
}
