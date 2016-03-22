<?php namespace App\Droit\Shop\Product\Repo;

interface ProductInterface {

	public function getAll($search = null, $nbr = null);
	public function getNbr($nbr = null,$reject = null);
    public function getSome($ids);
	public function getByCategorie($id);
	public function search($term);
	public function find($data);
	public function sku($product_id, $qty, $operator);
	public function create(array $data);
	public function update(array $data);
	public function delete($id);

}

