<?php namespace App\Droit\Shop\Order\Repo;

interface OrderInterface {

    public function getPeriod($start,$end,$status = null, $onlyfree = null);
    public function lastYear();
	public function find($data);
    public function maxOrder($year);
    public function newOrderNumber();
	public function create(array $data);
	public function update(array $data);
	public function delete($id);
}
