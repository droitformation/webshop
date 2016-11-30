<?php namespace App\Droit\Abo\Repo;

interface AboFactureInterface {

    public function getAll($product_id);
    public function getMultiple($ids);
    public function find($data);
    public function findByUserAndProduct($abo_user_id, $product_id);
    public function findByProduct($product_id);
    public function getFacturesAndRappels($product_id);
    public function create(array $data);
    public function update(array $data);
    public function delete($id);

}