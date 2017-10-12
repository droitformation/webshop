<?php namespace App\Droit\Process\Repo;

interface JobInterface {

    public function getAll();
    public function find($id);
    public function delete($id);
}