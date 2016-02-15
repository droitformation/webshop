<?php namespace App\Droit\Analyse\Repo;

use App\Droit\Analyse\Repo\AnalyseInterface;
use App\Droit\Analyse\Entities\Analyse as M;

class AnalyseEloquent implements AnalyseInterface{

	protected $analyse;

	/**
	 * Construct a new SentryUser Object
	 */
	public function __construct(M $analyse)
	{
		$this->analyse = $analyse;
	}

    public function getAll($site = null,$include = []){

        $analyse = $this->analyse->site($site)->with( array('analyse_authors','analyses_categories','analyses_arrets'));

        if(!empty($include))
        {
            $analyse->whereIn('id', $include);
        }

        return $analyse->orderBy('pub_date', 'DESC')->get();
    }

    public function getLast($nbr,$site)
    {
        return $this->analyse->with(['analyse_authors','analyses_categories','analyses_arrets'])->site($site)->orderBy('id', 'ASC')->take(5)->get();
    }

	public function find($id){
				
		return $this->analyse->with(array('analyse_authors','analyses_categories','analyses_arrets'))->find($id);
	}

	public function create(array $data){

		$analyse = $this->analyse->create(array(
			'user_id'    => $data['user_id'],
            'authors'    => $data['authors'],
            'pub_date'   => $data['pub_date'],
            'abstract'   => $data['abstract'],
            'file'       => $data['file'],
            'categories' => (isset($data['categories']) ? count($data['categories']) : 0),
            'arrets'     => (isset($data['arrets']) ? count($data['arrets']) : 0),
            'site_id'    => (isset($data['site_id']) ? $data['site_id'] : null),
			'created_at' => date('Y-m-d G:i:s'),
			'updated_at' => date('Y-m-d G:i:s')
		));

		if( ! $analyse )
		{
			return false;
		}

        if(isset($data['categories']))
        {
            // Insert related categories
            $analyse->analyses_categories()->sync($data['categories']);
        }

        if(isset($data['arrets']))
        {
            // Insert related arrets
            $analyse->analyses_arrets()->sync($data['arrets']);
        }

        if(isset($data['author_id']) && !empty($data['author_id']))
        {
            $analyse->analyse_authors()->sync($data['author_id']);
        }
		
		return $analyse;
		
	}
	
	public function update(array $data){

        $analyse = $this->analyse->findOrFail($data['id']);
		
		if( ! $analyse )
		{
			return false;
		}

        $analyse->fill($data);

        if(isset($data['file']))
        {
            $analyse->file = $data['file'];
        }

		$analyse->updated_at = date('Y-m-d G:i:s');

        if(isset($data['categories']))
        {
            // Insert related categories
            $analyse->categories = count($data['categories']);
            $analyse->analyses_categories()->sync($data['categories']);
        }

        if(isset($data['arrets']))
        {
            // Insert related arrets
            $analyse->arrets = count($data['arrets']);
            $analyse->analyses_arrets()->sync($data['arrets']);
        }

        if(isset($data['author_id']) && !empty($data['author_id']))
        {
            $analyse->analyse_authors()->sync($data['author_id']);
        }

		$analyse->save();
		
		return $analyse;
	}

	public function delete($id){

        $analyse = $this->analyse->find($id);

		return $analyse->delete();
		
	}

}
