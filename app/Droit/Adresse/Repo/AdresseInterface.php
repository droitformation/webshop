<?php namespace App\Droit\Adresse\Repo;

interface AdresseInterface {

	public function getAll();
    public function getPaginate();
	/**
	 * Return all infos of the user
	 *
	 * @return stdObject Collection of users
	 */
	public function find($data);
	public function getLast($nbr);
    public function searchSimple($terms);
	
	// function for gather infos on adresse to show
	public function show($id);
	
	public function isUser($adresse);
	public function adresseUser($user_id);
	public function infosIfUser($user_id);
	public function typeAdresse($adresse);
	
	public function create(array $data);
	public function update(array $data);
    public function updateColumn($id , $column , $value);
    public function changeLivraison($adresse_id , $user_id);
	public function delete($id);

    public function addSpecialisation($specialisation,$adresse_id);
    public function removeSpecialisation($specialisation,$adresse_id);
    public function addMembre($membre,$adresse_id);
    public function removeMembre($membre,$adresse_id);
	
	// Ajax call
	public function get_ajax( $sEcho , $iDisplayStart , $iDisplayLength , $sSearch = NULL , $iSortCol_0, $sSortDir_0);

}
