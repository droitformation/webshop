<?php namespace App\Droit\Email\Repo;

interface EmailInterface {

    public function getAll($period);
    public function find($id);
    public function search($email);
    public function delete($id);
}