<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Site\Repo\SiteInterface;

class HomeController extends Controller
{
    protected $site;

    public function __construct(SiteInterface $site)
    {
        $this->site = $site;
    }

    /**
     * Send contact message
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if($request->input('id')){
            if($request->input('id') == 275){
                return redirect('pubdroit/colloque');
            }

            return redirect('pubdroit');
        }
        
        return redirect('pubdroit');
    }

    public function subscribe($site_id)
    {
        $site = $this->site->find($site_id);
        $newsletter = $site->newsletter->first();

        return view('frontend.subscribe')->with(['site' => $site, 'newsletter' => $newsletter, 'action' => 'Inscription', 'url' => 'subscribe']);
    }

    public function unsubscribe($site_id)
    {
        $site = $this->site->find($site_id);
        $newsletter = $site->newsletter->first();

        return view('frontend.subscribe')->with(['site' => $site, 'newsletter' => $newsletter, 'action' => 'Désinscription', 'url' => 'unsubscribe']);
    }

    public function confirmation($site_id)
    {
        $site = $this->site->find($site_id);
        $newsletter = $site->newsletter->first();

        return view('frontend.confirmation')->with(['site' => $site, 'newsletter' => $newsletter]);
    }

    /*
     * Transfert function
     * */
    public function transfert()
    {
        return view('transfert');
    }

    public function dotransfert(Request $request)
    {
        setEnv('DB_DATABASE_TRANSFERT', $request->input('database'));

        if(env('DB_DATABASE_TRANSFERT') != $request->input('database')){
            alert()->danger('Refresh database connection');
            return redirect()->back()->withInput($request->all());
        }

        $transfert = new \App\Droit\Services\Transfert();

        $model = $transfert->getOld('Newsletter');
        $model = $model->first();

        $transfert->makeSite($request->all())->prepare();
        $transfert->makeNewsletter($model)->makeCampagne();
        $transfert->makeSubscriptions();

        alert()->success('Terminé');

        return redirect()->back();
    }

    public function setDatabase($slug)
    {
        setEnv('DB_DATABASE_TRANSFERT',$slug);
    }
}
