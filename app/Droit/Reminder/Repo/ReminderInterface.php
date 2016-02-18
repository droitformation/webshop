<?php namespace App\Droit\Reminder\Repo;

interface ReminderInterface {

    public function getAll();
    public function trashed();
    public function find($data);
    public function toSend();
    public function create(array $data);
    public function update(array $data);
    public function delete($id);

}