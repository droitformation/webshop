<?php namespace App\Droit\Content\Repo;

use App\Droit\Content\Repo\ContentInterface;
use App\Droit\Content\Entities\Content as M;

class ContentEloquent implements ContentInterface{

	protected $content;

	/**
	 * Construct a new SentryUser Object
	 */
	public function __construct(M $content)
	{
		$this->content = $content;
	}

    public function getAll(){

        return $this->content->all();
    }

	public function find($id){
				
		return $this->content->findOrFail($id);
	}

    public function findyByPosition(array $positions){

        return $this->content->whereIn('position', $positions)->orderBy('rang','ASC')->get();
    }

	public function findyByType($type){

		return $this->content->where('type','=',$type)->orderBy('rang','ASC')->get();
	}

	public function create(array $data){

		$content = $this->content->create(array(
			'titre'      => $data['titre'],
			'contenu'    => $data['contenu'],
            'image'      => (isset($data['image']) ? $data['image'] : ''),
			'url'        => (isset($data['url']) ? $data['url'] : ''),
			'type'       => $data['type'],
			'position'   => $data['position'],
            'rang'       => (isset($data['rang']) ? $data['rang'] : 0),
			'created_at' => date('Y-m-d G:i:s'),
			'updated_at' => date('Y-m-d G:i:s')
		));

		if( ! $content )
		{
			return false;
		}
		
		return $content;
		
	}
	
	public function update(array $data){

        $content = $this->content->findOrFail($data['id']);
		
		if( ! $content )
		{
			return false;
		}

        $content->fill($data);

        if(isset($data['image']))
        {
            $content->image = $data['image'];
        }

		$content->updated_at = date('Y-m-d G:i:s');

		$content->save();
		
		return $content;
	}

	public function delete($id){

        $content = $this->content->find($id);

		return $content->delete();

	}

}
