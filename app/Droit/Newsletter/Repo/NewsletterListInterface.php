<?php namespace App\Droit\Newsletter\Repo;

interface NewsletterListInterface {

	public function getAll();
	public function find($id);
	public function emailExist($id,$email);
	public function create(array $data);
	public function update(array $data);
	public function delete($id);

}
