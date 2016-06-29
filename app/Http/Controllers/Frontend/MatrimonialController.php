<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Arret\Repo\ArretInterface;
use App\Droit\Analyse\Repo\AnalyseInterface;

use App\Droit\Page\Repo\PageInterface;
use App\Droit\Arret\Worker\JurisprudenceWorker;
use App\Droit\Site\Repo\SiteInterface;

class MatrimonialController extends Controller
{
    protected $arret;
    protected $analyse;
    protected $page;
    protected $jurisprudence;
    protected $site_id;
    protected $site;
    protected $newsworker;

    public function __construct(ArretInterface $arret, AnalyseInterface $analyse, PageInterface $page, JurisprudenceWorker $jurisprudence, SiteInterface $site)
    {
        $this->site          = $site;
        $this->arret         = $arret;
        $this->analyse       = $analyse;
        $this->page          = $page;
        $this->site          = $site;
        $this->jurisprudence = $jurisprudence;
        $this->newsworker    = \App::make('newsworker');

        $site = $this->site->findBySlug('matrimonial');
        $this->site_id  = $site->id;

        setlocale(LC_ALL, 'fr_FR');
    }

    public function index()
    {
        $page = $this->page->getBySlug($this->site_id,'home');

        return view('frontend.matrimonial.index')->with([ 'page' => $page ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $slug
     * @param  int  $var
     * @return Response
     */
    public function page($slug, $var = null)
    {
        $data['page'] = $this->page->getBySlug($this->site_id,$slug);

        if($slug == 'jurisprudence')
        {
            $newsletters = $this->newsworker->siteNewsletters($this->site_id);
            $exclude     = $this->newsworker->arretsToHide($newsletters->lists('id')->all());

            $data['arrets']   = $this->arret->getAll($this->site_id,$exclude)->take(10);
            $data['analyses'] = $this->analyse->getAll($this->site_id,$exclude)->take(10);
        }

        if($slug == 'newsletter')
        {
            if($var)
            {
                $data['campagne'] = $this->newsworker->infos($var);
            }
            else
            {
                $newsletters = $this->newsworker->siteNewsletters($this->site_id);
                $campagnes   = $this->newsworker->last($newsletters->lists('id'));

                $data['campagne'] = $campagnes->first();
            }
        }

        return view('frontend.matrimonial.'.$data['page']->template)->with($data);
    }

}
