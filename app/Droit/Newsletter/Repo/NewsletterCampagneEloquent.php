<?php namespace App\Droit\Newsletter\Repo;

use App\Droit\Newsletter\Repo\NewsletterCampagneInterface;
use App\Droit\Newsletter\Entities\Newsletter_campagnes as M;

class NewsletterCampagneEloquent implements NewsletterCampagneInterface{

	protected $campagne;

	public function __construct(M $campagne)
	{
		$this->campagne = $campagne;
	}
	
	public function getAll(){
		
		return $this->campagne->orderBy('created_at','DESC')->get();
	}

    public function getAllSent(){

        return $this->campagne->where('status','=','envoyé')->orderBy('id','DESC')->get();
    }

    public function getLastCampagne(){

        return $this->campagne->where('status','=','envoyé')->orderBy('id','DESC')->get();
    }

	public function find($id){
				
		return $this->campagne->with(array('newsletter','content'))->findOrFail($id);
	}

	public function create(array $data){

		$campagne = $this->campagne->create(array(
			'sujet'          => $data['sujet'],
			'auteurs'        => $data['auteurs'],
            'newsletter_id'  => $data['newsletter_id'],
			'created_at'     => date('Y-m-d G:i:s'),
			'updated_at'     => date('Y-m-d G:i:s')
		));
		
		if( ! $campagne )
		{
			return false;
		}
		
		return $campagne;
		
	}
	
	public function update(array $data){

        $campagne = $this->campagne->findOrFail($data['id']);
		
		if( ! $campagne )
		{
			return false;
		}

        $campagne->fill($data);
		$campagne->updated_at = date('Y-m-d G:i:s');

		$campagne->save();
		
		return $campagne;
	}

    public function updateStatus($data){

        $campagne = $this->campagne->findOrFail($data['id']);

        if( ! $campagne )
        {
            return false;
        }

        $campagne->status      = $data['status'];
        $campagne->updated_at  = date('Y-m-d G:i:s');

        $campagne->save();

        return $campagne;
    }

	public function delete($id){

        $campagne = $this->campagne->find($id);

		return $campagne->delete();
		
	}
}
