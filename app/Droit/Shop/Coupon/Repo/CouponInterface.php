<?php namespace App\Droit\Shop\Coupon\Repo;

interface CouponInterface {

    public function getAll();
	public function find($data);
    public function findByTitle($title);
    public function getGlobal();
	public function getValid();
	public function create(array $data);
	public function update(array $data);
	public function delete($id);
}
