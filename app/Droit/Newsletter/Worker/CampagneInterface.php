<?php namespace App\Droit\Newsletter\Worker;

interface CampagneInterface {

	public function prepareCampagne($id);
    public function getCategoriesArrets();
    public function getCampagne($id);
    public function html($id);

}
