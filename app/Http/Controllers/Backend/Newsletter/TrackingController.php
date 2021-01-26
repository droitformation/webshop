<?php

namespace App\Http\Controllers\Backend\Newsletter;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Droit\Newsletter\Repo\NewsletterTrackingInterface;
use App\Droit\Newsletter\Repo\NewsletterCampagneInterface;

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
        // Tracking from mailjet
        if(!empty($request->all())){
            foreach ($request->all() as $event){
                $this->tracking->create($event);
            }
        }
    }

    public function incoming(Request $request)
    {
        $bounce = new \App\Droit\Tracking\Entities\Bounce($request->all());

        \Log::info($request->all());

        if($bounce->failed()){
            \Mail::to('droitformation.web@gmail.com')->send(new \App\Mail\NotifyBounce($bounce->failed(), $bounce->headers(), $bounce->body()));
            \Mail::to('droit.formation@unine.ch')->send(new \App\Mail\NotifyBounce($bounce->failed(), $bounce->headers(), $bounce->body()));
        }

        return response()->json(['ok']);
    }
}
