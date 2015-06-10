<?php namespace App\Droit\Shop\Shipping\Repo;

interface ShippingInterface {

    public function getAll($type = null);
	public function find($data);
    public function getShipping($weight = null);
	public function create(array $data);
	public function update(array $data);
	public function delete($id);
}
