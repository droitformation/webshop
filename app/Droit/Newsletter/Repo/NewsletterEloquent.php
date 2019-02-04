<?php namespace App\Droit\Newsletter\Repo;

use App\Droit\Newsletter\Repo\NewsletterInterface;
use App\Droit\Newsletter\Entities\Newsletter as M;

class NewsletterEloquent implements NewsletterInterface{

	protected $newsletter;

	public function __construct(M $newsletter)
	{
		$this->newsletter = $newsletter;
	}
	
	public function getAll(){
		
		return $this->newsletter->with(['campagnes','sent','draft','pending'])->get();
	}

	public function find($id){
				
		return $this->newsletter->with(['site','specialisations'])->find($id);
	}

	public function findMultiple($ids)
	{
		return $this->newsletter->with(['site'])->whereIn('id',$ids)->get();
	}

	public function getSite($site_id)
	{
		return $this->newsletter->with(['campagnes'])->where('site_id', '=', $site_id)->get();
	}

	public function create(array $data){

		$newsletter = $this->newsletter->create(array(
			'titre'        => $data['titre'],
			'from_name'    => $data['from_name'],
            'from_email'   => $data['from_email'],
            'return_email' => $data['return_email'],
            'unsuscribe'   => $data['unsuscribe'],
            'preview'      => $data['preview'],
            'list_id'      => (isset($data['list_id']) ? $data['list_id'] : ''),
            'color'        => (isset($data['color']) ? $data['color'] : ''),
            'logos'        => (isset($data['logos']) ? $data['logos'] : ''),
            'header'       => (isset($data['header']) ? $data['header'] : ''),
            'second_color' => (isset($data['second_color']) ? $data['second_color'] : null),
            'static'       => (isset($data['static']) ? $data['static'] : null),
			'created_at'   => date('Y-m-d G:i:s'),
			'updated_at'   => date('Y-m-d G:i:s')
		));
		
		if( ! $newsletter ) {
			return false;
		}

        if($newsletter->static){
            $newsletter->specialisations()->attach($data['specialisations']);
            event(new \App\Events\NewsletterStaticCreated($newsletter,$data['$name']));
        }
		
		return $newsletter;
		
	}
	
	public function update(array $data){

        $newsletter = $this->newsletter->findOrFail($data['id']);
		
		if( ! $newsletter )
		{
			return false;
		}

        $newsletter->fill($data);
		$newsletter->updated_at = date('Y-m-d G:i:s');
        $newsletter->second_color = isset($data['second_color']) && !empty($data['second_color']) ? $data['second_color'] : null;
        $newsletter->pdf = isset($data['pdf']) && !empty($data['pdf']) ? $data['pdf'] : null;
        $newsletter->comment = isset($data['comment']) && !empty($data['comment']) ? $data['comment'] : null;
        $newsletter->classe = isset($data['classe']) && !empty($data['classe']) ? $data['classe'] : null;
        $newsletter->comment_title = isset($data['comment_title']) && !empty($data['comment_title']) ? $data['comment_title'] : null;
        $newsletter->hide_title = isset($data['hide_title']) && !empty($data['hide_title']) ? $data['hide_title'] : null;
        $newsletter->display = isset($data['display']) && !empty($data['display']) ? $data['display'] : null;

        $newsletter->save();
		
		return $newsletter;
	}

	public function delete($id){

        $newsletter = $this->newsletter->find($id);

		return $newsletter->delete();
		
	}
}
