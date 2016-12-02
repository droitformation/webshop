<?php

namespace App\Droit\Shop\Rappel\Repo;

interface RappelInterface
{
    public function getAll($period);
    public function find($id);
    public function create(array $data);
    public function update(array $data);
    public function delete($id);
}