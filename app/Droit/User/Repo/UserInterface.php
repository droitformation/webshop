<?php namespace App\Droit\User\Repo;

interface UserInterface {

    public function getAll();
    public function find($data);
    public function create(array $data);
    public function update(array $data);
    public function delete($id);
    public function findByUserNameOrCreate($userData);
    public function checkIfUserNeedsUpdating($userData, $user);

}
