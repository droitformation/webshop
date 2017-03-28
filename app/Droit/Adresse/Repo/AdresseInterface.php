<?php namespace App\Droit\Adresse\Repo;

interface AdresseInterface {

	public function getAll();
    public function getPaginate();
	/**
	 * Return all infos of the user
	 *
	 * @return stdObject Collection of users
	 */
	public function find($id);
	public function getLast($nbr);
    public function search($term);
    public function searchSimple($terms);
	public function findByEmail($email);
    public function searchMultiple($terms, $each = false);
	public function duplicates();
	public function setSpecialisation($adresse_id,$data);

	public function assignOrdersToUser($id, $user_id);
	
	public function getDeleted($term = null);
	
	// function for gather infos on adresse to show
	public function create(array $data);
	public function update(array $data);
    public function updateColumn($id , $column , $value);
    public function changeLivraison($adresse_id , $user_id);
	public function delete($id);
	
	// Ajax call fot tables
	public function get_ajax( $sEcho , $iDisplayStart , $iDisplayLength , $sSearch = NULL , $iSortCol_0, $sSortDir_0);

}
