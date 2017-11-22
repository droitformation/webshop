<?php namespace App\Droit\Newsletter\Repo;

interface NewsletterTrackingInterface {

	public function getAll();
	public function find($id);
	public function create(array $data);
	public function update(array $data);
	public function delete($id);
    public function logSent($data);
}
