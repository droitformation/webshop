<?php namespace App\Droit\Newsletter\Repo;

use App\Droit\Newsletter\Entities\Newsletter_tracking as M;
use App\Droit\Newsletter\Repo\NewsletterTrackingInterface;

class NewsletterTrackingEloquent implements NewsletterTrackingInterface{

	protected $tracking;

	public function __construct(M $tracking)
	{
		$this->tracking = $tracking;
	}
	
	public function getAll(){
		
		return $this->tracking->all();
	}
	
	public function find($id){

		return $this->tracking->findOrFail($id);
	}

    public function create(array $data){

        $tracking = $this->tracking->create(array(
            'event' => $data['event'],
            'time' => $data['time'],
            'MessageID' => $data['MessageID'],
            'email' => $data['email'],
            'mj_campaign_id' => $data['mj_campaign_id'],
            'mj_contact_id' => $data['mj_contact_id'],
            'customcampaign' => $data['customcampaign'],
            'mj_message_id' => $data['mj_message_id'],
            'smtp_reply' => $data['smtp_reply'],
            'CustomID' => $data['CustomID'],
            'Payload' => $data['Payload'],
        ));

        if( ! $tracking )
        {
            return false;
        }

        return $tracking;
    }

    public function update(array $data){

        $tracking = $this->tracking->findOrFail($data['id']);

        if( !$tracking )
        {
            return false;
        }

        $tracking->fill($data);
        $tracking->save();

        return $tracking;
    }

	public function delete($id){

        $tracking = $this->tracking->find($id);

        return $tracking->delete();
    }
}
