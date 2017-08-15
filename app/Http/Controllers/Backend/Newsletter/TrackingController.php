<?php

namespace App\Http\Controllers\Backend\Newsletter;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Droit\Newsletter\Repo\NewsletterTrackingInterface;

class TrackingController extends Controller
{
    protected $tracking;

    public function __construct(NewsletterTrackingInterface $tracking)
    {
        $this->tracking = $tracking;
    }

    public function tracking(Request $request)
    {
        $this->tracking->create($request->all());
    }
}
