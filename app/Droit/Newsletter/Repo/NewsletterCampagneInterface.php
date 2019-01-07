<?php namespace App\Droit\Newsletter\Repo;

interface NewsletterCampagneInterface {

	public function getAll();
    public function getAllBySite($site_id);
    public function getAllSent();
	public function getLastCampagne($newsletter_id = null);
    public function getLastCampagneBySite($site_id);
	public function getArchives($newsletter_id,$year);
    public function getArchivesBySite($site_id,$year);
	public function find($data);
    public function old($id);
	public function archive($id);
	public function create(array $data);
	public function update(array $data);
    public function updateStatus($data);
	public function delete($id);

}
