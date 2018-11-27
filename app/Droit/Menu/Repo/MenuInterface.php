<?php namespace App\Droit\Menu\Repo;

interface MenuInterface {

    public function getAll($site = null);
    public function getBySitePosition($site_id,$position);
    public function find($data);
    public function create(array $data);
    public function update(array $data);
    public function delete($id);

}