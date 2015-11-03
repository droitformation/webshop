<?php namespace App\Droit\Member\Repo;

interface MemberInterface {

    public function getAll();
    public function find($data);
    public function search($term, $like = null);
    public function create(array $data);
    public function update(array $data);
    public function delete($id);

}