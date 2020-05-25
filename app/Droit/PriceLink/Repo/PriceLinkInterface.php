<?php namespace App\Droit\PriceLink\Repo;

interface PriceLinkInterface {

    public function getAll();
    public function find($data);
    public function create(array $data);
    public function update(array $data);
    public function delete($id);

}