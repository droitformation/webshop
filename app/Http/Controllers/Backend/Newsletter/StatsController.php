<?php

namespace App\Http\Controllers\Backend\Newsletter;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Newsletter\Worker\MailjetServiceInterface;
use App\Droit\Newsletter\Repo\NewsletterCampagneInterface;
use App\Droit\Newsletter\Worker\StatsWorker;

class StatsController extends Controller
{
    protected $worker;
    protected $campagne;
    protected $statsworker;
    protected $charts;

    public function __construct(MailjetServiceInterface $worker, NewsletterCampagneInterface $campagne, StatsWorker $statsworker)
    {
        $this->worker       = $worker;
        $this->campagne     = $campagne;
        $this->statsworker  = $statsworker;
        $this->charts       = new \App\Droit\Newsletter\Worker\Charts();

        setlocale(LC_ALL, 'fr_FR');

        view()->share('isNewsletter',true);
    }

    /**
     * Display the specified resource.
     * GET /stats/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $campagne      = $this->campagne->find($id);
        $tracking      = \App\Droit\Newsletter\Entities\Newsletter_tracking::where('CustomID','=',$campagne->id)->get();
        $statistiques  = !$tracking->isEmpty() ? $this->charts->stats($tracking) : collect([]);

        return view('backend.newsletter.stats.show')->with(['campagne' => $campagne, 'statistiques' => $statistiques]);
    }

    /*
     * Deprecated 2021
     * */
    public function sumStatsClicksLinks($CampaignID, $offset = 0){

        $result = [];

        $data  = $this->worker->clickStatistics($CampaignID, $offset);
        $count = $this->statsworker->getTotalCount($data);

        if($count == 500) {
            $result[] = $data->Data;
            $offset   = $offset + 500;

            $result   = array_merge($result,$this->sumStatsClicksLinks($CampaignID, $offset));
        }
        else {
            $result[] = $data->Data;
        }

        return $result;
    }
}
