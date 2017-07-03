<?php namespace App\Droit\Shop\Order\Repo;

interface OrderInterface {

    public function getLast($nbr);
    public function getTrashed($period);
    public function getPeriod($data);
    public function getRappels($period);
    public function getMultiple($orders);
    public function search($order_no);
    public function lastYear();
    public function getYear($year,$month = null);
	public function find($data);
    public function maxOrder($year);
    public function hasPayed($user_id);
    public function newOrderNumber();
	public function create(array $data);
	public function update(array $data);
    public function updateDate(array $data);
	public function delete($id);
    public function restore($id);
}
