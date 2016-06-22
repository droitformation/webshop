<?php namespace App\Droit\Arret\Repo;

use App\Droit\Arret\Repo\ArretInterface;
use App\Droit\Arret\Entities\Arret as M;

class ArretEloquent implements ArretInterface{

	protected $arret;

	public function __construct(M $arret)
	{
		$this->arret = $arret;
	}

    public function getAll($site = null)
    {
        return $this->arret->with(['categories','analyses'])->site($site)->orderBy('reference', 'ASC')->get();
    }

    public function getCount($site = null)
    {
        return $this->arret->site($site)->select('id')->get();
    }

    public function getLast($nbr,$site)
    {
        return $this->arret->with(['categories','analyses'])->site($site)->orderBy('id', 'ASC')->take(5)->get();
    }

    public function annees($site)
    {
        $arrets   = $this->arret->where('site_id','=',$site)->get();

        $prepared = $arrets->lists('pub_date');

        $grouped = $prepared->groupBy(function ($item, $key) {
            return $item->year;
        });

        return array_reverse(array_keys($grouped->toArray()));
    }

    public function getAllActives($include = [], $site = null)
    {
        $arrets = $this->arret->where('site_id','=',$site)->with(['categories','analyses']);

        if(!empty($include))
        {
            $arrets->whereIn('id', $include);
        }

        return $arrets->orderBy('reference', 'ASC')->get();
    }

    public function getPaginate($nbr)
    {
        return $this->arret->with(['categories','analyses'])->orderBy('pub_date', 'DESC')->paginate($nbr);
    }

    public function getLatest($include = []){

        if(!empty($include))
        {
            $arrets = $this->arret->whereIn('id', $include)->with(['analyses'])->orderBy('id', 'ASC')->get();

            $new = $arrets->filter(function($item)
            {
                if (!$item->analyses->isEmpty()) {
                    return true;
                }
            });

            return $new->take(5);
        }

        return false;
    }

	public function find($id, $trashed = null){

        if(is_array($id))
        {
            return $this->arret->trashed($trashed)->whereIn('id', $id)->with(['categories','analyses'])->get();
        }

		return $this->arret->with(['categories','analyses'])->where('id','=',$id)->trashed($trashed)->get()->first();
	}

    public function findyByImage($file){

        return $this->arret->where('file','=',$file)->get();
    }

	public function create(array $data){

		$arret = $this->arret->create(array(
			'site_id'    => $data['site_id'],
            'user_id'    => isset($data['user_id']) ? $data['user_id'] : null,
            'reference'  => $data['reference'],
            'pub_date'   => $data['pub_date'],
            'abstract'   => $data['abstract'],
            'pub_text'   => $data['pub_text'],
            'file'       => $data['file'],
            'dumois'     => (isset($data['dumois']) && $data['dumois'] == 1 ? 1 : 0),
			'created_at' => date('Y-m-d G:i:s'),
			'updated_at' => date('Y-m-d G:i:s')
		));

		if( ! $arret )
		{
			return false;
		}

        // Insert related categories
        if(isset($data['categories']))
        {
            $arret->categories()->sync($data['categories']);
        }

		return $arret;
		
	}
	
	public function update(array $data){

        $arret = $this->arret->findOrFail($data['id']);
		
		if( ! $arret )
		{
			return false;
		}

        $arret->fill($data);

        if(isset($data['file']))
        {
            $arret->file = $data['file'];
        }

		$arret->updated_at = date('Y-m-d G:i:s');

		$arret->save();

        // Insert related categories
        if(isset($data['categories']))
        {
            $arret->categories()->sync($data['categories']);
        }
		
		return $arret;
	}

	public function delete($id){

        $arret = $this->arret->find($id);

		return $arret->delete();
	}

}
