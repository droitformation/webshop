<?php

namespace App\Http\Controllers\Backend\Newsletter;

use Illuminate\Http\Request;
use App\Http\Requests\TrackingRequest;
use App\Http\Controllers\Controller;
use App\Droit\Newsletter\Repo\NewsletterTrackingInterface;
use App\Droit\Newsletter\Repo\NewsletterCampagneInterface;
use Illuminate\Support\Facades\Log;

class TrackingController extends Controller
{
    protected $tracking;
    protected $campagne;

    public function __construct(NewsletterTrackingInterface $tracking, NewsletterCampagneInterface $campagne)
    {
        $this->tracking = $tracking;
        $this->campagne = $campagne;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }

    public function tracking(Request $request)
    {
        \Log::info(json_encode($request->all()));
        \Log::info(json_encode($request->getMethod()));
        \Log::info(json_encode($request->getRequestUri()));

        if(!empty($request->all())){
            foreach ($request->all() as $event){
                $this->tracking->create($event);
            }
        }
    }

    public function stats($id)
    {
        $campagne = $this->campagne->find($id);

        //Mailgun stats
        $mailgun = \App::make('App\Droit\Newsletter\Worker\MailgunInterface');
        $date     = '2017-11-20';
        $tag      = 'campagne_'.$campagne->id;
        $response = $mailgun->getStats($date,$tag);

        $results = $mailgun->mailgun_agregate($response);


        $stats = $this->tracking->find($id);
        $stats = $stats->groupBy(function ($stat, $key) {
            return $stat->time->toDateString();
        });

        return view('backend.newsletter.lists.stats')->with(['campagne' => $campagne, 'stats' => $stats]);
    }
}
