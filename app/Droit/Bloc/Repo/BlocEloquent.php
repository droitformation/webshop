<?php namespace App\Droit\Bloc\Repo;

use App\Droit\Bloc\Repo\BlocInterface;
use App\Droit\Bloc\Entities\Bloc as M;

class BlocEloquent implements BlocInterface{

	protected $bloc;

	/**
	 * Construct a new SentryUser Object
	 */
	public function __construct(M $bloc)
	{
		$this->bloc = $bloc;
	}

    public function getAll(){

        return $this->bloc->all();
    }

	public function find($id){
				
		return $this->bloc->find($id);
	}

    public function findyByPosition(array $positions){

        return $this->bloc->whereIn('position', $positions)->orderBy('rang','ASC')->get();
    }

	public function findyByType($type){

		return $this->bloc->where('type','=',$type)->orderBy('rang','ASC')->get();
	}

	public function create(array $data){

		$bloc = $this->bloc->create([
			'title'      => (isset($data['title']) ? $data['title'] : ''),
			'content'    => $data['content'],
			'image'      => (isset($data['image']) ? $data['image'] : ''),
			'url'        => (isset($data['url']) ? $data['url'] : ''),
			'slug'       => $data['slug'],
			'rang'       => (isset($data['rang']) ? $data['rang'] : 0),
			'site_id'    => $data['site_id'],
			'type'       => $data['type'],
			'position'   => $data['position'],
			'page_id'    => $data['page_id'],
			'created_at' => date('Y-m-d G:i:s'),
			'updated_at' => date('Y-m-d G:i:s')
		]);

		if( ! $bloc )
		{
			return false;
		}
		
		return $bloc;
		
	}
	
	public function update(array $data){

        $bloc = $this->bloc->find($data['id']);
		
		if( ! $bloc )
		{
			return false;
		}

        $bloc->fill($data);

        if(isset($data['image']))
        {
            $bloc->image = $data['image'];
        }

		$bloc->updated_at = date('Y-m-d G:i:s');

		$bloc->save();
		
		return $bloc;
	}

	public function delete($id){

        $bloc = $this->bloc->find($id);

		return $bloc->delete();

	}

}
