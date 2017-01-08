<?php namespace App\Droit\Calculette\Repo;

use App\Droit\Calculette\Repo\CalculetteInterface;
use App\Droit\Calculette\Entities\Calculette_taux as M;

class CalculetteTauxEloquent implements CalculetteTauxInterface{

	protected $calculette;

	public function __construct(M $calculette)
	{
		$this->calculette = $calculette;
	}

	public function getAll(){

		return $this->calculette->orderBy('start_at','DES')->get();
	}

	public function find($id){

		return $this->calculette->find($id);
	}

	public function create(array $data){

		$calculette = $this->calculette->create(array(
            'start_at' => $data['start_at'],
            'canton'   => $data['canton'],
            'taux'     => $data['taux'],
		));

		if( ! $calculette )
		{
			return false;
		}

		return $calculette;
	}

	public function update(array $data){

		$calculette = $this->calculette->findOrFail($data['id']);

		if( ! $calculette )
		{
			return false;
		}

		$calculette->fill($data);
		$calculette->save();

		return $calculette;
	}

	public function delete($id){

		$calculette = $this->calculette->find($id);

		return $calculette->delete();
	}

}
