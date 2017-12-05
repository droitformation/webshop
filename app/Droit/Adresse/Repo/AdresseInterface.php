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
	public function setMember($adresse_id,$data);

	public function assignOrdersToUser($id, $user_id);

	// Deleted adresses filters
	public function getDeleted($terms = [], $operator = null);
	public function getMultiple($ids);
	public function findWithTrashed($id);
	
	// function for gather infos on adresse to show
	public function create(array $data);
	public function update(array $data);
    public function updateColumn($id , $column , $value);
    public function changeLivraison($adresse_id , $user_id);
	public function delete($id);
	public function restore($id);
	public function getBySpecialisations($specialisations);
	
	// Ajax call fot tables
	public function get_ajax( $sEcho , $iDisplayStart , $iDisplayLength , $sSearch = NULL , $iSortCol_0, $sSortDir_0);

}
