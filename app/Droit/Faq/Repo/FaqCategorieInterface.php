<?php namespace  App\Droit\Faq\Repo;

interface FaqCategorieInterface {

    public function getAll($site = null);
    public function find($data);
    public function create(array $data);
    public function update(array $data);
    public function delete($id);

}
