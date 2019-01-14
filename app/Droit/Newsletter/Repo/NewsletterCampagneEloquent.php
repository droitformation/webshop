<?php namespace App\Droit\Newsletter\Repo;

use App\Droit\Newsletter\Repo\NewsletterCampagneInterface;
use App\Droit\Newsletter\Entities\Newsletter_campagnes as M;

class NewsletterCampagneEloquent implements NewsletterCampagneInterface{

	protected $campagne;

	public function __construct(M $campagne)
	{
		$this->campagne = $campagne;
	}

	// used in arrtes to hide campagneworker
	public function getAll($newsletter_id = null)
	{
		return $this->campagne->news($newsletter_id)->orderBy('created_at','DESC')->get();
	}

    // used in arrtes to hide campagneworker
    public function getAllBySite($site_id)
    {
        return $this->campagne->where('status','=','brouillon')->whereHas('newsletter', function ($query) use ($site_id){
            $query->where('site_id', '=', $site_id);
        })->orderBy('created_at','DESC')->get();
    }

    public function getAllSent(){

        return $this->campagne->where('status','=','envoyé')->orderBy('id','DESC')->get();
    }

    public function getLastCampagne($newsletter_id = null)
	{
        return $this->campagne->where('status','=','envoyé')->whereNull('hidden')->news($newsletter_id)->orderBy('created_at','DESC')->get();
    }

    public function getLastCampagneBySite($site_id)
    {
        $campagne = $this->campagne->where('status','=','envoyé')->whereNull('hidden')->whereHas('newsletter', function ($query) use ($site_id){
            $query->where('site_id', '=', $site_id);
        })->orderBy('send_at','DESC')->get();

        return !$campagne->isEmpty() ? $campagne->first() : null;
    }

    public function getArchives($newsletter_id,$year)
	{
		return $this->campagne->where('newsletter_id','=',$newsletter_id)
			->where('status','=','envoyé')
			->where(function ($query) {
				$query->whereNotNull('send_at');
			})
			->whereRaw('YEAR(`send_at`) = ?', [$year])
			->orderBy('send_at','DESC')
			->get();
	}

    public function getArchivesBySite($site_id,$year)
    {
        return $this->campagne->where('status','=','envoyé')
            ->with(['newsletter'])
            ->year($year)
            ->where(function ($query) {
                $query->whereDate('send_at', '<', \Carbon\Carbon::now())->orWhereNull('send_at');
            })
            ->whereHas('newsletter', function ($query) use ($site_id) {
                $query->where('site_id', '=', $site_id);
            })->orderBy('send_at','DESC')
            ->get();
    }

	public function find($id)
	{
        $with = ['newsletter','content'];

		$with = config('newsletter.multi') ? array_merge($with,['newsletter.site']) : $with;
		$with = in_array(5,array_keys(config('newsletter.components'))) ? array_merge($with,['content.arret']) : $with;
        $with = in_array(7,array_keys(config('newsletter.components'))) ? array_merge($with,['content.groupe.arrets','content.groupe']) : $with;

		return $this->campagne->with($with)->find($id);
	}

    public function old($id)
    {
        $with = ['newsletter','content'];

        $with = config('newsletter.multi') ? array_merge($with,['newsletter.site']) : $with;
        $with = in_array(5,array_keys(config('newsletter.components'))) ? array_merge($with,['content.arret']) : $with;
        $with = in_array(7,array_keys(config('newsletter.components'))) ? array_merge($with,['content.groupe.arrets','content.groupe']) : $with;

        return $this->campagne->with($with)->where('old_id','=',$id)->first();
    }

	public function archive($id)
	{
		$campagne = $this->campagne->find($id);
		
		$campagne->send_at = \Carbon\Carbon::now()->toDateTimeString();
		$campagne->status  = 'envoyé';

		return $campagne->save();
	}

	public function create(array $data){

		$campagne = $this->campagne->create(array(
			'sujet'          => $data['sujet'],
			'auteurs'        => $data['auteurs'],
            'newsletter_id'  => $data['newsletter_id'],
			'hidden'         => isset($data['hidden']) && $data['hidden'] > 0 ? 1 : null,
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

		$campagne->hidden = isset($data['hidden']) && $data['hidden'] > 0 ? 1 : null;

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
