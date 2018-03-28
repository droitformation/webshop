<?php

namespace App\Http\Controllers\Backend\Newsletter;

use Illuminate\Http\Request;
use App\Http\Requests\TrackingRequest;
use App\Http\Controllers\Controller;
use App\Droit\Newsletter\Repo\NewsletterTrackingInterface;
use App\Droit\Newsletter\Repo\NewsletterCampagneInterface;
use App\Droit\Newsletter\Worker\MailgunInterface;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class TrackingController extends Controller
{
    protected $tracking;
    protected $mailgun;
    protected $campagne;

    public function __construct(NewsletterTrackingInterface $tracking, MailgunInterface  $mailgun, NewsletterCampagneInterface $campagne)
    {
        $this->tracking = $tracking;
        $this->mailgun = $mailgun;
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
        $mailgun_stats = [];

        //Mailgun stats
        $dates = !$campagne->sent->isEmpty() ? $campagne->sent->groupBy(function ($item, $key) {
            return $item->send_at->format('Y-m-d');
        }) : null;

        if($dates){
            foreach($dates as $date => $time){
                $response = $this->mailgun->getStats($date, 'campagne_'.$campagne->id);
                $mailgun_stats[$date]['stats'] = $this->mailgun->mailgun_agregate($response);
                $mailgun_stats[$date]['time'] =  $time->map(function ($day) {
                    return [
                        'day'  => $day->send_at->formatLocalized('%d %B %Y à %I:%M:%S'),
                        'liste' => $day->liste->title
                    ];
                });
            }
        }

        $stats = $this->tracking->find($id);
        $stats = $stats->groupBy(function ($stat, $key) {
            return $stat->time->toDateString();
        });

        return view('backend.newsletter.lists.stats')->with(['campagne' => $campagne, 'stats' => $stats, 'mailgun_stats' => $mailgun_stats]);
    }

    public function bounce(Request $request)
    {
        if(env('SEND_ADMIN')){
           // \Mail::to('droit.formation@unine.ch')->send(new \App\Mail\NotifyBounce($request->input('recipient'), $request->input('event'), []));
        }

        \Mail::to('cindy.leschaud@gmail.com')->send(new \App\Mail\NotifyBounce($request->input('recipient'), $request->input('event'), $request->all()));

        //\Log::info(json_encode($request->all()));
    }

    public function incoming(Request $request)
    {
        $bounce = new \App\Droit\Tracking\Entities\Bounce($request->all());

        \Log::info($request->all());

        \Mail::to('cindy.leschaud@gmail.com')->send(new \App\Mail\NotifyBounce($bounce->failed(), $bounce->headers(), $bounce->body()));
        \Mail::to('droit.formation@unine.ch')->send(new \App\Mail\NotifyBounce($bounce->failed(), $bounce->headers(), $bounce->body()));

        return response()->json(['ok']);
    }
}
