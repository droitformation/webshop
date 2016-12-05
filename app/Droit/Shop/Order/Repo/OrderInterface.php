<?php namespace App\Droit\Shop\Order\Repo;

interface OrderInterface {

    public function getLast($nbr);
    public function getTrashed($start, $end);
    public function getPeriod($period, $status = null, $send = null, $onlyfree = null);
    public function getRappels($period);
    public function getMultiple($orders);
    public function search($order_no);
    public function lastYear();
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
