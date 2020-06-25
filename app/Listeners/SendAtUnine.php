<?php namespace App\Listeners;

use App\Events\CampaignSent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\SendCampaign;
use Illuminate\Foundation\Bus\DispatchesJobs;

class SendAtUnine
{
    use DispatchesJobs;

    protected $user;
    protected $campagne;
    protected $worker;

    public function __construct()
    {
        $this->user     = \App::make('App\Droit\Newsletter\Repo\NewsletterUserInterface');
        $this->campagne = \App::make('App\Droit\Newsletter\Repo\NewsletterCampagneInterface');
        $this->worker   = \App::make('App\Droit\Newsletter\Worker\CampagneInterface');
    }

    /**
     * Handle the event.
     *
     * @param  CampaignSent  $event
     * @return void
     */
    public function handle(CampaignSent $event)
    {
        $emails   = $this->user->getByNewsletterAndDomain($event->newsletter_id, '@unine.ch');
        $campagne = $this->campagne->find($event->campaign_id);

        if(!$emails->isEmpty()){
            foreach ($emails->chunk(10) as $row){

                $recipients = $row->map(function ($email, $key) {
                    return ['Email' => $email->email, 'Name'  => ""];
                })->toArray();

                // GET html
                $html = $this->worker->html($campagne->id);

                SendCampaign::dispatch($campagne,$html,$recipients)->delay(now()->addSeconds(30));
            }
        }

    }
}
