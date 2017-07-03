<?php namespace App\Droit\Inscription\Repo;

interface InscriptionInterface {

    public function getAll($nbr = null);
    public function getMultiple(array $inscriptions);
    public function getByColloque($id,$type = false,$paginate = false);
    public function getByColloqueExport($id,$occurrence = []);
    public function getRappels($id);
    public function getByUser($colloque_id,$user_id);
    public function isRegistered($colloque_id,$user_id);
    public function getByColloqueTrashed($id);
    public function getByGroupe($groupe_id);
    public function hasPayed($user_id);
    public function getYear($year,$month = null);
    public function find($id);
    public function findByNumero($numero,$colloque_id);
    public function restore($id);
    public function create(array $data);
    public function update(array $data);
    public function updateColumn(array $data);
    public function delete($id);

}